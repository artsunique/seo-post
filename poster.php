<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://artsunique.de
 * @since             1.0.0
 * @package           Poster
 *
 * @wordpress-plugin
 * Plugin Name:       Quick SEO Text Generator
 * Plugin URI:        https://artsunique.de
 * Description:       Generate SEO text quickly and easily with this plugin.
 * Version:           1.0.0
 * Author:            Andreas
 * Author URI:        https://artsunique.de/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       poster
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
define( 'POSTER_VERSION', '1.0.0' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-poster-activator.php
 */
function activate_poster() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-poster-activator.php';
	Poster_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-poster-deactivator.php
 */
function deactivate_poster() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-poster-deactivator.php';
	Poster_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_poster' );
register_deactivation_hook( __FILE__, 'deactivate_poster' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-poster.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_poster() {

	$plugin = new Poster();
	$plugin->run();

}
run_poster();