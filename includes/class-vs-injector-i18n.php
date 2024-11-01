<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.vinoshipper.com
 * @since      1.0.0
 *
 * @package    VinoshipperInjector
 * @subpackage VinoshipperInjector/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    VinoshipperInjector
 * @subpackage VinoshipperInjector/includes
 * @author     Vinoshipper <support@vinoshipper.com>
 */
class Vs_Injector_I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.1.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'vinoshipper',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
