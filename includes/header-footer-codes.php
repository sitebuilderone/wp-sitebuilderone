<?php
/**
 * Add Google Analytics and custom header code from ACF options to head
 */
function add_header_codes() {
    if (!function_exists('get_field')) {
        return;
    }

    // Get both codes from ACF options
    $analytics_code = get_field('one_google_analytics_tag_code', 'option');
    $header_code = get_field('one_header_code', 'option');

    // Output Google Analytics if it exists
    if (!empty($analytics_code)) {
        $analytics_code = preg_replace('/\s+/', ' ', trim($analytics_code));
        echo '<!-- Google Analytics -->' . 
             wp_kses($analytics_code, [
                'script' => [
                    'src' => [],
                    'async' => [],
                    'type' => []
                ]
             ]) . 
             '<!-- End Google Analytics -->';
    }

    // Output custom header code if it exists
    if (!empty($header_code)) {
        $header_code = preg_replace('/\s+/', ' ', trim($header_code));
        echo '<!-- Custom Header Code -->' . 
             wp_kses($header_code, [
                'script' => [
                    'src' => [],
                    'async' => [],
                    'type' => []
                ],
                'meta' => [
                    'name' => [],
                    'content' => [],
                    'property' => []
                ],
                'link' => [
                    'rel' => [],
                    'href' => [],
                    'type' => []
                ]
             ]) . 
             '<!-- End Custom Header Code -->';
    }
}

/**
 * Add custom footer code from ACF options
 */
function add_footer_code() {
    if (!function_exists('get_field')) {
        return;
    }

    $footer_code = get_field('one_footer_code', 'option');

    if (!empty($footer_code)) {
        $footer_code = preg_replace('/\s+/', ' ', trim($footer_code));
        echo '<!-- Custom Footer Code -->' . 
             wp_kses($footer_code, [
                'script' => [
                    'src' => [],
                    'async' => [],
                    'type' => [],
                    'defer' => []
                ],
                'noscript' => [],
                'iframe' => [
                    'src' => [],
                    'width' => [],
                    'height' => [],
                    'style' => [],
                    'frameborder' => [],
                    'allowfullscreen' => [],
                    'loading' => []
                ]
             ]) . 
             '<!-- End Custom Footer Code -->';
    }
}

// Add header codes with priority 1
add_action('wp_head', 'add_header_codes', 1);

// Add footer code with priority 20 to ensure it loads after other scripts
add_action('wp_footer', 'add_footer_code', 20);



// https://developers.google.com/search/docs/appearance/structured-data/local-business
// JSON local business structured data output to header here