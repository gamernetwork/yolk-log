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

/**
 * Provides logging to the default PHP error log.
 */
class PHPLogger extends BaseLogger {

	protected function output( $level, $msg ) {
		error_log(rtrim($msg));
	}
   
}

// EOF