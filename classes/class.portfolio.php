<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ProfessionalPortfolio{
    /**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 */	
	protected $loader;

	public function __construct(){

		$this->loadDependencies();
		$this->setLocale();
		if(is_admin()){
		    $this->defineAdminHooks();	
		}else{
		    $this->definePublicHooks();
		}
		$this->defineGlobal();
	}

	/**
	 *	Load all dependencies for localization, admin and public classes
	 */
	private function loadDependencies(){
            require_once( PPORT_CLASSES_DIR . 'class.loader.php' );
            require_once( PPORT_CLASSES_DIR . 'class.i18n.php');
            require_once( PPORT_CLASSES_DIR . 'class.admin.php');
            require_once( PPORT_CLASSES_DIR . 'class.public.php');
            $this->loader = new PPORT_Loader();
            $this->loader->add_action( 'after_setup_theme', $this,'professional_portfolio_set_custom_thumbnail_size' );
	}


	public function professional_portfolio_set_custom_thumbnail_size(){
		$image_config=array();

		$image_config['imgSize']['widget'] 			 	= array('width'=>36,  'height'=>36,'crop'=>true);
		$image_config['imgSize']['p_portfolio_slide'] 		 	= array('width'=>800, 'height'=>500,'crop'=>true );
		$image_config['imgSize']['p_portfolio_small'] 		= array('width'=>800, 'height'=>500 ,'crop'=>true);
		foreach ($image_config['imgSize'] as $sizeName => $size)
		{
			if($sizeName == 'base')
			{
				set_post_thumbnail_size($image_config['imgSize'][$sizeName]['width'], $image_config[$sizeName]['height'], true);
			}
			else
			{
				if(!isset($image_config['imgSize'][$sizeName]['crop'])){
				 	$image_config['imgSize'][$sizeName]['crop'] = true;
				}

				add_image_size(
					$sizeName,
					$image_config['imgSize'][$sizeName]['width'],
					$image_config['imgSize'][$sizeName]['height'],
					$image_config['imgSize'][$sizeName]['crop']);
			}
		}
	}

	/**
	 *	Set internationalization for plugin
	 */

	private function setLocale() {
            $plugin_i18n = new PPORT_i18n();
            $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'pluginTextdomain' );
	}
        
    
    public function defineAdminHooks(){
        $plugin_admin = new PPORT_Admin();
        $this->loader->add_action('init',$plugin_admin,'professional_portfolio_save_settings');
        $this->loader->add_action('admin_enqueue_scripts',$plugin_admin,'professional_portfolio_include_scripts');
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_page_to_portfolio_type');
        add_filter("manage_posts_columns",array($plugin_admin, "post_edit_columns"));
		add_action("manage_posts_custom_column",array($plugin_admin,  "post_custom_columns"),10,2);

    }
    public function definePublicHooks(){
        $plugin_public = new PPORT_Public();
        $this->loader->add_action('professional_portfolio_dispay_filter',$plugin_public,'professional_portfolio_dispay_filter');
        $this->loader->add_action('professional_portfolio_dispay_item',$plugin_public,'professional_portfolio_dispay_item');
        $this->loader->add_action('professional_portfolio_after_header',$plugin_public,'professional_portfolio_after_header');
        $this->loader->add_action('wp_enqueue_scripts',$plugin_public,'professional_portfolio_enqueue_scripts',9999);
	$this->loader->add_shortcode('professional_portfolio',$plugin_public,'render_professional_portfolio_shortcode');
    }
    
    public function defineGlobal(){
    	//require_once( PPORT_CLASSES_DIR . 'functions.php');
    	$plugin_admin = new PPORT_Admin();
    	$this->loader->add_action('init',$plugin_admin,'register_professional_portfolio');

    }
        

        /**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class the hooks with the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}
}
