<?php
/**
 * Vinoshipper Injector: Club Registration, Client Render
 *
 * @package VinoshipperInjector
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$vs_injector_wrapper_pre_attributes = array(
	'class' => 'vs-club-registration',
);

if ( ! boolval( $attributes['clubsDisplayAll'] ) && isset( $attributes['allow'] ) ) {
	$vs_injector_wrapper_pre_attributes['data-vs-club-allow'] = implode( ',', $attributes['allow'] );
}
if ( isset( $attributes['defaultClub'] ) ) {
	$vs_injector_wrapper_pre_attributes['data-vs-club-default'] = (int) $attributes['defaultClub'];
}
if ( isset( $attributes['headline'] ) ) {
	$vs_injector_wrapper_pre_attributes['data-vs-club-headline'] = $attributes['headline'];
}

?>
<div <?php echo wp_kses_data( get_block_wrapper_attributes( $vs_injector_wrapper_pre_attributes ) ); ?>></div>
