<?php
/*
 * This file is part of Yolk - Gamer Network's PHP Framework.
 *
 * Copyright (c) 2014 Gamer Network Ltd.
 * 
 * Distributed under the MIT License, a copy of which is available in the
 * LICENSE file that was bundled with this package, or online at:
 * https://github.com/gamernetwork/yolk-logger
 */

namespace yolk\log;

use yolk\contracts\log\Logger;

/**
 * Base logger class that defines the types of message that can be logged and
 * provides shortcut functions to log messages of a particular type.
 */
abstract class BaseLogger implements Logger {

	/**
	 * Messages above this level will not be logged.
	 * @var integer
	 */
	protected $threshold;

	/**
	 * Array of defined logging levels and their associated human-readable form.
	 * @var array
	 */
	protected $levels;

	public function __construct() {

		$this->setThreshold(LogLevel::WARNING);

		$this->levels = [
			LogLevel::EMERGENCY => 'emergency',
			LogLevel::ALERT     => 'alert',
			LogLevel::CRITICAL  => 'critical',
			LogLevel::ERROR     => 'error',
			LogLevel::WARNING   => 'warning',
			LogLevel::NOTICE    => 'notice',
			LogLevel::INFO      => 'info',
			LogLevel::DEBUG     => 'debug',
		];

	}

	/**
	 * Specify a threshold for logging messages.
	 * @param integer $level   new logging threshold level.
	 */
	public function setThreshold( $level ) {
		$this->threshold = $this->getLevel($level);
		return $this;
	}

	/**
	 * Get the current threshold.
	 * @return integer
	 */
	public function getThreshold() {
		return $this->threshold;
	}

	/**
     * System is unusable.
     * 
     * @param string   $msg
     * @param array    $context
     * @return $this
     */
    public function emergency( $msg, array $context = [] ) {
    	return $this->log(LogLevel::EMERGENCY, $msg, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string   $msg
     * @param array    $context
     * @return $this
     */
    public function alert( $msg, array $context = [] ) {
    	return $this->log(LogLevel::ALERT, $msg, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string   $msg
     * @param array    $context
     * @return $this
     */
    public function critical( $msg, array $context = [] ) {
    	return $this->log(LogLevel::CRITICAL, $msg, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string   $msg
     * @param array    $context
     * @return $this
     */
    public function error( $msg, array $context = [] ) {
    	return $this->log(LogLevel::ERROR, $msg, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string   $msg
     * @param array    $context
     * @return $this
     */
    public function warning( $msg, array $context = [] ) {
    	return $this->log(LogLevel::WARNING, $msg, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string   $msg
     * @param array    $context
     * @return $this
     */
    public function notice( $msg, array $context = [] ) {
    	return $this->log(LogLevel::NOTICE, $msg, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string   $msg
     * @param array    $context
     * @return $this
     */
    public function info( $msg, array $context = [] ) {
    	return $this->log(LogLevel::INFO, $msg, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string   $msg
     * @param array    $context
     * @return $this
     */
    public function debug( $msg, array $context = [] ) {
    	return $this->log(LogLevel::DEBUG, $msg, $context);
    }

	/**
	 * Log a message of a specific level.
	 * @param integer  $level     one of the LOG_* constants.
	 * @param string   $msg       the message to log.
	 * @param array    $context   array of context information.
	 * @return $this
	 */
	public function log( $level, $msg, array $context = [] ) {

		$level = $this->getLevel($level);

		if( $level <= $this->threshold ) {
			$this->output(
				$level,
				$this->buildMessage($level, $msg, $context)
			);
		}

		return $this;

	}

	/**
	 * Send the message to the logger's output.
	 * @param string   $msg
	 * @param integer  $level
	 */
	abstract protected function output( $level, $msg );

	/**
	 * Make sure we have a valid level.
	 * @param  integer|string $level a 
	 * @return integer
	 */
	protected function getLevel( $level ) {
		if( $l = (int) $level )
			return $l;
		elseif( $l = (int) array_search($level, $this->levels) )
			return $l;
		else
			throw new \InvalidArgumentException("Invalid log level: '{$level}'");
	}

	/**
	 * Builds a nice error message including the level and current date/time.
	 * @param integer  $level   one of the LOG_* constants.
	 * @param string   $msg     the message to log.
	 * @param array    $context array of strings to be interpolated into the message
	 */
	protected function buildMessage( $level, $msg, $context = [] ) {
		return sprintf(
			"%s %s %s: %s\n",
			date('Y-m-d H:i:s'),
			$this->getPrefix($level),
			$this->levels[$level],
			trim($this->interpolate($msg, $context))
		);
	}

	/**
	 * Replace the token placeholders in a message with the corresponding values from the content array.
	 * @param  string $msg     the log message
	 * @param  array  $context the values to be interpolated into the log message
	 * @return string          the combined log message
	 */
	protected function interpolate( $msg, array $context ) {
		$replace = [];
		foreach( $context as $k => $v ) {
			$replace['{'.$k.'}'] = $v;
		}
		return strtr($msg, $replace);
	}

	/**
	 * A simple text token to indicate the level of a logged message
	 * @param  integer $level
	 * @return string
	 */
	protected function getPrefix( $level ) {
		$prefixes = [
			LogLevel::EMERGENCY => '[!!]',
			LogLevel::ALERT     => '[!!]',
			LogLevel::CRITICAL  => '[!!]',
			LogLevel::ERROR     => '[**]',
			LogLevel::WARNING   => '[**]',
			LogLevel::NOTICE    => '[--]',
			LogLevel::INFO      => '[--]',
			LogLevel::DEBUG     => '[..]',
		];
		return isset($prefixes[$level]) ? $prefixes[$level] : '[??]';
	}

}

// EOF