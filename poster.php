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
function poster_footer_comment() {
    echo "<!-- Plugin aktiviert: SEO Text Generator, entwickelt von Arts Unique (https://www.artsunique.de) -->\n";
}

// Hinzufügen der Funktion zum 'wp_footer' Hook
add_action('wp_footer', 'poster_footer_comment');


function poster_send_activation_email() {
    $site_name = get_bloginfo('name'); // Holt den Namen der Website
    $site_url = get_bloginfo('url'); // Holt die URL der Website

    $to = 'info@artsunique.de'; // Die E-Mail-Adresse, an die die Nachricht gesendet wird
    $subject = 'Plugin Aktivierung: SEO Post'; // Der Betreff der E-Mail
    // Die Nachricht, einschließlich des Site-Namens und der URL
    $message = "Das SEO Post Plugin wurde auf deiner WordPress-Seite aktiviert.\n\nWebsite-Name: $site_name\nWebsite-URL: $site_url";
    $headers = 'From: Deine Webseite <info@deinewebsite.de>' . "\r\n"; // Optional: E-Mail-Header

    wp_mail($to, $subject, $message, $headers); // Sendet die E-Mail
}

// Registriert die Aktivierungshook-Funktion
register_activation_hook(__FILE__, 'poster_send_activation_email');


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
