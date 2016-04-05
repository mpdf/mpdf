<?php

namespace Mpdf\Logger\Formatter;

use DateTime;
use Traversable;

/**
 * Simple string formatter
 */
class Simple implements FormatterInterface
{
    /**
     * String template
     *
     * @var string
     */
    protected $template;

    /**
     * Date/time format string
     *
     * @var string
     */
    protected $dateFormat;

    /**
     * @param string $template
     * @param string $dateFormat
     */
    public function __construct($template, $dateFormat)
    {
        $this->template   = $template;
        $this->dateFormat = $dateFormat;
    }

    /**
     * Formats single log message into a single string
     *
     * @param  array $event
     * @return string
     */
    public function format(array $event)
    {
        $out = $this->template;

        array_walk(
            $event,
            function ($value, $key) use (&$out) {
                $out = str_replace("%$key%", $this->formatValue($value), $out);
            }
        );

        return $out;
    }

    /**
     * Gets date format string
     *
     * @return string
     */
    public function getDateTimeFormat()
    {
        return $this->dateFormat;
    }

    /**
     * Formats given argument into string depends on type
     *
     * @param  mixed $value
     * @return string
     */
    protected function formatValue($value)
    {
        switch (true) {
            case is_scalar($value) || is_null($value):
                return $value;
            case $value instanceof DateTime:
                return $value->format($this->getDateTimeFormat());
            case $value instanceof Traversable:
                return $this->toString(iterator_to_array($value));
            case is_array($value):
                return $this->toString($value);
            case is_object($value) && !method_exists($value, '__toString'):
                return sprintf('object %s: %s', get_class($value), $this->toString($value));
            case is_resource($value):
                return sprintf('resource %s', get_resource_type($value));
            default:
                return (string) $value;
        }
    }

    /**
     * Array formatter
     *
     * @param  array $array
     * @return string
     */
    protected function toString(array $array)
    {
        return !empty($array) ? json_encode($array) : '';
    }
}
