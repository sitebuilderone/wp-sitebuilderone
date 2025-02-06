<?php
if (!defined('ABSPATH')) {
    exit;
}
function sbo_get_tasks() {
    $json_file = plugin_dir_path(__FILE__) . '/tasks.json';
    if (!file_exists($json_file)) {
        return [];
    }
    $tasks = json_decode(file_get_contents($json_file), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [];
    }
    return $tasks;
}
function sbo_group_tasks_by_category($tasks) {
    $grouped_tasks = [];
    foreach ($tasks as $task) {
        $category = isset($task['category']) ? $task['category'] : 'Uncategorized';
        if (!isset($grouped_tasks[$category])) {
            $grouped_tasks[$category] = [];
        }
        $grouped_tasks[$category][] = $task;
    }
    ksort($grouped_tasks); // Sort categories alphabetically
    return $grouped_tasks;
}
// Replace dashboard widget setup with admin menu page
add_action('admin_menu', 'sbo_add_checklist_page');
function sbo_add_checklist_page() {
    add_menu_page(
        'Site Checklist', // Page title
        'Checklist', // Menu title
        'manage_options', // Capability required
        'sbo-checklist', // Menu slug
        'sbo_display_checklist_page', // Callback function
        'dashicons-checklist', // Icon (you can change this)
        30 // Position in menu
    );
}
function sbo_display_checklist_page() {
    // Add page wrapper
    echo '<div class="wrap">';
    echo '<h1>' . get_admin_page_title() . '</h1>';
    // Reuse your existing widget display logic
    $tasks = sbo_get_tasks();
    $completed_tasks = get_option('sbo_task_completion', []);
    $grouped_tasks = sbo_group_tasks_by_category($tasks);
    // Add controls
    echo '<div class="sbo-task-controls" style="margin: 20px 0; padding: 15px; background: #fff; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">';
    echo '<label><input type="checkbox" id="sbo-show-completed" style="margin-right: 5px;">Show completed tasks</label>';
    // Add category filter
    if (!empty($grouped_tasks)) {
        echo '<select id="sbo-category-filter" style="margin-left: 15px;">';
        echo '<option value="all">All Categories</option>';
        foreach (array_keys($grouped_tasks) as $category) {
            echo '<option value="' . esc_attr($category) . '">' . esc_html($category) . '</option>';
        }
        echo '</select>';
    }
    echo '</div>';
    // Main content area
    echo '<div class="sbo-checklist-content" style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">';
    if (!empty($grouped_tasks)) {
        foreach ($grouped_tasks as $category => $category_tasks) {
            echo '<div class="sbo-category-section" data-category="' . esc_attr($category) . '">';
            echo '<h2 style="margin: 20px 0 10px; padding-bottom: 5px; border-bottom: 2px solid #eee;">' . 
                 esc_html($category) . '</h2>';
            echo '<ul class="sbo-task-list" style="list-style: none; padding: 0;">';
            foreach ($category_tasks as $task) {
                $task_id = esc_attr($task['id']);
                $is_completed = !empty($completed_tasks[$task_id]);
                $checked = $is_completed ? 'checked' : '';
                $display_style = $is_completed ? 'display: none;' : '';
                echo '<li class="sbo-task-item ' . ($is_completed ? 'completed' : 'pending') . '" 
                          style="' . $display_style . 'display: flex; align-items: center; margin-bottom: 15px; padding: 10px; border: 1px solid #eee;">';
                echo '<input type="checkbox" class="sbo-task-checkbox" data-id="' . $task_id . '" ' . $checked . ' style="margin-right: 15px;">';
                echo '<div>';
                echo '<h3 style="margin: 0 0 5px 0;">' . esc_html($task['task']) . '</h3>';
                echo '<p style="margin: 0; color: #666;">' . wp_kses_post($task['instructions']) . '</p>';
                echo '</div>';
                echo '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
        // Keep your existing JavaScript
        ?>
        


        

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Task completion handling
    document.querySelectorAll(".sbo-task-checkbox").forEach(checkbox => {
        checkbox.addEventListener("change", function() {
            const taskId = this.dataset.id;
            const completed = this.checked ? 1 : 0;
            const taskItem = this.closest('.sbo-task-item');

            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "action=sbo_save_task_completion&task_id=" + taskId + "&completed=" + completed
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (completed) {
                        taskItem.classList.remove('pending');
                        taskItem.classList.add('completed');
                        if (!document.getElementById('sbo-show-completed').checked) {
                            taskItem.style.display = 'none';
                        }
                    } else {
                        taskItem.classList.remove('completed');
                        taskItem.classList.add('pending');
                        taskItem.style.display = 'flex';
                    }
                    updateCategoryVisibility();
                }
            });
        });
    });

    // Toggle completed tasks visibility
    document.getElementById('sbo-show-completed').addEventListener('change', function() {
        const completedTasks = document.querySelectorAll('.sbo-task-item.completed');
        completedTasks.forEach(task => {
            task.style.display = this.checked ? 'flex' : 'none';
        });
        updateCategoryVisibility();
    });

    // Category filter handling
    document.getElementById('sbo-category-filter').addEventListener('change', function() {
        const selectedCategory = this.value;
        const categorySections = document.querySelectorAll('.sbo-category-section');
        
        categorySections.forEach(section => {
            if (selectedCategory === 'all' || section.dataset.category === selectedCategory) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        });
    });

    // Helper function to hide empty categories
    function updateCategoryVisibility() {
        const showCompleted = document.getElementById('sbo-show-completed').checked;
        document.querySelectorAll('.sbo-category-section').forEach(section => {
            const visibleTasks = section.querySelectorAll('.sbo-task-item').length;
            const visibleCompletedTasks = section.querySelectorAll('.sbo-task-item.completed').length;
            const visiblePendingTasks = visibleTasks - visibleCompletedTasks;
            
            if (!showCompleted && visiblePendingTasks === 0) {
                section.style.display = 'none';
            } else if (showCompleted && visibleTasks === 0) {
                section.style.display = 'none';
            } else {
                section.style.display = 'block';
            }
        });
    }

    // Initial category visibility check
    updateCategoryVisibility();
});
</script>



        <?php
    } else {
        echo '<p>No tasks found.</p>';
    }
    echo '</div>'; // Close checklist content
    echo '</div>'; // Close wrap
}


add_action('wp_ajax_sbo_save_task_completion', 'sbo_save_task_completion');

function sbo_save_task_completion() {
    $task_id = sanitize_text_field($_POST['task_id']);
    $completed = intval($_POST['completed']);

    $current_state = get_option('sbo_task_completion', []);
    $current_state[$task_id] = $completed;
    update_option('sbo_task_completion', $current_state);

    wp_send_json_success('Task saved successfully.');
}

