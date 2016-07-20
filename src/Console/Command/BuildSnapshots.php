<?php

namespace Mpdf\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\ExceptionInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Process\ProcessBuilder;

use Mpdf\MpdfException;

use Imagick;

/**
 * Class BuildSnapshots
 *
 * Builds images of the example template files (https://github.com/mpdf/mpdf-examples) which are used as the baseline
 * for comparisons during unit tests
 *
 * This package can be run with: php mpdf.php testing:buildsnapshots
 *
 * Non-required options include:
 *
 * --template=example01_basic.php or -t example01_basic.php (can be passed multiple times)
 * --example-dir=/full/path/to/mpdf/examples/dir/ or -e /full/path/to/mpdf/examples/dir/
 * --output-dir=/full/path/to/snapshot/output/dir/ or -o /full/path/to/snapshot/output/dir/
 * --tmp-dir=/full/path/to/tmp/dir/ or -m /full/path/to/tmp/dir/
 * --php-path=/full/path/to/php/executable/ or -p /full/path/to/php/executable/
 *
 * @package Mpdf\Console\Command
 * @since 7.0
 */
class BuildSnapshots extends Command
{

    /**
     * Don't create snapshots for any of these example templates
     * This is either due to processing issues with Imagick / Ghostscript
     * or they take too long to process.
     *
     * @var array
     * @since 7.0
     */
    public $exclusions = [
        'example14_page_numbers_ToC_Index_Bookmarks.php',
        'example27_CJK_using_Adobe_fonts.php',
        'example42_MPDFI_templatedoc.php',
        'example54_new_mPDF_v5-1_features_gradients_and_images.php',
        'example56_new_mPDF_v5-1_features_grayscale.php',
        'example57_new_mPDF_v5-3_active_forms.php',
        'example65_CMYK_colour_charts.php',
        'example37_barcodes.php',
    ];

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    private $fs;

    /**
     * BuildSnapshots constructor.
     * @param Filesystem $fs
     * @since 7.0
     */
    public function __construct(Filesystem $fs)
    {
        parent::__construct();

        $this->fs = $fs;
    }

    /**
     * Symfony's Console Configuration
     * See http://symfony.com/doc/current/components/console/introduction.html for docs
     * @since 7.0
     */
    public function configure()
    {
        $this
            ->setName('testing:buildsnapshots')
            ->setDescription('This will rebuild the snapshot images we use for unit testing changes. This is a processive-intensive task and usually takes 3+ minutes.')
            ->addOption(
                'template',
                't',
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'When set, only the selected templates will be rebuilt: -t example01_basic.php -t example02_CSS_styles.php'
            )
            ->addOption(
                'example-dir',
                'e',
                InputOption::VALUE_REQUIRED,
                'The full path to the examples directory'
            )->addOption(
                'output-dir',
                'o',
                InputOption::VALUE_REQUIRED,
                'The full path the snapshots should be saved to'
            )->addOption(
                'tmp-dir',
                'm',
                InputOption::VALUE_REQUIRED,
                'The full path to the tmp directory'
            )->addOption(
                'php-path',
                'p',
                InputOption::VALUE_REQUIRED,
                'The full path to the PHP executable file'
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
        /* Setup the command options, or fallback to defaults if they don't exist */
        $example_dir = $this->getExampleDir($input);
        $output_dir = $this->getOutputDir($input);
        $php_path = $this->getPhpPath($input);
        $tmp_dir = $this->getTmpDir($input);
        $filter = $this->getTemplateFilters($input);

        try {
            $this->checkDependancies($example_dir, $output_dir); // ensure we can actually run this command
            $example_files = $this->getExampleTemplates($example_dir, $filter); // get the Mpdf Example PHP files

            $progress = new ProgressBar($output, sizeof($example_files));

            foreach ($example_files as $file) {

                $output->writeln('Generating ' . basename($file), OutputInterface::VERBOSITY_VERBOSE);
                $pdfs = $this->generatePdfs($file, $php_path, $tmp_dir); // create the test PDF

                foreach ($pdfs as $pdf) {
                    $output->writeln('Snapshotting ' . basename($pdf), OutputInterface::VERBOSITY_VERBOSE);
                    $this->generateSnapshots($pdf, $output_dir); // create the snapshot images from the test PDF
                }

                $progress->advance();
            }

        } catch (MpdfException $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        } finally {
            $this->cleanupDir($tmp_dir); // remove our test PDFs
            $progress->finish();
        }
    }

    /**
     * Ensures the appropriate dependancies are available to run this command
     *
     * We require Imagick and the Mpdf Examples directory to run
     *
     * @param string $example_dir
     * @param string $output_dir
     * @throws MpdfException
     * @since 7.0
     */
    public function checkDependancies($example_dir, $output_dir)
    {
        if (!class_exists('Imagick')) {
            throw new MpdfException('You need to have Imagick installed to run this command');
        }

        if (!is_dir($example_dir)) {
            throw new MpdfException('We could not find the example PDF files. Run `composer update` to download the files or pass in the --example-dir option.');
        }

        if (!is_dir($output_dir)) {
            $this->fs->mkdir($output_dir);
        }
    }

    /**
     * Get's the path to the Mpdf example directory
     * Defaults to the /examples/ directory in the root folder, but can be overriden with --example-dir option
     * @param InputInterface $input
     * @param string $option The option name
     * @return string
     * @since 7.0
     */
    public function getExampleDir(InputInterface $input, $option = 'example-dir')
    {
        return ($input->getOption($option)) ? $input->getOption($option) : MPDF_ROOT . 'vendor/mpdf/examples/';
    }

    /**
     * Get's the PHP binary to execute when generating the PDFs
     * @param InputInterface $input
     * @param string $option The option name
     * @return string
     * @since 7.0
     */
    public function getPhpPath(InputInterface $input, $option = 'php-path')
    {
        return ($input->getOption($option)) ? $input->getOption($option) : 'php';
    }

    /**
     * Get's the directory we should output the snapshot images
     * @param InputInterface $input
     * @param string $option The option name
     * @return string
     * @since 7.0
     */
    public function getOutputDir(InputInterface $input, $option = 'output-dir')
    {
        return ($input->getOption($option)) ? $input->getOption($option) : MPDF_ROOT . 'vendor/mpdf/snapshots/';
    }

    /**
     * The directory we store the generated PDFs temporarily
     * @param InputInterface $input
     * @param string $option The option name
     * @return string
     * @since 7.0
     */
    public function getTmpDir(InputInterface $input, $option = 'tmp-dir')
    {
        return ($input->getOption($option)) ? $input->getOption($option) : MPDF_ROOT . 'tmp/snapshots/';
    }

    /**
     * The templates we should process
     * @param InputInterface $input
     * @param string $option The option name
     * @return array|null
     * @since 7.0
     */
    public function getTemplateFilters(InputInterface $input, $option = 'template')
    {
        return ($input->getOption($option)) ? $input->getOption($option) : [];
    }

    /**
     * Gets a list of Mpdf PHP files in the examples directory
     * @param string $path
     * @return array
     * @throws MpdfException
     * @since 7.0
     */
    public function getExampleTemplates($path, $filter = [])
    {
        $files = glob($path . 'example*.php');

        if (false === $files) {
            throw new MpdfException('There was a problem locating the templates');
        }

        /* Ignore any template files that cause issues with our comparison */
        $files = $this->filterExampleTemplates($files, $this->exclusions);

        /* Filter our list if we need to */
        $files = $this->filterExampleTemplates($files, $filter, 'keep');

        return $files;
    }

    /**
     * Filter the list of example files and return any matches
     * @param array $files The full list of example templates
     * @param array $filter A list that we should check exists and keep in our list
     * @param string $filter_by Either 'remove' or 'keep'. When 'remove' if the $filter array items are found they will
     * be removed from $files. When 'keep' and items aren't found in $filter they will be removed from $files.
     * @return array The filtered array list
     * @since 7.0
     */
    public function filterExampleTemplates($files = array(), $filter = array(), $filter_by = 'remove')
    {
        /* Don't filter the list if $filter_by set to 'keep' and $filter is empty */
        if (sizeof($filter) == 0 && $filter_by === 'keep') {
            return $files;
        }

        foreach ($files as $key => $file) {
            $basename = basename($file);
            $matched = in_array($basename, $filter, true);
            if ($matched && $filter_by === 'remove') {
                unset($files[$key]);
            } elseif (!$matched && $filter_by === 'keep') {
                unset($files[$key]);
            }
        }

        return $files;
    }

    /**
     * Generates and saves the example PDFs into the tmp directory
     * @param string|array $files A list of PHP files to execute
     * @param string $php_path The path to the PHP binary we should use the generate the PDFs
     * @param string $tmp_dir The path to the tmp directory
     * @return array A list of PDFs that have been saved to the tmp directory
     * @throws MpdfException
     * @since 7.0
     */
    public function generatePdfs($files, $php_path, $tmp_dir)
    {
        if (is_string($files)) {
            $files = [$files];
        }

        $generated = [];

        foreach ($files as $file) {

            $php_filename = basename($file);
            $php_dirname = dirname($file);
            $pdf_fullpath = $tmp_dir . substr($php_filename, 0, -4) . '.pdf';

            if (!is_file($pdf_fullpath)) {

                $builder = new ProcessBuilder();
                $builder->setPrefix($php_path);
                $process = $builder
                    ->setWorkingDirectory($php_dirname)
                    ->setPrefix($php_path)
                    ->add($php_filename)
                    ->setEnv('MPDF_ROOT', MPDF_ROOT)
                    ->getProcess();
                $process->run();

                $pdf = $process->getOutput();

                if (!$process->isSuccessful() || strpos($pdf, '%PDF-') === false) {
                    throw new MpdfException('Could not generate PDF for: ' . $php_filename);
                }

                $this->saveFile($pdf, $pdf_fullpath);
            }

            $generated[] = $pdf_fullpath;
        }

        return $generated;
    }

    /**
     * Uses Imagick to generate images of each page of the PDFs
     * @param string|array $files A list of PDFs
     * @param string $output_dir The directory we should save the snapshots
     * @return array Path to saved images
     * @since 7.0
     */
    public function generateSnapshots($files = array(), $output_dir = '')
    {
        $generated = [];

        if (is_string($files)) {
            $files = [$files];
        }

        if (!is_dir($output_dir)) {
            $this->fs->mkdir($output_dir);
        }

        foreach ($files as $file) {
            $img = new Imagick();
            $img->setResolution(120, 120); //ensure better quality of the text
            $img->readImage($file);
            $img->setImageBackgroundColor('white'); //prevents black background on any objects with transparency
            $img->setImageCompressionQuality(100);

            $page_no = $img->getNumberImages();
            $filename = basename($file, '.pdf');

            for ($i = 0; $i < $page_no; $i++) {
                $img->setIteratorIndex($i); //set iterator position
                $img->setImageFormat('jpg');
                $save_filename = $output_dir . $filename . '-' . ($i + 1) . '.jpg';
                $img->writeImage($save_filename);
                $generated[] = $save_filename;
            }

            $img->destroy();
        }

        return $generated;
    }

    /**
     * Cleanup method
     * @param string $tmp_dir
     * @since 7.0
     */
    public function cleanupDir($tmp_dir)
    {
        $this->fs->remove($tmp_dir);
    }

    /**
     * Save the contents to disk
     * @param string $content The PDF that should be saved
     * @param $fullpath The absolute path we are saving to
     * @since 7.0
     */
    public function saveFile($content, $fullpath)
    {
        $this->fs->dumpFile($fullpath, $content);
    }
}