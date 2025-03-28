<?php
namespace stellarqueryloopblock;

use WP_Query;

function get_all_registered_post_statuses(): array {
    if(isset(global $wp_post_statuses) && is_array(global $wp_post_statuses)){
        global $wp_post_statuses;
    }   
    return array_keys($wp_post_statuses);
}

function disable_publishpress_filters_if_active(WP_Query $query): void {
    if (!is_admin() && $query->is_main_query()) {
        if (!function_exists('is_plugin_active')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        if (is_plugin_active('publishpress/publishpress.php')) {
            remove_all_filters('posts_where');
            remove_all_filters('posts_join');
            remove_all_filters('posts_groupby');
        }
    }
}
add_action('pre_get_posts', 'stellarqueryloopblock\disable_publishpress_filters_if_active');

function stellar_render_callback($attributes, $content): bool|string {
    if (!isset($attributes['postType'])) return '';

    $args = [
        'post_type'      => $attributes['postType'],
        'post_status'    => $attributes['postStatus'] ?? ['publish'],
        'posts_per_page' => $attributes['postsPerPage'] ?? 5,
        'order'          => $attributes['order'] ?? 'DESC',
        'orderby'        => $attributes['orderBy'] ?? 'date',
    ];

    if (!empty($attributes['authorType'])) {
        if ($attributes['authorType'] === 'current' && is_user_logged_in()) {
            $args['author'] = get_current_user_id();
        } elseif ($attributes['authorType'] === 'specific' && is_numeric($attributes['specificAuthor'])) {
            $args['author'] = intval($attributes['specificAuthor']);
        }
    }

    if (!empty($attributes['taxonomy']) && !empty($attributes['taxonomyTerm'])) {
        $args['tax_query'] = [[
            'taxonomy' => sanitize_key($attributes['taxonomy']),
            'field'    => 'slug',
            'terms'    => sanitize_text_field($attributes['taxonomyTerm']),
        ]];
    }

    $query = new WP_Query($args);
    ob_start();

    if ($query->have_posts()) {
        echo '<div class="stellar-user-post-query">';
        while ($query->have_posts()) {
            $query->the_post();
            echo '<div class="user-post-item">';
            echo do_blocks($content); // Render your InnerBlocks layout here
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p>No posts found.</p>';
    }

    wp_reset_postdata();
    return ob_get_clean();
}

register_block_type(__DIR__, [
    'render_callback' => 'stellarqueryloopblock\stellar_query_loop_block_render_callback'
]);
