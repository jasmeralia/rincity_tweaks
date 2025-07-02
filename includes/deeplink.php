<?php
// filepath: /home/morgan/git_local/rc_tweaks/includes/deeplink.php

// Adds query-param / hash deep-linking to Envira **Filterable Album** category buttons.

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'envira-album-deeplink',
        plugin_dir_url( __FILE__ ) . 'deeplink.js',
        [ 'jquery' ],      // keep it simple: only depend on jQuery
        '1.2',
        true               // load in the footer, after Envira’s own JS
    );
}, 15 );
