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
 * Provides logging to STDERR.
 */
class StdErrLogger extends StreamLogger {

	public function __construct() {

		if( !defined('STDERR') )
			throw new Exception('STDERR stream not available');

		parent::__construct(STDERR);

	}

}

// EOF