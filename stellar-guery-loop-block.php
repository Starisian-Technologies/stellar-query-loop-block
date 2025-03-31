<?php
/**
 * Plugin Name:       Stellar Query Loop
 * Plugin URI:        https://sparxstar.com
 * Description:       Query loop block including params for author, status, custom post type  s taxxonomies and  ACF
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            Starisian Technologies, Max Barrett
 * Author URI: 	      https://github.com/MaximillianGroup
 * Developer: 		  Starisian Technologies
 * Developer URI: 	  https://starisian.com
 * License:           GPL-2.0
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       stellar-guery-loop-block
 * Domain Path:       /language
 * Update URI:        https://github.com/Starisian-Technologies/stellar-query-loop-block
 *
 * @package StellarQueryLoopBlock
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function stellar_query_loop_block_stellar_guery_loop_block_block_init() {
	if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) { // Function introduced in WordPress 6.8.
		wp_register_block_types_from_metadata_collection( __DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php' );
	} else {
		if ( function_exists( 'wp_register_block_metadata_collection' ) ) { // Function introduced in WordPress 6.7.
			wp_register_block_metadata_collection( __DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php' );
		}
		$manifest_data = require __DIR__ . '/build/blocks-manifest.php';
		foreach ( array_keys( $manifest_data ) as $block_type ) {
			register_block_type( __DIR__ . "/build/{$block_type}" );
		}
	}
}
add_action( 'init', 'stellar_query_loop_block_stellar_guery_loop_block_block_init' );
