<?php

//init variables
$id 				= get_the_ID();
$portfolio_template = 'small-images';
$title 				= get_the_title();
$traner_slug = sanitize_title($title);
//is portfolio template set for current portfolio?
if(get_post_meta(get_the_ID(), "qode_choose-portfolio-single-view", true) != "") {
	$portfolio_template = get_post_meta(get_the_ID(), "qode_choose-portfolio-single-view", true);
} elseif($qode_options['portfolio_style'] !== '') {
	//get default portfolio template if set in theme's options
	$portfolio_template = $qode_options['portfolio_style'];
}

?>

<?php get_header(); ?>
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<?php if(get_post_meta($id, "qode_page_scroll_amount_for_sticky", true)) { ?>
				<script>
				var page_scroll_amount_for_sticky = <?php echo get_post_meta($id, "qode_page_scroll_amount_for_sticky", true); ?>;
				</script>
			<?php } ?>
				<?php get_template_part( 'title' ); ?>
			<?php
			$revslider = get_post_meta($id, "qode_revolution-slider", true);
			if (!empty($revslider)){ ?>
				<div class="q_slider">
					<div class="q_slider_inner">
						<?php echo do_shortcode($revslider); ?>
					</div> <!-- close div.q_slider_inner -->
				</div> <!-- close div.q_slider -->
			<?php
			}

			//is current portfolio template full width?
			if($portfolio_template !== 'full-width-custom') {
				//load general portfolio structure which will load proper template
				get_template_part('templates/portfolio/portfolio-structure');
			} else {
				//load custom full width template that doesn't have anything in common with other
				get_template_part('templates/portfolio/portfolio', $portfolio_template);
			}

			?>
		<?php endwhile; ?>
	<?php endif; ?>	


<!-- Added by Rahul Start -->
<div id="booking-calender_sect" data-trainer="<?php echo $title; ?>" class="default_template_holder clearfix">
	<div class="portfolio_single big-images">

		<div class="loader-section">
			<img class="load-classes" src="<?php echo get_stylesheet_directory_uri(); ?>/images/loading_apple.gif" alt="Loader" />	
			<h3>Please wait! While we are loading <?php echo $traner_slug; ?>'s  classes.</h3>
		</div>
		<?php $date = date("Y-m-d");// current date ?>
		<div id="booked-tcalender_trainer" style="display: none;" class="booked-calendar-shortcode-wrap">
			<div id="data-ajax-url"><?php echo site_url(); ?></div>
			<div  id="booked-tcalender" class="booked-calendar-wrap large" style="height: 0px;" data-trainer="<?php echo $traner_slug; ?>" data-default="<?php echo $date; ?>">
				<div id="trainer-calender" class="booked-calendar owl-carousel owl-theme">

				<?php

					

				?>
				</div>					
			</div>
		</div>
	</div>
</div>
<!-- Added by Rahul Start -->
	
<?php get_footer(); ?>	