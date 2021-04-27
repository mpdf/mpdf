<?php

namespace Mpdf\Writer;

use Mockery;
use Mpdf\Mpdf;
use Mpdf\Form;
use Mpdf\Pdf\Protection;
use Psr\Log\LoggerInterface;
use PHPUnit_Framework_TestCase;

class MetadataWriterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mpdf
     */
    private $mpdf;

    /**
     * @var BaseWriter
     */
    private $writer;

    /**
     * @var Form
     */
    private $form;

    /**
     * @var Protection
     */
    private $protection;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var MetadataWriter
     */
    private $metadataWriter;

    protected function setUp()
    {
        /** @var Mpdf $mpdf */
        $this->mpdf = Mockery::mock('Mpdf\Mpdf');
        /** @var BaseWriter writer */
        $this->writer = Mockery::mock('Mpdf\Writer\BaseWriter');
        /** @var Form $form */
        $this->form = Mockery::mock('Mpdf\Form');
        /** @var Protection $protection */
        $this->protection = Mockery::mock('Mpdf\Pdf\Protection');
        /** @var LoggerInterface logger */
        $this->logger = Mockery::mock('Psr\Log\LoggerInterface');

        $this->metadataWriter = new MetadataWriter($this->mpdf, $this->writer, $this->form, $this->protection, $this->logger);
    }

    protected function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }
}