<?php

add_filter("manage_edit-portfolio_columns", "prod_edit_columns");
add_filter("manage_edit-post_columns", "post_edit_columns");
add_filter("manage_edit-page_columns", "post_edit_columns");
add_action("manage_posts_custom_column",  "prod_custom_columns");
add_action("manage_pages_custom_column",  "prod_custom_columns");

function professional_listtable_image_css() 
{
    ?>
        <style>
            .widefat thead tr th#portfolio-image {
                width: 45px;
            }
        </style>
    <?php
}

function post_edit_columns($columns)
{
	$newcolumns = array(
		"cb" => "<input type=\"checkbox\" />",
		"portfolio-image" => "Image",
	);

	$columns= array_merge($newcolumns, $columns);

	add_action('admin_footer', array($this,'professional_listtable_image_css'));
	return $columns;
}

function prod_edit_columns($columns)
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

function prod_custom_columns($column)
{
	global $post;

	switch ($column)
	{
		case "portfolio-image":
			if (has_post_thumbnail($post->ID)){
				echo get_the_post_thumbnail($post->ID, 'thumbnail');
			}
		break;
		case "portfolio_entries":
			echo get_the_term_list($post->ID, 'portfolio_entries', '', ', ','');
		break;
	}
}  