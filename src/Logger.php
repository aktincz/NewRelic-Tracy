<?php

namespace VrtakCZ\NewRelic\Tracy;

class Logger implements \Tracy\ILogger
{

	/** @var \Tracy\ILogger */
	private $oldLogger;

	/** @var string[] */
	private $logLevels;

	/**
	 * @param array $logLevels
	 */
	public function __construct(array $logLevels)
	{
		$this->oldLogger = \Tracy\Debugger::getLogger();
		$this->logLevels = $logLevels;
	}

	/**
	 * @param mixed $message
	 * @param string $level
	 * @return string|null logged error filename
	 */
	public function log($message, string $level = self::INFO): ?string
	{
		$exceptionFile = $this->oldLogger->log($message, $level);

		if (in_array($level, $this->logLevels)) {
			if (is_array($message)) {
				$message = implode(' ', $message);
			}

			newrelic_notice_error($message);
		}

		return $exceptionFile;
	}

}
