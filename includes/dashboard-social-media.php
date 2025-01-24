<?php
// Add Social Media Dashboard Widget
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
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
    $social_shortcodes = [
        '[social_facebook_link fill="#4267B2"]' => 'Facebook', // Official Blue
        '[social_linkedin_link fill="#0A66C2"]' => 'LinkedIn', // Official Blue
        '[social_instagram_link fill="#E4405F"]' => 'Instagram', // Official Pink
        '[social_google_business_link fill="#4285F4"]' => 'Google Business', // Official Blue
        '[social_youtube_link fill="#FF0000"]' => 'YouTube', // Official Red
        '[social_twitter_x_link fill="#1DA1F2"]' => 'Twitter-X', // Official Blue
        '[social_pinterest_link fill="#E60023"]' => 'Pinterest', // Official Red
        '[social_wordpress_link fill="#21759B"]' => 'WordPress', // Official Blue
        '[social_yelp_link fill="#D32323"]' => 'Yelp', // Official Red
        '[social_github_link fill="#333333"]' => 'GitHub', // Official Gray
        '[social_bing_link fill="#008373"]' => 'Bing', // Official Teal
        '[social_tiktok_link fill="#010101"]' => 'TikTok', // Black (with accents)
        '[social_snapchat_link fill="#FFFC00"]' => 'Snapchat', // Official Yellow
        '[social_reddit_link fill="#FF4500"]' => 'Reddit', // Official Orange
        '[social_tripadvisor_link fill="#00AF87"]' => 'TripAdvisor', // Official Green
        '[social_whatsapp_link fill="#25D366"]' => 'WhatsApp', // Official Green
        '[social_bbb_link fill="#00457C"]' => 'Better Business Bureau (BBB)', // Official Blue
    ];

   /* $social_shortcodes = [
        '[social_facebook_link fill="#4267B2"]' => 'Facebook',
        '[social_wordpress_link fill="#21759B"]' => 'WordPress',
        '[social_youtube_link fill="#FF0000"]' => 'YouTube',
        '[social_instagram_link fill="#E4405F"]' => 'Instagram',
        '[social_twitter_link fill="#1DA1F2"]' => 'Twitter (now X)',
        '[social_google_business_link fill="#4285F4"]' => 'Google Business',
        '[social_pinterest_link fill="#E60023"]' => 'Pinterest',
        '[social_yelp_link fill="#D32323"]' => 'Yelp',
        '[social_github_link fill="#333333"]' => 'GitHub',
    ];
    */
    foreach ($social_shortcodes as $shortcode => $name) {
        echo '<div style="margin-right:5px; display:inline-block;">' . do_shortcode($shortcode) . '</div>';
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

// social media short codes

// Output the shortcodes in a code block
echo '<hr style="margin-top:20px;"><h3 style="margin-top:10px;">Shortcodes for content or layouts</h3>';
echo '<pre><code style="font-size: 12px; line-height: 2;">';
echo esc_html('[social_facebook_link fill="#4267B2"]') . "\n";
echo esc_html('[social_linkedin_link fill="#0A66C2"]') . "\n";
echo esc_html('[social_instagram_link fill="#E4405F"]') . "\n";
echo esc_html('[social_google_business_link fill="#4285F4"]') . "\n";
echo esc_html('[social_youtube_link fill="#FF0000"]') . "\n";
echo esc_html('[social_twitter_x_link fill="#1DA1F2"]') . "\n";
echo esc_html('[social_pinterest_link fill="#E60023"]') . "\n";
echo esc_html('[social_wordpress_link fill="#21759B"]') . "\n";
echo esc_html('[social_yelp_link fill="#D32323"]') . "\n";
echo esc_html('[social_github_link fill="#333333"]') . "\n";
echo esc_html('[social_bing_link fill="#008373"]') . "\n";
echo esc_html('[social_tiktok_link fill="#010101"]') . "\n";
echo esc_html('[social_snapchat_link fill="#FFFC00"]') . "\n";
echo esc_html('[social_reddit_link fill="#FF4500"]') . "\n";
echo esc_html('[social_tripadvisor_link fill="#00AF87"]') . "\n";
echo esc_html('[social_whatsapp_link fill="#25D366"]') . "\n";
echo esc_html('[social_bbb_link fill="#00457C"]') . "\n";
echo '</code></pre>';









        echo '</div>';
    }