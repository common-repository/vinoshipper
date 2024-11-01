<?php
/**
 * Vinoshipper Injector: Announcement, Client Render
 *
 * @package VinoshipperInjector
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$vs_injector_wrapper_pre_attributes = array(
	'class' => 'vs-announcement',
);

?>
<div <?php echo wp_kses_data( get_block_wrapper_attributes( $vs_injector_wrapper_pre_attributes ) ); ?>></div>
