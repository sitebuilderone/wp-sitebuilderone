<?php
// Add Instructions Dashboard Widget
if (!defined('ABSPATH')) {
    exit;
}

// Add dashboard widget and ensure proper loading
add_action('wp_dashboard_setup', 'sbo_add_instructions_widget');
add_action('admin_enqueue_scripts', 'sbo_dashboard_widget_scripts');

// Add refresh capability to the widget
function sbo_dashboard_widget_scripts($hook) {
    if ('index.php' !== $hook) {
        return;
    }
    
    // Get the correct path to the JS file
    $js_path = plugin_dir_url(__FILE__) . 'js/dashboard-refresh.js';
    
    // Add inline script for debugging
    wp_enqueue_script('jquery');
    
    wp_add_inline_script('jquery', '
        console.log("Dashboard widget script loading...");
        jQuery(document).ready(function($) {
            console.log("Dashboard widget ready...");
            
            function refreshWidget() {
                console.log("Refresh clicked...");
                var widget = $("#sbo_instructions_widget");
                var widgetContent = widget.find(".inside");
                
                widgetContent.css("opacity", "0.5");
                
                $.ajax({
                    url: "' . admin_url('admin-ajax.php') . '",
                    type: "POST",
                    data: {
                        action: "sbo_refresh_widget",
                        nonce: "' . wp_create_nonce('sbo_refresh_widget') . '"
                    },
                    success: function(response) {
                        console.log("Refresh successful");
                        widgetContent.html(response);
                        widgetContent.css("opacity", "1");
                    },
                    error: function(xhr, status, error) {
                        console.log("Refresh failed:", error);
                        widgetContent.css("opacity", "1");
                        alert("Failed to refresh widget. Please try again.");
                    }
                });
            }

            $(document).on("click", ".sbo-refresh-widget", refreshWidget);
            
            // Auto-refresh on load
            if ($("#sbo_instructions_widget").length) {
                console.log("Auto-refreshing widget...");
                refreshWidget();
            }
        });
    ', 'before');
}

// Add AJAX refresh handler
add_action('wp_ajax_sbo_refresh_widget', 'sbo_refresh_widget_content');
function sbo_refresh_widget_content() {
    check_ajax_referer('sbo_refresh_widget', 'nonce');
    
    // Log for debugging
    error_log('Widget refresh requested');
    
    sbo_display_instructions_widget();
    wp_die();
}

function sbo_add_instructions_widget() {
    wp_add_dashboard_widget(
        'sbo_instructions_widget',
        'Local business guide',
        'sbo_display_instructions_widget',
        'sbo_dashboard_widget_control'
    );
}

// Add widget control for refresh button
function sbo_dashboard_widget_control() {
    echo '<div class="sbo-widget-controls" style="padding: 12px; border-bottom: 1px solid #e5e5e5;">';
    echo '<button type="button" class="button button-primary sbo-refresh-widget">Refresh Information</button>';
    echo '<span class="spinner" style="float: none; margin: 0 0 0 4px;"></span>';
    echo '</div>';
}

function sbo_display_instructions_widget() {
    // Clear any existing output buffering
    while (ob_get_level()) {
        ob_end_clean();
    }

    // Start fresh output buffer
    ob_start();

    // Clear all relevant caches
    wp_cache_delete('acf_options', 'options');
    wp_cache_delete('posts_count', 'counts');
    wp_cache_delete('alloptions', 'options');
    
    // Fetch data with cache busting
    $support_email = get_field('one_support_email', 'options', true);
    if (!$support_email) {
        $support_email = 'support@example.com';
    }

    // Get fresh post count
    $services_count = wp_count_posts('services');
    $published_count = $services_count->publish;

    // Business Information Fields
    $business_fields = array(
        'business_name' => get_field('one_business_name', 'options', true),
        'street_address' => get_field('one_street_address', 'options', true),
        'city' => get_field('one_city', 'options', true),
        'state' => get_field('one_state', 'options', true),
        'postal_code' => get_field('one_postal_code', 'options', true),
        'country' => get_field('one_country', 'options', true),
        'business_phone' => get_field('one_business_phone', 'options', true),
        'business_email' => get_field('one_business_email', 'options', true),
        'business_fax' => get_field('one_business-fax', 'options', true)
    );

    // Social Media Fields
    $social_fields = array(
        'facebook_url' => get_field('facebook_url', 'options', true),
        'twitter_url' => get_field('twitter_url', 'options', true),
        'instagram_url' => get_field('instagram_url', 'options', true),
        'linkedin_url' => get_field('linkedin_url', 'options', true)
    );

    echo '<div class="sbo-dashboard-widget" data-last-updated="' . esc_attr(current_time('timestamp')) . '">';
    
    // Rest of your display code remains the same
    // Current Business Information Section
    echo '<h3 style="font-weight:bold;">Current Business Information</h3>';
    echo '<ul class="sbo-business-info">';
    foreach ($business_fields as $key => $value) {
        if (!empty($value)) {
            echo '<li>' . esc_html(ucwords(str_replace('_', ' ', $key))) . ': ' . esc_html($value) . '</li>';
        }
    }
    echo '</ul>';

    // Services Count
    echo '<p>Published Services: <a href="' . esc_url(admin_url('edit.php?post_type=services')) . '">' . 
         esc_html($published_count) . ' ' . _n('Service', 'Services', $published_count, 'text-domain') . '</a></p>';

    // Missing Information Section
    echo '<h3 style="font-weight:bold; color: #d63638;">Missing Information</h3>';
    echo '<ul class="sbo-missing-info" style="color: #d63638;">';
    
    // Check Business Fields
    $missing_found = false;
    foreach ($business_fields as $key => $value) {
        if (empty($value)) {
            $missing_found = true;
            echo '<li>' . esc_html(ucwords(str_replace('_', ' ', $key))) . ' - <a href="' . 
                 esc_url(admin_url('admin.php?page=business-information')) . '">Add now</a></li>';
        }
    }

    // Check Social Media Fields
    foreach ($social_fields as $key => $value) {
        if (empty($value)) {
            $missing_found = true;
            echo '<li>' . esc_html(ucwords(str_replace('_', ' ', $key))) . ' - <a href="' . 
                 esc_url(admin_url('admin.php?page=social-media')) . '">Add now</a></li>';
        }
    }

    if (!$missing_found) {
        echo '<li style="color: #00a32a;">All required information has been provided!</li>';
    }
    echo '</ul>';

    // Standard sections remain the same
    echo '<h3 style="font-weight:bold;">Shortcodes</h3>';
    echo '<p>Use <a href="' . esc_url(admin_url('admin.php?page=sbo-shortcodes')) . '">shortcodes</a> in your content to display dynamic information. For example, use <code>[sbo_business_name]</code> to display the business name.</p>';

    echo '<h3 style="font-weight:bold;">Import/Export</h3>';
    echo '<p>Use <a href="' . esc_url(admin_url('options-general.php?page=sbo-acf-import-export')) . '">Import/Export Business Data</a></p>';

    echo '<h3 style="font-weight:bold;">Need help?</h3>';
    echo '<ul>';
    echo '<li>Send e-mail to <a href="mailto:' . esc_html($support_email) . '">' . esc_html($support_email) . '</a></li>';
    echo '</ul>';
    
    echo '<p class="sbo-last-updated" style="font-size: 0.8em; color: #666;">Last updated: ' . 
         esc_html(current_time('F j, Y g:i a')) . '</p>';
    
    echo '</div>';

    // Output and clean the buffer
    $output = ob_get_clean();
    echo $output;
}