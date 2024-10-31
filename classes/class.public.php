<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PPORT_Public {
    
    public function __construct() {
        add_filter('template_include',array($this,'loadProfessionalPortfolioTemplate'));       
    } 

    public function render_professional_portfolio_shortcode($atts){
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
        $style='<style type="text/css">
            .ittantalist{padding: 0;margin: 0;}
            .ittanta-portfolio .ittantalist .ittantalist-item h4 a{font-size: '.$title_font_size.'px !important;color: '.$title_text_color.' !important;}';
            if(!empty($display_border_in_image)){
                $style.='.ittanta-portfolio .ittantalist .ittantalist-item .ittanta-portfolio-inner{border: 1px solid #ccc !important;}';
            }
            $style.='.polio-theme-black .polio-container{background: '.$portfolio_detail_bg_color.' !important;}
            .polio-theme-black, .polio-theme-black a{color: '.$portfolio_detail_text_color.' !important;font-size: '.$portfolio_detail_text_font_size.'px !important;}
            .closeimg{fill:'.$portfolio_detail_text_color.' !important;}
            .poliomain a.visit-link{
                z-index: 99999999;
                position: relative;
                color:'.$portfolio_detail_visit_link_color.' !important;
                background:'.$portfolio_detail_visit_link_bg_color.' !important;
                border-color:'.$portfolio_detail_visit_link_border_color.' !important;
            }
            .poliomain a.visit-link:hover{
                color:'.$portfolio_detail_visit_link_hover_color.' !important;
                background:'.$portfolio_detail_visit_link_hover_bg_color.' !important;
                border-color:'.$portfolio_detail_visit_link_hover_border_color.' !important;
            }
            .polio-theme-black h3{
                    color: '.$portfolio_detail_title_color.' !important;
                    font-size: '.$portfolio_detail_title_font_size.'px !important;
            }
            .polio-theme-black .polio-navigation a{
                background: '.$portfolio_detail_button_bg_color.' !important;
            }
            .polio-theme-black .polio-navigation a:hover{
                    background: '.$portfolio_detail_button_hover_bg_color.' !important;
            }
            .flex-control-nav .flex-active{
                    background: '.$portfolio_detail_slider_button_active_color.' !important;
            }
            .flex-control-nav a{
                    background: '.$portfolio_detail_slider_button_default_color.' !important;
            }
        </style>';

        $args = array(
            'taxonomy' => 'portfolio_entries',
            'hide_empty' => false,
            'parent'=>0
        );
        $term_argus = apply_filters('professional_portfolio_parent_term_arguments',$args);

        $terms = get_terms($term_argus);
        $filter_html='<div class="filters">
            <a href="javascript:void(0);" id="showall" class="filter-active filtertags" data-target="all">All</a>';
            if(!empty($terms) && count($terms)>0){
                foreach ($terms as $term) {
                    $filter_html.='<a href="javascript:void(0);" class="showSingle filtertags" data-target="#div-'.$term->term_id.'">'.$term->name.'</a>';       
                }
            }
        $filter_html.='</div>';
        if(!empty($terms) && count($terms)>0){
            foreach($terms as $term){
                $term_child_argus=array(
                    'taxonomy' => 'portfolio_entries',
                    'hide_empty' => false,
                    'parent'=>$term->term_id
                );
                $child_argus = apply_filters('professional_portfolio_child_term_arguments',$term_child_argus);
                $childterms = get_terms($child_argus);
                if(!empty($childterms) && count($childterms)>0){
                    $filter_html.='<div id="div-'.$term->term_id.'" class="filters targetDiv">';
                        foreach ($childterms as $childterm){
                            $filter_html.='<a href="javascript:void(0);" class="subfilter" data-parent="#div-'.$term->term_id.'" data-filter=".'.$childterm->slug.'">'.$childterm->name.'</a>';
                        }
                    $filter_html.='</div>';
                }
            }
        }

        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $big = 999999999; // need an unlikely integer
        $number_of_item_per_page = get_option('number_of_item_per_page',12);
        $post_args=array(
            'post_type' => "prof-portfolio",
            'posts_per_page' => $number_of_item_per_page,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true,
            'paged'          => $paged
        );

        $args = apply_filters('professional_portfolio_item_arguments',$post_args);
        global $wp_query;
        $temp = $wp_query;
        $wp_query = new WP_Query( $args);
        $portfolio_detail_image_position = get_option('portfolio_detail_image_position','right');
        if($wp_query->have_posts()):
            
            $item_html='<ul class="ittantalist">';
                while ( $wp_query->have_posts() ) : $wp_query->the_post();
                    $post_class = $this->getCategoriesClass(get_the_ID());
                    
        
                    $item_html.='<li class="ittantalist-item '.$post_class.'" data-content="#'.md5(get_the_ID()).'">
                        <div class="ittanta-portfolio-inner">
                            <div class="thumb">
                                <div class="view"> <a class="button polio-link" href="#"></a> </div>
                                    '.get_the_post_thumbnail(get_the_ID(),'p_portfolio_small').'
                            </div>
                            <h4><a class="polio-link" href="#">'.get_the_title().'</a></h4>
                        </div>
                        <div id="'.md5(get_the_ID()).'" class="polio-content">';
                       
                            if($portfolio_detail_image_position=='left'){
                                $item_html.='<div class="side">';
                                    $ids = get_post_meta(get_the_ID(),'professional_portfolio_attachments',true);
                                    if(!empty($ids)){
                                        $images=[];
                                        $attachment_ids = explode(',',$ids);
                                        $item_html.='<div class="flexslider">
                                            <ul class="slides">';
                                                foreach ($attachment_ids as $id) {
                                                    $url=wp_get_attachment_image_src($id,'p_portfolio_slide')[0];
                                                    $item_html.='<li><img src="'.$url.'" alt="Pic"/></li>';
                                                }
                                            $item_html.='</ul>
                                        </div>';
                                    }
                                $item_html.='</div>
                                <div class="poliomain">
                                    <h3>'.get_the_title().'</h3>
                                    <div>'.get_the_content().'</div>';
                                    $portfolio_website_url = get_post_meta(get_the_ID(),'portfolio_website_url',true);
                                    if(!empty($portfolio_website_url)){
                                        $item_html.='<a class="visit-link" target="_blank" href="'.esc_url($portfolio_website_url).'">Visit Site</a>';
                                    }
                                $item_html.='</div>';
                                
                            }else{ 
                                
                                $item_html.='<div class="poliomain">
                                    <h3>'.get_the_title().'</h3>
                                    <div>'.get_the_content().'</div>';
                                    $portfolio_website_url = get_post_meta(get_the_ID(),'portfolio_website_url',true);
                                    if(!empty($portfolio_website_url)){
                                        $item_html.='<a class="visit-link" target="_blank" href="'.esc_url($portfolio_website_url).'">Visit Site</a>';
                                    }
                                $item_html.='</div>
                                <div class="side">';
                                    $ids = get_post_meta(get_the_ID(),'professional_portfolio_attachments',true);
                                    if(!empty($ids)){
                                        $images=[];
                                        $attachment_ids = explode(',',$ids);
                                        $item_html.='<div class="flexslider">
                                            <ul class="slides">';
                                                foreach ($attachment_ids as $id) {
                                                    $url=wp_get_attachment_image_src($id,'p_portfolio_slide')[0];
                                                    $item_html.='<li><img src="'.$url.'" alt="Pic"/></li>';
                                                }
                                            $item_html.='</ul>
                                        </div>';
                                    }
                                $item_html.='</div>';
                                
                            }           
                        $item_html.='</div>
                    </li>';
                endwhile;
            $item_html.='</ul>';
            
        endif;
        
        if(get_option('portfolio_pagination')=='loadmore'){
            $url = get_permalink(get_option('portfolio_display_page',0));
            $item_html.='<div class="moreLoad">
                <a href="javascript:void(0)" class="load-btn red-btn elm-button '.($wp_query->max_num_pages == 1 ? ' ajax-inactive' : '').'" data-href="'.$url.'" data-page="'.( get_query_var('paged') ? get_query_var('paged') : '1' ).'" data-max-pages="'.$wp_query->max_num_pages.'">Load more</a>
              </div>';
        }else if(get_option('portfolio_pagination')=='infinitescroll'){
            $item_html.='<div class="ittanta-portfolio-pagination">
                <div class="scroller-status">
                  <div class="infinite-scroll-request loader-ellips"></div>
                  <p class="infinite-scroll-last"></p>
                  <p class="infinite-scroll-error"></p>
                </div>';
                $item_html.= $this->custom_pagination($wp_query->max_num_pages, "", $paged,false);
            $item_html.='</div>';
        }else{
            $item_html.='<div class="ittanta-portfolio-pagination">'.$this->custom_pagination($wp_query->max_num_pages, "", $paged,false).'</div>';
        }
        $html=$style.'<div class="container"><div class="ittanta-portfolio clearfix">';
        $html.= $filter_html;
        $html.= $item_html;    
        $html.='</div></div>';
        wp_reset_postdata();
        $wp_query = $temp;
        return $html;
    }

    public function loadProfessionalPortfolioTemplate($template){
        global $post;
        $page_id = get_option('portfolio_display_page',0);
        $default_file = 'professional-portfolio/portfolio.php';

        
        if($post->ID==$page_id){
            //echo $template;exit;
            $template =  locate_template($default_file);
            if(!$template){
                $template = PPORT_DIR.'/template/portfolio.php';
            }
        }   
        return $template;
    }

    public function getCategoriesClass($id){
        $categories = wp_get_post_terms($id,'portfolio_entries'); 
        $classes=[];
        if ( ! empty( $categories ) ) {
            foreach ($categories as $key => $value) {
                array_push($classes, $value->slug);
            }
        }
        //col-l-3 col-m-4 col-s-6
        $number_of_item_per_row_desktop = get_option('number_of_item_per_row_desktop',4);
        $number_of_item_per_row_tablet = get_option('number_of_item_per_row_tablet',3);

        $desktop_column = (12/$number_of_item_per_row_desktop);
        $tablet_column = (12/$number_of_item_per_row_tablet);
        $desktop_class="col-l-$desktop_column";
        $tablet_class="col-m-$tablet_column";
        array_push($classes, $desktop_class);
        array_push($classes, $tablet_class);
        array_push($classes, 'col-s-6');
        return implode(' ',$classes);
    }

    public function professional_portfolio_after_header(){
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
        <style type="text/css">
            .ittantalist{
                padding: 0;
                margin: 0;
            }
            .ittanta-portfolio .ittantalist .ittantalist-item h4 a{
                <?php if(!empty($title_font_size)){?>
                    font-size: <?php echo $title_font_size;?>px !important;
                <?php }?>
                <?php if(!empty($title_text_color)){?>
                    color: <?php echo $title_text_color;?> !important;
                <?php }?>
            }
            .ittanta-portfolio .ittantalist .ittantalist-item .ittanta-portfolio-inner{
                <?php if(!empty($display_border_in_image)){?>
                    border: 1px solid #ccc !important;
                <?php }?>
                
            }
            .polio-theme-black .polio-container{
                <?php if(!empty($portfolio_detail_bg_color)){?>
                   background: <?php echo $portfolio_detail_bg_color;?> !important;
                <?php }?>
                
            }
            .polio-theme-black, .polio-theme-black a{
                <?php if(!empty($portfolio_detail_text_color)){?>
                    color: <?php echo $portfolio_detail_text_color;?> !important;
                <?php }?>
                 <?php if(!empty($portfolio_detail_text_font_size)){?>
                    font-size: <?php echo $portfolio_detail_text_font_size;?>px !important;
                <?php }?>
            }
            .closeimg{fill:'.$portfolio_detail_text_color.' !important;}
            .poliomain a.visit-link{
                z-index: 99999999;
                position: relative;
                color:<?php echo $portfolio_detail_visit_link_color;?> !important;
                background:<?php echo $portfolio_detail_visit_link_bg_color;?> !important;
                border-color:<?php echo $portfolio_detail_visit_link_border_color;?> !important;
            }
            .poliomain a.visit-link:hover{
                color:<?php echo $portfolio_detail_visit_link_hover_color;?> !important;
                background:<?php echo $portfolio_detail_visit_link_hover_bg_color;?> !important;
                border-color:<?php echo $portfolio_detail_visit_link_hover_border_color;?> !important;
            }
            .polio-theme-black h3{
                 <?php if(!empty($portfolio_detail_title_color)){?>
                    color: <?php echo $portfolio_detail_title_color;?> !important;
                <?php }?>
                <?php if(!empty($portfolio_detail_title_font_size)){?>
                    font-size: <?php echo $portfolio_detail_title_font_size;?>px !important;
                <?php }?>
            }
            .polio-theme-black .polio-navigation a{
                <?php if(!empty($portfolio_detail_button_bg_color)){?>
                    background: <?php echo $portfolio_detail_button_bg_color;?> !important;
                <?php }?>
            }
            .polio-theme-black .polio-navigation a:hover{
                <?php if(!empty($portfolio_detail_button_hover_bg_color)){?>
                    background: <?php echo $portfolio_detail_button_hover_bg_color;?> !important;
                <?php }?>
            }
            .flex-control-nav .flex-active{
                <?php if(!empty($portfolio_detail_slider_button_active_color)){?>
                    background: <?php echo $portfolio_detail_slider_button_active_color;?> !important;
                <?php }?>
            }
            .flex-control-nav a{
                <?php if(!empty($portfolio_detail_slider_button_default_color)){?>
                    background: <?php echo $portfolio_detail_slider_button_default_color;?> !important;
                <?php }?>
            }
        </style>
        <?php
    }

    public function professional_portfolio_enqueue_scripts(){
        global $post;
        $page_id = get_option('portfolio_display_page',0);
        //if(is_page($page_id)){
            wp_enqueue_style('professional-porfoilio-style',PPORT_ASSETS_URL.'css/portfolio-css.css');
            if(get_option('portfolio_pagination')=='infinitescroll'){
                wp_enqueue_script('infinite-scroll', "https://unpkg.com/infinite-scroll@3/dist/infinite-scroll.pkgd.min.js", array('jquery'), NULL, true);
            }
            wp_enqueue_script('jquery.scrollUp', PPORT_ASSETS_URL.'js/jquery.scrollUp.min.js', array('jquery'), NULL, true);
            wp_enqueue_script('jquery.isotope', PPORT_ASSETS_URL.'js/jquery.isotope.min.js', array('jquery.scrollUp'), NULL, true);
            wp_enqueue_script('jquery.polio', PPORT_ASSETS_URL.'js/jquery.polio.min.js', array('jquery.isotope'), NULL, true);
            wp_enqueue_script('jquery.flexslider', PPORT_ASSETS_URL.'js/jquery.flexslider.js', array('jquery.polio'), NULL, true);
            wp_enqueue_script('professional.portfolio', PPORT_ASSETS_URL.'js/professional-portfolio.js', array('jquery.flexslider'), NULL, true);
            wp_localize_script('professional.portfolio', 'portfolio_options', array('portfolio_pagination' => get_option('portfolio_pagination')));
            
        //}
    }
    public function professional_portfolio_dispay_filter(){
        $args = array(
            'taxonomy' => 'portfolio_entries',
            'hide_empty' => false,
            'parent'=>0
        );
        $term_argus = apply_filters('professional_portfolio_parent_term_arguments',$args);

        $terms = get_terms($term_argus);
        ?>
            <div class="filters">
                <a href="javascript:void(0);" id="showall" class="filter-active filtertags" data-target="all">All</a>
                <?php if(!empty($terms) && count($terms)>0){?>
                    <?php foreach ($terms as $term) {?>
                        <a href="javascript:void(0);" class="showSingle filtertags" data-target="#div-<?php echo $term->term_id;?>"><?php echo $term->name;?></a>       
                    <?php }?>
                <?php }?>
            </div>
            <?php if(!empty($terms) && count($terms)>0){?>
                <?php foreach ($terms as $term) {
                    $term_child_argus=array(
                        'taxonomy' => 'portfolio_entries',
                        'hide_empty' => false,
                        'parent'=>$term->term_id
                    );
                    $child_argus = apply_filters('professional_portfolio_child_term_arguments',$term_child_argus);
                    $childterms = get_terms($child_argus);
                    ?>
                    <?php if(!empty($childterms) && count($childterms)>0){?>
                        <div id="div-<?php echo $term->term_id;?>" class="filters targetDiv">
                            <?php foreach ($childterms as $childterm) {?>
                                <a href="javascript:void(0);" class="subfilter" data-parent="#div-<?php echo $term->term_id;?>" data-filter=".<?php echo $childterm->slug;?>"><?php echo $childterm->name;?></a>
                            <?php }?>
                        </div>
                    <?php }?>
                <?php }?>
            <?php }?>
        <?php
    }

    public function professional_portfolio_dispay_item(){
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $big = 999999999; // need an unlikely integer
        $number_of_item_per_page = get_option('number_of_item_per_page',12);
        $post_args=array(
            'post_type' => "prof-portfolio",
            'posts_per_page' => $number_of_item_per_page,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true,
            'paged'          => $paged
        );

        $args = apply_filters('professional_portfolio_item_arguments',$post_args);
        global $wp_query;
        $temp = $wp_query;
        $wp_query = new WP_Query( $args);
        $portfolio_detail_image_position = get_option('portfolio_detail_image_position','right');
        if($wp_query->have_posts()) : ?>
            <ul class="ittantalist">
                <?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
                        <?php $post_class = $this->getCategoriesClass(get_the_ID());?>
                        <li class="ittantalist-item <?php echo $post_class;?>" data-content="#<?php echo md5(get_the_ID());?>">
                            <div class="ittanta-portfolio-inner">
                                <div class="thumb">
                                    <div class="view"> <a class="button polio-link" href="#"></a> </div>
                                     <?php echo get_the_post_thumbnail(get_the_ID(),'p_portfolio_small');?>
                                </div>
                                <h4><a class="polio-link" href="#"><?php the_title();?></a></h4>
                            </div>
                            <div id="<?php echo md5(get_the_ID());?>" class="polio-content">
                                <?php if($portfolio_detail_image_position=='left'){?>
                                    <div class="side">
                                        <?php
                                        $ids = get_post_meta(get_the_ID(),'professional_portfolio_attachments',true);
                                        if(!empty($ids)){
                                            $images=[];
                                            $attachment_ids = explode(',',$ids);
                                            ?>
                                            <div class="flexslider">
                                                <ul class="slides">
                                                <?php foreach ($attachment_ids as $id) {
                                                    $url=wp_get_attachment_image_src($id,'p_portfolio_slide')[0];?>
                                                    <li><img src="<?php echo $url;?>" alt="Pic"/></li>
                                                <?php }?>
                                                </ul>
                                            </div>
                                        <?php }?>
                                    </div><!-- side -->
                                    <div class="poliomain">
                                        <h3><?php the_title();?></h3>
                                        <div><?php the_content();?></div>
                                        <?php $portfolio_website_url = get_post_meta(get_the_ID(),'portfolio_website_url',true);?>
                                        <?php if(!empty($portfolio_website_url)){?>
                                            <a class="visit-link" target="_blank" href="<?php echo esc_url($portfolio_website_url);?>">Visit Site</a>
                                        <?php }?>
                                    </div><!-- poliomain -->    
                                <?php }else{?> 
                                    <div class="poliomain">
                                        <h3><?php the_title();?></h3>
                                        <div><?php the_content();?></div>
                                        <?php $portfolio_website_url = get_post_meta(get_the_ID(),'portfolio_website_url',true);?>
                                        <?php if(!empty($portfolio_website_url)){?>
                                            <a class="visit-link" target="_blank" href="<?php echo esc_url($portfolio_website_url);?>">Visit Site</a>
                                        <?php }?>
                                    </div><!-- poliomain -->                  
                                    <div class="side">
                                        <?php
                                        $ids = get_post_meta(get_the_ID(),'professional_portfolio_attachments',true);
                                        if(!empty($ids)){
                                            $images=[];
                                            $attachment_ids = explode(',',$ids);
                                            ?>
                                            <div class="flexslider">
                                                <ul class="slides">
                                                <?php foreach ($attachment_ids as $id) {
                                                    $url=wp_get_attachment_image_src($id,'p_portfolio_slide')[0];?>
                                                    <li><img src="<?php echo $url;?>" alt="Pic"/></li>
                                                <?php }?>
                                                </ul>
                                            </div>
                                        <?php }?>
                                    </div><!-- side -->
                                <?php }?>           
                                
                                
                            </div>
                        </li>
                <?php endwhile;?>
            </ul>
        <?php endif;?>
        <?php 
        if(get_option('portfolio_pagination')=='loadmore'){
            $url = get_permalink(get_option('portfolio_display_page',0));
            ?>
            <div class="moreLoad">
                <a href="javascript:void(0)" class="load-btn red-btn elm-button <?php echo ($wp_query->max_num_pages == 1 ? ' ajax-inactive' : '');?>" data-href="<?php echo $url;?>" data-page="<?php echo ( get_query_var('paged') ? get_query_var('paged') : '1' );?>" data-max-pages="<?php echo $wp_query->max_num_pages;?>">Load more</a>
              </div>
            <?php
        }else if(get_option('portfolio_pagination')=='infinitescroll'){
            ?>
            <div class="ittanta-portfolio-pagination">
                <div class="scroller-status">
                  <div class="infinite-scroll-request loader-ellips"></div>
                  <p class="infinite-scroll-last"></p>
                  <p class="infinite-scroll-error"></p>
                </div>
                
                <?php $this->custom_pagination($wp_query->max_num_pages, "", $paged);?>
            </div>
            <?php
        }else{
             ?><div class="ittanta-portfolio-pagination">
                <?php $this->custom_pagination($wp_query->max_num_pages, "", $paged);?>
            </div><?php 
        }
       wp_reset_postdata();
       $wp_query = $temp;
    }

    protected function custom_pagination($numpages = '', $pagerange = '', $paged='',$echo=true) {
 
        if (empty($pagerange)) {
            $pagerange = 2;
        }
     
        global $paged;
         
        if (empty($paged)) {
            $paged = 1;
        }
         
        if ($numpages == '') {
            global $wp_query;
             
            $numpages = $wp_query->max_num_pages;
             
            if(!$numpages) {
                $numpages = 1;
            }
        }
     
        $pagination_args = array(
            'format'          => 'page/%#%',
            'total'           => $numpages,
            'current'         => $paged,
            'show_all'        => false,
            'end_size'        => 1,
            'mid_size'        => $pagerange,
            'prev_next'       => True,
            'prev_text'       => __('&laquo;'),
            'next_text'       => __('&raquo;'),
            'type'            => 'plain',
            'add_args'        => false,
            'add_fragment'    => ''
        );
        $paginate_links = paginate_links($pagination_args);
        if($echo==false){
            if ($paginate_links) {
                return '<div class="pagination">'.$paginate_links.'</div>';
            }    
        }else{
            if ($paginate_links) {
                echo "<div class='pagination'>";
                    echo $paginate_links;
                echo "</div>";
            }
        }
        
    }

}
