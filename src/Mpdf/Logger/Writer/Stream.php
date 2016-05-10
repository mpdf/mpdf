<?php

namespace Mpdf\Logger\Writer;

use Mpdf\Logger\Formatter\FormatterInterface;

/**
 * Stream log writer
 */
class Stream extends AbstractWriter
{
    /**
     * Stream for log
     *
     * @var resource
     */
    protected $stream;

    /**
     * Default stream open mode
     *
     * @var string
     */
    protected $defaultMode = 'a';

    /**
     * Default separator string between log entries
     *
     * @var string
     */
    protected $separator = PHP_EOL;

    /**
     * @param  FormatterInterface $formatter
     * @param  array              $filters
     * @param  resource|string    $stream
     * @param  string|null        $mode
     * @param  string|null        $separator
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function __construct(
        FormatterInterface $formatter,
        array $filters,
        $stream,
        $mode = null,
        $separator = null
    ) {
        if (!is_null($separator)) {
            $this->setSeparator($separator);
        }

        if (is_resource($stream)) {
            if (get_resource_type($stream) != 'stream') {
                $message = sprintf(
                    'Unexpected resource type %s. Expected %s',
                    get_resource_type($stream),
                    'stream'
                );
                throw new Exception\InvalidArgumentException($message);
            }

            $this->stream = $stream;
        } else {
            if (is_null($mode)) {
                $mode = $this->defaultMode;
            }

            $this->stream = fopen($stream, $mode);

            if (!$this->stream) {
                $message = 'Stream open error';
                throw new Exception\RuntimeException($message);
            }
        }

        $this->setFormatter($formatter);

        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }
    }

    /**
     * Sets separator string
     *
     * @param  string $separator
     * @return void
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;
    }

    /**
     * Closes stream resource on shutdown
     *
     * @return void
     */
    public function shutdown()
    {
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }
    }

    /**
     * Log writing realisation
     *
     * @param  array $event
     * @return void
     */
    protected function writeLog(array $event)
    {
        $record = $this->formatter->format($event) . $this->separator;
        fwrite($this->stream, $record);
    }
}
