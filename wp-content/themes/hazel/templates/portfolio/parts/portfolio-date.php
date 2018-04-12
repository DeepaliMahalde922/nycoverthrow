<?php if(get_post_meta(get_the_ID(), "qode_portfolio_date", true)) { ?>
	<div class="info portfolio_single_custom_date">
		<h6 class="info_section_title"><?php _e('Date','qode'); ?></h6>
		<p><?php echo get_post_meta(get_the_ID(), "qode_portfolio_date", true); ?></p>
	</div>
<?php } ?>