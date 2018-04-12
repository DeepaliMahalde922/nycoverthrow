<?php
global $qode_options;

//init variables
$page_id 							= $wp_query->get_queried_object_id();
$content_bottom_area 				= "yes";
$content_bottom_area_sidebar 		= "";
$content_bottom_area_in_grid 		= true;
$content_bottom_background_color 	= '';
$uncovering_footer					= false;
$footer_classes_array				= array();
$footer_classes						= '';
$footer_border_columns				= 'yes';

if(isset($qode_options['footer_border_columns']) && $qode_options['footer_border_columns'] !== '') {
	$footer_border_columns = $qode_options['footer_border_columns'];
}


//is content bottom area enabled for current page?
if(get_post_meta($page_id, "qode_enable_content_bottom_area", true) != ""){
	$content_bottom_area = get_post_meta($page_id, "qode_enable_content_bottom_area", true);
} elseif(isset($qode_options['enable_content_bottom_area'])) {
	//content bottom area is turned on in theme options
	$content_bottom_area = $qode_options['enable_content_bottom_area'];
}

//is content bottom area enabled?
if($content_bottom_area == 'yes') {
	//is sidebar chosen for content bottom area for current page?
	if(get_post_meta($page_id, 'qode_choose_content_bottom_sidebar', true) != ""){
		$content_bottom_area_sidebar = get_post_meta($page_id, 'qode_choose_content_bottom_sidebar', true);
	} elseif(isset($qode_options['content_bottom_sidebar_custom_display'])) {
		//sidebar is chosen for content bottom area in theme options
		$content_bottom_area_sidebar = $qode_options['content_bottom_sidebar_custom_display'];
	}

	//take content bottom area in grid option for current page if set or from theme options otherwise
	if(get_post_meta($page_id, 'qode_content_bottom_sidebar_in_grid', true) != ""){
		$content_bottom_area_in_grid = get_post_meta($page_id, 'qode_content_bottom_sidebar_in_grid', true);
	} elseif(isset($qode_options['content_bottom_in_grid'])) {
		$content_bottom_area_in_grid = $qode_options['content_bottom_in_grid'];
	}

	//is background color for content bottom area set for current page
	if(get_post_meta($page_id, "qode_content_bottom_background_color", true) != ""){
		$content_bottom_background_color = get_post_meta($page_id, "qode_content_bottom_background_color", true);
	}
}
?>
<?php if($content_bottom_area == "yes") { ?>

	<div class="content_bottom" <?php if($content_bottom_background_color != ''){ echo 'style="background-color:'.$content_bottom_background_color.';"'; } ?>>
        <?php if($content_bottom_area_in_grid == 'yes'){ ?>
            <div class="container">
            <div class="container_inner clearfix">
        <?php } ?>
            <?php dynamic_sidebar($content_bottom_area_sidebar); ?>
        <?php if($content_bottom_area_in_grid == 'yes'){ ?>
            </div>
            </div>
        <?php } ?>
	</div>
<?php } ?>

<?php

//is uncovering footer option set in theme options?
if(isset($qode_options['uncovering_footer']) && $qode_options['uncovering_footer'] == "yes") {
	//add uncovering footer class to array
	$footer_classes_array[] = 'uncover';
}

if($footer_border_columns == 'yes') {
	$footer_classes_array[] = 'footer_border_columns';
}

//is some class added to footer classes array?
if(is_array($footer_classes_array) && count($footer_classes_array)) {
	//concat all classes and prefix it with class attribute
	$footer_classes = 'class="'. implode(' ', $footer_classes_array).'"';
}

?>
    </div>
</div>
<footer <?php echo $footer_classes; ?>>
	<div class="footer_inner clearfix">
		<?php
		$footer_in_grid = true;
		if(isset($qode_options['footer_in_grid'])){
			if ($qode_options['footer_in_grid'] != "yes") {
				$footer_in_grid = false;
			}
		}
		$display_footer_top = true;
		if (isset($qode_options['show_footer_top'])) {
			if ($qode_options['show_footer_top'] == "no") $display_footer_top = false;
		}

		$footer_top_columns = 4;
		if (isset($qode_options['footer_top_columns'])) {
			$footer_top_columns = $qode_options['footer_top_columns'];
		}

		if($display_footer_top) { ?>
			<div class="footer_top_holder">
				<div class="footer_top<?php if(!$footer_in_grid) {echo " footer_top_full";} ?>">
					<?php if($footer_in_grid){ ?>
					<div class="container">
						<div class="container_inner">
							<?php } ?>
							<?php switch ($footer_top_columns) {
								case 6:
									?>
									<div class="two_columns_50_50 clearfix">
										<div class="qode_column column1">
											<div class="column_inner">
												<?php dynamic_sidebar( 'footer_column_1' ); ?>
											</div>
										</div>
										<div class="qode_column column2">
											<div class="column_inner">
												<div class="two_columns_50_50 clearfix">
													<div class="qode_column column1">
														<div class="column_inner">
															<?php dynamic_sidebar( 'footer_column_2' ); ?>
														</div>
													</div>
													<div class="qode_column column2">
														<div class="column_inner">
															<?php dynamic_sidebar( 'footer_column_3' ); ?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<?php
									break;
								case 5:
									?>
									<div class="two_columns_50_50 clearfix">
										<div class="qode_column column1">
											<div class="column_inner">
												<div class="two_columns_50_50 clearfix">
													<div class="qode_column column1">
														<div class="column_inner">
															<?php dynamic_sidebar( 'footer_column_1' ); ?>
														</div>
													</div>
													<div class="qode_column column2">
														<div class="column_inner">
															<?php dynamic_sidebar( 'footer_column_2' ); ?>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="qode_column column2">
											<div class="column_inner">
												<?php dynamic_sidebar( 'footer_column_3' ); ?>
											</div>
										</div>
									</div>
									<?php
									break;
								case 4:
									?>
									<div class="four_columns clearfix">
										<div class="qode_column column1">
											<div class="column_inner">
												<?php dynamic_sidebar( 'footer_column_1' ); ?>
											</div>
										</div>
										<div class="qode_column column2">
											<div class="column_inner">
												<?php dynamic_sidebar( 'footer_column_2' ); ?>
											</div>
										</div>
										<div class="qode_column column3">
											<div class="column_inner">
												<?php dynamic_sidebar( 'footer_column_3' ); ?>
											</div>
										</div>
										<div class="qode_column column4">
											<div class="column_inner">
												<?php dynamic_sidebar( 'footer_column_4' ); ?>
											</div>
										</div>
									</div>
									<?php
									break;
								case 3:
									?>
									<div class="three_columns clearfix">
										<div class="qode_column column1">
											<div class="column_inner">
												<?php dynamic_sidebar( 'footer_column_1' ); ?>
											</div>
										</div>
										<div class="qode_column column2">
											<div class="column_inner">
												<?php dynamic_sidebar( 'footer_column_2' ); ?>
											</div>
										</div>
										<div class="qode_column column3">
											<div class="column_inner">
												<?php dynamic_sidebar( 'footer_column_3' ); ?>
											</div>
										</div>
									</div>
									<?php
									break;
								case 2:
									?>
									<div class="two_columns_50_50 clearfix">
										<div class="qode_column column1">
											<div class="column_inner">
												<?php dynamic_sidebar( 'footer_column_1' ); ?>
											</div>
										</div>
										<div class="qode_column column2">
											<div class="column_inner">
												<?php dynamic_sidebar( 'footer_column_2' ); ?>
											</div>
										</div>
									</div>
									<?php
									break;
								case 1:
									dynamic_sidebar( 'footer_column_1' );
									break;
							}
							?>
							<?php if($footer_in_grid){ ?>
						</div>
					</div>
				<?php } ?>
				</div>
			</div>
		<?php } ?>
		<?php
		$display_footer_text = false;
		if (isset($qode_options['footer_text'])) {
			if ($qode_options['footer_text'] == "yes") $display_footer_text = true;
		}
		if($display_footer_text): ?>
			<div class="footer_bottom_holder">
				<div class="footer_bottom">
					<?php dynamic_sidebar( 'footer_text' ); ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</footer>
</div>
</div>
<?php wp_footer(); ?>
<style type="text/css">
#hear_about_us_opt_field span.optt {
    display: block;
    margin: 5px 0 0;
}
#hear_about_us_opt_field span.optt label {
    display: inline;
    left: 5px;
    position: relative;
    top: -1px;
}
#hear_about_us_opt_field #hear_about_us_opt {
	display: none;
    margin-top: 10px;
}
/* Month header background color */
#wc-bookings-booking-form .wc-bookings-date-picker .ui-datepicker-header {
	background-color: #000000;
} 
/* Previous/Next month arrow background color */
#wc-bookings-booking-form .wc-bookings-date-picker .ui-datepicker .ui-datepicker-next, 
#wc-bookings-booking-form .wc-bookings-date-picker .ui-datepicker .ui-datepicker-prev {
	background-color: #000000;
} 
/* Previous/Next month arrows if not allowed, and calendar dates that are not available */
.ui-state-disabled, 
.ui-widget-content .ui-state-disabled, 
.ui-widget-header .ui-state-disabled {
	opacity: 0.35;
} 
/* Days of the week header background color */
.ui-datepicker-calendar thead {
	background-color: #000000;
} 
/* Days of the week header font color */
#wc-bookings-booking-form .wc-bookings-date-picker .ui-datepicker th {
	color: #000000;
} 
/* Past calendar days background color (not available) */
.ui-datepicker-calendar tbody {
	background-color: #a5a5a4;
} 
/* Available calendar days background color */
#wc-bookings-booking-form .wc-bookings-date-picker .ui-datepicker td.bookable a {
	background-color: #ed656f !important;
} 
/* Available calendar day hover background color */
#wc-bookings-booking-form .wc-bookings-date-picker .ui-datepicker td.bookable a:hover {
	background-color: #edd865 !important;
} 
/* Fully booked calendar days */
.wc-bookings-date-picker .ui-datepicker td.fully_booked a, 
.wc-bookings-date-picker .ui-datepicker td.fully_booked span {
	background-color: #000000 !important;
} 
/* Days not bookable based on the availability rules */
.wc-bookings-date-picker .ui-datepicker td.not_bookable {
	background-color: #a5a5a4 !important;
} 
/* Today's date on calendar background color */
#wc-bookings-booking-form .wc-bookings-date-picker .ui-datepicker td.ui-datepicker-today a {
	background-color: #000000 !important;
}
</style>
</body>
</html>