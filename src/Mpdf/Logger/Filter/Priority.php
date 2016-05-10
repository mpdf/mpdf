<?php

namespace Mpdf\Logger\Filter;

use Mpdf\Logger\Logger;

/**
 * Log level priority filter
 */
class Priority implements FilterInterface
{
    /**
     * Priority
     *
     * @var integer
     */
    protected $priority;

    /**
     * Comparison operator
     *
     * @var string
     */
    protected $operator;

    /**
     * @param  integer $priority
     * @param  string  $operator
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($priority, $operator)
    {
        if (!is_integer($priority)) {
            $message = 'Priority must be integer';
            throw new Exception\InvalidArgumentException($message);
        }

        $this->priority = $priority;
        $this->operator = (string) $operator;
    }

    /**
     * Return true to accept message and false otherwise
     *
     * @param  array $event
     * @return boolean
     */
    public function filter(array $event)
    {
        version_compare($event[Logger::LOG_EVENT_PRIORITY], $this->priority, $this->operator);
    }
}
