<?php
namespace Sparxstar;
/**
* Plugin Name: stellar-query-loop-block
* Plugin URI: https://starisian.com
* Description: A stellar query loop block with parameters to display CPTs, post by author, non published posts.
* Version: 1.0.0
* Author: Max Barrett
* Author URI: https://github.com/MaximillianGroup
* Developer: Starisian Technologies
* Developer URI: https://github.com/Starisian-Technologies
* Text Domain: stellar-query-loop-block
* Domain Path: /languages
*
*
* License: GNU General Public License v3.0
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

function stellar-query-loop-block_init() {
    register_block_type(__DIR__ . '/build');
}
add_action('init', 'stellar-query-loop-block_init');
