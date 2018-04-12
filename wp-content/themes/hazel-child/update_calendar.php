<?php 
/*
Template Name: Update Calendar
*/
//$update_booking = new WC_Bookings_Google_Calendar_Integration();
$counter = 1;
$args = array(
	'post_type' => 'wc_booking',
	'date_query' => array(
		array(
			'after'     => 'Jun 15th 2015',
			'before'    => 'Jun 30th 2015',
			'inclusive' => true,
		),
	),
	'showposts' => -1,
	'order' => 'ASC'
);
query_posts($args);
// The Loop
while ( have_posts() ) : the_post();
	
	$booking = get_wc_booking( get_the_ID() );
	$order = $booking->get_order();
	if( $order->status && $order->status == 'completed'){
		//$update_booking->sync_booking( get_the_ID() );	
		echo 'Test success - #'.get_the_ID().' - #'.$counter.' - #'.$order->status.'<br>';
		$counter++;
	}

endwhile;	
// Reset Query
wp_reset_query();
?>