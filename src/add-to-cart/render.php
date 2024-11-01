<?php
/**
 * Vinoshipper Injector: Add To Cart, Client Render
 *
 * @package VinoshipperInjector
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$vs_injector_wrapper_pre_attributes = array(
	'class'              => 'vs-add-to-cart',
	'data-vs-product-id' => isset( $attributes['productId'] ) ? $attributes['productId'] : null,
);

if ( isset( $attributes['accountId'] ) ) {
	$vs_injector_wrapper_pre_attributes['data-vs-account-id'] = $attributes['accountId'];
}
if ( isset( $attributes['includeQty'] ) ) {
	$vs_injector_wrapper_pre_attributes['data-vs-include-qty'] = $attributes['includeQty'];
}
if ( isset( $attributes['productUnits'] ) ) {
	$vs_injector_wrapper_pre_attributes['data-vs-product-units'] = $attributes['productUnits'];
}

$vs_injector_normalized_attributes = array();
foreach ( $vs_injector_wrapper_pre_attributes as $vs_injector_key => $vs_injector_value ) {
	$vs_injector_normalized_attributes[] = $vs_injector_key . '="' . esc_attr( $vs_injector_value ) . '"';
}
$vs_injector_final_output = implode( ' ', $vs_injector_normalized_attributes );

?>
<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
	<div <?php echo wp_kses_data( $vs_injector_final_output ); ?>></div>
</div>
