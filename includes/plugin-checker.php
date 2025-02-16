<?php
/**
 * Plugin Dependency Checker
 * 
 * Checks for required and recommended plugins for the Local Business SEO plugin
 */

class LocalBusiness_Plugin_Checker {
    /**
     * Stores plugin requirements
     */
    private $plugin_requirements;

    /**
     * Constructor
     */
    public function __construct() {
        // Initialize plugin requirements from JSON
        $this->init_requirements();
        
        // Add admin notice if requirements not met
        add_action('admin_notices', array($this, 'display_plugin_notices'));
        
        // Add plugin checker to admin menu
        add_action('admin_menu', array($this, 'add_plugin_checker_menu'));
        
        // Add information to plugins page
        add_action('admin_init', array($this, 'add_plugins_page_hooks'));
    }

    /**
     * Add hooks for plugins page
     */
    public function add_plugins_page_hooks() {
        foreach ($this->plugin_requirements['required'] as $plugin_slug => $plugin_data) {
            $basename = $this->get_plugin_basename($plugin_slug);
            if ($basename) {
                add_action("after_plugin_row_{$basename}", array($this, 'display_dependency_notice'), 10, 3);
            }
        }
    }

    /**
     * Get plugin basename from slug
     */
    private function get_plugin_basename($plugin_slug) {
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        foreach (array_keys(get_plugins()) as $basename) {
            if (strpos($basename, $plugin_slug) !== false) {
                return $basename;
            }
        }
        return false;
    }

    /**
     * Display dependency notice in plugins list
     */
    public function display_dependency_notice($plugin_file, $plugin_data, $status) {
        // Generate a unique key for this plugin
        static $displayed = array();
        
        if (!isset($displayed[$plugin_file])) {
            $displayed[$plugin_file] = true;
            
            $required_by = 'Local Business SEO Plugin';
            
            echo '<tr class="plugin-update-tr active">
                <td colspan="4" class="plugin-update colspanchange">
                    <div class="notice inline notice-alt">
                        <p>
                            <span class="dashicons dashicons-star-filled"></span>
                            Required by ' . esc_html($required_by) . ' for optimal functionality.
                        </p>
                    </div>
                </td>
            </tr>';
        }
    }

    /**
     * Initialize plugin requirements from JSON
     */
    private function init_requirements() {
        // Default requirements if JSON not found
        $default_requirements = [
            'required' => [
                'advanced-custom-fields-pro' => [
                    'name' => 'Advanced Custom Fields PRO',
                    'slug' => 'advanced-custom-fields-pro',
                    'required' => true,
                    'version' => '5.0.0'
                ]
            ],
            'recommended' => [
                'wordpress-seo' => [
                    'name' => 'Yoast SEO',
                    'slug' => 'wordpress-seo',
                    'required' => false,
                    'version' => '20.0'
                ],
                'wp-schema-pro' => [
                    'name' => 'Schema Pro',
                    'slug' => 'wp-schema-pro',
                    'required' => false,
                    'version' => '2.0.0'
                ]
            ]
        ];

        // Get the plugin's base directory
        $plugin_dir = plugin_dir_path(dirname(__FILE__));
        
        // Path to JSON file (in plugin root/config directory)
        $json_path = $plugin_dir . 'config/plugin-requirements.json';
        
        // Add debug info
        if (WP_DEBUG) {
            error_log('Plugin Directory: ' . $plugin_dir);
            error_log('JSON Path: ' . $json_path);
            error_log('File exists: ' . (file_exists($json_path) ? 'Yes' : 'No'));
        }
        
        if (file_exists($json_path)) {
            $json_content = file_get_contents($json_path);
            
            if (WP_DEBUG) {
                error_log('JSON Content: ' . $json_content);
            }
            
            $json_requirements = json_decode($json_content, true);
            
            // Check for JSON decode errors
            if (json_last_error() !== JSON_ERROR_NONE) {
                if (WP_DEBUG) {
                    error_log('JSON decode error: ' . json_last_error_msg());
                }
                $this->plugin_requirements = $default_requirements;
            } else {
                $this->plugin_requirements = $json_requirements;
            }
        } else {
            if (WP_DEBUG) {
                error_log('Using default requirements - JSON file not found');
            }
            $this->plugin_requirements = $default_requirements;
        }
        
        // Log final requirements
        if (WP_DEBUG) {
            error_log('Final requirements: ' . print_r($this->plugin_requirements, true));
        }
    }

    /**
     * Check if a plugin is installed and active
     */
    private function is_plugin_active($plugin_slug) {
        $active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
        
        foreach ($active_plugins as $plugin) {
            if (strpos($plugin, $plugin_slug) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get plugin version if installed
     */
    private function get_plugin_version($plugin_slug) {
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        $all_plugins = get_plugins();
        
        foreach ($all_plugins as $plugin_path => $plugin_data) {
            if (strpos($plugin_path, $plugin_slug) !== false) {
                return $plugin_data['Version'];
            }
        }
        
        return null;
    }

    /**
     * Display admin notices for missing required plugins
     */
    public function display_plugin_notices() {
        static $displayed = false;
        
        if ($displayed) {
            return;
        }
        
        $missing_required = [];
        
        foreach ($this->plugin_requirements['required'] as $plugin_slug => $plugin_data) {
            if (!$this->is_plugin_active($plugin_slug)) {
                $missing_required[] = $plugin_data['name'];
            }
        }
        
        if (!empty($missing_required)) {
            $displayed = true;
            $message = '<div class="notice notice-error">';
            $message .= '<p><strong>Local Business SEO Plugin:</strong> The following required plugins are missing or inactive:</p>';
            $message .= '<ul><li>' . implode('</li><li>', $missing_required) . '</li></ul>';
            $message .= '<p>Please install and activate these plugins for full functionality.</p>';
            $message .= '</div>';
            
            echo $message;
        }
    }

    /**
     * Add plugin checker page to admin menu
     */
    public function add_plugin_checker_menu() {
        // Add menu under Settings instead of Business Information for testing
        add_options_page(
            'Plugin Requirements',
            'Plugin Requirements',
            'manage_options',
            'local-business-plugins',
            array($this, 'render_plugin_checker_page')
        );
        
        // Remove debug notice
        // add_action('admin_notices', function() {
        //     echo '<div class="notice notice-info"><p>Plugin checker menu function was called.</p></div>';
        // });
    }

    /**
     * Render plugin checker admin page
     */
    public function render_plugin_checker_page() {
        ?>
        <div class="wrap">
            <h1>Plugin Requirements</h1>
            
            <h2>Required Plugins</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Plugin</th>
                        <th>Status</th>
                        <th>Minimum Version</th>
                        <th>Installed Version</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->plugin_requirements['required'] as $plugin_slug => $plugin_data): ?>
                        <tr>
                            <td><?php echo esc_html($plugin_data['name']); ?></td>
                            <td>
                                <?php if ($this->is_plugin_active($plugin_slug)): ?>
                                    <span style="color: green;">✓ Active</span>
                                <?php else: ?>
                                    <span style="color: red;">✗ Inactive/Missing</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html($plugin_data['version']); ?></td>
                            <td><?php echo esc_html($this->get_plugin_version($plugin_slug) ?: 'Not Installed'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h2 style="margin-top: 2em;">Recommended Plugins</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Plugin</th>
                        <th>Status</th>
                        <th>Recommended Version</th>
                        <th>Installed Version</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->plugin_requirements['recommended'] as $plugin_slug => $plugin_data): ?>
                        <tr>
                            <td><?php echo esc_html($plugin_data['name']); ?></td>
                            <td>
                                <?php if ($this->is_plugin_active($plugin_slug)): ?>
                                    <span style="color: green;">✓ Active</span>
                                <?php else: ?>
                                    <span style="color: orange;">○ Not Active</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html($plugin_data['version']); ?></td>
                            <td><?php echo esc_html($this->get_plugin_version($plugin_slug) ?: 'Not Installed'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}

// Initialize the plugin checker
new LocalBusiness_Plugin_Checker();