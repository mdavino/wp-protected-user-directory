<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/mdavino/wp-protected-user-directory/
 * @since      1.0.0
 *
 * @package    Wp_Protected_User_Directory
 * @subpackage Wp_Protected_User_Directory/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_Protected_User_Directory
 * @subpackage Wp_Protected_User_Directory/includes
 * @author     Michele D'Avino <mic.davino@gmail.com>
 */
class Wp_Protected_User_Directory {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Protected_User_Directory_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WP_PROTECTED_USER_DIRECTORY_VERSION' ) ) {
			$this->version = WP_PROTECTED_USER_DIRECTORY_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->wp_protected_user_directory = 'wp-protected-user-directory';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Protected_User_Directory_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Protected_User_Directory_i18n. Defines internationalization functionality.
	 * - Wp_Protected_User_Directory_Admin. Defines all hooks for the admin area.
	 * - Wp_Protected_User_Directory_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-protected-user-directory-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-protected-user-directory-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-protected-user-directory-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-protected-user-directory-public.php';

		$this->loader = new Wp_Protected_User_Directory_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Protected_User_Directory_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Protected_User_Directory_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Protected_User_Directory_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		// $this->loader->add_action( 'admin_menu', $plugin_admin, 'menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'check_requirement' );
		$this->loader->add_action( 'show_user_profile', $plugin_admin, 'user_profile_edit_action');
		$this->loader->add_action( 'edit_user_profile', $plugin_admin, 'user_profile_edit_action');
		$this->loader->add_action( 'user_new_form', $plugin_admin, 'user_profile_edit_action');
		$this->loader->add_action( 'personal_options_update', $plugin_admin, 'user_profile_update_action');
		$this->loader->add_action( 'edit_user_profile_update', $plugin_admin, 'user_profile_update_action');
		$this->loader->add_action( 'user_register', $plugin_admin, 'user_profile_update_action');


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Protected_User_Directory_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action('register_library_viewer_shortcode_child_class', $this, 'register_library_viewer_shortcode_classes');
		$this->loader->add_action('register_library_viewer_file_child_class', $this, 'register_library_viewer_file_classes');
		$this->loader->add_action('lv_shortcode_class_names', $this, 'filter_lv_shortcode_class_names', 10);
		$this->loader->add_action('lv_file_viewer_class_names', $this, 'filter_lv_file_viewer_class_names', 10);
	}

	public function register_library_viewer_shortcode_classes(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-protected-user-directory-shortcode-child-class.php';
	}

	public function register_library_viewer_file_classes(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-library-viewer-file-child-class.php';
	}

	public function filter_lv_file_viewer_class_names(){
		return array('Library_Viewer_File_Protected');
	}

	public function filter_lv_shortcode_class_names(){
		return array('Library_Viewer_Shortcode_Protected');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wp_Protected_User_Directory_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
