<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 */

class PPORT_Activator{
	public static function activate() {
            global $wpdb;
            require_once(ABSPATH.'wp-admin/includes/upgrade.php' );
            
            $defult_options =array(
            	'number_of_item_per_row_desktop'=>4,
            	'number_of_item_per_row_tablet'=>3,
            	'portfolio_display_page'=>0,
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
            	'portfolio_detail_title_font_size' =>28
            );

            foreach($defult_options as $option=>$value){
            	if(!get_option($option)){
            		update_option($option,$value);
            	}
            }
      }
        
}

