<?php
/**
 *
 *
 * @since 1.0
 *
 * @category   WordPress\Plugin
 * @package
 * @subpackage
 * @author     Steven A. Zahm
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2024, Steven A. Zahm
 * @link       https://connections-pro.com/
 */
declare( strict_types=1 );

namespace Connections_Directory\Extension\Click_To_Call_And_SMS;

/**
 * Trait Singleton
 *
 * @package BB_Care_Services
 */
trait Singleton {

	/**
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * @return self
	 */
	final public static function getInstance(): self {

		if ( is_null( self::$instance ) ) {
			self::$instance = new static;
		}

		return self::$instance;
	}

	/**
	 * Constructor protected from the outside
	 */
	final private function __construct() {

		if ( method_exists( $this, 'initialize' ) ) {
			$this->initialize();
		}
	}

	/**
	 * prevent the instance from being cloned
	 *
	 * @throws \LogicException
	 */
	final public function __clone() {
		throw new \LogicException( 'A singleton must not be cloned!' );
	}

	/**
	 * prevent from being serialized
	 *
	 * @throws \LogicException
	 */
	final public function __sleep() {
		throw new \LogicException( 'A singleton must not be serialized!' );
	}

	/**
	 * prevent from being unserialized
	 *
	 * @throws \LogicException
	 */
	final public function __wakeup() {
		throw new \LogicException( 'A singleton must not be unserialized!' );
	}

	/**
	 * Destruct your instance
	 *
	 * @return void
	 */
	final public static function destroy() {
		static::$instance = null;
	}
}
