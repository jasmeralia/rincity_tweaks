<?php
// filepath: /home/morgan/git_local/rc_tweaks/includes/feed-generator.php

function rc_tweaks_generate_feed() {
    header('Content-Type: application/rss+xml; charset=' . get_option('blog_charset'), true);

    // Get gallery IDs from the Members Gallery album (ID 1411)
    $album_id = 1411;
    $album_data = get_post_meta($album_id, '_eg_album_data', true);
    // After fetching $album_data
    // echo '<!-- album_data: ' . print_r($album_data, true) . ' -->';
    $gallery_ids = [];

    if (!empty($album_data['galleryIDs']) && is_array($album_data['galleryIDs'])) {
        $gallery_ids = $album_data['galleryIDs'];
    }

    if (empty($gallery_ids)) {
        // No galleries in album, output empty feed
        echo '<?xml version="1.0" encoding="' . esc_attr(get_option('blog_charset')) . '"?><rss version="2.0"><channel><title>No Galleries</title></channel></rss>';
        return;
    }

    $args = array(
        'post_type'      => 'envira',
        'post_status'    => 'publish',
        'post__in'       => $gallery_ids,
        'posts_per_page' => 10,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $query = new WP_Query($args);

    // Use DOMDocument for pretty printing
    $dom = new DOMDocument('1.0', get_option('blog_charset'));
    $dom->formatOutput = true;

    $rss = $dom->createElement('rss');
    $rss->setAttribute('version', '2.0');
    $rss->setAttribute('xmlns:content', 'http://purl.org/rss/1.0/modules/content/'); // Add content namespace

    $channel = $dom->createElement('channel');

    $title = $dom->createElement('title', get_bloginfo('name'));
    $link = $dom->createElement('link', get_bloginfo('url'));
    $description = $dom->createElement('description', get_bloginfo('description'));

    $channel->appendChild($title);
    $channel->appendChild($link);
    $channel->appendChild($description);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $item = $dom->createElement('item');

            $item_title = $dom->createElement('title', get_the_title());
            $item_link = $dom->createElement('link', get_permalink());
            $item_description = $dom->createElement('description');
            $desc_text = get_the_excerpt();

            // Get first image from Envira gallery (stored in '_eg_gallery_data' post meta)
            $gallery_data = get_post_meta(get_the_ID(), '_eg_gallery_data', true);
            $img_tag = '';
            if (!empty($gallery_data['gallery']) && is_array($gallery_data['gallery'])) {
                $first_image = reset($gallery_data['gallery']);
                if (!empty($first_image['src'])) {
                    $img_tag = '<img src="' . esc_url($first_image['src']) . '" alt="" style="max-width:100%;" /><br />';
                }
            }

            // Add Envira categories as hashtags
            $categories = get_the_terms(get_the_ID(), 'envira-category');
            if (!is_wp_error($categories) && !empty($categories)) {
                $hashtags = [];
                foreach ($categories as $cat) {
                    $hashtags[] = '#' . preg_replace('/[^A-Za-z0-9_]/', '', $cat->slug);
                }
                $desc_text .= "\n\n" . implode(' ', $hashtags);
            }

            // Prepend image tag to description
            $desc_text = $img_tag . $desc_text;

            $item_description->appendChild($dom->createCDATASection($desc_text));
            $item_pubDate = $dom->createElement('pubDate', get_the_date(DATE_RSS));

            $item->appendChild($item_title);
            $item->appendChild($item_link);
            $item->appendChild($item_description);
            $item->appendChild($item_pubDate);

            $channel->appendChild($item);
        }
    }

    $rss->appendChild($channel);
    $dom->appendChild($rss);

    echo $dom->saveXML();

    wp_reset_postdata();
}

// Register the feed
add_action( 'init', 'rc_tweaks_register_feed' );

function rc_tweaks_register_feed() {
    add_feed( 'envira-feed', 'rc_tweaks_generate_feed' );
}
