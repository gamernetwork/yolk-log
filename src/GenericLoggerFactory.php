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

use yolk\contracts\log\LoggerFactory;
use yolk\contracts\log\Logger;

class GenericLoggerFactory implements LoggerFactory {

	protected $classes;

	public function __construct( array $classes = [] ) {

		$this->classes = $classes + [
			'php'    => __NAMESPACE__. '\adapter\PHPLogger',
			'file'   => __NAMESPACE__. '\adapter\FileLogger',
			'stderr' => __NAMESPACE__. '\adapter\StdErrLogger',
			'stdout' => __NAMESPACE__. '\adapter\StdOutLogger',
			'syslog' => __NAMESPACE__. '\adapter\SysLogger',
			'null'   => __NAMESPACE__. '\adapter\NullLogger',
		];

	}

	public function create( $config ) {

		// if config isn't an array then treat it as the log type
		// - some adapters don't require configuration options
		if( !is_array($config) ) {
			$config = array(
				'type' => $config,
			);
		}
		
		$config = $config + array(
			'type'   => '',
			'threshold' => LogLevel::WARNING,
		);

		switch ( $config['type'] ) {
			case 'php':
				$log = $this->createPHPLogger($config['threshold']);
				break;

			case 'file':
				$config = $config + array('file' => '');
				$log = $this->createFileLogger($config['file'], $config['threshold']);
				break;

			case 'syslog':
				$config = $config + array('prefix' => '');
				$log = $this->createSysLogger($config['prefix'], $config['threshold']);
				break;

			case 'stderr':
				$log = $this->createStdErrLogger($config['threshold']);
				break;

			case 'stdout':
				$log = $this->createStdOutLogger($config['threshold']);
				break;

			case 'null':
				$log = $this->createNullLogger($config['threshold']);
				break;

			default:
				throw new Exception("Invalid logger type: {$type}");
		}

		return $log;

	}

	public function createPHPLogger( $threshold = LogLevel::WARNING ) {
		return $this->createLogger('php')
			->setThreshold($threshold);
	}

	public function createFileLogger( $file, $threshold = LogLevel::WARNING ) {
		return $this->createLogger('file', $file)
			->setThreshold($threshold);
	}

	public function createSysLogger( $prefix = '', $threshold = LogLevel::WARNING ) {
		return $this->createLogger('syslog', $prefix)
			->setThreshold($threshold);
	}

	public function createStdOutLogger( $threshold = LogLevel::WARNING ) {
		return $this->createLogger('stdout')
			->setThreshold($threshold);
	}

	public function createStdErrLogger( $threshold = LogLevel::WARNING ) {
		return $this->createLogger('stderr')
			->setThreshold($threshold);
	}

	public function createNullLogger( $threshold = LogLevel::WARNING ) {
		return $this->createLogger('null')
			->setThreshold($threshold);
	}

	protected function createLogger( $type, $arg1 = null, $arg2 = null, $arg3 = null ) {

		// check the class exists before we try and use it
		if( !class_exists($class = $this->classes[$type]) )
			throw new Exception(sprintf("Class '\\%s' not found for logger type '%s'", $class, $type));

		// create a new instance
		$log = new $class($arg1, $arg2, $arg3);

		// check the instance implements the correct interface
		if( !$log instanceof Logger )
			throw new Exception(sprintf("Class '\\%s' does not implement yolk\contracts\log\Logger", $class));

		return $log;

	}

}

// EOF