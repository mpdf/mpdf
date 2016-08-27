<?php

namespace Mpdf\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

use Mpdf\MpdfException;

use Imagick;


/**
 * Class CompareSnapshots
 *
 * Builds images of the example template files (https://github.com/mpdf/mpdf-examples) which are compared against the
 * baseline snapshot images generated using the testing:buildsnapshots CLI command
 *
 * Non-required options include:
 *
 * --template=example01_basic.php or -t example01_basic.php (can be passed multiple times)
 * --example-dir=/full/path/to/mpdf/examples/dir/ or -e /full/path/to/mpdf/examples/dir/
 * --snapshot-dir=/full/path/to/snapshot/output/dir/ or -s /full/path/to/snapshot/output/dir/
 * --tmp-dir=/full/path/to/tmp/dir/ or -m /full/path/to/tmp/dir/
 * --php-path=/full/path/to/php/executable/ or -p /full/path/to/php/executable/
 * --diff=0.01 or -d 0.01
 *
 * @package Mpdf\Console\Command
 * @since 7.0
 */
class CompareSnapshots extends Command
{
    /**
     * @var \Mpdf\Console\Command\BuildSnapshots
     */
    protected $builder;

    /**
     * Symfony's Console Configuration
     * See http://symfony.com/doc/current/components/console/introduction.html for docs
     * @since 7.0
     */
    protected function configure()
    {
        $this
            ->setName('testing:comparesnapshots')
            ->setDescription('This will create snapshots of the current example templates and compare them to the base snaps. This is a process intensive task and usually takes 3+ minutes.')
            ->addOption(
                'template',
                't',
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'When set, only the selected templates will be tested: -t example01_basic.php -t example02_CSS_styles.php'
            )->addOption(
                'example-dir',
                'e',
                InputOption::VALUE_REQUIRED,
                'The full path to the examples directory'
            )->addOption(
                'snapshot-dir',
                's',
                InputOption::VALUE_REQUIRED,
                'The full path the core snapshots are located. Default /tests/data/snapshots/'
            )->addOption(
                'tmp-dir',
                'm',
                InputOption::VALUE_REQUIRED,
                'The full path to the tmp directory'
            )->addOption(
                'php-path',
                'p',
                InputOption::VALUE_REQUIRED,
                'The full path to the PHP executable'
            )->addOption(
                'diff',
                'd',
                InputOption::VALUE_REQUIRED,
                'The allowed percentage difference between the snapshots. Defaults to 0.02 (2%)'
            );
    }

    /**
     * Handles the actual execution of our command
     * @param InputInterface $input
     * @param OutputInterface $output
     * @since 7.0
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* Get our helper snapshot builder object */
        $this->builder = $this->getApplication()->find('testing:buildsnapshots');

        /* Setup the command options, or fallback to defaults if they don't exist */
        $example_dir = $this->builder->getExampleDir($input);
        $snapshot_dir = $this->builder->getOutputDir($input, 'snapshot-dir');
        $php_path = $this->builder->getPhpPath($input);
        $tmp_dir = $this->builder->getTmpDir($input);
        $filter = $this->builder->getTemplateFilters($input);
        $diff = $this->getDiff($input);

        try {
            $this->builder->checkDependancies($example_dir, $tmp_dir); // ensure we can actually run this command
            $example_files = $this->builder->getExampleTemplates($example_dir, $filter); // get the example PHP files

            $progress_ticker = sizeof($example_files) * 2; // double the results for snapshotting and then comparing
            $progress = new ProgressBar($output, $progress_ticker);

            foreach ($example_files as $file) {
                /* Build new snapshots to compare against the base snaps */
                $output->writeln(sprintf('Generating %s', basename($file)), OutputInterface::VERBOSITY_VERBOSE);
                $pdfs = $this->builder->generatePdfs($file, $php_path, $tmp_dir . 'pdfs/'); // create the test PDF

                foreach ($pdfs as $pdf) {
                    $output->writeln(sprintf('Snapshotting %s', basename($pdf)), OutputInterface::VERBOSITY_VERBOSE);
                    $this->builder->generateSnapshots($pdf, $tmp_dir); // create the snapshot images from the test PDF
                    $progress->advance();
                }
            }

            /* Clean up our PDF directory */
            $this->builder->cleanupDir($tmp_dir . 'pdfs/');

            /* Load the base snapshots and compare them against the newly-generated snaps */
            $snapshots = $this->getSnapshots($tmp_dir);

            foreach ($snapshots as $img) {
                try {
                    /* Run the snapshot comparison */
                    $result = $this->compareToBaseSnap(
                        $img,
                        $snapshot_dir,
                        $tmp_dir . '../composition/',
                        false,
                        $diff
                    );

                    $output->writeln(sprintf('The snapshot difference for %s is %s', basename($img), $result),
                        OutputInterface::VERBOSITY_VERBOSE);

                } catch (MpdfException $e) { /* Catch the exception early so we don't stop the comparison process */
                    $output->writeln('<error>' . $e->getMessage() . '</error>');
                }
            }

            $progress->advance();

        } catch (MpdfException $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        } finally {
            $this->builder->cleanupDir($tmp_dir);

            /* Check $progress is set in case the checkDependancies() or getExampleTemplates() trigger an exception */
            if (isset($progress)) {
                $progress->finish();
            }
        }
    }

    /**
     * Get the allowed user-defined percentage difference between the snapshots
     * @param InputInterface $input
     * @return string
     * @since 7.0
     */
    public function getDiff(InputInterface $input)
    {
        return ($input->getOption('diff')) ? $input->getOption('diff') : 0.02; /* default to 2% to allow fuzzyness */
    }

    /**
     * Get the new snapshots we've generated
     * @param string $path
     * @return array
     * @throws MpdfException
     * @since 7.0
     */
    public function getSnapshots($path)
    {
        $files = glob($path . '*.jpg');

        if (false === $files) {
            throw new MpdfException('There was a problem locating the base snapshots');
        }

        return $files;
    }

    /**
     * Compare the current image to one with the same name in our base snapshot directory
     * @param string $img The path to our newly generated template snapshot
     * @param string $snapshot_dir The path to the base snapshot directory
     * @param string $composition_dir The path where we should save images that have more than 2% difference
     * @param bool $dont_save_composition If true we won't save a composition image to disk
     * @param float $difference The percentage difference before an Exception is thrown
     * @return float The percentage difference
     * @throws MpdfException Thrown if image differs more than $difference
     * @since 7.0
     */
    public function compareToBaseSnap(
        $img,
        $snapshot_dir,
        $composition_dir,
        $dont_save_composition = false,
        $difference = 0.0
    ) {
        $img_filename = basename($img);
        $base_img = $snapshot_dir . $img_filename;

        if (!is_file($base_img)) {
            throw new MpdfException(sprintf('Cannot find the base snapshot for comparison: %s', $img_filename));
        }

        /* Create Imagick objects and do our comparison */
        $image1 = new Imagick($img);
        $image2 = new Imagick($base_img);
        $result = $image1->compareImages($image2, Imagick::METRIC_MEANABSOLUTEERROR);

        /* Cleanup Imagick */
        $image1->destroy();
        $image2->destroy();

        /* If changes more than $difference we'll log an error */
        if (!$dont_save_composition && $result[1] > $difference) {
            $composition_file = $composition_dir . $img_filename;
            $php_filename = basename($img_filename, '.jpg') . '.php';

            $this->builder->saveFile($result[0], $composition_file);

            throw new MpdfException(sprintf('The snapshot "%s" differs from the base snap by %s%%. For comparison see: %s',
                $php_filename, $result[1], $composition_file));
        }

        return $result[1];
    }
}