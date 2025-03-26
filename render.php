<?php
function sparxstar_user_post_query_render_callback($attributes, $content) {
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
        echo '<div class="sparxstar-user-post-query">';
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
    'render_callback' => 'sparxstar_user_post_query_render_callback'
]);
