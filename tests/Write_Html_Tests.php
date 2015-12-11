<?php

/**
 * Setup two classes we'll use in our unit tests
 */
class write_html_class
{
}

class write_html_string_class
{
    public function __toString()
    {
        return 'special';
    }
}


/**
 * Class Write_HTML_Tests
 *
 * Unit tests for our experimental Write_Html class which is a WIP refractoring of the
 * $mpdf->WriteHTML() method
 *
 * @group writehtml
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
     * Verify what types of variables are accepted to $mpdf->WriteHTML()
     *
     * @dataProvider provider_cast_type
     *
     * @param boolean $exception Whether we expect an exception or not
     * @param mixed   $html      The variable to test
     */
    public function test_cast_type($exception, $html)
    {
        $thrown = '';

        try {
            $this->mpdf->WriteHTML($html);
        } catch (MpdfException $e) {
            $thrown = $e->getMessage();
        }

        if ($exception) {
            $this->assertEquals('WriteHTML() required $html be an integer, float, string, boolean or a call with the __toString() magic method.', $thrown);
        } else {
            $this->assertEquals('', $thrown);
        }
    }

    /**
     * Data provider for test_cast_type
     *
     * @return array
     */
    public function provider_cast_type()
    {
        return array(
            array(false, 'This is my string'),
            array(false, 20),
            array(false, 125.52),
            array(false, false),
            array(true, array('item', 'item2')),
            array(true, new write_html_class()),
            array(false, new write_html_string_class()),
            array(true, null),
            array(false, ''),
        );
    }

}
