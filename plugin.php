<?php
/**
 * An extension for Connections Business Directory which adds clink to SMS and Call link shortcodes.
 *
 * @package   Connections Business Directory Extension - Click To Call And SMS
 * @category  Extension
 * @author    Steven A. Zahm
 * @license   GPL-2.0+
 * @link      https://connections-pro.com
 * @copyright Copyright (c) 2024 Steven A. Zahm
 *
 * @wordpress-plugin
 * Plugin Name:       Connections Business Directory Extension - Click To Call And SMS
 * Plugin URI:        https://connections-pro.com/
 * Description:       An extension for Connections Business Directory which adds clink to SMS and Call link shortcodes.
 * Version:           1.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Steven A. Zahm
 * Author URI:        https://connections-pro.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       connections_call_sms
 * Domain Path:       /languages
 */

namespace Connections_Directory\Extension\Click_To_Call_And_SMS;

include __DIR__ . '/includes/Singleton.php';
final class Plugin {

	use Singleton;

	const VERSION = '1.0';

	/**
	 * @var string The absolute path this file.
	 *
	 * @since 1.0
	 */
	private static $file = '';

	/**
	 * @var string The URL to the plugin's folder.
	 *
	 * @since 1.
	 */
	private static $url = '';

	/**
	 * @var string The absolute path to this plugin's folder.
	 *
	 * @since 1.0
	 */
	private static $path = '';

	/**
	 * @var string The basename of the plugin.
	 *
	 * @since 1.0
	 */
	private static $basename = '';

	protected function initialize() {

		self::$file     = __FILE__;
		self::$url      = plugin_dir_url( self::$file );
		self::$path     = plugin_dir_path( self::$file );
		self::$basename = plugin_basename( self::$file );

		/**
		 * This should run on the `plugins_loaded` action hook. Since the extension loads on the
		 * `plugins_loaded` action hook, load immediately.
		 */
		\cnText_Domain::register(
			'connections_call_sms',
			self::$basename,
			'load'
		);

		$this->includes();
		$this->hooks();

		Content_Blocks\SMS::add();
		Content_Blocks\Tel::add();
	}

	/**
	 * Include the dependencies.
	 *
	 * @since 1.0
	 */
	private function includes() {

		require_once __DIR__ . '/includes/Content_Blocks/SMS.php';
		require_once __DIR__ . '/includes/Content_Blocks/Tel.php';
		require_once __DIR__ . '/includes/Shortcode/SMS.php';
		require_once __DIR__ . '/includes/Shortcode/Tel.php';
	}

	/**
	 * Register the hooks.
	 *
	 * @since 1.0
	 */
	private function hooks() {

		add_action( 'init', array( \Connections_Directory\Extension\Click_To_Call_And_SMS\Shortcode\SMS::class, 'add' ) );
		add_action( 'init', array( \Connections_Directory\Extension\Click_To_Call_And_SMS\Shortcode\Tel::class, 'add' ) );

		add_action( 'cn_metabox', array( __CLASS__, 'registerClickToCallMetabox' ) );
		add_action( 'cn_metabox', array( __CLASS__, 'registerClickToSMSMetabox' ) );

		// add_filter( 'cn_content_blocks', array( __CLASS__, 'settingsOption' ) );
	}

	/**
	 * Register the 'Click to Call' metabox.
	 *
	 * @internal
	 * @since 1.0
	 */
	public static function registerClickToCallMetabox() {

		$atts = array(
			'title'    => 'Click to Call',
			// Change this to a name which applies to your project.
			'id'       => 'click_to_call',
			// Change this so it is unique to you project.
			'context'  => 'normal',
			'priority' => 'core',
			'fields'   => array(
				array(
					'name'       => 'Phone Number',
					// Change this field name to something which applies to you project.
					'show_label' => true,
					// Whether to display the 'name'. Changing it to false will suppress the name.
					'id'         => '_click_to_call_phone_number',
					// Change this so it is unique to you project. Each field id MUST be unique.
					'type'       => 'text',
					// This is the field type being added.
					'size'       => 'regular',
					// This can be changed to one of the following: 'small', 'regular', 'large'
					'desc'       => 'Enter the phone number for the click to call link.',
					// Placement will depend on the field type.
					// 'before'     => 'Text that will be displayed before the field.',
					// 'after'      => 'Text that will be displayed after the field.',
				),
				array(
					'name'       => 'Link Text',
					// Change this field name to something which applies to you project.
					'show_label' => true,
					// Whether to display the 'name'. Changing it to false will suppress the name.
					'id'         => '_click_to_call_text',
					// Change this so it is unique to you project. Each field id MUST be unique.
					'type'       => 'text',
					// This is the field type being added.
					'size'       => 'regular',
					// This can be changed to one of the following: 'small', 'regular', 'large'
					'desc'       => 'Enter the link text.',
					// Placement will depend on the field type.
					// 'before'     => 'Text that will be displayed before the field.',
					// 'after'      => 'Text that will be displayed after the field.',
				),
			),
		);

		\cnMetaboxAPI::add( $atts );
	}

	/**
	 * Register the 'Click to SMS' metabox.
	 *
	 * @internal
	 * @since 1.0
	 */
	public static function registerClickToSMSMetabox() {

		$atts = array(
			'title'    => 'Click to SMS',
			// Change this to a name which applies to your project.
			'id'       => 'click_to_sms',
			// Change this so it is unique to you project.
			'context'  => 'normal',
			'priority' => 'core',
			'fields'   => array(
				array(
					'name'       => 'Phone Number',
					// Change this field name to something which applies to you project.
					'show_label' => true,
					// Whether to display the 'name'. Changing it to false will suppress the name.
					'id'         => '_click_to_sms_phone_number',
					// Change this so it is unique to you project. Each field id MUST be unique.
					'type'       => 'text',
					// This is the field type being added.
					'size'       => 'regular',
					// This can be changed to one of the following: 'small', 'regular', 'large'
					'desc'       => 'Enter the phone number for the click to SMS link.',
					// Placement will depend on the field type.
					// 'before'     => 'Text that will be displayed before the field.',
					// 'after'      => 'Text that will be displayed after the field.',
				),
				array(
					'name'       => 'SMS Message Text',
					// Change this field name to something which applies to you project.
					'show_label' => true,
					// Whether to display the 'name'. Changing it to false will suppress the name.
					'id'         => '_click_to_sms_message',
					// Change this so it is unique to you project. Each field id MUST be unique.
					'type'       => 'text',
					// This is the field type being added.
					'size'       => 'regular',
					// This can be changed to one of the following: 'small', 'regular', 'large'
					'desc'       => 'Enter the SMS message text.',
					// Placement will depend on the field type.
					// 'before'     => 'Text that will be displayed before the field.',
					// 'after'      => 'Text that will be displayed after the field.',
				),
				array(
					'name'       => 'Link Text',
					// Change this field name to something which applies to you project.
					'show_label' => true,
					// Whether to display the 'name'. Changing it to false will suppress the name.
					'id'         => '_click_to_sms_text',
					// Change this so it is unique to you project. Each field id MUST be unique.
					'type'       => 'text',
					// This is the field type being added.
					'size'       => 'regular',
					// This can be changed to one of the following: 'small', 'regular', 'large'
					'desc'       => 'Enter the link text.',
					// Placement will depend on the field type.
					// 'before'     => 'Text that will be displayed before the field.',
					// 'after'      => 'Text that will be displayed after the field.',
				),
			),
		);

		\cnMetaboxAPI::add( $atts );
	}

	/**
	 * Generate the SMS link.
	 *
	 * @since 1.0
	 *
	 * @param string $number
	 * @param string $message
	 * @param string $text
	 *
	 * @return string
	 */
	public function generateSMSLink( string $number, string $message, string $text = '' ): string {

		if ( 0 === strlen( $text ) ) {

			$text = $number;
		}

		// URL encode the message.
		$encodedMessage = urlencode( $message );

		// Generate the sms link.
		$url = "sms:{$number}?body={$encodedMessage}";

		return '<a href="' . esc_url( $url ) . '">' . esc_html( $text ) . '</a>';
	}

	/**
	 * Generate the tel link.
	 *
	 * @since 1.0
	 *
	 * @param string $number
	 * @param string $text
	 *
	 * @return string
	 */
	public function generateTelLink( string $number, string $text = '' ): string {

		if ( 0 === strlen( $text ) ) {

			$text = $number;
		}

		return '<a href="' . esc_url( "tel:{$number}" ) . '">' . esc_html( $text ) . '</a>';
	}
}

add_action(
	'Connections_Directory/Loaded',
	array(
		'Connections_Directory\Extension\Click_To_Call_And_SMS\Plugin',
		'getInstance',
	)
);
