<?php

class Library_Viewer_Shortcode_Protected extends Library_Viewer_Shortcode{
    
    public function __construct( $parameters = array() ){
        if (isset($parameters['protected']) && $parameters['protected']){
            $this->parameters['have_file_access'] = array('logged_in');
            if ( empty($parameters) ) {
                $parameters = array();
            }
            $parameters['have_file_access'] = 'logged_in';
            add_action('lv_filter_global_real_path', array($this, 'filter_global_real_path'), 10);    
        }
        parent::__construct($parameters);
        if (isset($parameters['protected']) && $parameters['protected']){
            $this->htaccess();
        }

    }
    private function htaccess(){
        $f = fopen(ABSPATH . $this->globals['path'] . "/.htaccess", "w");
        fwrite($f, "Deny from all");
        fclose($f);        
    }

	protected function Library_Viewer_Shortcode_Protected__filter_allowed_globals(){
		return array('have_file_access', 'my_doc_viewer', 'login_page', 'real_path');
	}

	protected function Library_Viewer_Shortcode_Protected__filter_allowed_parameters(){
		return array('have_file_access', 'my_doc_viewer', 'login_page', 'real_path');
	}

    protected function Library_Viewer_Shortcode_Protected__init_parameters_default_values($parameters){	
        parent::Library_Viewer_Shortcode__init_parameters_default_values($parameters);
        $this->globals['have_file_access'] = array('logged_in');
    }

    protected function Library_Viewer_Shortcode_Protected__rest_globals($rest_globals)
	{
		return parent::Library_Viewer_Shortcode__rest_globals($rest_globals);
	}

    public function filter_global_real_path($real_path){
        
        // $abspath = realpath(ABSPATH);
        // $dirname = realpath($real_path);
        // if (strrpos($dirname, $abspath . '/') === 0){
        //     $real_path = substr($dirname, strlen($abspath) + 1  ) . '/';
        // }
        // else{
        //     $real_path = $this->globals['path'] . '/';
        // }

		if ($real_path == $this->globals['path'] . '/' ){
            $real_path = $this->globals['path'] . '/' . $this->get_private_folder_name() . '/';
            $this->globals['dir'] = substr($real_path, 0, -1);
		}

        $user_root_dir = $this->globals['path'] . '/' . $this->get_private_folder_name() ;
        
        if ( strrpos($real_path, $user_root_dir) !== 0 && strrpos($real_path, $this->globals['path'] . '/public' ) ){
            $real_path = $this->globals['path'] . '/';
        }

        if ( rtrim($real_path, '/') == $user_root_dir && ! file_exists($user_root_dir) ){
			mkdir(ABSPATH . $user_root_dir, 0777, true);
			echo library_viewer_error('path_folder_created', $user_root_dir) . '<script>window.location.reload(true);</script>';
        }

        return $real_path;
	}
    
    private function get_private_folder_name(){
        if (is_user_logged_in()){
            $user = wp_get_current_user();
            $directory_name = get_user_meta($user->ID, 'directory_name', true);
            return $directory_name = $directory_name ? $directory_name : $user->user_login;
        }else{
            return 'public';
        }
        
    }

    protected function init_parameter_protected($parameters)
	{
		$this->globals['protected'] = !empty($parameters['protected']) ? $parameters['protected'] : false;
	}

    protected function init_parameter_path($parameters)
	{
		$this->globals['path'] = !empty($parameters['path']) ? $parameters['path'] : 'library';
	}

    protected function is_dir_accessible($dir)
	{
		if ( 0 === strpos($dir , $this->globals['path'] ) ) {
			return true;
		} else {
			return false;
		}
	}


}