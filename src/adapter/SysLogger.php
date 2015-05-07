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

namespace yolk\log\adapter;

use yolk\log\BaseLogger;
use yolk\log\LogLevel;

/**
 * Provides logging to syslog.
 */
class SysLogger extends BaseLogger {
	
	/**
	 * Value prefixed to all messages.
	 * @var string
	 */
	protected $prefix;
	
	/**
	 * Create a syslogger.
	 * @param string $prefix   value prefixed to all message.
	 */
	public function __construct( $prefix = '' ) {
		parent::__construct();
		$prefix = trim($prefix);
		$this->prefix = $prefix ? $prefix. ': ' : '';
	}
	
	protected function output( $level, $msg ) {

		// map logging levels to syslog priority levels
		$priorities = array(
			LogLevel::EMERGENCY => LOG_EMERG,
			LogLevel::ALERT     => LOG_ALERT,
			LogLevel::CRITICAL  => LOG_CRIT,
			LogLevel::ERROR     => LOG_ERR,
			LogLevel::WARNING   => LOG_WARNING,
			LogLevel::NOTICE    => LOG_NOTICE,
			LogLevel::INFO      => LOG_INFO,
			LogLevel::DEBUG     => LOG_DEBUG,
		);

		$priority = isset($priorities[$level]) ? $priorities[$level] : LOG_INFO;

		syslog($priority, $this->prefix. $msg);

	}
   
}

// EOF