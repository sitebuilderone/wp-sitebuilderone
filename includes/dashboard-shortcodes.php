<?php
// generates shortcodes for ACF options page fields
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

function sbo_generate_acf_option_shortcodes_from_db() {
    // Get all field groups
    $field_groups = acf_get_field_groups();

    if (!$field_groups) {
        error_log('SBO: No ACF field groups found.');
        return;
    }

    foreach ($field_groups as $group) {
        // Get all fields in the group
        $fields = acf_get_fields($group['key']);
        if (!$fields) {
            continue;
        }

        foreach ($fields as $field) {
            // Generate a shortcode for each field
            $shortcode_name = 'sbo_' . sanitize_title($field['name']);
            add_shortcode($shortcode_name, function() use ($field) {
                $value = get_field($field['name'], 'option'); // Fetch value from ACF options
                return $value ?: '<em>No value set for ' . esc_html($field['label']) . '</em>';
            });
        }
    }
}
add_action('init', 'sbo_generate_acf_option_shortcodes_from_db');

function sbo_register_admin_page() {
    add_menu_page(
        'Shortcodes',           // Page title
        'Shortcodes',           // Menu title
        'manage_options',           // Capability
        'sbo-shortcodes',           // Menu slug
        'sbo_render_shortcodes_page', // Callback function
        'dashicons-editor-code',    // Icon (Dashicon class)
        4                          // Position
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
                $value = get_field($field['name'], 'option'); // Fetch value from ACF options
                $value_display = $value ?: '<em>-</em>';

                echo '<tr>';
                echo '<td style="padding: 8px; width:200px;">' . esc_html($field['label']) . '</td>';
                echo '<td style="padding: 8px; width:60px;">
                        <button class="sbo-copy-btn" data-shortcode="[' . esc_attr($shortcode_name) . ']">Copy</button>
                      </td>';
                echo '<td style="padding: 8px;width:300px;"><code>[' . esc_html($shortcode_name) . ']</code></td>';
                echo '<td style="padding: 8px;">' . wp_kses_post($value_display) . '</td>';
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
    echo '<script>
        document.querySelectorAll(".sbo-copy-btn").forEach(button => {
            button.addEventListener("click", function() {
                const shortcode = this.getAttribute("data-shortcode");
                navigator.clipboard.writeText(shortcode).then(() => {
                    console.log("Shortcode copied to clipboard: " + shortcode);
                }).catch(err => {
                    console.error("Failed to copy shortcode: ", err);
                });
            });
        });
    </script>';


    
}