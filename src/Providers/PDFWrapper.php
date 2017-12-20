<?php
/**
 * Created by PhpStorm.
 * User: ghans
 * Date: 19/12/17
 * Time: 04:52 PM
 */

namespace Mpdf\Providers;


class PDFWrapper
{
    protected $mpdf;
    protected $render = false;
    protected $options;
    protected $html;
    protected $file;

    /**
     * PDFWrapper constructor.
     */
    public function __construct($mpdf)
    {
        $this->mpdf = $mpdf;
        $this->options = array();
    }

    /**Load a html string
     * @param $str
     * @param int $mode
     * @return $this
     */
    public function writeHTML($str)
    {
        $this->html = $str;
        $this->file = null;
        return $this;
    }

    /**Load a View
     * @param $view
     * @param array $data
     * @param array $mergeData
     * @return $this
     */
    public function loadView($view, $data = [], $mergeData = [])
    {
        $this->html = \View::make($view, $data, $mergeData)->render();
        $this->file = null;
        return $this;
    }

    /**Create a output pdf
     * @return mixed
     */
    public function output()
    {
        if ($this->html) {
            $this->mpdf->writeHTML($this->html);
        } elseif ($this->file) {
            $this->mpdf->WriteHTML($this->file);
        }

        return $this->mpdf->Output();
    }

    /**Save the PDF File
     * @param $filename
     * @return mixed
     */
    public function save($filename)
    {
        if ($this->html) {
            $this->mpdf->WriteHTML($this->html);
        } elseif ($this->file) {
            $this->mpdf->WriteHTML($this->file);
        }
        return $this->mpdf->Output($filename, 'F');
    }

    /**Generate a PDF and Download
     * @param string $filename
     * @return mixed
     */
    public function download($filename = 'document.pdf')
    {
        if ($this->html) {
            $this->mpdf->WriteHTML($this->html);
        } elseif ($this->file) {
            $this->mpdf->WriteHTML($this->file);
        }
        return $this->mpdf->Output($filename, 'D');
    }

    /**
     * Return a response with the PDF to show in the browser
     *
     * @param string $filename
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function stream($filename = 'document.pdf')
    {
        if ($this->html) {
            $this->mpdf->WriteHTML($this->html);
        } elseif ($this->file) {
            $this->mpdf->WriteHTML($this->file);
        }
        return $this->mpdf->Output($filename, 'I');
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->mpdf, $name), $arguments);
    }


}