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

use yolk\log\AbstractLogger;
use yolk\log\Exception;

/**
 * Provides logging to a stream resource.
 */
abstract class StreamLogger extends AbstractLogger {

	/**
	 * Open log file.
	 * @var resource
	 */
	protected $stream;

	/**
	 * Create a logger that outputs to the specified stream resource
	 * @param resource $stream
	 */
	public function __construct( $stream ) {

		if( !is_resource($stream) || (get_resource_type($stream) != 'stream') )
			throw new Exception('Not a valid stream: '. gettype($stream));

		$this->stream = $stream;

		parent::__construct();

	}

	protected function output( $level, $msg ) {
		fwrite($this->stream, $msg);
	}

}

// EOF