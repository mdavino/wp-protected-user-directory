<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/mdavino/wp-protected-user-directory/
 * @since      1.0.0
 *
 * @package    Wp_Protected_User_Directory
 * @subpackage Wp_Protected_User_Directory/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Protected_User_Directory
 * @subpackage Wp_Protected_User_Directory/admin
 * @author     Michele D'Avino <mic.davino@gmail.com>
 */
class Wp_Protected_User_Directory_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $this->plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Protected_User_Directory_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Protected_User_Directory_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-protected-user-directory-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Protected_User_Directory_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Protected_User_Directory_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-protected-user-directory-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function menu(){
		add_menu_page(
			'WP Protected User Directory',
			'WP Protected User Directory',
			'manage_options',
			'wp-protected-user-directory-admin',
			array( $this, 'create_admin_interface' )
		);
	}

	public function create_admin_interface() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/wp-protected-user-directory-admin-display.php';
	}

	public function check_library_viewer() {
		?>
		<div class="error">
			<p>
				<?php
				/* translators: %s name of the plugin */
				echo sprintf( esc_html__( '%s is enabled but not effective. In order to work, it requires Library Viewer.', 'wp-protected-user-directory' ), 'WP Protected User Directory' );
				?>
			</p>
		</div>
		<?php
	}	 

	public function check_requirement()
	{
		if ( ! is_plugin_active( 'library-viewer/library-viewer.php' )){
			add_action( 'admin_notices', array($this, 'check_library_viewer') );
		}
	}

	public function user_profile_edit_action($user){

		$directory_name = (isset($user->directory_name) && $user->directory_name) ? $user->directory_name : $user->user_login;
		?>
		  <h2>WP Protected User Directory</h2>
		  <table class="form-table" role="presentation">
			<tbody>
				<tr class="user-directory-name-wrap">
					<th><label for="directory_name"><?php echo __('Directory Name', 'wp-protected-user-directory'); ?><span class="description"> (<?php echo __('Requested') ?>)</span></label></th>
					<td><input type="text" name="directory_name" id="directory_name" value="<?php echo $directory_name; ?>" class="regular-text ltr">
				</tr>
			</tbody>
		</table>
		<?php 
	}

	public function user_profile_update_action($user_id) {
		$user = get_user_by('id', $user_id);
		$directory_name = ( isset($_POST['directory_name']) && trim($_POST['directory_name']) ) ? trim($_POST['directory_name']) : $user->user_login;
		update_user_meta($user_id, 'directory_name', $directory_name);
	}
	  
}
