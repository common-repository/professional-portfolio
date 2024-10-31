<?php get_header();?>

<?php do_action('professional_portfolio_after_header');?>
<div class="container">
	<div class="ittanta-portfolio clearfix">
		<?php do_action('professional_portfolio_dispay_filter');?>
		<?php do_action('professional_portfolio_dispay_item');?>	
	</div>
</div>
<?php do_action('professional_portfolio_before_footer');?>

<?php get_footer();?>
