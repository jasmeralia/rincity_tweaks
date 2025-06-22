<?php
// filepath: /home/morgan/git_local/rc_tweaks/includes/widgets.php

// Register Envira Tags Widget
add_action( 'widgets_init', function() {
    register_widget( 'RC_Envira_Tags_Widget' );
    register_widget( 'RC_Envira_Album_Categories_Widget' );
});

class RC_Envira_Tags_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'rc_envira_tags_widget',
            __('Envira Gallery Categories', 'rc_tweaks'),
            array( 'description' => __( 'Displays Envira categories for Envira galleries (only on Envira gallery posts).', 'rc_tweaks' ) )
        );
    }

    public function widget( $args, $instance ) {
        global $post;

        // Try to get the current Envira post object robustly, including pretty permalinks like /envira/gallery-slug/
        $post_id = null;

        // Prefer global $post if it's an Envira post
        if ( isset( $post ) && $post instanceof WP_Post && $post->post_type === 'envira' ) {
            $post_id = $post->ID;
        }

        // Fallback: check queried object
        if ( ! $post_id ) {
            $queried = get_queried_object();
            if ( $queried instanceof WP_Post && $queried->post_type === 'envira' ) {
                $post_id = $queried->ID;
            }
        }

        // Fallback: try to get by URL if on a single envira post (handles pretty permalinks)
        if ( ! $post_id && is_singular('envira') ) {
            $post_id = get_queried_object_id();
        }

        // Hide widget if no post ID
        if ( ! $post_id ) {
            return;
        }

        // Use Envira categories taxonomy (usually 'envira-category')
        $categories = get_the_terms( $post_id, 'envira-category' );
        if ( is_wp_error( $categories ) ) {
            echo $args['before_widget'];
            echo $args['before_title'] . esc_html__( 'Gallery Categories', 'rc_tweaks' ) . $args['after_title'];
            echo '<p style="color:red;">' . esc_html__( 'Error retrieving categories for this gallery:', 'rc_tweaks' ) . ' ' . esc_html( $categories->get_error_message() ) . '</p>';
            echo $args['after_widget'];
            return;
        } elseif ( empty( $categories ) ) {
            // Hide widget if no categories found
            return;
        }

        // Show widget with categories and counts
        echo $args['before_widget'];
        echo $args['before_title'] . esc_html__( 'Gallery Categories', 'rc_tweaks' ) . $args['after_title'];
        echo '<ul class="rc-envira-categories">';
        foreach ( $categories as $cat ) {
            $count = (int) $cat->count;
            echo '<li>&bull; <a href="' . esc_url( get_term_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a> (' . $count . ')</li>';
        }
        echo '</ul>';
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        // No options for this widget
        echo '<p>' . esc_html__( 'Displays Envira categories for Envira galleries. Only appears on Envira gallery posts.', 'rc_tweaks' ) . '</p>';
    }
}

// New widget: Display all categories with counts on Envira album pages only
class RC_Envira_Album_Categories_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'rc_envira_album_categories_widget',
            __('Envira Album Categories', 'rc_tweaks'),
            array( 'description' => __( 'Displays all Envira categories with their counts on Envira album pages.', 'rc_tweaks' ) )
        );
    }

    public function widget( $args, $instance ) {
        global $post;

        $is_album_page = false;

        // Check if current post is an Envira album
        if ( isset($post) && $post instanceof WP_Post && $post->post_type === 'envira_album' ) {
            $is_album_page = true;
        }

        // Check if this is an Envira album taxonomy archive (pretty permalinks like /album/slug)
        if ( is_tax('envira_album') ) {
            $is_album_page = true;
        }
        if ( function_exists('is_envira_album') && is_envira_album() ) {
            $is_album_page = true;
        }

        if ( ! $is_album_page ) {
            return;
        }

        $categories = get_terms( array(
            'taxonomy' => 'envira-category',
            'hide_empty' => true,
        ) );

        if ( is_wp_error( $categories ) || empty( $categories ) ) {
            return;
        }

        echo $args['before_widget'];
        echo $args['before_title'] . esc_html__( 'All Gallery Categories', 'rc_tweaks' ) . $args['after_title'];
        echo '<ul class="rc-envira-album-categories">';
        foreach ( $categories as $cat ) {
            $count = (int) $cat->count;
            echo '<li>&bull; <a href="' . esc_url( get_term_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a> (' . $count . ')</li>';
        }
        echo '</ul>';
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        echo '<p>' . esc_html__( 'Displays all Envira categories with their gallery counts. Only appears on Envira album pages.', 'rc_tweaks' ) . '</p>';
    }
}