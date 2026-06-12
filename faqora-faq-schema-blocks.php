<?php
/**
 * Plugin Name: Faqora FAQ Schema Blocks
 * Plugin URI:  https://proficrm.com.ua/seo/
 * Description: Create reusable FAQ blocks with a shortcode and automatically output FAQPage JSON-LD schema markup.
 * Version:     1.0.2
 * Author:      ProfiCRM-UA
 * Author URI:  https://proficrm.com.ua/
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: faqora-faq-schema-blocks
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FSB_VERSION', '1.0.2' );
define( 'FSB_PLUGIN_FILE', __FILE__ );
define( 'FSB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'FSB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once FSB_PLUGIN_DIR . 'includes/class-fsb-plugin.php';
require_once FSB_PLUGIN_DIR . 'includes/class-fsb-cpt.php';
require_once FSB_PLUGIN_DIR . 'includes/class-fsb-metabox.php';
require_once FSB_PLUGIN_DIR . 'includes/class-fsb-shortcode.php';
require_once FSB_PLUGIN_DIR . 'includes/class-fsb-settings.php';

register_activation_hook( __FILE__, array( 'FSB_CPT', 'activate' ) );

add_action( 'plugins_loaded', array( 'FSB_Plugin', 'init' ) );
