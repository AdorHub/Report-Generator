<?php

namespace App\Helpers;

class Logger
{
	const DEBUG = 100;
	const INFO = 200;
	const WARNING = 300;
	const ERROR = 400;

	private $logFile;

	public function __construct($logFile)
	{
		$this->logFile = $logFile;
	}

	public function log(int $level, string $message)
	{
		switch ($level) {
			case self::DEBUG:
				$prefix = '[DEBUG]';
				break;
			case self::INFO:
				$prefix = '[INFO]';
				break;
			case self::WARNING:
				$prefix = '[WARNING]';
				break;
			case self::ERROR:
				$prefix = '[ERROR]';
				break;
			default:
				$prefix = '[UNKNOWN]';
		}
		$timestamp = date('Y-m-d H-i-s');
		$formattedMessage = "$timestamp $prefix $message";
		file_put_contents($this->logFile, $formattedMessage . "\n", FILE_APPEND);
	}

	public function debug(string $message)
	{
		$this->log(self::DEBUG, $message);
	}

	public function info(string $message)
	{
		$this->log(self::INFO, $message);
	}

	public function warn(string $message)
	{
		$this->log(self::WARNING, $message);
	}

	public function error(string $message)
	{
		$this->log(self::ERROR, $message);
	}

	public function registerErrorHandler()
	{
		set_error_handler([$this, 'handleError']);
	}

	public function registerFatalErrorHandler()
	{
		register_shutdown_function([$this, 'handleFatalError']);
	}

	public function handleError($errno, $errstr, $errfile, $errline)
	{
		switch ($errno) {
			case E_USER_ERROR:
				$this->error("$errstr in $errfile:$errline");
				break;
			case E_WARNING:
				$this->warn("$errstr in $errfile:$errline");
				break;
			case E_NOTICE:
				$this->debug("$errstr in $errfile:$errline");
				break;
			default:
				$this->info("$errstr in $errfile:$errline");
		}
	}

	public function handleFatalError()
	{
		$lastError = error_get_last();
		if ($lastError !== null) {
			$this->error("Fatal error: {$lastError['message']} in {$lastError['file']}:{$lastError['line']}");
		}
	}
}