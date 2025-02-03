<?php
/**
 * ACF Import/Export Handler for SiteBuilderOne Local Business
 *
 * @package SiteBuilderOne
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

class SBO_ACF_Import_Export_Handler {
    private $allowed_import_types = ['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
    
    public function __construct() {
        add_action('admin_menu', [$this, 'add_submenu_page']);
        add_action('admin_init', [$this, 'handle_form_submission']);
    }

    /**
     * Add submenu page under Settings
     */
    public function add_submenu_page() {
        add_options_page(
            'Import/Export Business Data',
            'Import/Export Data',
            'manage_options',
            'sbo-acf-import-export',
            [$this, 'render_admin_page']
        );
    }

    /**
     * Handle form submissions
     */
    public function handle_form_submission() {
        if (!isset($_POST['sbo_acf_action'])) {
            return;
        }

        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized access');
        }

        // Verify nonce
        check_admin_referer('sbo_acf_import_export', 'sbo_acf_nonce');

        if ($_POST['sbo_acf_action'] === 'export') {
            $this->handle_export();
        } elseif ($_POST['sbo_acf_action'] === 'import' && !empty($_FILES['import_file'])) {
            $this->handle_import($_FILES['import_file']);
        }
    }

    /**
     * Handle data export
     */
    private function handle_export() {
        $format = $_POST['export_format'] ?? 'csv';
        $export_data = $this->get_acf_data();

        if ($format === 'csv') {
            $this->export_as_csv($export_data);
        } else {
            $this->export_as_json($export_data);
        }
    }

    /**
     * Get all ACF field data
     */
    private function get_acf_data() {
        $field_groups = acf_get_field_groups();
        $export_data = [];

        foreach ($field_groups as $group) {
            $fields = acf_get_fields($group['key']);
            $group_data = [];

            foreach ($fields as $field) {
                $value = get_field($field['name'], 'option');
                if ($value !== null && $value !== '') {
                    $group_data[$field['name']] = $value;
                }
            }

            if (!empty($group_data)) {
                $export_data[$group['title']] = $group_data;
            }
        }

        return $export_data;
    }

    /**
     * Export data as CSV
     */
    private function export_as_csv($data) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="sbo_acf_export_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Add UTF-8 BOM for Excel compatibility
        fputs($output, "\xEF\xBB\xBF");
        
        // Header row
        fputcsv($output, ['Section', 'Field', 'Value']);
        
        // Data rows
        foreach ($data as $section => $fields) {
            foreach ($fields as $field => $value) {
                fputcsv($output, [$section, $field, $value]);
            }
        }
        
        fclose($output);
        exit;
    }

    /**
     * Export data as JSON
     */
    private function export_as_json($data) {
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="sbo_acf_export_' . date('Y-m-d') . '.json"');
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Handle file import
     */
    private function handle_import($file) {
        try {
            if (!in_array($file['type'], $this->allowed_import_types)) {
                throw new Exception('Invalid file type. Please upload a CSV or Excel file.');
            }

            $data = [];
            if ($file['type'] === 'text/csv') {
                $data = $this->process_csv($file['tmp_name']);
            } else {
                $data = $this->process_excel($file['tmp_name']);
            }

            $this->update_acf_fields($data);
            add_settings_error('sbo_acf_import', 'import_success', 'Data imported successfully', 'success');
        } catch (Exception $e) {
            add_settings_error('sbo_acf_import', 'import_error', 'Import failed: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * Process CSV file
     */
    private function process_csv($file) {
        $data = [];
        if (($handle = fopen($file, "r")) !== FALSE) {
            // Skip header row
            $header = fgetcsv($handle);
            
            while (($row = fgetcsv($handle)) !== FALSE) {
                if (count($row) >= 3) {
                    $data[$row[0]][$row[1]] = $row[2];
                }
            }
            fclose($handle);
        }
        return $data;
    }

    /**
     * Process Excel file
     */
    private function process_excel($file) {
        if (!class_exists('PhpOffice\PhpSpreadsheet\IOFactory')) {
            require_once ABSPATH . 'wp-admin/includes/class-phpspreadsheet.php';
        }

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $data = [];

        foreach ($worksheet->getRowIterator(2) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }
            if (!empty($rowData[0]) && isset($rowData[2])) {
                $data[$rowData[0]][$rowData[1]] = $rowData[2];
            }
        }

        return $data;
    }

    /**
     * Update ACF fields with imported data
     */
    private function update_acf_fields($data) {
        foreach ($data as $section => $fields) {
            foreach ($fields as $field_name => $value) {
                update_field($field_name, $value, 'option');
            }
        }
    }

    /**
     * Render admin page
     */
    public function render_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <?php settings_errors('sbo_acf_import'); ?>

            <!-- Export Section -->
            <div class="card">
                <h2>Export Data</h2>
                <p>Export your ACF field data in CSV or JSON format.</p>
                
                <form method="post" action="">
                    <?php wp_nonce_field('sbo_acf_import_export', 'sbo_acf_nonce'); ?>
                    <input type="hidden" name="sbo_acf_action" value="export">
                    
                    <p>
                        <label>
                            <input type="radio" name="export_format" value="csv" checked>
                            Export as CSV
                        </label>
                        <br>
                        <label>
                            <input type="radio" name="export_format" value="json">
                            Export as JSON
                        </label>
                    </p>
                    
                    <?php submit_button('Export Data'); ?>
                </form>
            </div>

            <!-- Import Section -->
            <div class="card">
                <h2>Import Data</h2>
                <p>Import your ACF field data from a CSV or Excel file.</p>
                
                <form method="post" action="" enctype="multipart/form-data">
                    <?php wp_nonce_field('sbo_acf_import_export', 'sbo_acf_nonce'); ?>
                    <input type="hidden" name="sbo_acf_action" value="import">
                    
                    <p>
                        <input type="file" name="import_file" accept=".csv,.xlsx,.xls" required>
                        <br>
                        <span class="description">Supported formats: CSV, Excel (.xlsx, .xls)</span>
                    </p>
                    
                    <?php submit_button('Import Data'); ?>
                </form>
            </div>

            <!-- Sample Data Template -->
            <div class="card">
                <h2>Sample Template</h2>
                <p>Download a sample template to see the correct format for importing data.</p>
                
                <form method="post" action="">
                    <?php wp_nonce_field('sbo_acf_import_export', 'sbo_acf_nonce'); ?>
                    <input type="hidden" name="sbo_acf_action" value="export">
                    <input type="hidden" name="export_format" value="csv">
                    <?php submit_button('Download Sample Template', 'secondary'); ?>
                </form>
            </div>
        </div>
        <style>
            .card {
                background: #fff;
                border: 1px solid #ccd0d4;
                padding: 20px;
                margin-top: 20px;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
            }
            .card h2 {
                margin-top: 0;
            }
        </style>
        <?php
    }
}

// Initialize the handler
new SBO_ACF_Import_Export_Handler();