<?php
/**
 * Content Block to render the Click to SMS metabox.
 *
 * @since      1.0
 *
 * @category   WordPress\Plugin
 * @package    Connections_Directory\Extension\Click_To_Call_And_SMS
 * @subpackage Connections_Directory\Extension\Click_To_Call_And_SMS\Content_Blocks
 * @author     Steven A. Zahm
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2024, Steven A. Zahm
 * @link       https://connections-pro.com/
 */
declare( strict_types=1 );

namespace Connections_Directory\Extension\Click_To_Call_And_SMS\Content_Blocks;

use cnEntry;
use Connections_Directory\Content_Block;
use Connections_Directory\Extension\Click_To_Call_And_SMS\Plugin;

final class SMS extends Content_Block {

	/**
	 * @since 1.0
	 * @var string
	 */
	const ID = 'click_to_sms';

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 *
	 * @param string $id The Content Block ID.
	 */
	public function __construct( string $id ) {

		$atts = array(
			'name'                => __( 'Click to SMS', 'connections_call_sms' ),
			'register_option'     => true,
			'permission_callback' => array( $this, 'permission' ),
			'heading'             => __( 'Click to SMS', 'connections_call_sms' ),
			'render_heading'      => false,
		);

		parent::__construct( $id, $atts );
	}

	/**
	 * Callback for the `permission_callback` parameter.
	 *
	 * @since 1.0
	 *
	 * @return bool
	 */
	public function permission(): bool {

		return true;
	}

	/**
	 * Renders the Content Block.
	 *
	 * @since 1.0
	 */
	public function content() {

		$entry = $this->getObject();

		if ( ! $entry instanceof cnEntry ) {

			return;
		}

		$number = (string) $entry->getMeta(
			array(
				'key'    => '_click_to_sms_phone_number',
				'single' => true,
			)
		);

		if ( 0 === strlen( $number ) ) {

			return;
		}

		$message = $entry->getMeta(
			array(
				'key'    => '_click_to_sms_message',
				'single' => true,
			)
		);

		$text = $entry->getMeta(
			array(
				'key'    => '_click_to_sms_text',
				'single' => true,
			)
		);

		echo Plugin::getInstance()->generateSMSLink( $number, $message, $text );
	}
}
