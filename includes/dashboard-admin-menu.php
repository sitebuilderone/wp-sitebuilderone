<?php
// Add Instructions Dashboard Widget
if (!defined('ABSPATH')) {
    exit;
}

// Add menu item to WordPress admin toolbar
add_action('admin_bar_menu', 'add_business_info_link', 999);

function add_business_info_link($wp_admin_bar) {
    $args = array(
        'id'    => 'business-info',
        'title' => 'Business',
        'href'  => admin_url('admin.php?page=business-information'),
        'meta'  => array(
            'class' => 'business-info-toolbar'
        )
    );
    $wp_admin_bar->add_node($args);

        // Services Link with Count
        $services_count = wp_count_posts('services');
        $published_count = $services_count->publish;
        
        $services_args = array(
            'id' => 'services-info',
            'title' => sprintf('Services (%d)', $published_count), // Shows "Services (X)" where X is the count
            'href' => admin_url('edit.php?post_type=services'),
            'meta' => array(
                'class' => 'services-info-toolbar'
            )
        );
        $wp_admin_bar->add_node($services_args);

    $shortcode_args = array(
        'id'    => 'shortcode-info',
        'title' => 'Shortcodes',
        'href'  => admin_url('admin.php?page=sbo-shortcodes'),
        'meta'  => array(
            'class' => 'shortcode-info-toolbar'
        )
    );
    $wp_admin_bar->add_node($shortcode_args);


}








