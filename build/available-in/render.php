<?php
/**
 * Vinoshipper Injector: Available In, Client Render
 *
 * @package VinoshipperInjector
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$vs_injector_wrapper_pre_attributes = array(
	'class' => 'vs-available',
);

if ( isset( $attributes['tooltip'] ) ) {
	$vs_injector_wrapper_pre_attributes['data-vs-tooltips'] = boolval( $attributes['tooltip'] ) ? 'true' : 'false';
}

?>
<div <?php echo wp_kses_data( get_block_wrapper_attributes( $vs_injector_wrapper_pre_attributes ) ); ?>></div>
