<?php
//get global variables
global $wp_query;
global $qode_options;

//init variables
$id 						= $wp_query->get_queried_object_id();
$container_styles			= 'style="';

//is page background color set for current page?
if(get_post_meta($id, "qode_page_background_color", true) != ""){
	$container_styles .= 'background-color: '.get_post_meta($id, "qode_page_background_color", true).';';
}

//close style tag. Don't delete this line
$container_styles .= '"';

//get current portfolio template
$portfolio_template = 'small-images';
if(get_post_meta($id, "qode_choose-portfolio-single-view", true) != "") {
	$portfolio_template = get_post_meta($id, "qode_choose-portfolio-single-view", true);
} elseif($qode_options['portfolio_style'] !== '') {
	$portfolio_template = $qode_options['portfolio_style'];
}

?>

<div class="container" <?php echo $container_styles; ?>>
	<div class="container_inner default_template_holder clearfix">
		<div class="portfolio_single <?php echo $portfolio_template; ?>">
			<?php
			//load proper portfolio file based on portfolio template
			get_template_part('templates/portfolio/portfolio', $portfolio_template);

			?>
		</div> <!-- close div.portfolio single -->
	</div> <!-- close div.container inner -->
</div> <!-- close div.container -->