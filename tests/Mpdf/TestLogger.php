<?php

namespace Mpdf;

/**
 * Copied from PSR repository where not available since 2.x
 */
class TestLogger extends \Psr\Log\AbstractLogger
{

	/**
	 * @var mixed[]
	 */
	public $records = [];

	public $recordsByLevel = [];

	/**
	 * @inheritdoc
	 */
	public function log($level, $message, array $context = [])
	{
		$record = [
			'level' => $level,
			'message' => $message,
			'context' => $context,
		];

		$this->recordsByLevel[$record['level']][] = $record;
		$this->records[] = $record;
	}

	public function hasRecords($level)
	{
		return isset($this->recordsByLevel[$level]);
	}

	public function hasRecord($record, $level)
	{
		if (is_string($record)) {
			$record = ['message' => $record];
		}
		return $this->hasRecordThatPasses(function ($rec) use ($record) {
			if ($rec['message'] !== $record['message']) {
				return false;
			}
			if (isset($record['context']) && $rec['context'] !== $record['context']) {
				return false;
			}
			return true;
		}, $level);
	}

	public function hasRecordThatContains($message, $level)
	{
		return $this->hasRecordThatPasses(function ($rec) use ($message) {
			return strpos($rec['message'], $message) !== false;
		}, $level);
	}

	public function hasRecordThatMatches($regex, $level)
	{
		return $this->hasRecordThatPasses(function ($rec) use ($regex) {
			return preg_match($regex, $rec['message']) > 0;
		}, $level);
	}

	public function hasRecordThatPasses(callable $predicate, $level)
	{
		if (!isset($this->recordsByLevel[$level])) {
			return false;
		}
		foreach ($this->recordsByLevel[$level] as $i => $rec) {
			if (call_user_func($predicate, $rec, $i)) {
				return true;
			}
		}
		return false;
	}

	public function __call($method, $args)
	{
		if (preg_match('/(.*)(Debug|Info|Notice|Warning|Error|Critical|Alert|Emergency)(.*)/', $method, $matches) > 0) {
			$genericMethod = $matches[1] . ('Records' !== $matches[3] ? 'Record' : '') . $matches[3];
			$level = strtolower($matches[2]);
			if (method_exists($this, $genericMethod)) {
				$args[] = $level;
				return call_user_func_array([$this, $genericMethod], $args);
			}
		}
		throw new \BadMethodCallException('Call to undefined method ' . get_class($this) . '::' . $method . '()');
	}

	public function reset()
	{
		$this->records = [];
		$this->recordsByLevel = [];
	}

}
