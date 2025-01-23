<?php
// file: dashboard-widget-tasks.php
// Add Tasks Dashboard Widget

function sbo_get_tasks() {
    $json_file = plugin_dir_path(__FILE__) . '/tasks.json';

    // Check if the file exists
    if (!file_exists($json_file)) {
        return []; // Return an empty array if the file is missing
    }

    // Decode the JSON file
    $tasks = json_decode(file_get_contents($json_file), true);

    // Handle JSON errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        return []; // Return an empty array on error
    }

    return $tasks;
}

add_action('wp_dashboard_setup', 'sbo_add_task_widget');

function sbo_add_task_widget() {
    wp_add_dashboard_widget(
        'sbo_task_widget', // Widget slug
        'Task List', // Widget title
        'sbo_display_task_widget' // Display callback
    );
}

function sbo_display_task_widget() {
    // Fetch tasks from JSON
    $tasks = sbo_get_tasks();

    // Fetch saved completion state from WordPress options
    $completed_tasks = get_option('sbo_task_completion', []);

    if (!empty($tasks)) {
        echo '<ul id="sbo-task-list" style="list-style: none; padding: 0;">';
        foreach ($tasks as $task) {
            $task_id = esc_attr($task['id']);
            $checked = !empty($completed_tasks[$task_id]) ? 'checked' : '';
            echo '<li style="display: flex; align-items: center; margin-bottom: 10px;">';
            echo '<input type="checkbox" class="sbo-task-checkbox" data-id="' . $task_id . '" ' . $checked . ' style="margin-right: 10px;">';
            echo '<div>';
            echo '<h4 style="margin: 0;">' . esc_html($task['task']) . '</h4>';
            echo '<p style="margin: 0;">' . wp_kses_post($task['instructions']) . '</p>'; // Allow safe HTML
            echo '</div>';
            echo '</li>';
        }
        echo '</ul>';

        // Add JavaScript for AJAX handling
        echo '<script>
            document.querySelectorAll(".sbo-task-checkbox").forEach(checkbox => {
                checkbox.addEventListener("change", function() {
                    const taskId = this.dataset.id;
                    const completed = this.checked ? 1 : 0;

                    fetch("' . admin_url('admin-ajax.php') . '", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "action=sbo_save_task_completion&task_id=" + taskId + "&completed=" + completed
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log("Task " + taskId + " saved successfully.");
                        } else {
                            console.error("Failed to save task " + taskId);
                        }
                    });
                });
            });
        </script>';
    } else {
        echo '<p>No tasks found.</p>';
    }
}

add_action('wp_ajax_sbo_save_task_completion', 'sbo_save_task_completion');

function sbo_save_task_completion() {
    // Verify and sanitize input
    $task_id = sanitize_text_field($_POST['task_id']);
    $completed = intval($_POST['completed']);

    // Retrieve the current state
    $current_state = get_option('sbo_task_completion', []);

    // Update the state using the task ID as the key
    $current_state[$task_id] = $completed;
    update_option('sbo_task_completion', $current_state);

    wp_send_json_success('Task saved successfully.');
}