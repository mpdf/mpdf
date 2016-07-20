<?php

namespace Mpdf\Console\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use Mpdf\MpdfException;

/**
 * Class CompareSnapshotsTest
 * @package Mpdf\Console\Command
 * @group console
 */
class CompareSnapshotsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Mpdf\Console\Command\CompareSnapshots
     */
    private $compare;

    /**
     * @var \Mpdf\Console\Command\BuildSnapshots
     */
    protected $builder;

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    private $fs;

    protected function setUp()
    {
        parent::setUp();

        $this->fs = new Filesystem();

        $application = new Application();
        $application->add(new BuildSnapshots($this->fs));
        $application->add(new CompareSnapshots());

        $this->compare = $application->find('testing:comparesnapshots');
        $this->builder = $application->find('testing:buildsnapshots');
    }

    protected function tearDown()
    {
        parent::tearDown();

        /* Cleanup from our tests */
        try {
            $this->fs->remove(MPDF_ROOT . 'tmp/test/');
        } catch (IOExceptionInterface $e) {
            //ignore
        }
    }

    public function testExecute()
    {
        $snapshot_dir = MPDF_ROOT . 'tmp/test/snapshot/';
        $tmp_dir = MPDF_ROOT . 'tmp/test/tmp/';

        /* Move our test files to the tmp directory and change the extension to php */
        $org_test_dir = MPDF_ROOT . 'tests/data/examples/';
        $new_test_dir = MPDF_ROOT . 'tmp/test/examples/';

        try {

            $this->fs->copy($org_test_dir . 'example01.php.txt', $new_test_dir . 'example01.php');
            $this->fs->copy($org_test_dir . 'example02.php.txt', $new_test_dir . 'example02.php');

            /* Create snapshots of these templates */
            $files = $this->builder->getExampleTemplates($new_test_dir);
            $pdfs = $this->builder->generatePdfs($files, 'php', $tmp_dir);
            $this->builder->generateSnapshots($pdfs, $snapshot_dir);

            $commandTester = new CommandTester($this->compare);
            $commandTester->execute([
                'command' => $this->builder->getName(),
                '--example-dir' => $new_test_dir,
                '--snapshot-dir' => $snapshot_dir,
                '--tmp-dir' => $tmp_dir,
            ]);
        } catch(MpdfException $e) {
            $this->fail($e->getMessage());
        }

        $this->assertRegExp('/100\%/', $commandTester->getDisplay());
    }

    public function testGetSnapshots() {
        $tmp_dir = MPDF_ROOT . 'tmp/test/';
        $this->fs->dumpFile($tmp_dir . 'file.jpg', '');
        $this->fs->dumpFile($tmp_dir . 'file1.jpg', '');
        $this->fs->dumpFile($tmp_dir . 'file3.jpg', '');

        $this->assertSame(3, sizeof($this->compare->getSnapshots($tmp_dir)));
    }
    
    public function testCompareToBaseSnap() {
        /* Copy our images to our "base" directory */
        $snapshot_dir = MPDF_ROOT . 'tmp/test/snapshot/';
        $example_dir = MPDF_ROOT . 'tests/data/images/';

        $this->fs->copy($example_dir . 'compare2.jpg', $snapshot_dir . 'compare1.jpg', true);

        /* Test the two images are the same */
        $diff = $this->compare->compareToBaseSnap($example_dir . 'compare1.jpg', $snapshot_dir, $example_dir . 'composition/', true);
        $this->assertSame(0.0, $diff);

        /* Test the two images are different */
        $this->fs->copy($example_dir . 'compare3.jpg', $snapshot_dir . 'compare1.jpg', true);
        $diff = $this->compare->compareToBaseSnap($example_dir . 'compare1.jpg', $snapshot_dir, $example_dir . 'composition/', true);
        $this->assertNotSame(0.0, $diff);
    }
}
