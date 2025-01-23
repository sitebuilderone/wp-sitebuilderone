<?php
// Add Social Media Dashboard Widget
add_action('wp_dashboard_setup', 'sbo_add_social_media_widget');

function sbo_add_social_media_widget() {
    wp_add_dashboard_widget(
        'sbo_social_media_widget', // Widget slug
        'Social Media Properties', // Widget title
        'sbo_display_social_media_widget' // Display callback
    );
}

function sbo_display_social_media_widget() {
    // Define all social media fields with labels
    $social_media_fields = [
        'social-facebook' => 'Facebook',
        'social-linkedin' => 'LinkedIn',
        'social-instagram' => 'Instagram',
        'social-google_business' => 'Google Business',
        'social-youtube' => 'YouTube',
        'socal-twitter-x' => 'Twitter-X',
        'social-pinterest' => 'Pinterest',
        'social-wordpress' => 'WordPress',
        'social-yelp' => 'Yelp',
        'social-github' => 'GitHub',
        'social-bing' => 'Bing',
        'social-tiktok' => 'TikTok',
        'social-snapchat' => 'Snapchat',
        'social-reddit' => 'Reddit',
        'social-tripadvisor' => 'TripAdvisor',
        'social-whatsapp' => 'WhatsApp',
        'social-bbb' => 'Better Business Bureau (BBB)',
    ];

    // Initialize arrays for set and missing fields
    $set_fields = [];
    $missing_fields = [];

    // Check each field's value
    foreach ($social_media_fields as $key => $label) {
        $value = get_field($key, 'options');
        if ($value) {
            $set_fields[$label] = $value;
        } else {
            $missing_fields[] = $label;
        }
    }

    // Display the widget content
    echo '<div>';
 

// Display fields with values
if (!empty($set_fields)) {
    // Sort the set fields alphabetically by label
    ksort($set_fields);

    echo '<ol>';
    foreach ($set_fields as $label => $value) {
        echo '<li><strong>' . esc_html($label) . ':</strong> <a href="' . esc_url($value) . '" target="_blank">' . esc_html($value) . '</a></li>';
    }
    echo '</ol>';
} else {
    echo '<p><strong>No social media properties are set.</strong></p>';
}
// Display missing fields
if (!empty($missing_fields)) {
    // Sort the missing fields alphabetically
    sort($missing_fields);

    echo '<h4>Missing social accounts:</h4><ul>';
    foreach ($missing_fields as $label) {
        echo '<li>' . esc_html($label) . '</li>';
    }
    echo '</ul>';
} else {
    echo '<p><strong>All social media properties are set.</strong></p>';
}
// Add a link to the options page
echo '<div style="text-align: center; margin-top: 20px;">';
echo '<a href="' . esc_url(admin_url('admin.php?page=social-media')) . '" class="button button-primary">Edit Social Media Accounts</a>';
echo '</div>';
        echo '</div>';
    }