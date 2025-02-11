<?php
// generates shortcodes for ACF options page fields
if (!defined('ABSPATH')) {
    exit;
}

function sbo_generate_acf_option_shortcodes_from_db() {
    global $shortcode_tags;
    $field_groups = acf_get_field_groups();

    if (!$field_groups) {
        error_log('SBO: No ACF field groups found.');
        return;
    }

    foreach ($field_groups as $group) {
        $fields = acf_get_fields($group['key']);
        if (!$fields) {
            continue;
        }

        foreach ($fields as $field) {
            $shortcode_name = 'sbo_' . sanitize_title($field['name']);
            if (isset($shortcode_tags[$shortcode_name])) {
                continue;
            }

            if ($field['type'] === 'repeater') {
                // Handle repeater fields
                add_shortcode($shortcode_name, function() use ($field) {
                    $repeater_data = get_field($field['name'], 'option');
                    if (!empty($repeater_data) && is_array($repeater_data)) {
                        $output = '<div class="' . esc_attr($field['name']) . '-wrapper">';
                        foreach ($repeater_data as $row) {
                            $output .= '<div class="' . esc_attr($field['name']) . '-item">';
                            foreach ($row as $key => $value) {
                                // Skip empty values and ensure we're only outputting strings
                                if (!empty($value) && is_string($value)) {
                                    $output .= '<div class="' . esc_attr($key) . '">' . esc_html($value) . '</div>';
                                } elseif (!empty($value) && is_array($value)) {
                                    // Handle nested arrays (like image arrays) by getting the URL
                                    if (isset($value['url'])) {
                                        $output .= '<div class="' . esc_attr($key) . '">' . esc_url($value['url']) . '</div>';
                                    }
                                }
                            }
                            $output .= '</div>';
                        }
                        $output .= '</div>';
                        return $output;
                    }
                    return '';
                });
            } else {
                // Handle non-repeater fields
                add_shortcode($shortcode_name, function() use ($field) {
                    $value = get_field($field['name'], 'option');
                    
                    // Handle different types of values
                    if (is_array($value)) {
                        if (isset($value['url'])) {
                            // Handle image fields
                            return esc_url($value['url']);
                        }
                        // For other arrays, return empty to avoid errors
                        return '';
                    } elseif (is_string($value)) {
                        return wp_kses_post($value);
                    }
                    
                    return '';
                });
            }
        }
    }
}

add_action('init', 'sbo_generate_acf_option_shortcodes_from_db');

// Add custom testimonial shortcode
function sbo_add_custom_testimonial_shortcode() {
    add_shortcode('sbo_testimonials', function() {
        $testimonials = get_field('one_testimonials', 'option');
        if (!$testimonials || !is_array($testimonials)) {
            return '';
        }

        $output = '<div class="testimonials-wrapper">';
        foreach ($testimonials as $testimonial) {
            $output .= '<div class="testimonial-item">';
            if (!empty($testimonial['one_testimonial_name'])) {
                $output .= '<h3 class="testimonial-name">' . esc_html($testimonial['one_testimonial_name']) . '</h3>';
            }
            if (!empty($testimonial['one_testimonial_company'])) {
                $output .= '<div class="testimonial-company">' . esc_html($testimonial['one_testimonial_company']) . '</div>';
            }
            if (!empty($testimonial['one_testimonial_review'])) {
                $output .= '<div class="testimonial-review">' . wp_kses_post($testimonial['one_testimonial_review']) . '</div>';
            }
            if (!empty($testimonial['one_testimonial_image'])) {
                $output .= '<div class="testimonial-image"><img src="' . esc_url($testimonial['one_testimonial_image']) . '" alt="Testimonial"></div>';
            }
            $output .= '</div>';
        }
        $output .= '</div>';
        return $output;
    });
}
add_action('init', 'sbo_add_custom_testimonial_shortcode');

function sbo_register_admin_page() {
    add_menu_page(
        'Shortcodes',           // Page title
        'Shortcodes',           // Menu title
        'manage_options',       // Capability
        'sbo-shortcodes',       // Menu slug
        'sbo_render_shortcodes_page', // Callback function
        'dashicons-editor-code',    // Icon (Dashicon class)
        4                      // Position
    );
}
add_action('admin_menu', 'sbo_register_admin_page');

function sbo_render_shortcodes_page() {
    // Fetch all ACF field groups
    $field_groups = acf_get_field_groups();

    if (!$field_groups) {
        echo '<div class="wrap"><h1>SBO Shortcodes</h1><p>No ACF field groups found.</p></div>';
        return;
    }

    echo '<div class="wrap">';
    echo '<h1>SBO Shortcodes</h1>';
    echo '<p>Below is the list of available shortcodes grouped by their field groups, with a copy-to-clipboard option:</p>';

    foreach ($field_groups as $group) {
        echo '<h2 style="margin-top: 20px;">' . esc_html($group['title']) . '</h2>';
        echo '<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">';
        echo '<thead>
                <tr>
                    <th style="text-align: left; border-bottom: 1px solid #ccc; padding: 8px;">Field Label</th>
                    <th style="text-align: left; border-bottom: 1px solid #ccc; padding: 8px;">Action</th>
                    <th style="text-align: left; border-bottom: 1px solid #ccc; padding: 8px;">Shortcode</th>
                    <th style="text-align: left; border-bottom: 1px solid #ccc; padding: 8px;">Value</th>
                </tr>
              </thead>';
        echo '<tbody>';

        // Get all fields in the group
        $fields = acf_get_fields($group['key']);
        if (!$fields) {
            echo '<tr><td colspan="4" style="padding: 8px;">No fields found in this group.</td></tr>';
        } else {
            foreach ($fields as $field) {
                $shortcode_name = 'sbo_' . sanitize_title($field['name']);
                $value = get_field($field['name'], 'option');
                
                // Handle value display
                $value_display = '';
                if (is_string($value)) {
                    $value_display = wp_kses_post($value);
                } elseif (is_array($value)) {
                    if ($field['type'] === 'repeater') {
                        $value_display = '<em>' . count($value) . ' items</em>';
                    } elseif (isset($value['url'])) {
                        $value_display = esc_url($value['url']);
                    } else {
                        $value_display = '<em>Complex field</em>';
                    }
                } else {
                    $value_display = '<em>-</em>';
                }

                echo '<tr>';
                echo '<td style="padding: 8px; width:200px;">' . esc_html($field['label']) . '</td>';
                echo '<td style="padding: 8px; width:60px;">
                        <button class="sbo-copy-btn" data-shortcode="[' . esc_attr($shortcode_name) . ']">Copy</button>
                      </td>';
                echo '<td style="padding: 8px;width:300px;"><code>[' . esc_html($shortcode_name) . ']</code></td>';
                echo '<td style="padding: 8px;">' . $value_display . '</td>';
                echo '</tr>';
            }
        }

        echo '</tbody>';
        echo '</table>';
    }

    // List all registered shortcodes
    echo '<h2 style="margin-top: 40px;">All Registered Shortcodes</h2>';
    echo '<p>Below is the list of all shortcodes registered on this WordPress site:</p>';
    echo '<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">';
    echo '<thead>
            <tr>
                <th style="text-align: left; border-bottom: 1px solid #ccc; padding: 8px;">Shortcode</th>
                <th style="text-align: left; border-bottom: 1px solid #ccc; padding: 8px;">Description</th>
            </tr>
          </thead>';
    echo '<tbody>';

    global $shortcode_tags;
    foreach ($shortcode_tags as $shortcode => $callback) {
        echo '<tr>';
        echo '<td style="padding: 8px; width:300px;"><code>[' . esc_html($shortcode) . ']</code></td>';
        echo '<td style="padding: 8px;">' . (is_callable($callback) ? 'Custom shortcode' : '<em>No description available</em>') . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';

    echo '</div>';

    // Add JavaScript for "Click to Copy"
    ?>
    <script>
        document.querySelectorAll(".sbo-copy-btn").forEach(button => {
            button.addEventListener("click", function() {
                const shortcode = this.getAttribute("data-shortcode");
                navigator.clipboard.writeText(shortcode)
                    .then(() => {
                        // Store the original text
                        const originalText = this.textContent;
                        // Change button text to indicate success
                        this.textContent = "Copied!";
                        // Reset button text after 2 seconds
                        setTimeout(() => {
                            this.textContent = originalText;
                        }, 2000);
                    })
                    .catch(err => {
                        console.error("Failed to copy shortcode: ", err);
                        // Indicate failure
                        this.textContent = "Failed!";
                        setTimeout(() => {
                            this.textContent = "Copy";
                        }, 2000);
                    });
            });
        });
    </script>
    <style>
        .sbo-copy-btn {
            padding: 4px 8px;
            background-color: #2271b1;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .sbo-copy-btn:hover {
            background-color: #135e96;
        }
        .sbo-copy-btn:active {
            background-color: #0a4b78;
        }
    </style>
    <?php
}

add_shortcode('sbo_one_google_map_embed', function() {
    $embed_code = get_field('one_google_map_embed', 'option');
    if ($embed_code) {
        return $embed_code; // Ensure the raw iframe HTML is returned
    }
    return 'Google Map Embed not set.';
});
