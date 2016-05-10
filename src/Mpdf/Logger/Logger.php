<?php

namespace Mpdf\Logger;

use DateTime;
use Exception;
use Mpdf\Logger\Writer\WriterInterface;
use Psr\Log;

/**
 * MPDF injectable logger
 */
class Logger extends Log\AbstractLogger implements Log\LoggerInterface
{
    const LOG_EVENT_TIMESTAMP = 'timestamp';
    const LOG_EVENT_LEVEL     = 'level';
    const LOG_EVENT_PRIORITY  = 'priority';
    const LOG_EVENT_MESSAGE   = 'message';
    const LOG_EVENT_CONTEXT   = 'context';

    /**
     * Writers for logger
     *
     * @var WriterInterface[]
     */
    protected $writers = [];

    /**
     * Priority levels
     *
     * @var array
     */
    protected $priorities = [
        Log\LogLevel::EMERGENCY => 1,
        Log\LogLevel::ALERT     => 2,
        Log\LogLevel::CRITICAL  => 3,
        Log\LogLevel::ERROR     => 4,
        Log\LogLevel::WARNING   => 5,
        Log\LogLevel::NOTICE    => 6,
        Log\LogLevel::INFO      => 7,
        Log\LogLevel::DEBUG     => 8,
    ];

    /**
     * @param WriterInterface[] $writers
     */
    public function __construct(array $writers)
    {
        array_walk(
            $writers,
            function (WriterInterface $writer) {
                $this->addWriter($writer);
            }
        );
    }

    /**
     * @return void
     * @throws Exception
     */
    public function __destruct()
    {
        try {
            array_walk(
                $this->writers,
                function (WriterInterface $writer) {
                    $writer->shutdown();
                }
            );
        } catch (Exception $exception) {

        }
    }

    /**
     * Logs message
     *
     * @param  mixed  $level
     * @param  string $message
     * @param  array  $context
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        $timestamp = new DateTime();
        $event     = [
            self::LOG_EVENT_TIMESTAMP => $timestamp,
            self::LOG_EVENT_LEVEL     => $level,
            self::LOG_EVENT_PRIORITY  => $this->priorities[$level],
            self::LOG_EVENT_MESSAGE   => (string) $message,
            self::LOG_EVENT_CONTEXT   => $context,
        ];

        array_walk(
            $this->writers,
            function (WriterInterface $writer) use ($event) {
                $writer->write($event);
            }
        );
    }

    /**
     * Adds writer into list
     *
     * @param  WriterInterface $writer
     * @return void
     */
    public function addWriter(WriterInterface $writer)
    {
        $this->writers[] = $writer;
    }
}
