<?php

namespace Mpdf\Logger\Filter;

/**
 * Log writer filter interface
 */
interface FilterInterface
{
    /**
     * Return true to accept message and false otherwise
     *
     * @param  array $event
     * @return boolean
     */
    public function filter(array $event);
}
