<?php
/**
 * Plugin Name: SiteBuilderOne Local Business
 * Plugin URI:        https://github.com/sitebuilderone/wp-sitebuilderone
 * GitHub Plugin URI: https://github.com/sitebuilderone/wp-sitebuilderone
 * GitHub Branch: main
 * Description:       ACF, LiveCanvas compatible plugin for Local Business websites
 * Version:           0.0.7
 * Author:            sitebuilderone.com
 * Author URI:        https://github.com/sitebuilderone
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       admin-site-enhancements
 * 
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * This is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Easy Table of Contents; if not, see <http://www.gnu.org/licenses/>.
 * 
 */

 
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

include_once plugin_dir_path(__FILE__) . 'includes/shortcodes-social.php';
include_once plugin_dir_path(__FILE__) . 'includes/acf-conditional-shortcodes.php';