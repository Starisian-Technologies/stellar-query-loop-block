<?php
namespace src\StellarQueryLoopBlock;
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
use WP_Query;

function stellar_get_all_post_statuses() {
    global $wp_post_statuses;
    return array_keys($wp_post_statuses ?? []);
}

function stellar_disable_publishpress_filters(WP_Query $query) {
    if (!is_admin() && $query->is_main_query()) {
        remove_all_filters('posts_where');
        remove_all_filters('posts_join');
        remove_all_filters('posts_groupby');
    }
}
add_action('pre_get_posts', 'stellar_disable_publishpress_filters');

function stellar_render_query_block($attributes, $content, $block) {
    $post_type     = $attributes['postType'] ?? 'post';
    $post_status   = $attributes['postStatus'] ?? ['publish'];
    $author_type   = $attributes['authorType'] ?? 'any';
    $specific_user = $attributes['specificAuthor'] ?? null;

    // Only allow public posts for guests
    $non_public_statuses = ['draft', 'private', 'pending', 'future'];
    if (!is_user_logged_in() && array_intersect((array) $post_status, $non_public_statuses)) {
        return '';
    }

    $args = [
        'post_type'      => $post_type,
        'post_status'    => $post_status === 'all' ? stellar_get_all_post_statuses() : (array) $post_status,
        'posts_per_page' => intval($attributes['postsPerPage'] ?? 5),
        'orderby'        => $attributes['orderBy'] ?? 'date',
        'order'          => $attributes['order'] ?? 'DESC',
    ];

    if ($author_type === 'current' && is_user_logged_in()) {
        $args['author'] = get_current_user_id();
    } elseif ($author_type === 'specific' && is_numeric($specific_user)) {
        $args['author'] = intval($specific_user);
    }

    // Handle taxQuery attribute (associative array: taxonomy => term(s))
    if (!empty($attributes['taxQuery']) && is_array($attributes['taxQuery'])) {
        $tax_query = ['relation' => 'AND'];
        foreach ($attributes['taxQuery'] as $taxonomy => $terms) {
            if (!taxonomy_exists($taxonomy)) continue;
            $tax_query[] = [
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => is_array($terms) ? $terms : [$terms],
            ];
        }
        if (count($tax_query) > 1) {
            $args['tax_query'] = $tax_query;
        }
    }

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) {
        echo '<div class="stellar-query-loop">';
        while ($query->have_posts()) {
            $query->the_post();
            echo '<div class="stellar-post-item">';
            echo do_blocks($content);
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p>No posts found.</p>';
    }

    wp_reset_postdata();
    return ob_get_clean();
}

echo stellar_render_query_block($attributes, $content, $block);