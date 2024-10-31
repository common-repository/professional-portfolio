<?php

/**
 * The admin-specific functionality of the plugin.
 */
class PPORT_Admin {

    
    public function __construct() {
        //add_action('init',array($this,'register_professional_portfolio'));
        //add_action('admin_enqueue_scripts',array($this,'professional_portfolio_include_scripts'));
        add_action('save_post',array($this,'professional_portfolio_attachments_save'));				
    } 

    
    public function add_menu_page_to_portfolio_type(){
    	$add_setting_page = add_menu_page(__('Portfolio Settings', PPORT_NAME), __('Portfolio Settings', PPORT_NAME), 'manage_options','professional-portfolio-settings', array($this, 'addSettingPage'));
    	add_action('admin_print_scripts-' . $add_setting_page, array($this, 'loadScript'));
    }

    public function professional_portfolio_attachments_save($post_id){
    	if ( ! current_user_can( 'edit_posts', $post_id )){ 
		 	return 'not permitted'; 
		 }

	    if (isset( $_POST['professional-portfolio-images-nonce']) && wp_verify_nonce($_POST['professional-portfolio-images-nonce'],'professional-portfolio-images-nonce' )){
	    	$ids = esc_attr($_POST['professional_portfolio_attachments']);
	    	update_post_meta( $post_id, 'professional_portfolio_attachments', $ids);

	    	$portfolio_website_url = esc_url($_POST['portfolio_website_url']);
	    	update_post_meta($post_id,'portfolio_website_url',$portfolio_website_url);
	    }
    }

    public function loadScript(){
    	wp_enqueue_style('bootstrap-font-awe-css', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    	wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css');
        wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js', array('jquery'), NULL, true);
        wp_enqueue_style('wp-color-picker');

        wp_enqueue_script('pport-custom-js', PPORT_ASSETS_URL . 'js/custom.js', array('wp-color-picker'), NULL, true);
    }

    public function professional_portfolio_save_settings(){
    	if (isset($_POST['pp_save_settings']) && (isset($_POST['professional-portfolio-settings-form-save']) && wp_verify_nonce($_POST['professional-portfolio-settings-form-save'], 'professional-portfolio-settings-form-save'))) 
        {
        	update_option('number_of_item_per_row_desktop',$_POST['number_of_item_per_row_desktop']);
	        update_option('number_of_item_per_row_tablet',$_POST['number_of_item_per_row_tablet']);
	        update_option('portfolio_display_page',$_POST['portfolio_display_page']);
	        update_option('number_of_item_per_page',$_POST['number_of_item_per_page']);
	        update_option('portfolio_detail_image_position',$_POST['portfolio_detail_image_position']);
	        update_option('portfolio_pagination',$_POST['portfolio_pagination']);
	        update_option('title_font_size',$_POST['title_font_size']);
	        update_option('title_text_color',$_POST['title_text_color']);
	        update_option('display_border_in_image',$_POST['display_border_in_image']);
	        update_option('portfolio_detail_bg_color',$_POST['portfolio_detail_bg_color']);
	        update_option('portfolio_detail_text_color',$_POST['portfolio_detail_text_color']);
	        update_option('portfolio_detail_title_color',$_POST['portfolio_detail_title_color']);
	        update_option('portfolio_detail_button_bg_color',$_POST['portfolio_detail_button_bg_color']);
	        update_option('portfolio_detail_button_hover_bg_color',$_POST['portfolio_detail_button_hover_bg_color']);
	        update_option('portfolio_detail_slider_button_default_color',$_POST['portfolio_detail_slider_button_default_color']);
	        update_option('portfolio_detail_slider_button_active_color',$_POST['portfolio_detail_slider_button_active_color']);
	        update_option('portfolio_detail_text_font_size',$_POST['portfolio_detail_text_font_size']);
			update_option('portfolio_detail_title_font_size',$_POST['portfolio_detail_title_font_size']);
			
			update_option('portfolio_detail_visit_link_color',$_POST['portfolio_detail_visit_link_color']);
	        update_option('portfolio_detail_visit_link_bg_color',$_POST['portfolio_detail_visit_link_bg_color']);
	        update_option('portfolio_detail_visit_link_border_color',$_POST['portfolio_detail_visit_link_border_color']);
	        update_option('portfolio_detail_visit_link_hover_color',$_POST['portfolio_detail_visit_link_hover_color']);
	        update_option('portfolio_detail_visit_link_hover_bg_color',$_POST['portfolio_detail_visit_link_hover_bg_color']);
			update_option('portfolio_detail_visit_link_hover_border_color',$_POST['portfolio_detail_visit_link_hover_border_color']);
			


	        if (wp_get_referer())
            {
                wp_safe_redirect(wp_get_referer());
                exit;
            } else {
                wp_safe_redirect(admin_url('admin.php?page=professional-portfolio-settings'));
                exit;
            }

        }else if(isset($_GET['custom-action']) && $_GET['custom-action']=='setdefault-options' && (isset($_GET['update_default_options']) && wp_verify_nonce($_GET['update_default_options'], 'update_default_options'))){
        	$defult_options =array(
            	'number_of_item_per_row_desktop'=>4,
            	'number_of_item_per_row_tablet'=>3,
            	//'portfolio_display_page'=>0,
            	'number_of_item_per_page'=>12,
            	'portfolio_detail_image_position' => 'right',
            	'portfolio_pagination' => 'pagenumber',
            	'title_font_size' => 18,
            	'title_text_color' => '#f97921',
            	'display_border_in_image'=>0,
            	'portfolio_detail_bg_color' =>'#333',
            	'portfolio_detail_text_color'=>'#999',
            	'portfolio_detail_title_color' =>'#ccc',
            	'portfolio_detail_button_bg_color'=>'#f97921',
            	'portfolio_detail_button_hover_bg_color' =>'#555',
            	'portfolio_detail_slider_button_default_color' =>'#d2d2d2',
            	'portfolio_detail_slider_button_active_color' =>'#f97921',
            	'portfolio_detail_text_font_size' =>14,
				'portfolio_detail_title_font_size' =>28,
				'portfolio_detail_visit_link_color'=>'#ffffff',
				'portfolio_detail_visit_link_bg_color'=>'#cccccc',
				'portfolio_detail_visit_link_border_color'=>'#ffffff',
				'portfolio_detail_visit_link_hover_color'=>'#ffffff',
				'portfolio_detail_visit_link_hover_bg_color'=>'#cccccc',
				'portfolio_detail_visit_link_hover_border_color'=>'#ffffff',
            );

            foreach($defult_options as $option=>$value){
            	update_option($option,$value);
            }
            if (wp_get_referer())
            {
                wp_safe_redirect(wp_get_referer());
                exit;
            } else {
                wp_safe_redirect(admin_url('admin.php?page=professional-portfolio-settings'));
                exit;
            }
        }
    }

    public function addSettingPage(){
    	$pages = $this->get_pages();
        $number_of_item_per_row_desktop = get_option('number_of_item_per_row_desktop',4);
        $number_of_item_per_row_tablet = get_option('number_of_item_per_row_tablet',3);
        $portfolio_display_page = get_option('portfolio_display_page',0);
        $number_of_item_per_page = get_option('number_of_item_per_page',12);
        $portfolio_detail_image_position = get_option('portfolio_detail_image_position','right');
        $portfolio_pagination = get_option('portfolio_pagination','pagenumber');
        $title_font_size = get_option('title_font_size',18);
        $title_text_color = get_option('title_text_color','#f97921');
        $display_border_in_image = get_option('display_border_in_image',0);
        $portfolio_detail_bg_color = get_option('portfolio_detail_bg_color','#333');
        $portfolio_detail_text_color = get_option('portfolio_detail_text_color','#999');
        $portfolio_detail_title_color = get_option('portfolio_detail_title_color','#ccc');
        $portfolio_detail_button_bg_color = get_option('portfolio_detail_button_bg_color','#f97921');
        $portfolio_detail_button_hover_bg_color = get_option('portfolio_detail_button_hover_bg_color','#555');
        $portfolio_detail_slider_button_default_color = get_option('portfolio_detail_slider_button_default_color','#d2d2d2');
        $portfolio_detail_slider_button_active_color = get_option('portfolio_detail_slider_button_active_color','#f97921');
        $portfolio_detail_text_font_size = get_option('portfolio_detail_text_font_size',14);
		$portfolio_detail_title_font_size = get_option('portfolio_detail_title_font_size',28);
		$portfolio_detail_visit_link_color = get_option('portfolio_detail_visit_link_color','#ffffff');
		$portfolio_detail_visit_link_bg_color = get_option('portfolio_detail_visit_link_bg_color','#cccccc');
		$portfolio_detail_visit_link_border_color = get_option('portfolio_detail_visit_link_border_color','#ffffff');
		$portfolio_detail_visit_link_hover_color = get_option('portfolio_detail_visit_link_hover_color','#ffffff');
		$portfolio_detail_visit_link_hover_bg_color = get_option('portfolio_detail_visit_link_hover_bg_color','#cccccc');
		$portfolio_detail_visit_link_hover_border_color = get_option('portfolio_detail_visit_link_hover_border_color','#ffffff');
    	?>
    	<div class="wrap">
    		<style type="text/css">
    			.form-check-input {
				    position: absolute;
				    margin-top: .3rem !important;
				    margin-left: -1.25rem !important;
				}
				.card{
					max-width:none !important;
				}
				.dicdlg_add_category_form label{
					display:block !important;
				}
    		</style>
    		<div class="page-header">
		      <h1><?php _e('Professional Portfolio Settings',PPORT_NAME);?></h1>
		    </div>
			<div class="row">
		    <div class="col-lg-6">
		    	<form method="post" action="" class="dicdlg_add_category_form">
		        	<?php wp_nonce_field('professional-portfolio-settings-form-save','professional-portfolio-settings-form-save');?>
	        		<div class="card w-100 p-0" >
	            		<div class="card-header"><?php _e('Grid settings',PPORT_NAME);?></div>
	                	<div class="card-body">
		                    <div class="form-group">
		                        <label for="number_of_item_per_row_desktop"><?php _e('Number of items per row (Desktop)',PPORT_NAME);?></label>
		                        <select id="number_of_item_per_row_desktop" name="number_of_item_per_row_desktop" class="form-control" required="required">
		                        	<?php for($i=1;$i<=6;$i++){ if($i==5) continue;?>
		                        		<option value="<?php echo $i;?>" <?php selected($i,$number_of_item_per_row_desktop,true);?>><?php echo $i;?></option>
		                        	<?php }?>
		                        </select>
		                    </div>
		                    <div class="form-group">
		                        <label for="number_of_item_per_row_tablet"><?php _e('Number of items per row (Mobile)',PPORT_NAME);?></label>
		                        <select id="number_of_item_per_row_tablet" name="number_of_item_per_row_tablet" class="form-control"  required="required">
		                        	<?php for($i=1;$i<=6;$i++){  if($i==5) continue;?>
		                        		<option value="<?php echo $i;?>" <?php selected($i,$number_of_item_per_row_tablet,true);?>><?php echo $i;?></option>
		                        	<?php }?>
		                        </select>
		                    </div>
		                </div>
		            	<div class="clearfix"></div>
		            	<div class="card-header"><?php _e('General settings',PPORT_NAME);?></div>
		            	<div class="card-body">
		            		<div class="form-group">
		                        <label for="portfolio_display_page"><?php _e('Select page to display portfolio',PPORT_NAME);?></label>
		                        <select name="portfolio_display_page" id="portfolio_display_page" class="form-control"  required="required">
		                        	<option value="0"><?php _e('----',PPORT_NAME);?></option>
									<?php if(!empty($pages)){?>
		                        		<?php foreach($pages as $page){?>
		                        			<option value="<?php echo $page->ID;?>" <?php selected($page->ID,$portfolio_display_page,true);?>><?php echo $page->post_title;?></option>
		                        		<?php }?>
		                        	<?php }?>
									
		                        </select>
		                    </div>
		            		<div class="form-group">
		                        <label for="number_of_item_per_page"><?php _e('Number of items per page',PPORT_NAME);?></label>
		                        <input class="form-control" type="number" name="number_of_item_per_page" id="number_of_item_per_page" min="1" step="1"  required="required" value="<?php echo $number_of_item_per_page;?>">
		                    </div>
		                    <div class="form-group">
		                        <label for="portfolio_detail_image_position"><?php _e('Portfolio detail image position',PPORT_NAME);?></label>
		                        <select name="portfolio_detail_image_position" id="portfolio_detail_image_position" class="form-control"  required="required">
		                        	<option value="right" <?php selected('right',$portfolio_detail_image_position,true);?>>Right</option>
		                        	<option value="left" <?php selected('left',$portfolio_detail_image_position,true);?>>Left</option>
		                        </select>
		                    </div>
		                    <div class="form-group">
		                        <label for="portfolio_pagination"><?php _e('Portfolio Pagination',PPORT_NAME);?></label>
		                        <select name="portfolio_pagination" id="portfolio_pagination" class="form-control"  required="required">
		                        	<option value="pagenumber" <?php selected('pagenumber',$portfolio_pagination,true);?>>Page Number</option>
		                        	<option value="infinitescroll" <?php selected('infinitescroll',$portfolio_pagination,true);?>>Infinite scroll</option>
		                        	<option value="loadmore" <?php selected('loadmore',$portfolio_pagination,true);?>>Load More</option>
		                        </select>
		                    </div>
		                    <div class="form-group">
	                            <div class="row">
	                                <div class="col-12 col-md-6">
	                                    <label for="title_font_size"><?php _e('Title font size',PPORT_NAME);?></label>
	                                    <input type="number" name="title_font_size" class="form-control" id="title_font_size" placeholder="" value="<?php echo $title_font_size;?>" min="0" step="1"  required="required">
	                                </div>
	                                <div class="col-xs-12 col-md-6">
	                                    <label for="title_text_color" ><?php _e('Title text color',PPORT_NAME);?></label>
	                                    <input type="text" name="title_text_color" class="cpa-color-picker" id="title_text_color" placeholder="" value="<?php echo $title_text_color;?>"  required="required">
	                                </div>
	                            </div>
	                        </div>
		                    <div class="form-group form-check">
		                        <input type="checkbox" class="form-check-input" id="display_border_in_image" name="display_border_in_image" value="1" style="margin: 0;" <?php checked(1,$display_border_in_image,true);?>>
		                        <label for="display_border_in_image" class="form-check-label"><?php _e('Display border in image',PPORT_NAME);?></label>
		                    </div>
		            	</div>
		            	<div class="clearfix"></div>
		            	<div class="card-header"><?php _e('Portfolio Detail settings',PPORT_NAME);?></div>
		            	<div class="card-body">
		            		
	                        <div class="form-group">
	                            <div class="row">
									<div class="col-xs-12 col-md-6">
	                                    <label for="portfolio_detail_title_color" ><?php _e('Title Color ',PPORT_NAME);?></label>
	                                    <input type="text" name="portfolio_detail_title_color" class="cpa-color-picker" id="portfolio_detail_title_color" placeholder="" value="<?php echo $portfolio_detail_title_color;?>" required="required">
	                                </div>
									<div class="col-xs-12 col-md-6">
	                                    <label for="portfolio_detail_title_font_size" ><?php _e('Title font size',PPORT_NAME);?></label>
	                                    <input type="number" min="0" step="1" name="portfolio_detail_title_font_size" class="form-control" id="portfolio_detail_title_font_size" placeholder="" value="<?php echo $portfolio_detail_title_font_size;?>" required="required">
	                                </div>		
	                                
	                            </div>
	                        </div>
							<div class="form-group">
	                            <div class="row">
									<div class="col-xs-12 col-md-6">
	                                    <label for="portfolio_detail_text_color" ><?php _e('Text Color ',PPORT_NAME);?></label>
	                                    <input type="text" name="portfolio_detail_text_color" class="cpa-color-picker" id="portfolio_detail_text_color" placeholder="" value="<?php echo $portfolio_detail_text_color;?>"  required="required">
	                                </div>		
	                                <div class="col-xs-12 col-md-6">
	                                    <label for="portfolio_detail_text_font_size" ><?php _e('Text font size ',PPORT_NAME);?></label>
	                                    <input type="number" min="0" step="1" name="portfolio_detail_text_font_size" class="form-control" id="portfolio_detail_text_font_size" placeholder="" value="<?php echo $portfolio_detail_text_font_size;?>" required="required">
	                                </div>
	                                
	                            </div>
	                        </div>
							<div class="form-group">
	                            <div class="row">
	                                <div class="col-xs-12 col-md-6">
	                                    <label for="portfolio_detail_button_bg_color" ><?php _e('Navigation Button Background Color ',PPORT_NAME);?></label>
	                                    <input type="text" name="portfolio_detail_button_bg_color" class="cpa-color-picker" id="portfolio_detail_button_bg_color" placeholder="" value="<?php echo $portfolio_detail_button_bg_color;?>" required="required">
	                                </div>
	                                <div class="col-xs-12 col-md-6">
	                                    <label for="portfolio_detail_button_hover_bg_color" ><?php _e('Navigation Button Hover Background Color ',PPORT_NAME);?></label>
	                                    <input type="text" name="portfolio_detail_button_hover_bg_color" class="cpa-color-picker" id="portfolio_detail_button_hover_bg_color" placeholder="" value="<?php echo $portfolio_detail_button_hover_bg_color;?>" required="required">
	                                </div>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <div class="row">
	                                <div class="col-xs-12 col-md-6">
	                                    <label for="portfolio_detail_slider_button_default_color" ><?php _e('Slider button defult color',PPORT_NAME);?></label>
	                                    <input type="text" name="portfolio_detail_slider_button_default_color" class="cpa-color-picker" id="portfolio_detail_slider_button_default_color" placeholder="" value="<?php echo $portfolio_detail_slider_button_default_color;?>" required="required">
	                                </div>
	                                <div class="col-xs-12 col-md-6">
	                                    <label for="portfolio_detail_slider_button_active_color" ><?php _e('Slider button active color',PPORT_NAME);?></label>
	                                    <input type="text" name="portfolio_detail_slider_button_active_color" class="cpa-color-picker" id="portfolio_detail_slider_button_active_color" placeholder="" value="<?php echo $portfolio_detail_slider_button_active_color;?>" required="required">
	                                </div>
	                            </div>
	                        </div>
							<div class="form-group">
	                            <div class="row">
	                                <div class="col-xs-12 col-md-6">
	                                    <label for="portfolio_detail_visit_link_color" ><?php _e('Button text color',PPORT_NAME);?></label>
	                                    <input type="text" name="portfolio_detail_visit_link_color" class="cpa-color-picker" id="portfolio_detail_visit_link_color" placeholder="" value="<?php echo $portfolio_detail_visit_link_color;?>" required="required">
	                                </div>
	                                <div class="col-xs-12 col-md-6">
	                                    <label for="portfolio_detail_visit_link_bg_color" ><?php _e('Button background color',PPORT_NAME);?></label>
	                                    <input type="text" name="portfolio_detail_visit_link_bg_color" class="cpa-color-picker" id="portfolio_detail_visit_link_bg_color" placeholder="" value="<?php echo $portfolio_detail_visit_link_bg_color;?>" required="required">
	                                </div>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <div class="row">
	                                <div class="col-xs-12 col-md-6">
	                                    <label for="portfolio_detail_visit_link_border_color" ><?php _e('Button border color',PPORT_NAME);?></label>
	                                    <input type="text" name="portfolio_detail_visit_link_border_color" class="cpa-color-picker" id="portfolio_detail_visit_link_border_color" placeholder="" value="<?php echo $portfolio_detail_visit_link_border_color;?>" required="required">
	                                </div>
	                                <div class="col-xs-12 col-md-6">
	                                    <label for="portfolio_detail_visit_link_hover_color" ><?php _e('Button hover text color',PPORT_NAME);?></label>
	                                    <input type="text" name="portfolio_detail_visit_link_hover_color" class="cpa-color-picker" id="portfolio_detail_visit_link_hover_color" placeholder="" value="<?php echo $portfolio_detail_visit_link_hover_color;?>" required="required">
	                                </div>
	                            </div>
	                        </div>
							<div class="form-group">
	                            <div class="row">
	                                <div class="col-xs-12 col-md-6">
	                                    <label for="portfolio_detail_visit_link_hover_bg_color" ><?php _e('Button hover background color',PPORT_NAME);?></label>
	                                    <input type="text" name="portfolio_detail_visit_link_hover_bg_color" class="cpa-color-picker" id="portfolio_detail_visit_link_hover_bg_color" placeholder="" value="<?php echo $portfolio_detail_visit_link_hover_bg_color;?>" required="required">
	                                </div>
	                                <div class="col-xs-12 col-md-6">
	                                    <label for="portfolio_detail_visit_link_hover_border_color" ><?php _e('Button hover border color',PPORT_NAME);?></label>
	                                    <input type="text" name="portfolio_detail_visit_link_hover_border_color" class="cpa-color-picker" id="portfolio_detail_visit_link_hover_border_color" placeholder="" value="<?php echo $portfolio_detail_visit_link_hover_border_color;?>" required="required">
	                                </div>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <div class="row">
									<div class="col-xs-12 col-md-6">
	                                    <label for="portfolio_detail_bg_color" ><?php _e('Background Color ',PPORT_NAME);?></label>
	                                    <input type="text" name="portfolio_detail_bg_color" class="cpa-color-picker" id="portfolio_detail_bg_color" placeholder="" value="<?php echo $portfolio_detail_bg_color;?>"  required="required">
	                                </div>
	                                
	                                <?php /*?><div class="col-xs-12 col-md-6">
	                                    <label for="portfolio_detail_closeicon_color" ><?php _e('Close Icon Color ',PPORT_NAME);?></label>
	                                    <input type="text" name="portfolio_detail_closeicon_color" class="cpa-color-picker" id="portfolio_detail_closeicon_color" placeholder="" value="" required="required">
	                                </div> <?php */?>
	                            </div>
	                        </div>
		            	</div>
		            	
					</div>	
					<div class="clearfix"></div>
	            	<div class="button-group mt-2">
	            		<button type="submit" class="btn btn-primary btn-lg" name="pp_save_settings"><?php _e('Save',PPORT_NAME);?></button>
	            		<a href="<?php echo wp_nonce_url(admin_url('admin.php?page=professional-portfolio-settings&custom-action=setdefault-options'),'update_default_options','update_default_options'); ?>" class="btn btn-warning btn-lg ml-2">Restore Default Settings</a>
	            	</div>		           
		        </form>
		    </div>
			<div class="col-lg-6">
				<h2>Shortcode</h2>
				<p style="font-size:20px;">You can incude portfolio in any page using following shortcode.</p>
				<p style="font-size:20px;">[professional_portfolio]</p>											
			</div>
			</div>
    	</div>
    	<?php
    }

    public function register_professional_portfolio(){
    	$labels = array(
			'name' => _x('Professional Portfolio', 'post type general name',PPORT_NAME),
			'singular_name' => _x('Portfolio Entry', 'post type singular name',PPORT_NAME),
			'add_new' => _x('Add New', 'portfolio',PPORT_NAME),
			'add_new_item' => __('Add New Portfolio',PPORT_NAME),
			'edit_item' => __('Edit Portfolio',PPORT_NAME),
			'new_item' => __('New Portfolio',PPORT_NAME),
			'view_item' => __('View Portfolio',PPORT_NAME),
			'search_items' => __('Search Portfolio',PPORT_NAME),
			'not_found' =>  __('No Portfolio found',PPORT_NAME),
			'not_found_in_trash' => __('No Portfolio found in Trash',PPORT_NAME),
			'parent_item_colon' => ''
		);
 
	    $args = array(
			'labels' => $labels,
			'public' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => array('slug'=>_x('professional-portfolio-item','URL slug',PPORT_NAME), 'with_front'=>true),
			'query_var' => true,
			'show_in_nav_menus'=> true,
			'show_in_rest' => false,				//	set to false to disallow block editor
			//'taxonomies' => array('post_tag'),
			'supports' => array('title','thumbnail','excerpt','editor','comments', 'revisions' ),
			'menu_icon' => 'dashicons-images-alt2',
			'register_meta_box_cb' => array($this,'professional_portfolio_image_section')
		);
	
	
		register_post_type( 'prof-portfolio' , $args );


		$tax_args = array(	
			"hierarchical"		=> true,
			"label"				=> "Portfolio Categories",
			"singular_label"	=> "Portfolio Category",
			"rewrite"			=> array('slug'=>_x('portfolio_entries','URL slug',PPORT_NAME), 'with_front'=>true),
			"query_var"			=> true,
			'show_in_rest'		=> false			//	set to false to disallow block editor
		);
	 
	 	register_taxonomy("portfolio_entries", array("prof-portfolio"), $tax_args);
    }



    public function professional_portfolio_image_section(){
		add_meta_box('professional-portfolio-images',__( 'Portfolio Images', PPORT_NAME ),array($this,'set_professional_portfolio_images_box'));
	}
	public function set_professional_portfolio_images_box($post){
		wp_nonce_field( 'professional-portfolio-images-nonce', 'professional-portfolio-images-nonce' );
		$portfolio_website_url = esc_url(get_post_meta($post->ID,'portfolio_website_url',true));
		$ids = get_post_meta($post->ID,'professional_portfolio_attachments',true);
		if(!empty($ids)){
			$images=[];
			$attachment_ids = explode(',',$ids);
			foreach ($attachment_ids as $id) {
				$images[]=array(
					'id'=>$id,
					'url'=>wp_get_attachment_image_src($id,'thumbnail')[0]
				);
			}
		}
		?>

		<div class="professional-portfolio-view professional-portfolio-items-view-manage ">
			<input type="hidden" name="professional_portfolio_attachments" id="professional_portfolio_attachments" value="<?php echo $ids;?>">
			<div>
	        	<ul class="professional-portfolio-attachments-list ui-sortable">
	        		<?php if(!empty($images) && count($images)>0){?>
	                    <?php foreach($images as $image){?>
	                        <li class="attachment details" data-attachment-id="<?php echo $image['id'];?>">
	                            <div class="attachment-preview type-image">
	                                <div class="thumbnail">
	                                    <div class="centered">
	                                        <img width="150" height="150" src="<?php echo $image['url'];?>">
	                                    </div>
	                                </div>
	                                <a class="remove" href="#" title="Remove from gallery"><span class="dashicons dashicons-dismiss"></span></a>
	                            </div>
	                        </li>
	                    <?php }?>
	                <?php }?>
	            	<li class="add-attachment">
	                	<a href="javascript:void(0);" data-uploader-title="Add Media To Gallery" data-uploader-button-text="Add Media" class="upload_image_button" title="Add Media To Gallery">
	                    	<div class="dashicons dashicons-format-gallery"></div>
	                        <span>Add Media</span>
	                    </a>
	                </li>
	            </ul>
	            <div style="clear: both;"></div>
			</div>
	    </div>
	    <div class="form-group" style="margin-top: 10px">
			<label style="display: block; font-weight: bold;">Website URL:</label>
			<input type="url" name="portfolio_website_url" class="form-control" value="<?php echo $portfolio_website_url;?>" style="width: 100%;">
		</div>
	    <textarea style="display: none" id="professional-portfolio-attachment-template">&lt;li class="attachment details" &gt;&lt;div class="attachment-preview type-image"&gt;&lt;div class="thumbnail"&gt;&lt;div class="centered"&gt;&lt;img width="150" height="150" /&gt;&lt;/div&gt;&lt;/div&gt;&lt;a class="remove" href="#" title="Remove from gallery"&gt;&lt;span class="dashicons dashicons-dismiss"&gt;&lt;/span&gt;&lt;/a&gt;&lt;/div&gt;&lt;/li&gt;</textarea>
	    <?php

	}

	public function professional_portfolio_include_scripts($hooks){
		global $post;
		if(!empty($post) && $post->post_type=='prof-portfolio'){
			wp_enqueue_style('professional-porfoilio-admin-style',PPORT_ASSETS_URL.'css/portfolio-meta.css');	

			if (get_bloginfo('version') >= 3.5) {
	            wp_enqueue_media();
	        } else {
	            wp_enqueue_style('thickbox');
	            wp_enqueue_script('thickbox');
	        }
	        //wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js', array('jquery'), NULL, true);
	        wp_enqueue_script('professional-portfolio-meta-js', PPORT_ASSETS_URL.'js/portfolio-meta.js', array('jquery'), NULL, true);
	        wp_localize_script('professional-portfolio-meta-js', 'bg', array('wpv' => get_bloginfo('version')));
		}
	}

	public function professional_listtable_image_css() 
	{
	    ?>
	        <style>
	            .widefat thead tr th#portfolio-image {
	                width: 45px;
	            }
	        </style>
	    <?php
	}

	public function post_edit_columns($columns)
	{
		$newcolumns = array(
			"cb" => "<input type=\"checkbox\" />",
			"portfolio-image" => "Image",
			"title" => "Title",
			"portfolio_entries" => "Categories"
		);

		$columns= array_merge($newcolumns, $columns);

		add_action('admin_footer', array($this,'professional_listtable_image_css'));
		return $columns;
	}

	

	public function post_custom_columns($column,$post_id)
	{
		
		switch ($column)
		{
			case "portfolio-image":
			if (has_post_thumbnail($post_id)){
					echo get_the_post_thumbnail($post_id, 'widget');
				}
			break;

			case "description":
			#the_excerpt();
			break;
			case "price":
			#$custom = get_post_custom();
			#echo $custom["price"][0];
			break;
			case "portfolio_entries":
			echo get_the_term_list($post_id, 'portfolio_entries', '', ', ','');
			break;
		}
	} 

	private function get_pages() {
        $args = array(
            'sort_order' => 'asc',
            'sort_column' => 'post_title',
            'hierarchical' => 1,
            'offset' => 0,
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $pages = get_pages($args);
        return $pages;
    }  
}
