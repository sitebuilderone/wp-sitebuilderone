<?php
/**
 * Plugin Name: SiteBuilderOne Local Business
 * Plugin URI: https://github.com/sitebuilderone/wp-sitebuilderone
 * GitHub Plugin URI: https://github.com/sitebuilderone/wp-sitebuilderone
 * GitHub Branch: main
 * Description: ACF, LiveCanvas compatible plugin for Local Business websites
 * Version: 0.0.14
 * Author: sitebuilderone.com
 * Author URI: https://github.com/sitebuilderone
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: admin-site-enhancements
 */
// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}
// Check and create the ACF JSON directory
$acf_json_dir = plugin_dir_path(__FILE__) . 'acf-json';
if (!file_exists($acf_json_dir)) {
    mkdir($acf_json_dir, 0755, true);
}
// Save ACF JSON to the plugin's `acf-json` directory
add_filter('acf/settings/save_json', 'my_acf_json_save_point');
function my_acf_json_save_point($path) {
    return plugin_dir_path(__FILE__) . 'acf-json';
}
// Load ACF JSON from the plugin's `acf-json` directory
add_filter('acf/settings/load_json', 'my_acf_json_load_point');
function my_acf_json_load_point($paths) {
    $paths[] = plugin_dir_path(__FILE__) . 'acf-json';
    return $paths;
}
// Extend 'At a Glance' widget
add_action('dashboard_glance_items', 'sbo_add_services_to_glance');
function sbo_add_services_to_glance($items) {
    $services_count = wp_count_posts('services'); // Replace 'services' with your custom post type slug
    $published_count = $services_count->publish;
    if ($published_count > 0) {
        $text = _n('Service', 'Services', $published_count, 'text-domain');
        $items[] = sprintf(
            '<a href="%s">%s %s</a>',
            esc_url(admin_url('edit.php?post_type=services')),
            number_format_i18n($published_count),
            esc_html($text)
        );
    }
    return $items;
}
// Include additional files
include_once plugin_dir_path(__FILE__) . 'includes/dashboard-instructions.php';
include_once plugin_dir_path(__FILE__) . 'includes/dashboard-social-media.php';
include_once plugin_dir_path(__FILE__) . 'includes/shortcodes-social.php';
include_once plugin_dir_path(__FILE__) . 'includes/acf-conditional-shortcodes.php';
include_once plugin_dir_path(__FILE__) . 'includes/dashboard-widget-tasks.php';
include_once plugin_dir_path(__FILE__) . 'includes/dashboard-shortcodes.php';