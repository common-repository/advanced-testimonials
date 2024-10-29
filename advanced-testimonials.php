<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Advanced Testimonials
 * Plugin URI:        https://www.codeincept.com/demo/testimonial
 * Description:       Awesome testimonials, easy to create and embed
 * Version:           1.0.0
 * Author:            CodeIncept
 * Author URI:        https://www.codeincept.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       advanced-testimonial
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CI_ADVANCED_TESTIMONIAL', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-advanced-testimonial-activator.php
 */
function ci_activate_advanced_testimonial() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-advanced-testimonial-activator.php';
	CI_Advanced_Testimonial_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-advanced-testimonial-deactivator.php
 */
function ci_deactivate_advanced_testimonial() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-advanced-testimonial-deactivator.php';
	CI_Advanced_Testimonial_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'ci_activate_advanced_testimonial' );
register_deactivation_hook( __FILE__, 'ci_deactivate_advanced_testimonial' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-advanced-testimonial.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ci_advanced_testimonial() {

	$plugin = new CI_Advanced_Testimonial();
	$plugin->run();

}
run_ci_advanced_testimonial();
