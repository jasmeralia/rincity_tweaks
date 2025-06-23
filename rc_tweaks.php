<?php
/**
 * Plugin Name: RC Tweaks
 * Description: A plugin to generate an XML/RSS feed for the last 10 published 'envira' posts, display a gallery table page, and provide a tag widget for Envira galleries.
 * Version: 1.18.5
 * Author: Morgan Blackthorne
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Include the feed generator
require_once plugin_dir_path( __FILE__ ) . 'includes/feed-generator.php';

// Include the gallery table generator
require_once plugin_dir_path( __FILE__ ) . 'includes/gallery-table-generator.php';

// Include the widgets
require_once plugin_dir_path( __FILE__ ) . 'includes/widgets.php';
