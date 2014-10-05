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

use yolk\log\Exception;

/**
 * Provides logging to the filesystem.
 */
class FileLogger extends StreamLogger {
   
	/**
	 * Open a file on the filesystem.
	 * @param string $file   full path and file name of target file
	 */
	public function __construct( $file ) {

		if( !$file )
			throw new \InvalidArgumentException('No file specified');
		elseif( !$stream = fopen($file, 'a+') )
			throw new Exception("Unable to open log file: {$file}");

		parent::__construct($stream);

	}

}

// EOF