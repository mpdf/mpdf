<?php

namespace Mpdf\Logger\Writer;

use Mpdf\Logger\Filter\FilterInterface;
use Mpdf\Logger\Formatter\FormatterInterface;

/**
 * Logger writer prototype
 */
abstract class AbstractWriter implements WriterInterface
{
    /**
     * Formatter
     *
     * @var FormatterInterface
     */
    protected $formatter;

    /**
     * Filters list
     *
     * @var FilterInterface[]
     */
    protected $filters = [];

    /**
     * Writes log event
     *
     * @param  array $event
     * @return void
     */
    public function write(array $event)
    {
        foreach ($this->filters as $filter) {
            if (!$filter->filter($event)) {
                return;
            }
        }

        $this->writeLog($event);
    }

    /**
     * Adds filter into list
     *
     * @param  FilterInterface $filter
     * @return mixed
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * Clears filters list
     *
     * @return void
     */
    public function clearFilters()
    {
        $this->filters = [];
    }

    /**
     * Sets writer formatter
     *
     * @param  FormatterInterface $formatter
     * @return void
     */
    public function setFormatter(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Shutdown action
     *
     * @return void
     */
    public function shutdown()
    {

    }

    /**
     * Log writing realisation
     *
     * @param  array $event
     * @return void
     */
    abstract protected function writeLog(array $event);
}
