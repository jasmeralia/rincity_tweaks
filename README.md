# rc_tweaks WordPress Plugin

## Description
The `rc_tweaks` plugin provides three main features for WordPress sites using the custom post type `envira`:

1. **Envira RSS Feed:** Generates an XML/RSS feed for the last 10 published `envira` posts, sorted by publication date (descending).
2. **Envira Gallery Table Page:** Automatically creates a page that displays a table of the 3 most recent published `envira` galleries, each showing up to 3 images from the gallery, rendered within your theme's header and footer.
3. **Envira Tags Widget:** Adds a widget that displays a list of tags in the right navigation bar, but only on single Envira gallery pages.

## Features
- Fetches the latest 10 published posts of type `envira` and outputs them as an RSS feed.
- Adds a new page (`/envira-gallery-table/`) with a table of the 3 latest Envira galleries and images.
- Includes a shortcode `[rc_envira_gallery_table]` to display the gallery table anywhere.
- Provides a widget to display tags for Envira galleries in the sidebar, visible only on single Envira gallery pages.
- Easy integration with WordPress.

## Installation
1. Download the `rc_tweaks` plugin files.
2. Upload the `rc_tweaks` folder to the `/wp-content/plugins/` directory of your WordPress installation.
3. Activate the plugin through the 'Plugins' menu in WordPress.

## Usage

### Envira RSS Feed
Once activated, the plugin will automatically generate an RSS feed for the `envira` post type.  
You can access the feed at the following URL:
```
http://yourdomain.com/?feed=envira-feed
```

### Envira Gallery Table Page
- On activation, a page titled **Envira Gallery Table** is created at `/envira-gallery-table/`.
- This page displays a table of the 3 most recent published Envira galleries, each with up to 3 images.
- You can also use the `[rc_envira_gallery_table]` shortcode in any page or post to display the gallery table.

### Envira Tags Widget
- Go to **Appearance → Widgets** in your WordPress admin.
- Add the **Envira Gallery Tags** widget to your sidebar or any widget area.
- The widget will only appear on single Envira gallery pages and will display a list of tags for the current gallery.

## Requirements
- WordPress 5.0 or higher
- PHP 7.0 or higher

## Support
For support, please open an issue on the plugin's repository or contact the developer directly.

## Changelog
- **1.14.0** - Gallery table now sorts randomized results by oldest publication date first. Added a new widget that displays all Envira categories with their counts, visible only on Envira album pages.
- **1.13.0** - Gallery table now displays 3 random Envira galleries, but always sorts the random selection by publication date (newest first) before display.
- **1.12.0** - RSS feed excludes galleries not in the "Members Gallery" album and those scheduled for the future. Envira Gallery Categories widget now shows a total count of galleries in each category.
- **1.11.0** - Envira Gallery Categories widget now hides itself if no categories are found for the gallery, and displays categories as a bulleted list when present.
- **1.10.0** - Gallery table now includes a third column with a copyable text field containing the slugs of the Envira categories applied to each gallery in hashtag format. Galleries with the "Dustrat" category are excluded from the table.
- **1.9.0** - Envira Tags Widget now displays a clear error if the gallery post ID cannot be determined, and a separate error with details if there is an error retrieving tags for a gallery. Improved detection for Envira galleries, including support for standalone URLs like `/envira/gallery-slug/`.
- **1.8.0** - Improved Envira Tags Widget: now reliably detects the current Envira gallery post/page and its tags, including support for standalone URLs like `/envira/gallery-slug/`. Widget always displays, even if no tags are found.
- **1.7.0** - Gallery table now displays 3 random published Envira galleries (not just the latest), makes the gallery title a clickable link, and provides copyable links to each image and the gallery page. Envira Tags Widget now always displays on Envira posts, even if no tags are found.
- **1.6.0** - Gallery table now makes the gallery title a clickable link, and provides copyable links to each gallery page.
- **1.5.0** - Gallery table now displays 3 random published Envira galleries (not just the latest), and provides copyable links to each image. Fixed Envira Tags Widget to display on all Envira post types.
- **1.4.0** - Fixed Envira Tags Widget to display on all Envira post types, not just single gallery pages.
- **1.3.0** - Added support for Envira gallery preview image in the RSS feed and improved pretty printing of XML output.
- **1.2.0** - Added Envira tags widget for single gallery pages.
- **1.1.0** - Added Envira gallery table page and shortcode.
- **1.0.0** - Initial release
