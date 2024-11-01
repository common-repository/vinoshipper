<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.vinoshipper.com
 * @since      1.0.0
 *
 * @package    VinoshipperInjector
 * @subpackage VinoshipperInjector/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    VinoshipperInjector
 * @subpackage VinoshipperInjector/public
 * @author     Vinoshipper <support@vinoshipper.com>
 */
class Vs_Injector_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_styles() {
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, 'https://vinoshipper.com/injector/index.js', array(), $this->version, false );

		$temp_account_id = get_option( 'vs_injector_account_id' );

		if ( is_numeric( $temp_account_id ) ) {
			$temp_theme      = get_option( 'vs_injector_theme' );
			$temp_theme_dark = boolval( get_option( 'vs_injector_theme_dark' ) );
			$computed_theme  = null;

			if ( $temp_theme && $temp_theme_dark ) {
				$computed_theme = $temp_theme . '-dark';
			} elseif ( $temp_theme ) {
				$computed_theme = $temp_theme;
			} elseif ( $temp_theme_dark ) {
				$computed_theme = 'dark';
			}

			$settings       = array(
				'vsPlugin'     => 'vs-wordpress:' . esc_html( VS_INJECTOR_VERSION ),
				'theme'        => $computed_theme,
				'cartPosition' => get_option( 'vs_injector_cart_position', 'end' ),
				'cartButton'   => boolval( get_option( 'vs_injector_cart_button', true ) ),
			);
			$script_content = 'window.wpVsInjectorSettings = ' . wp_json_encode( $settings ) . ';
			window.document.addEventListener(\'vinoshipper:loaded\', () => {
				window.Vinoshipper.init(' . esc_html( $temp_account_id ) . ', window.wpVsInjectorSettings);
			});
			if (window.Vinoshipper) {
				window.Vinoshipper.init(' . esc_html( $temp_account_id ) . ', window.wpVsInjectorSettings);
			}';
			wp_add_inline_script( $this->plugin_name, $script_content, 'before' );
		} else {
			$script_content = 'console.error("Vinoshipper Injector: Account ID not defined.");';
			wp_add_inline_script( $this->plugin_name, $script_content, 'before' );
		}
	}

	/**
	 * Register all settings.
	 */
	public function settings_init() {
		register_setting(
			'vs_injector_settings',
			'vs_injector_account_id',
			array(
				'type'         => 'number',
				'description'  => 'The Vinoshipper Account ID.',
				'show_in_rest' => true,
				'default'      => null,
			)
		);
		register_setting(
			'vs_injector_settings',
			'vs_injector_theme',
			array(
				'type'         => 'string',
				'description'  => 'Global theme setting.',
				'show_in_rest' => true,
				'default'      => null,
			)
		);
		register_setting(
			'vs_injector_settings',
			'vs_injector_theme_dark',
			array(
				'type'         => 'boolean',
				'description'  => 'Enable Dark Mode.',
				'show_in_rest' => true,
				'default'      => false,
			)
		);

		// Cart Options.
		register_setting(
			'vs_injector_settings',
			'vs_injector_cart_position',
			array(
				'type'              => 'string',
				'description'       => 'Position the cart to either the start or end of the screen.',
				'show_in_rest'      => true,
				'default'           => 'end',
				'sanitize_callback' => array( $this, 'sanitize_cart_position' ),
			)
		);
		register_setting(
			'vs_injector_settings',
			'vs_injector_cart_button',
			array(
				'type'         => 'boolean',
				'description'  => 'Display the cart button',
				'show_in_rest' => true,
				'default'      => true,
			)
		);
	}

	/**
	 * Sanitize: Cart Position
	 *
	 * @param string $settings The input string.
	 */
	public function sanitize_cart_position( $settings ) {
		$testing_string = strtolower( sanitize_text_field( $settings ) );
		if ( 'end' === $testing_string ) {
			return 'end';
		} else {
			return 'start';
		}
	}
}
