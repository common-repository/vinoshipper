<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.vinoshipper.com
 * @since      1.0.0
 *
 * @package    VinoshipperInjector
 * @subpackage VinoshipperInjector/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    VinoshipperInjector
 * @subpackage VinoshipperInjector/admin
 * @author     Vinoshipper <support@vinoshipper.com>
 */
class Vs_Injector_Admin {

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
	 * The Icon for this plugin in Admin settings.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $vs_icon    The icon of this plugin in Admin settings.
	 */
	private $vs_icon;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->vs_icon     = 'data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iMTAyNHB4IiBoZWlnaHQ9IjEwMjRweCIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMTAyNCAxMDI0OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PHBhdGggZmlsbD0iYmxhY2siIGQ9Ik01MTIsMEMyMjkuMjMxLDAsMCwyMjkuMjI5LDAsNTEyYzAsMjgyLjc2OSwyMjkuMjMxLDUxMiw1MTIsNTEyczUxMi0yMjkuMjMxLDUxMi01MTIgQzEwMjQsMjI5LjIyOSw3OTQuNzY5LDAsNTEyLDB6IE03NDAuMSwyNjYuNjY0YzAsMC0xMzcuNTEzLDUwOC41Ny0xMzkuMTM5LDUxNC41ODVjLTIuMjU3LDguMzUyLTYuMjMsMTYuMjE0LTExLjM4MSwyMy4wNjcgYy0xMi40MjksMTYuMzY0LTMyLjAwMiwyNi42MDItNTMuNSwyNi42MDJoLTQ2Ljk4NWMtMzAuMzY0LDAtNTYuOTU1LTIwLjM1Ny02NC44ODEtNDkuNjY4TDI4NS4wNzYsMjY2LjY2NCBjLTYuODgzLTI1LjQ1NywxMi4yOS01MC41MDQsMzguNjYyLTUwLjUwNGg0My4yODljMTguMTg4LDAsMzQuMDkzLDEyLjI1NywzOC43MjgsMjkuODQ2bDEwNi44MjYsNDA1LjQxbDEwNi44NC00MDUuNDEyIGM0LjYzNS0xNy41ODgsMjAuNTM5LTI5Ljg0MywzOC43MjgtMjkuODQzaDQzLjI5MUM3MjcuODEsMjE2LjE2LDc0Ni45ODMsMjQxLjIwNyw3NDAuMSwyNjYuNjY0eiIvPjwvc3ZnPg==';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugins_url( '../build/core/index.css', __FILE__ ), array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts() {
	}

	/**
	 * Register REST endpoints for Admin Block UI.
	 *
	 * @since    0.1.0
	 */
	public function rest_proxy_register() {
		register_rest_route(
			'vinoshipper-injector/v1',
			'/products',
			array(
				'permission_callback' => array( $this, 'rest_proxy_permissions_check' ),
				'methods'             => 'GET',
				'callback'            => array( $this, 'rest_proxy_products' ),
			)
		);
		register_rest_route(
			'vinoshipper-injector/v1',
			'/clubs',
			array(
				'permission_callback' => array( $this, 'rest_proxy_permissions_check' ),
				'methods'             => 'GET',
				'callback'            => array( $this, 'rest_proxy_clubs' ),
			)
		);
	}

	/**
	 * REST permission callback
	 *
	 * @since    0.1.0
	 */
	public function rest_proxy_permissions_check() {
		// Restrict endpoint to only users who have the edit_posts capability.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return new WP_Error( 'rest_forbidden', esc_html__( 'Access deined.', 'vinoshipper' ), array( 'status' => 401 ) );
		}
		return true;
	}

	/**
	 * REST endpoint for Products list.
	 *
	 * @since    0.1.0
	 * @param    any $data Data from products request.
	 */
	public function rest_proxy_products( $data ) {
		$account_id = get_option( 'vs_injector_account_id', null );
		if ( $account_id ) {
			$fetch_url    = 'https://vinoshipper.com/api/v3/feeds/vs/' . $account_id . '/products';
			$api_response = wp_remote_get( $fetch_url );

			if ( empty( $api_response ) || 200 !== $api_response['response']['code'] ) {
				return new WP_Error(
					'error',
					array(
						'input'    => $data,
						'response' => $api_response,
					)
				);
			}

			return new WP_REST_Response( json_decode( $api_response['body'] ) );
		} else {
			return new WP_Error(
				'error',
				array(
					'input'    => $data,
					'response' => 'No Account ID Defined',
				)
			);
		}
	}

	/**
	 * REST endpoint for Clubs list.
	 *
	 * @since    0.1.0
	 * @param    any $data Data from products request.
	 */
	public function rest_proxy_clubs( $data ) {
		$account_id = get_option( 'vs_injector_account_id', null );
		if ( $account_id ) {
			$fetch_url    = 'https://vinoshipper.com/api/v3/club/subscribe/' . $account_id;
			$api_response = wp_remote_get( $fetch_url );

			if ( empty( $api_response ) || 200 !== $api_response['response']['code'] ) {
				return new WP_Error(
					'error',
					array(
						'input'    => $data,
						'response' => $api_response,
					)
				);
			}

			return new WP_REST_Response( json_decode( $api_response['body'] ) );
		} else {
			return new WP_Error(
				'error',
				array(
					'input'    => $data,
					'response' => 'No Account ID Defined',
				)
			);
		}
	}

	/**
	 * Register Options Page
	 *
	 * @since    0.1.0
	 */
	public function options_page() {
		add_menu_page(
			'Vinoshipper Injector',
			'Vinoshipper',
			'manage_options',
			'vs_injector_settings',
			array( $this, 'vs_injector_settings_page_html' ),
			$this->vs_icon
		);
	}

	/**
	 * Include Admin Display HTML
	 *
	 * @since    0.1.0
	 */
	public function vs_injector_settings_page_html() {
		include plugin_dir_path( __DIR__ ) . 'admin/partials/vs-injector-admin-display.php';
	}

	/**
	 * Include all Setting sections and fields.
	 *
	 * @since    0.1.0
	 */
	public function settings_init() {

		// Section: General.
		add_settings_section(
			'vs_injector_settings_section',
			'Account Settings',
			array( $this, 'settings_section_general_callback' ),
			'vs_injector_settings_section_general'
		);
		add_settings_field(
			'vs_injector_account_id_field',
			'Vinoshipper Account ID',
			array( $this, 'settings_account_id_input' ),
			'vs_injector_settings_section_general',
			'vs_injector_settings_section',
			array(
				'label_for' => 'vs_injector_account_id',
			)
		);
		// Section: General.
		add_settings_section(
			'vs_injector_settings_section',
			'Theme Settings',
			array( $this, 'settings_section_theme_callback' ),
			'vs_injector_settings_section_theme'
		);
		add_settings_field(
			'vs_injector_theme_field',
			'Theme',
			array( $this, 'settings_theme_input' ),
			'vs_injector_settings_section_theme',
			'vs_injector_settings_section',
			array(
				'label_for' => 'vs_injector_theme',
			)
		);
		add_settings_field(
			'vs_injector_theme_dark_field',
			'Theme: Enable Dark Mode',
			array( $this, 'settings_theme_dark_input' ),
			'vs_injector_settings_section_theme',
			'vs_injector_settings_section',
			array(
				'label_for' => 'vs_injector_theme_dark',
			)
		);

		// Section: Cart Options.
		add_settings_section(
			'vs_injector_settings_section',
			'Cart Options',
			array( $this, 'settings_section_cart_callback' ),
			'vs_injector_settings_section_cart'
		);
		add_settings_field(
			'vs_injector_cart_position_field',
			'Cart Position',
			array( $this, 'settings_cart_position_input' ),
			'vs_injector_settings_section_cart',
			'vs_injector_settings_section',
			array(
				'label_for' => 'vs_injector_cart_position',
			)
		);
		add_settings_field(
			'vs_injector_cart_button_field',
			'Display Cart Button',
			array( $this, 'settings_cart_button_input' ),
			'vs_injector_settings_section_cart',
			'vs_injector_settings_section',
			array(
				'label_for' => 'vs_injector_cart_button',
			)
		);
	}

	/**
	 * General Settings
	 *
	 * @since    0.1.0
	 */
	public function settings_section_general_callback() {
		echo '<p>Required Account Settings for Vinoshipper Injector.</p>';
	}

	/**
	 * Account ID Setting UI
	 *
	 * @since    0.1.0
	 */
	public function settings_account_id_input() {
		echo '<input id="vs_injector_account_id" name="vs_injector_account_id" type="number" value="' . esc_attr( get_option( 'vs_injector_account_id' ) ) . '" required data-1p-ignore />';
		echo '<p><strong>Account ID is required.</strong></p>';
		echo '<p>Available on the Vinoshipper platform, located at <a href="https://vinoshipper.com/ui/producer/account" target="_blank">Account -> Profile</a>.</p>';
	}

	/**
	 * Theme Settings
	 *
	 * @since   0.1.0
	 */
	public function settings_section_theme_callback() {
		echo '<p>Apparance settings for all Vinoshiper Components.</p>';
	}

	/**
	 * Theme Setting UI
	 *
	 * @since    0.1.0
	 */
	public function settings_theme_input() {
		$selected_option = get_option( 'vs_injector_theme' );
		echo '<select id="vs_injector_theme" name="vs_injector_theme">';
		foreach ( VS_INJECTOR_THEMES as $key => $value ) {
			if ( $value === $selected_option ) {
				echo '<option value="' . esc_attr( $value ) . '" selected>' . esc_html( $key ) . '</option>';
			} else {
				echo '<option value="' . esc_attr( $value ) . '">' . esc_html( $key ) . '</option>';
			}
		}
		echo '</select>';
		echo '<p>The simple theme for Injector elements.</p>';
	}

	/**
	 * Dark Theme Setting UI
	 *
	 * @since    0.1.0
	 */
	public function settings_theme_dark_input() {
		$selected_option = get_option( 'vs_injector_theme_dark' );
		echo '<input type="checkbox" id="vs_injector_theme_dark" name="vs_injector_theme_dark" value="1"' . checked( 1, $selected_option, false ) . '/>';
		echo '<p>Enables dark mode for selected theme.</p>';
	}

	/**
	 * Cart Options Information
	 *
	 * @since    0.1.0
	 */
	public function settings_section_cart_callback() {
		echo '<p>Options for displaying the Cart.</p>';
	}

	/**
	 * Cart Position Setting UI
	 *
	 * @since    0.1.0
	 */
	public function settings_cart_position_input() {
		$selected_option = get_option( 'vs_injector_cart_position' );
		echo '<select id="vs_injector_cart_position" name="vs_injector_cart_position">';
		foreach ( VS_INJECTOR_START_END as $value ) {
			if ( $value === $selected_option ) {
				echo '<option value="' . esc_attr( $value ) . '" selected>' . esc_html( ucfirst( $value ) ) . '</option>';
			} else {
				echo '<option value="' . esc_attr( $value ) . '">' . esc_html( ucfirst( $value ) ) . '</option>';
			}
		}
		echo '</select>';
	}

	/**
	 * Cart Button Display Setting UI
	 *
	 * @since    0.1.0
	 */
	public function settings_cart_button_input() {
		$selected_option = get_option( 'vs_injector_cart_button' );
		echo '<input type="checkbox" id="vs_injector_cart_button" name="vs_injector_cart_button" value="1"' . checked( 1, $selected_option, false ) . '/>';
		echo '<p>Display the cart button.</p>';
		echo '<p>Note: If you disable the cart button, you will need to implement your own cart button.</p>';
	}
}
