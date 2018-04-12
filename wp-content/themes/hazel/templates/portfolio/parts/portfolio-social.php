<?php
global $qode_options;

$portfolio_qode_like 		= 'no';

//is like turned on for portfolio?
if (isset($qode_options['portfolio_qode_like'])) {
	$portfolio_qode_like = $qode_options['portfolio_qode_like'];
}

?>

<?php if($portfolio_qode_like == 'on' && function_exists('qode_like')) { ?>
	<div class="info portfolio_single_like">
		<h6 class="info_section_title"><?php qode_like(); ?></h6>
	</div>
<?php } ?>

<div class="portfolio_social_holder">
	<?php echo do_shortcode('[social_share]'); ?>
</div> <!-- close div.portfolio_social_holder -->