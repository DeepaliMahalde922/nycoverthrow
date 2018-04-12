<div class="portfolio_navigation">
	<div class="portfolio_prev">
		<?php previous_post_link('%link', '<span class="arrow_carrot-left"></span>'); ?>
	</div> <!-- close div.portfolio_prev -->

	<?php if(get_post_meta(get_the_ID(), "qode_choose-portfolio-list-page", true) != "") { ?>
		<div class="portfolio_button">
			<a href="<?php echo get_permalink(get_post_meta(get_the_ID(), "qode_choose-portfolio-list-page", true)); ?>"><span aria-hidden="true" class="icon_grid-3x3"></span></a>
		</div> <!-- close div.portfolio_button -->
	<?php } ?>
	<div class="portfolio_next">
		<?php next_post_link('%link','<span class="arrow_carrot-right"></span>'); ?>
	</div> <!-- close div.portfolio_next -->
</div> <!-- close div.portfolio_navigation -->