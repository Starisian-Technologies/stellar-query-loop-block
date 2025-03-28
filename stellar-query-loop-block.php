<<<<<<< HEAD:stellar-query-loop-block.php
<?php
namespace stellarqueryloopblock;
/**
* Plugin Name: stellar-query-loop-block
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

function stellar_query_loop_block_init() {
    register_block_type(__DIR__ . '/build');
}
add_action('init', 'stellarqueryloopblock\stellar_query_loop_block_init');
=======
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

function stellar_user_post_query_block_init() {
    register_block_type(__DIR__ . '/build');
}
add_action('init', 'stellar_user_post_query_block_init');
>>>>>>> bf973c4 (03282025MB):star-user-post-query-loop.php
