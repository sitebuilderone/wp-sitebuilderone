<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Generate and output LocalBusiness schema.org JSON-LD
 */
function output_local_business_schema() {
    if (!function_exists('get_field')) {
        return;
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        'name' => get_field('one_business_name', 'option'),
        'description' => get_field('one_business_description', 'option'),
        'url' => home_url(),
        'telephone' => get_field('one_business_phone', 'option'),
        'email' => get_field('one_business_email', 'option'),
        'image' => [
            get_field('one_business_logo', 'option'),
            get_field('one_banner_image', 'option')
        ]
    ];

    // Add address
    $street = get_field('one_street_address', 'option');
    $city = get_field('one_city', 'option');
    $state = get_field('one_state', 'option');
    $postal = get_field('one_postal_code', 'option');
    $country = get_field('one_country', 'option');

    if ($street || $city || $state || $postal || $country) {
        $schema['address'] = [
            '@type' => 'PostalAddress',
            'streetAddress' => $street,
            'addressLocality' => $city,
            'addressRegion' => $state,
            'postalCode' => $postal,
            'addressCountry' => $country
        ];
    }

    // Add social media profiles
    $social_profiles = [];
    $social_fields = [
        'social-facebook', 'social-linkedin', 'social-instagram',
        'social-youtube', 'social-twitter-x', 'social-pinterest',
        'social-yelp', 'social-github', 'social-tripadvisor',
        'social-whatsapp', 'social-reddit'
    ];

    foreach ($social_fields as $field) {
        $url = get_field($field, 'option');
        if ($url) {
            $social_profiles[] = $url;
        }
    }

    if (!empty($social_profiles)) {
        $schema['sameAs'] = $social_profiles;
    }

    // Output the schema
    echo PHP_EOL . '<!-- Local Business Schema -->' . PHP_EOL;
    echo '<script type="application/ld+json">' . PHP_EOL;
    echo wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    echo PHP_EOL . '</script>' . PHP_EOL;
}

// Add schema to wp_head
add_action('wp_head', 'output_local_business_schema', 10);