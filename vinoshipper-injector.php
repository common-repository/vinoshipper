<?php
/**
 * Plugin Name:       Vinoshipper
 * Plugin URI:        https://developer.vinoshipper.com/docs/wordpress-plugin
 * Description:       Incorporate Vinoshipper components in WordPress.
 * Requires at least: 6.6
 * Requires PHP:      7.4
 * Version:           1.1.0
 * Author:            Vinoshipper
 * Author URI:        https://www.vinoshipper.com
 * License:           GPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package VinoshipperInjector
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Currently plugin version.
 * Rename this for your plugin and update it as you release new versions.
 *
 * @since 0.1.0
 */
define( 'VS_INJECTOR_VERSION', '1.1.0' );

/**
 * VS Themes
 * List of Available themes for Vinoshipper Injector.
 *
 * @since 0.1.0
 */
define(
	'VS_INJECTOR_THEMES',
	array(
		'Default (Blue)' => null,
		'Indigo'         => 'indigo',
		'Purple'         => 'purple',
		'Pink'           => 'pink',
		'Red'            => 'red',
		'Orange'         => 'orange',
		'Yellow'         => 'yellow',
		'Green'          => 'green',
		'Teal'           => 'teal',
		'Cyan'           => 'cyan',
		'Gray'           => 'gray',
		'Black'          => 'black',
	)
);

/**
 * Cart Display Location Options
 *
 * @since 0.1.0
 */
define( 'VS_INJECTOR_START_END', array( 'start', 'end' ) );

/**
 * Plugin activation.
 *
 * @since 0.1.0
 */
function vs_injector_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-vs-injector-activator.php';
	Vs_Injector_Activator::activate();
}

/**
 * Plugin deactivation.
 *
 * @since 0.1.0
 */
function vs_injector_devs_injector_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-vs-injector-deactivator.php';
	Vs_Injector_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'vs_injector_activate' );
register_deactivation_hook( __FILE__, 'vs_injector_devs_injector_activate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 *
 * @since 0.1.0
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-vs-injector.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1.0
 */
function vs_injector_run() {

	$plugin = new Vs_Injector();
	$plugin->run();
}
vs_injector_run();

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @param   array $block_categories                         Array of categories for block types.
 * @since 0.1.0
 * @see https://developer.wordpress.org/reference/hooks/block_categories_all/
 */
function vs_injector_block_categories_init( $block_categories ) {
	$block_categories[] = array(
		'slug'  => 'vinoshipper',
		'title' => __( 'Vinoshipper Injector', 'vinoshipper' ),
		'icon'  => null,
	);

	return $block_categories;
}
add_filter( 'block_categories_all', 'vs_injector_block_categories_init' );

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 * @since 0.1.0
 */
function vs_injector_block_init() {
	register_block_type( __DIR__ . '/build/core' );
	register_block_type( __DIR__ . '/build/product-catalog' );
	register_block_type( __DIR__ . '/build/product-item' );
	register_block_type( __DIR__ . '/build/announcement' );
	register_block_type( __DIR__ . '/build/available-in' );
	register_block_type( __DIR__ . '/build/add-to-cart' );
	register_block_type( __DIR__ . '/build/club-registration' );
}
add_action( 'init', 'vs_injector_block_init' );
