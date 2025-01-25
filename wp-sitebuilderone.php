<?php
/**
 * Plugin Name: SiteBuilderOne Local Business
 * Plugin URI: https://github.com/sitebuilderone/wp-sitebuilderone
 * GitHub Plugin URI: https://github.com/sitebuilderone/wp-sitebuilderone
 * GitHub Branch: main
 * Description: ACF, LiveCanvas compatible plugin for Local Business websites
 * Version: 0.0.14.2
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

// ACF Dependency Check
if (!class_exists('ACF')) {
    // Show admin notice
    add_action('admin_notices', function () {
        echo '<div class="notice notice-error"><p><strong>SiteBuilderOne Local Business Plugin</strong> requires the <a href="https://wordpress.org/plugins/advanced-custom-fields/" target="_blank">Advanced Custom Fields (ACF)</a> plugin to be installed and activated.</p></div>';
    });

    // Add menu for resolving dependency
    add_action('admin_menu', function () {
        add_menu_page(
            'Plugin Dependencies',
            'Dependencies',
            'manage_options',
            'plugin-dependencies',
            function () {
                echo '<div class="wrap">';
                echo '<h1>Missing Dependency</h1>';
                echo '<p>This plugin requires the <strong>Advanced Custom Fields (ACF)</strong> plugin. Please install and activate it to use this plugin.</p>';
                echo '<a href="' . admin_url('plugin-install.php?s=advanced+custom+fields&tab=search&type=term') . '" class="button button-primary">Install ACF</a>';
                echo '</div>';
            }
        );
    });

    // Prevent plugin functionality if ACF is missing
    return;
}

// Prevent activation if ACF is missing
register_activation_hook(__FILE__, function () {
    if (!class_exists('ACF')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(
            '<p><strong>SiteBuilderOne Local Business Plugin</strong> requires the <a href="https://wordpress.org/plugins/advanced-custom-fields/" target="_blank">Advanced Custom Fields (ACF)</a> plugin to be installed and activated. Please install and activate ACF before activating this plugin.</p>',
            'Plugin Activation Error',
            ['back_link' => true]
        );
    }
});

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
    unset($paths[0]);
    $paths[] = plugin_dir_path(__FILE__) . 'acf-json';
    return $paths;
}

// Check and create the ACF JSON directory
$acf_json_dir = plugin_dir_path(__FILE__) . 'acf-json';
if (!file_exists($acf_json_dir)) {
    mkdir($acf_json_dir, 0755, true);
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

// remove default dashboard widgets from wordpress
function remove_dashboard_widgets() {
    global $wp_meta_boxes;
    //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );