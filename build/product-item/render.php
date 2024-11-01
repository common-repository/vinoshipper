<?php
/**
 * Vinoshipper Injector: Product Item, Client Render
 *
 * @package VinoshipperInjector
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$vs_injector_wrapper_pre_attributes = array(
	'class'              => 'vs-product-item',
	'data-vs-product-id' => isset( $attributes['productId'] ) ? $attributes['productId'] : null,
);

if ( isset( $attributes['accountId'] ) ) {
	$vs_injector_wrapper_pre_attributes['data-vs-account-id'] = $attributes['accountId'];
}
if ( isset( $attributes['cards'] ) ) {
	$vs_injector_wrapper_pre_attributes['data-vs-cards'] = ( 'cards' === $attributes['cards'] ) ? 'true' : 'false';
}
if ( isset( $attributes['image'] ) ) {
	$vs_injector_wrapper_pre_attributes['data-vs-product-image'] = boolval( $attributes['image'] ) ? 'true' : 'false';
}
if ( isset( $attributes['descForce'] ) ) {
	$vs_injector_wrapper_pre_attributes['data-vs-desc-force'] = boolval( $attributes['descForce'] ) ? 'true' : 'false';
}

?>
<div <?php echo wp_kses_data( get_block_wrapper_attributes( $vs_injector_wrapper_pre_attributes ) ); ?>></div>
