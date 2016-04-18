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

		$config = $this->checkConfig($config);

		$factories = [
			'php'    => 'createPHPLogger',
			'file'   => 'createFileLogger',
			'syslog' => 'createSysLogger',
			'stderr' => 'createStdErrLogger',
			'stdout' => 'createStdOutLogger',
			'null'   => 'createNullLogger',
		];

		if( empty($factories[$config['type']]) )
			throw new Exception("Invalid logger type: {$config['type']}");

		$factory = $factories[$config['type']];

		if( $config['type'] == 'file' ) {
			$config = $config + ['file' => ''];
			return $this->$factory($config['file'], $config['threshold']);
		}
		elseif( $config['type'] == 'syslog' ) {
			$config = $config + ['prefix' => ''];
			return $this->$factory($config['prefix'], $config['threshold']);
		}

		return $this->$factory($config['threshold']);

	}

	/**
	 * Create a logger that outputs to the standard PHP error log.
	 * @param  integer $threshold
	 * @return Logger
	 */
	public function createPHPLogger( $threshold = LogLevel::WARNING ) {
		return $this->createLogger('php')
			->setThreshold($threshold);
	}

	/**
	 * Create a logger that outputs to the specified file.
	 * @param  integer $threshold
	 * @return Logger
	 */
	public function createFileLogger( $file, $threshold = LogLevel::WARNING ) {
		return $this->createLogger('file', $file)
			->setThreshold($threshold);
	}

	/**
	 * Create a logger that outputs to Syslog.
	 * @param  integer $threshold
	 * @return Logger
	 */
	public function createSysLogger( $prefix = '', $threshold = LogLevel::WARNING ) {
		return $this->createLogger('syslog', $prefix)
			->setThreshold($threshold);
	}

	/**
	 * Create a logger that outputs to StdOut.
	 * The StdOut logger is only available when running via the command-line.
	 * @param  integer $threshold
	 * @return Logger
	 */
	public function createStdOutLogger( $threshold = LogLevel::WARNING ) {
		return $this->createLogger('stdout')
			->setThreshold($threshold);
	}

	/**
	 * Create a logger that outputs to StdErr.
	 * The StdErr logger is only available when running via the command-line.
	 * @param  integer $threshold
	 * @return Logger
	 */
	public function createStdErrLogger( $threshold = LogLevel::WARNING ) {
		return $this->createLogger('stderr')
			->setThreshold($threshold);
	}

	/**
	 * Create a logger that performs no output.
	 * @param  integer $threshold
	 * @return Logger
	 */
	public function createNullLogger( $threshold = LogLevel::WARNING ) {
		return $this->createLogger('null')
			->setThreshold($threshold);
	}

	/**
	 * Ensure the configuration is in a consistant format.
	 * @param  array|string $config
	 * @return array
	 */
	protected function checkConfig( $config ) {

		// if config isn't an array then treat it as the log type
		// - some adapters don't require configuration options
		if( !is_array($config) ) {
			$config = [
				'type' => $config,
			];
		}

		$config = $config + [
			'type'   => '',
			'threshold' => LogLevel::WARNING,
		];

		return $config;

	}

	/**
	 * Create a Logger instance.
	 * @param  string $type
	 * @param  mixed $arg1
	 * @param  mixed $arg2
	 * @param  mixed $arg3
	 * @return Logger
	 */
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