<?php

namespace Mpdf\Logger\Writer;

use Mpdf\Logger\Filter\FilterInterface;
use Mpdf\Logger\Formatter\FormatterInterface;

/**
 * Logger writer interface
 */
interface WriterInterface
{
    /**
     * Writes log event
     *
     * @param  array $event
     * @return void
     */
    public function write(array $event);

    /**
     * Adds filter into list
     *
     * @param  FilterInterface $filter
     * @return mixed
     */
    public function addFilter(FilterInterface $filter);

    /**
     * Clears filters list
     *
     * @return void
     */
    public function clearFilters();

    /**
     * Sets writer formatter
     *
     * @param  FormatterInterface $formatter
     * @return void
     */
    public function setFormatter(FormatterInterface $formatter);

    /**
     * Shutdown action
     *
     * @return void
     */
    public function shutdown();
}
