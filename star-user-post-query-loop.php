<?php
/**
* Plugin Name: user-post-query-block
* Plugin URI: https://starisian.com
* Description: Your extension's description text.
* Version: 1.0.0
* Author: Max Barrett
* Author URI: https://github.com/MaximillianGroup
* Developer: Starisian Technologies
* Developer URI: https://github.com/Starisian-Technologies
* Text Domain: starisian-user-post-query-block
* Domain Path: /languages
*
*
* License: GNU General Public License v3.0
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

function user_post_query_block_init() {
    register_block_type(__DIR__ . '/build');
}
add_action('init', 'user_post_query_block_init');
