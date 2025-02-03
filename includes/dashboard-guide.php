<?php
// Add Instructions Dashboard Widget
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
add_action('wp_dashboard_setup', 'sbo_add_instructions_widget');

function sbo_add_instructions_widget() {
    wp_add_dashboard_widget(
        'sbo_instructions_widget', // Widget slug
        'Local business guide', // Widget title
        'sbo_display_instructions_widget' // Display callback
    );
}
function sbo_display_instructions_widget() {
    // Fetch the support email dynamically
    $support_email = get_field('one_support_email', 'options'); // Replace 'one_support_email' with your actual ACF field name
    if (!$support_email) {
        $support_email = 'support@example.com'; // Default fallback email
    }
    // Get the custom post type count
    $services_count = wp_count_posts('services'); // Replace 'services' with your custom post type slug
    $published_count = $services_count->publish;

    // Add custom post type count to the items
    if ($published_count > 0) {
        $text = _n('Service', 'Services', $published_count, 'text-domain'); // Singular/Plural translation
        $items[] = sprintf(
            '<a href="%s">%s %s</a>',
            esc_url(admin_url('edit.php?post_type=services')),
            number_format_i18n($published_count),
            esc_html($text)
        );
    }

    echo '<div>';
    

    
    echo 'This website currently has:';
    echo '<ul>';
    echo '<li><a href="' . esc_url(admin_url('edit.php?post_type=services')) . '">(' . $published_count . ') Services</a>';
    echo '</ul>';

    echo 'Review the following sections to ensure your business information is up-to-date:';
    echo '<ol>';
    echo '<li><a href="' . esc_url(admin_url('admin.php?page=business-information')) . '">Business information</a>';
    echo '<li><a href="' . esc_url(admin_url('admin.php?page=social-media')) . '">Social media accounts</a>';
    echo '</ol>';

    /* shortcodes */
    echo '<h3 style="font-weight:bold;">Shortcodes</h3>';
    echo '<p>Use <a href="' . esc_url(admin_url('admin.php?page=sbo-shortcodes')) . '">shortcodes</a> in your content to display dynamic information. For example, use <code>[sbo_business_name]</code> to display the business name.</p>';

    /* import export */
    echo '<h3 style="font-weight:bold;">Import/export</h3>';
    echo '<p>Use <a href="' . esc_url(admin_url('options-general.php?page=sbo-acf-import-export')) . '">Import/Export Business Data</a></p>';

    
    echo '<h3 style="font-weight:bold;">Need help?</h3>';
    echo '<ul>';
    echo '<li>Send e-mail to <a href="mailto:' . esc_html($support_email) . '">' . esc_html($support_email) . '</a>.</li>';
    echo '</ul>';
    echo '</div>';
}