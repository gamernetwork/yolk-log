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

use Psr\Log\LoggerInterface;

/**
 * Provides logging capability to a class.
 */
trait LoggerAwareTrait {

	/**
	 * Log instance.
	 * @var \Psr\Log\LoggerInterface
	 */
	protected $log;
	
	/**
	 * Inject a logger object.
	 *
	 * @param   \Psr\Log\LoggerInterface   $log
	 * @return  self
	 */
	public function setLogger( LoggerInterface $log = null ) {
		$this->log = $log;
		return $this;
	}

}

// EOF