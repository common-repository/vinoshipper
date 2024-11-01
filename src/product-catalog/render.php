<?php
/**
 * Vinoshipper Injector: Product Catalog, Client Render
 *
 * @package VinoshipperInjector
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$vs_injector_wrapper_pre_attributes = array(
	'class' => 'vs-products',
);

if ( isset( $attributes['cards'] ) ) {
	$vs_injector_wrapper_pre_attributes['data-vs-cards'] = ( 'cards' === $attributes['cards'] ) ? 'true' : 'false';
}
if ( isset( $attributes['list'] ) ) {
	$vs_injector_wrapper_pre_attributes['data-vs-list'] = (int) $attributes['accountId'];
}
if ( isset( $attributes['available'] ) ) {
	$vs_injector_wrapper_pre_attributes['data-vs-available'] = boolval( $attributes['available'] ) ? 'true' : 'false';
}
if ( isset( $attributes['announcement'] ) ) {
	$vs_injector_wrapper_pre_attributes['data-vs-announcement'] = boolval( $attributes['announcement'] ) ? 'true' : 'false';
}
if ( isset( $attributes['tooltip'] ) ) {
	$vs_injector_wrapper_pre_attributes['data-vs-tooltips'] = boolval( $attributes['tooltip'] ) ? 'true' : 'false';
}

?>
<div <?php echo wp_kses_data( get_block_wrapper_attributes( $vs_injector_wrapper_pre_attributes ) ); ?>></div>
