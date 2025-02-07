<?php
if (!defined('ABSPATH')) {
    exit;
}

// Add the admin menu page
add_action('admin_menu', 'sbo_add_insights_page');

function sbo_add_insights_page() {
    add_menu_page(
        'Performance',
        'Performance',
        'manage_options',
        'site-performance',
        'sbo_render_insights_page',
        'dashicons-chart-area',
        3
    );
}

function sbo_render_insights_page() {
    echo '<div class="wrap">';
    echo '<h1>' . get_admin_page_title() . '</h1>';
    
    // Create the two-column layout wrapper
    echo '<div class="two-column-wrapper">';
    
    // Left Column (20%)
// Get the current domain name
$domain = parse_url(get_site_url(), PHP_URL_HOST);
// Create the Google search URL
$google_search_url = 'https://www.google.ca/search?q=site%3A' . urlencode($domain);

echo '<div class="left-column">';
echo '<div class="postbox" style="margin-top: 10px; padding: 15px;">';
echo '<h2>Test Your Website</h2>';
echo '<ul class="nav-list">';
echo '<li><a href="' . esc_url($google_search_url) . '" target="_blank">Google Site Search</a> - View number of pages indexed in Google</li>';
echo '</ul>';

echo '<h2>Tools</h2>';
echo '<ul class="nav-list">';
echo '<li><a href="https://gtmetrix.com/" target="_blank">GT Metrix</a> website speed test</li>';
echo '<li><a href="https://web.archive.org/" target="_blank">Internet Archive</a></li>';
echo '</ul>';

echo '</div>';
echo '</div>';
    
    // Right Column (80%) - Looker Studio
    echo '<div class="right-column">';
    echo '<div class="postbox" style="margin-top: 10px; padding: 0px;">';
    
    // Get the Looker Studio embed code from ACF
    $looker_embed = get_field('one_looker_studio', 'option'); // Added 'option' parameter
    
    echo '<div id="ga-chart" style="background: #f8f9fa; padding: 0px; margin-top: 0px;">';
    echo '<div class="responsive-iframe-container">';
    
    if ($looker_embed) {
        echo $looker_embed;
    } else {
        echo '<p style="color: red;">No Looker Studio report configured.</p>';
    }
    
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    
    echo '</div>'; // Close two-column-wrapper
    echo '</div>'; // Close wrap

    // Add JavaScript for tab handling
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.nav-tab');
        const contents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                tabs.forEach(t => t.classList.remove('nav-tab-active'));
                tab.classList.add('nav-tab-active');
            });
        });
    });
    </script>
    <?php
}

// Add styles to admin head for this page only
add_action('admin_head', 'add_admin_styles');
function add_admin_styles() {
    // Only add these styles on our specific admin page
    $screen = get_current_screen();
    if ($screen->base !== 'toplevel_page_site-performance') {
        return;
    }
    ?>
    <style>
        .two-column-wrapper {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }
        
        .left-column {
            flex: 0 0 30%;
            min-width: 200px;
        }
        
        .right-column {
            flex: 0 0 70%;
        }
        
        .nav-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        
        .nav-list li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }
        
        .nav-list li:last-child {
            border-bottom: none;
        }
        
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
        
        .postbox {
            background: white;
            border: 1px solid #ccd0d4;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
    </style>
    <?php
}