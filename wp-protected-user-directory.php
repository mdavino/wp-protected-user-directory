<?php

/**
 * @link              https://github.com/mdavino/wp-protected-user-directory/
 * @since             1.0.0
 * @package           Wp_Protected_User_Directory
 *
 * @wordpress-plugin
 * Plugin Name:       WP Protected User Directory
 * Plugin URI:        https://github.com/mdavino/wp-protected-user-directory/
 * Description:       This plugin extends the functionality of the Library Viewer plugin by allowing users to have personal and protected file folders.
 * Version:           1.0.0
 * Author:            Michele D'Avino
 * Author URI:        https://github.com/mdavino/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-protected-user-directory
 * Domain Path:       /languages/
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
define( 'WP_PROTECTED_USER_DIRECTORY_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-protected-user-directory-activator.php
 */
function activate_wp_protected_user_directory() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-protected-user-directory-activator.php';
	Wp_Protected_User_Directory_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-protected-user-directory-deactivator.php
 */
function deactivate_wp_protected_user_directory() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-protected-user-directory-deactivator.php';
	Wp_Protected_User_Directory_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_protected_user_directory' );
register_deactivation_hook( __FILE__, 'deactivate_wp_protected_user_directory' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-protected-user-directory.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_protected_user_directory() {

	$plugin = new Wp_Protected_User_Directory();
	$plugin->run();

}
run_wp_protected_user_directory();
