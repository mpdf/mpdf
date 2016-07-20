<?php

namespace Mpdf;

use Mpdf\Console\Command\CompareSnapshots;
use Mpdf\Console\Command\BuildSnapshots;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Filesystem\Filesystem;

use Mpdf\MpdfException;

/**
 * Class SnapshotComparisonTest
 * @package Mpdf
 * @group snapshot
 */
class SnapshotComparisonTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Mpdf\Console\Command\CompareSnapshots
     */
    private $compare;

    /**
     * @var \Mpdf\Console\Command\BuildSnapshots
     */
    protected $builder;

    public function setUp()
    {
        parent::setUp();

        $application = new Application();
        $application->add(new BuildSnapshots(new Filesystem()));
        $application->add(new CompareSnapshots());

        $this->compare = $application->find('testing:comparesnapshots');
        $this->builder = $application->find('testing:buildsnapshots');
    }

    public function tearDown()
    {
        parent::tearDown();

        $input = new ArrayInput([], $this->compare->getDefinition());
        $this->builder->cleanupDir($this->builder->getTmpDir($input) . 'pdfs/');
    }

    public function testSnapshots()
    {
        /* Get the default params */
        $input = new ArrayInput([], $this->compare->getDefinition());

        $example_dir = $this->builder->getExampleDir($input);
        $snapshot_dir = $this->builder->getOutputDir($input, 'snapshot-dir');
        $php_path = $this->builder->getPhpPath($input);
        $tmp_dir = $this->builder->getTmpDir($input);
        $diff = $this->compare->getDiff($input);

        /* Create snapshots of these templates */
        $files = $this->builder->getExampleTemplates($example_dir);

        foreach ($files as $file) {

            $pdfs = $this->builder->generatePdfs($file, $php_path, $tmp_dir . 'pdfs/');
            $snapshots = $this->builder->generateSnapshots($pdfs, $tmp_dir);

            foreach ($snapshots as $img) {
                try {

                    $result = $this->compare->compareToBaseSnap(
                        $img,
                        $snapshot_dir,
                        '',
                        true,
                        $diff
                    );

                    $this->assertLessThanOrEqual($diff, $result, basename($img) . ': Failed asserting that ' . $result . ' is equal to or less than ' . $diff );
                } catch (MpdfException $e) { /* Catch the exception early so we don't stop the comparison process */
                    $this->fail(basename($img) . ': ' . $e->getMessage());
                }
            }
        }
    }
}
