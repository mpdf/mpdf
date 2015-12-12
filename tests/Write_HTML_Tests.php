<?php

/**
 * Class Write_HTML_Tests
 *
 * Unit tests for our experimental Write_Html class which is a WIP refractoring of the
 * $mpdf->WriteHTML() method
 */
class Write_HTML_Tests extends PHPUnit_Framework_TestCase
{
    private $mpdf;

    public function setup()
    {
        parent::setup();

        $this->mpdf = new mPDF();
    }

    /**
     * Check if our buffers reset or stay the same when using
     * our $init boolean switch
     */
    public function test_maybe_reset_buffers()
    {
        $html_parser = new Write_Html($this->mpdf);

        /* Set a fouble of our parameters and verify they stay the same*/
        $this->mpdf->headerbuffer         = 'set';
        $this->mpdf->textbuffer           = array('item1', 'item2');
        $this->mpdf->lastblocklevelchange = 5;

        /* Run our test */
        $html_parser->maybe_reset_buffers(false);

        /* Verify nothing changed */
        $this->assertSame('set', $this->mpdf->headerbuffer);
        $this->assertSame(2, count($this->mpdf->textbuffer));
        $this->assertSame(5, $this->mpdf->lastblocklevelchange);

        /* Run our test again but reset the buffers */
        $html_parser->maybe_reset_buffers(true);

        /* Verify it was reset */
        $this->assertSame('', $this->mpdf->headerbuffer);
        $this->assertSame(0, count($this->mpdf->textbuffer));
        $this->assertSame(0, $this->mpdf->lastblocklevelchange);
    }

    /**
     * Check we wrap our styles in a <style> tag correctly
     *
     * @param string $expected
     * @param string $styles
     *
     * @dataProvider provider_wrap_header_css
     */
    public function test_wrap_header_css($expected, $styles)
    {
        $html_parser = new Write_Html($this->mpdf);

        $this->assertEquals($expected, $html_parser->wrap_header_css($styles));
    }

    /**
     * Data provider for our test_wrap_header_css() method
     *
     * @return array
     */
    public function provider_wrap_header_css()
    {
        return array(
            array('<style>  </style>', ''),
            array('<style> body { font-size: 12pt; } </style>', 'body { font-size: 12pt; }'),
            array("<style> #my-id {\n  background-color: black;\n   line-height: 30px;\n} </style>", "#my-id {\n  background-color: black;\n   line-height: 30px;\n}"),
        );
    }

}
