<?php

namespace Mpdf\Logger\Formatter;

/**
 * Logger writer formatter interface
 */
interface FormatterInterface
{
    /**
     * Formats single log message
     *
     * @param  array $event
     * @return string
     */
    public function format(array $event);
}
