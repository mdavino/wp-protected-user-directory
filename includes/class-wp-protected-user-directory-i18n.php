<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/mdavino/wp-protected-user-directory/
 * @since      1.0.0
 *
 * @package    Wp_Protected_User_Directory
 * @subpackage Wp_Protected_User_Directory/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Protected_User_Directory
 * @subpackage Wp_Protected_User_Directory/includes
 * @author     Michele D'Avino <mic.davino@gmail.com>
 */
class Wp_Protected_User_Directory_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-protected-user-directory',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
