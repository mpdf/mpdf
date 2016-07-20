<?php

namespace Mpdf\Console\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use Mpdf\MpdfException;

/**
 * Class BuildSnapshotsTest
 * @package Mpdf\Console\Command
 * @group console
 */
class BuildSnapshotsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Mpdf\Console\Command\BuildSnapshots
     */
    private $builder;

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

        $this->builder = $application->find('testing:buildsnapshots');
        $this->builder->configure();
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

        /* Move our test files to the tmp directory and change the extension to php */
        $org_test_dir = MPDF_ROOT . 'tests/data/examples/';
        $new_test_dir = MPDF_ROOT . 'tmp/test/examples/';

        $this->fs->copy($org_test_dir . 'example01.php.txt', $new_test_dir . 'example01.php');
        $this->fs->copy($org_test_dir . 'example02.php.txt', $new_test_dir . 'example02.php');

        $commandTester = new CommandTester($this->builder);
        $commandTester->execute([
            'command' => $this->builder->getName(),
            '--example-dir' => $new_test_dir,
            '--output-dir' => MPDF_ROOT . 'tmp/test/output/',
            '--tmp-dir' => MPDF_ROOT . 'tmp/test/tmp/',
        ]);

        $this->assertSame(2, sizeof(glob(MPDF_ROOT . 'tmp/test/output/*.jpg')));
    }

    public function testCheckDependancies()
    {
        $example_dir = '/somewhere/that/doesnt/exist/';
        $error = '';

        try {
            $this->builder->checkDependancies($example_dir, MPDF_ROOT);
        } catch (MpdfException $e) {
            $error = $e->getMessage();
        }

        $this->assertNotFalse(strpos($error, 'We could not find the example PDF files'));
    }

    /**
     * @dataProvider providerGetPathorDir
     */
    public function testGetPathOrDir($method, $input, $expected, $option)
    {
        $input = new ArrayInput($input, $this->builder->getDefinition());
        $params = ($option !== null) ? [$input, $option] : [$input];
        $this->assertEquals($expected, call_user_func_array([$this->builder, $method], $params));
    }

    public function providerGetPathorDir()
    {
        return [
            /* getExampleDir() tests */
            [
                'method' => 'getExampleDir',
                'input' => [],
                'expected' => MPDF_ROOT . 'vendor/mpdf/examples/',
                'option' => null,
            ],
            [
                'method' => 'getExampleDir',
                'input' => ['--example-dir' => '/alt-dir/'],
                'expected' => '/alt-dir/',
                'option' => null,
            ],
            [
                'method' => 'getExampleDir',
                'input' => ['--output-dir' => '/alt-dir/'],
                'expected' => '/alt-dir/',
                'option' => 'output-dir',
            ],

            /* getPhpPath() tests */
            [
                'method' => 'getPhpPath',
                'input' => [],
                'expected' => 'php',
                'option' => null,
            ],
            [
                'method' => 'getPhpPath',
                'input' => ['--php-path' => '/usr/bin/php'],
                'expected' => '/usr/bin/php',
                'option' => null,
            ],
            [
                'method' => 'getPhpPath',
                'input' => ['--output-dir' => '/alt-dir/'],
                'expected' => '/alt-dir/',
                'option' => 'output-dir',
            ],

            /* getOutputDir() tests */
            [
                'method' => 'getOutputDir',
                'input' => [],
                'expected' => MPDF_ROOT . 'vendor/mpdf/snapshots/',
                'option' => null,
            ],
            [
                'method' => 'getOutputDir',
                'input' => ['--output-dir' => '/output/'],
                'expected' => '/output/',
                'option' => null,
            ],
            [
                'method' => 'getOutputDir',
                'input' => ['--example-dir' => '/example-dir/'],
                'expected' => '/example-dir/',
                'option' => 'example-dir',
            ],

            /* getTmpPath() tests */
            [
                'method' => 'getTmpDir',
                'input' => [],
                'expected' => MPDF_ROOT . 'tmp/snapshots/',
                'option' => null,
            ],
            [
                'method' => 'getTmpDir',
                'input' => ['--tmp-dir' => '/tmp/'],
                'expected' => '/tmp/',
                'option' => null,
            ],
            [
                'method' => 'getTmpDir',
                'input' => ['--example-dir' => '/example-dir/'],
                'expected' => '/example-dir/',
                'option' => 'example-dir',
            ],

            /* getTemplateFilters() tests */
            [
                'method' => 'getTemplateFilters',
                'input' => [],
                'expected' => [],
                'option' => null,
            ],
            [
                'method' => 'getTemplateFilters',
                'input' => ['--template' => ['item1', 'item2']],
                'expected' => ['item1', 'item2'],
                'option' => null,
            ],
            [
                'method' => 'getTemplateFilters',
                'input' => ['--example-dir' => '/example-dir/'],
                'expected' => '/example-dir/',
                'option' => 'example-dir',
            ],
        ];
    }

    public function testGetExampleTemplates()
    {
        $files = [
            'example01_template.php',
            'exampl_not_matched.php',
            'other_files.php',
            'example_template.php',
            'example05_tables.php',
        ];

        $example_dir = MPDF_ROOT . 'tmp/test/';

        /* Create test files */
        foreach ($files as $file) {
            $this->fs->dumpFile($example_dir . $file, '');
        }

        $builder = clone $this->builder; /* ensure we don't modify the original object */

        /* Check we get the correct number of matches */
        $this->assertSame(3, sizeof($builder->getExampleTemplates($example_dir)));

        /* Ensure the filters work correctly */
        $builder->exclusions[] = 'example01_template.php';
        $this->assertSame(2, sizeof($builder->getExampleTemplates($example_dir)));
        $this->assertSame(1, sizeof($builder->getExampleTemplates($example_dir, ['example05_tables.php'])));
    }

    /**
     * @dataProvider providerFilterExampleTemplates
     */
    public function testFilterExampleTemplates($expected, $list, $filter, $filter_by)
    {
        $filtered = $this->builder->filterExampleTemplates($list, $filter, $filter_by);
        $this->assertSame($expected, sizeof($filtered));
    }

    public function providerFilterExampleTemplates()
    {
        return [
            [
                7,
                ['item 1', 'item 2', 'item 3', 'item 4', 'item 5', 'item 6', 'item 7'],
                [],
                'remove',
            ],

            [
                5,
                ['item 1', 'item 2', 'item 3', 'item 4', 'item 5', 'item 6', 'item 7'],
                ['item 2', 'item3', 'item 6', 0, 2, 'other'],
                'remove',
            ],

            [
                7,
                ['item 1', 'item 2', 'item 3', 'item 4', 'item 5', 'item 6', 'item 7'],
                [],
                'keep',
            ],

            [
                3,
                ['item 1', 'item 2', 'item 3', 'item 4', 'item 5', 'item 6', 'item 7'],
                ['item 2', 'item 3', 'item 7', 'other', 'item4'],
                'keep',
            ]
        ];
    }

    /**
     * @dataProvider providerGeneratePdfs
     */
    public function testGeneratePdfs($files)
    {
        $php_path = 'php';
        $tmp_dir = MPDF_ROOT . 'tmp/test/pdfs/';

        try {
            $generated = $this->builder->generatePdfs($files, $php_path, $tmp_dir);
        } catch (MpdfException $e) {
            $this->fail($e->getMessage());
        }

        $this->assertSame(sizeof((array)$files), sizeof(glob($tmp_dir . '*.pdf')));
    }

    public function providerGeneratePdfs()
    {
        return [
            [
                MPDF_ROOT . 'tests/data/examples/example01.php.txt'
            ],

            [
                [
                    MPDF_ROOT . 'tests/data/examples/example01.php.txt',
                    MPDF_ROOT . 'tests/data/examples/example02.php.txt'
                ]
            ]
        ];
    }

    /**
     * @dataProvider providerGenerateSnapshots
     */
    public function testGenerateSnapshots($files)
    {
        $pdf_dir = MPDF_ROOT . 'tmp/test/pdfs/';
        $snapshot_dir = MPDF_ROOT . 'tmp/test/snapshot/';

        try {
            $pdfs = $this->builder->generatePdfs($files, 'php', $pdf_dir);
            $this->builder->generateSnapshots($pdfs, $snapshot_dir);
        } catch (MpdfException $e) {
            $this->fail($e->getMessage());
        }

        $this->assertSame(sizeof((array)$files), sizeof(glob($snapshot_dir . '*.jpg')));
    }

    public function providerGenerateSnapshots()
    {
        return [
            [
                MPDF_ROOT . 'tests/data/examples/example01.php.txt'
            ],

            [
                [
                    MPDF_ROOT . 'tests/data/examples/example01.php.txt',
                    MPDF_ROOT . 'tests/data/examples/example02.php.txt'
                ]
            ]
        ];
    }

    public function testSaveFile()
    {
        $example_file = MPDF_ROOT . 'tmp/test/saved.txt';
        try {
            $this->builder->saveFile('working', $example_file);
        } catch (IOExceptionInterface $e) {
            //ignore
        }

        $this->assertFileExists($example_file);
    }

    public function testCleanupDir()
    {
        $example_dir = MPDF_ROOT . 'tmp/test/dir/';
        try {
            $this->builder->saveFile('working', $example_dir . 'saved.txt');
        } catch (IOExceptionInterface $e) {
            $this->fail('Test Incomplete: Could not create directory / file for test.');
        }

        $this->assertFileExists($example_dir . 'saved.txt');
        $this->builder->cleanupDir($example_dir);
        $this->assertFileNotExists($example_dir);
    }
}
