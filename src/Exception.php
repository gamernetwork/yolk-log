<?php
/*
 * This file is part of Yolk - Gamer Network's PHP Framework.
 *
 * Copyright (c) 2013 Gamer Network Ltd.
 * 
 * Distributed under the MIT License, a copy of which is available in the
 * LICENSE file that was bundled with this package, or online at:
 * https://github.com/gamernetwork/yolk-logger
 */

namespace yolk\log;

/**
 * Base logging exception.
 */
class Exception extends \Exception {

	/**
	 * Returns a simple string representation of a variable for use in debug/error messages.
	 *
	 * @param  mixed   $var
	 * @return string
	 */
	public static function info( $var ) {
		if( is_null($var) ) {
			$info = 'null';
		}
		elseif( is_scalar($var) ) {
			ob_start();
			var_dump($var);
			$info = ob_get_clean();
		}
		elseif( is_array($var) ) {
			$info = 'array('. count($var). ')';
		}
		elseif( is_object($var) ) {
			$info = '\\'. get_class($var);
		}
		elseif( is_resource($var) ) {
			$info = 'resource('. get_resource_type($var). ')';
		}
		// should never get here
		else {
			$info = gettype($var);
		}
		return trim($info);
	}

}

// EOF