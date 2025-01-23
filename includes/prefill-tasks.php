<?php
// Register activation hook for pre-filling tasks
register_activation_hook(__FILE__, 'sbo_prefill_tasks');

function sbo_prefill_tasks() {
    // Ensure ACF is active
    if (!function_exists('get_field') || !function_exists('update_field')) {
        return; // Skip if ACF is not active
    }

    // Check if tasks already exist
    if (get_field('sbo_website_tasks', 'options')) {
        return; // Tasks already exist
    }

    $default_tasks = [
        [
            'task' => 'Add Homepage Content',
            'instructions' => 'Write engaging and clear content for the homepage.',
            'completed' => [],
        ],
        [
            'task' => 'Optimize Images',
            'instructions' => 'Ensure all images are optimized for performance and include alt text.',
            'completed' => [],
        ],
        [
            'task' => 'Set Up Contact Form',
            'instructions' => 'Create a contact form with name, email, and message fields.',
            'completed' => [],
        ],
    ];

    // Save the default tasks to the ACF repeater field
    update_field('sbo_website_tasks', $default_tasks, 'options');
}
