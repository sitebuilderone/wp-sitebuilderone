<?php
if (!defined('ABSPATH')) {
    exit;
}
// Add the admin menu page
add_action('admin_menu', 'sbo_add_insights_page');

function sbo_add_insights_page() {
    add_menu_page(
        'Performance', // Page title
        'Performance', // Menu title
        'manage_options', // Capability required
        'site-performance', // Menu slug
        'sbo_render_insights_page', // Callback function
        'dashicons-chart-area', // Icon (chart icon)
        3 // Position in menu (high priority)
    );
}

function sbo_render_insights_page() {
    // Page wrapper
    echo '<div class="wrap">';
    echo '<h1>' . get_admin_page_title() . '</h1>';
    // Analytics Overview Section
    echo '<div id="analytics" class="tab-content">';
    echo '<div class="postbox" style="margin-top: 10px; padding: 0px;">';
    // Placeholder for Google Analytics integration
    echo '<div id="ga-chart" style="background: #f8f9fa; padding: 20px; margin-top: 10px;">';
    
    //echo '<iframe width="1200" height="800" src="https://lookerstudio.google.com/embed/reporting/ee6113ab-bd07-47f6-97ee-d391300ccc3b/page/a01gD" frameborder="0" style="border:0" allowfullscreen sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox"></iframe>';

    echo '<div class="responsive-iframe-container" style="position: relative; padding-bottom: 66.67%; /* 800/1200 = 66.67% */ height: 0; overflow: hidden; width: 100%; max-width: 100%;">
    <iframe 
        src="https://lookerstudio.google.com/embed/reporting/ee6113ab-bd07-47f6-97ee-d391300ccc3b/page/a01gD" 
        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" 
        frameborder="0" 
        allowfullscreen 
        sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox">
    </iframe>
</div>';

    echo '</div>';
    echo '</div>';
    echo '</div>';

    // Add other metric sections...
    
    echo '</div>'; // Close wrap

    // Add JavaScript for tab handling
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab handling code here
        const tabs = document.querySelectorAll('.nav-tab');
        const contents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                // Handle tab switching
                tabs.forEach(t => t.classList.remove('nav-tab-active'));
                tab.classList.add('nav-tab-active');
                // Show corresponding content
                // Additional tab logic here
            });
        });
    });
    </script>
    <?php
}


add_action('wp_head', 'add_responsive_iframe_styles');
function add_responsive_iframe_styles() {
    ?>
    <style>
        .responsive-iframe-container {
            position: relative;
            padding-bottom: 66.67%;
            height: 0;
            overflow: hidden;
            width: 100%;
            max-width: 100%;
        }
        .responsive-iframe-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
    <?php
}

