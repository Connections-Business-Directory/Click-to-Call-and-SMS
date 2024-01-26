<?php
/**
 * Shortcode to render a Tel link.
 *
 * @since 1.0
 *
 * @category   WordPress\Plugin
 * @package    Connections_Directory\Extension\Click_To_Call_And_SMS
 * @subpackage Connections_Directory\Extension\Click_To_Call_And_SMS\Shortcode
 * @author     Steven A. Zahm
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2024, Steven A. Zahm
 * @link       https://connections-pro.com/
 */
declare( strict_types=1 );
namespace Connections_Directory\Extension\Click_To_Call_And_SMS\Shortcode;

use Connections_Directory\Extension\Click_To_Call_And_SMS\Plugin;
use Connections_Directory\Request;
use Connections_Directory\Shortcode\Do_Shortcode;
use Connections_Directory\Shortcode\Get_HTML;

final class Tel {

	use Do_Shortcode;
	use Get_HTML;

	/**
	 * The shortcode tag.
	 *
	 * @since 1.0
	 */
	const TAG = 'tel_';

	/**
	 * The shortcode attributes.
	 *
	 * @since 1.0
	 *
	 * @var array
	 */
	private array $attributes;

	/**
	 * The content from an enclosing shortcode.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	private string $content;

	/**
	 * Register the shortcode.
	 *
	 * @since 1.0
	 */
	public static function add() {

		/*
		 * Do not register the shortcode when doing ajax requests.
		 * This is primarily implemented so the shortcodes are not run during Yoast SEO page score admin ajax requests.
		 * The page score can cause the ajax request to fail and/or prevent the page from saving when page score is
		 * being calculated on the output from the shortcode.
		 */
		if ( ! Request::get()->isAjax() ) {

			add_filter( 'pre_do_shortcode_tag', array( __CLASS__, 'maybeDoShortcode' ), 10, 4 );
			add_shortcode( self::TAG, array( __CLASS__, 'instance' ) );
		}
	}

	/**
	 * Generate the shortcode HTML.
	 *
	 * @since 1.0
	 *
	 * @param array  $untrusted The shortcode arguments.
	 * @param string $content   The shortcode content.
	 * @param string $tag       The shortcode tag.
	 */
	public function __construct( array $untrusted, string $content = '', string $tag = self::TAG ) {

		$defaults  = $this->getDefaultAttributes();
		$untrusted = shortcode_atts( $defaults, $untrusted, $tag );

		$this->attributes = $this->prepareAttributes( $untrusted );
		$this->content    = $content;
		$this->html       = $this->generateHTML();
	}

	/**
	 * Callback for `add_shortcode()`
	 *
	 * @internal
	 * @since 1.0
	 *
	 * @param array  $atts    The shortcode arguments.
	 * @param string $content The shortcode content.
	 * @param string $tag     The shortcode tag.
	 *
	 * @return static
	 */
	public static function instance( array $atts, string $content = '', string $tag = self::TAG ): self {

		return new self( $atts, $content, $tag );
	}

	/**
	 * The shortcode attribute defaults.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	private function getDefaultAttributes(): array {

		return array(
			'number' => '#',
		);
	}

	/**
	 * Parse and prepare the shortcode attributes.
	 *
	 * @since 1.0
	 *
	 * @param array $untrusted The shortcode arguments.
	 *
	 * @return array
	 */
	private function prepareAttributes( array $untrusted ): array {

		return $untrusted;
	}

	/**
	 * Generate the shortcode HTML.
	 *
	 * @since 1.0
	 */
	private function generateHTML(): string {

		return Plugin::getInstance()->generateTelLink(
			$this->attributes['number'],
			$this->content
		);
	}
}
