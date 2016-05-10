<?php

namespace Mpdf\Logger\Filter;

use Mpdf\Logger\Logger;

/**
 * Regular expression message filter
 */
class Regex implements FilterInterface
{
    /**
     * Regular expression string
     *
     * @var string
     */
    protected $regex;

    /**
     * @param  string $regex
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($regex)
    {
        if (preg_match($regex, 'string') === false) {
            $message = sprintf(
                'Invalid regexp %s',
                $regex
            );
            throw new Exception\InvalidArgumentException($message);
        }

        $this->regex = $regex;
    }

    /**
     * Return true to accept message and false otherwise
     *
     * @param  array $event
     * @return boolean
     */
    public function filter(array $event)
    {
        $record = $event[Logger::LOG_EVENT_MESSAGE];
        $record = is_array($record) ? var_export($record, true) : $record;

        return preg_match($this->regex, $record) > 0;
    }
}
