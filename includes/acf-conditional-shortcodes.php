<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Check if ACF is active or has value

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

function conditional_acf_shortcode($atts, $content = null)
{
    // Extract shortcode attributes
    $atts = shortcode_atts(
        [
            "field" => "", // ACF field name
            "post_id" => "options", // Post ID (default to 'options' for options page)
        ],
        $atts
    );

    // Get the ACF field value
    $field_value = get_field($atts["field"], $atts["post_id"]);

    // If the field exists and is not empty, return the content
    if (!empty($field_value)) {
        return do_shortcode($content);
    }

    // Return nothing if the condition is not met
    return "";
}
add_shortcode("acf_conditional", "conditional_acf_shortcode");
