<?php
	
	/* Template name: Test Booking Page */
	get_header();
?>

<div style="padding:50px;width='800px';margin='0 auto';">

	<?php		

		die();

	?>

	<?php

		global $current_user;
		/*echo '<pre>';
		print_r($current_user->ID);
		echo '</pre>';*/

		function check_subscription_validity($user_id){
			$user_subscriptions = wcs_get_users_subscriptions( $user_id );

			$has_subscription = false;
			
			if ( ! empty( $user_subscriptions ) ) {
				foreach ($user_subscriptions as $key => $value) {
					$subscription_status = $value->post_status;					
					$subscription_period = get_post_meta($key,'_billing_period', true);
					$subscription_interval = get_post_meta($key,'_billing_interval', true);

					if( isset($subscription_status) && $subscription_status == 'wc-active' && isset($subscription_period) && $subscription_period == 'month' && isset($subscription_interval) && $subscription_interval == '1' ){
						$has_subscription = true;
						break;
					}
				}
			}

			return $has_subscription;
		}

		if( check_subscription_validity(1307) ){
			echo 'valid subscription';
		}else{
			echo 'IN-valid subscription';
		}

		global $woocommerce;

  		/*$items = $woocommerce->cart->get_cart();

  		foreach($items as $item => $values) {
        	echo '<pre>';
        	print_r($values['data']->get_type());
        	echo '</pre>';
        	// print_r($item);
	        //$_product = $values['data']->post;
	        //echo $_product->post_title;
  		}

  		echo '<pre>';
  		print_r(get_post_meta(84174));
  		echo '</pre>';*/


  		//get old booking posts (post type = 'wc_booking')

  		echo '<br/>';

  		$args = array(
			'post_type' => 'wc_booking',
			'posts_per_page' => -1,
			//'post_status' => 'any',
			//'author' => $user_id,
			/*'meta_key' => '_appointment_timestamp',
			'orderby' => 'meta_value_num',
			'order' => $order,*/
			'order' => 'desc',
			'orderby' => 'ID',
		);

		$appointments_array = array();

		$wc_bookings = new WP_Query($args);

		echo '<h2>Old booking post meta</h2>';	

		if($wc_bookings->have_posts()):

			while ($wc_bookings->have_posts()):

				$wc_bookings->the_post();				

				

				//echo get_the_ID(); echo ' # '; echo get_the_title(); echo '<br/>';
				echo '<pre>';				
				print_r(get_post_meta(get_the_ID())); echo '<br/>';
				//print_r($booking = get_wc_booking( get_the_ID() )); echo '<br/>';

				$booking = get_wc_booking( get_the_ID() );

				print_r($booking->get_customer()); echo '<br/>';

				$customer_obj = $booking->get_customer();

				$user_id = get_post_meta(get_the_ID(),'_booking_customer_id', true);

				$order_id = $booking->get_order()->id;

				$resource_id = get_post_meta(get_the_ID(),'_booking_resource_id', true);

				$old_status = $booking->get_status();

				$attendence_status = get_post_meta($id, '_booking_attendance_status', true);

				if( isset($attendence_status) ){
					$attendence_status = $attendence_status;					
				}else{
					$attendence_status = 'no';
				}

				//print_r($booking->get_status()); echo '<br/>';
				//print_r(strtotime(get_the_date())); echo '<br/>';
				//print_r($booking->get_product()); echo '<br/>';
				//print_r($booking->get_order()); echo '<br/>';
				$start_date = '';
				$start_time = '';
				
				$start_time_str = get_post_meta(get_the_ID(),'_booking_start', true);

				//print_r($start_time_str);

				$start_year = substr($start_time_str, 0, 4);
				$start_month = substr($start_time_str, 4, 2);
				$start_day = substr($start_time_str, 6, 2);

				$start_hours = substr($start_time_str, 8, 2);
				$start_minutes = substr($start_time_str, 10, 2);
				$start_seconds = substr($start_time_str, 12, 2);

				$start_date = "$start_day-$start_month-$start_year";
				$start_time = "$start_hours:$start_minutes:$start_seconds";

				$end_date = '';
				$end_time = '';

				$end_time_str = get_post_meta(get_the_ID(),'_booking_end', true);

				//print_r($start_time_str);

				$end_year = substr($end_time_str, 0, 4);
				$end_month = substr($end_time_str, 4, 2);
				$end_day = substr($end_time_str, 6, 2);

				$end_hours = substr($end_time_str, 8, 2);
				$end_minutes = substr($end_time_str, 10, 2);
				$end_seconds = substr($end_time_str, 12, 2);

				$end_date = "$end_day-$end_month-$end_year";
				$end_time = "$end_hours:$end_minutes:$end_seconds";

				//echo $start_date.' '.$start_time; echo '<br/>';
				//echo $end_date.' '.$end_time; echo '<br/>';

				// Required info for new post start

				$post_published_date = get_the_date();
				$post_published_timestamp = strtotime($post_published_date);

				$new_post_appointment_timestamp = strtotime("$start_year-$start_month-$start_day $start_time");
				//2016-09-26 19:30:00

				$new_post_appointment_timeslot = $start_hours.$start_minutes.'-'.$end_hours.$end_minutes;
				//1930-2030

				if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {				

					migrate_to_new_booking_post($post_published_timestamp, $post_published_date, $new_post_appointment_timestamp, $new_post_appointment_timeslot, $customer_obj, $order_id, $resource_id, $old_status, $attendence_status);

				}

				//echo 'start # ';print_r(date( 'Y-m-d H:i:s', $start_time )); echo '<br/>';
				//echo 'end # ';print_r(date( 'Y-m-d H:i:s', $end_time )); echo '<br/>';
				echo '</pre>';

			endwhile;

		endif;

		wp_reset_postdata();

		echo '<h2>New booking post meta</h2>';

		//print_r(date( 'Y-m-d H:i:s', '1474918200' )); echo '<br/>';
		//print_r(strtotime('2016-09-26 19:30:00')); echo '<br/>';
		//2016-09-26 19:30:00
		//_appointment_timestamp

		/*echo '<pre>';
		print_r(get_post_meta(84183));
		echo '</pre>';*/

		function migrate_to_new_booking_post($old_post_timestamp, $old_post_date, $appointment_timestamp, $appointment_timeslot, $customer_obj, $order_id, $old_callendar_resource_id, $old_status, $attendence_status){

			$is_guest_user = false;

			if( $customer_obj->user_id == 0 ){
				$is_guest_user = true;
			}

			echo "old_post_timestamp : ".$old_post_timestamp; echo '<br/>';
			echo "old_post_date : ".$old_post_date; echo '<br/>';
			echo "appointment_timestamp : ".$appointment_timestamp; echo '<br/>';
			echo "appointment_timeslot : ".$appointment_timeslot; echo '<br/>';
			echo "user_id : ".$user_id; echo '<br/>';
			echo "order_id : ".$order_id; echo '<br/>';
			echo "old_callendar_resource_id : ".$old_callendar_resource_id; echo '<br/>';
			echo "old_status : ".$old_status; echo '<br/>';

			$new_status = 'booked_wc_awaiting';

			if( $old_status == 'unpaid' ){
				$new_status = 'booked_wc_awaiting';
			}
			if( $old_status == 'paid' ){
				$new_status = 'publish';	
			}

			$current_timestamp = $old_post_timestamp;
			$date = $old_post_date;

			$timestamp = $appointment_timestamp;
			$timeslot = $appointment_timeslot;

			$time_format = get_option('time_format');
			$date_format = get_option('date_format');

			$post_args = array(
				'post_title' => date_i18n($date_format,$current_timestamp).' @ '.date_i18n($time_format,$current_timestamp).' (User: '.$user_id.')',
				'post_content' => '',
				'post_status' => $new_status,
				'post_date' => date_i18n('Y',strtotime($date)).'-'.date_i18n('m',strtotime($date)).'-01 00:00:00',
				'post_type' => 'booked_appointments'
			);

			if( $is_guest_user ){

			}else{
				$post_args['post_author'] = $customer_obj->user_id;
			}

			$cf_meta_value = '<p class="cf-meta-value"><strong>Class</strong><br>&#036;34 - OVERTHROW BOXING CLASS<!-- product_id::29793 --></p>';

			// Create a new appointment post for this new customer
			$new_post = apply_filters('booked_new_appointment_args', $post_args);

			$post_id = -1;

			$post_id = wp_insert_post($new_post);

			echo '<pre>';
			print_r($post_id);
			echo '</pre>';

			if( $old_callendar_resource_id == '29802' ){
				$calendar_id = 370;
			}
			if( $old_callendar_resource_id == '29803' ){
				$calendar_id = 371;	
			}

			if( $post_id != -1 ){

				//update_post_meta($post_id, '_appointment_title', $title);
				update_post_meta($post_id, '_appointment_timestamp', $timestamp);
				update_post_meta($post_id, '_appointment_timeslot', $timeslot);

				if( $is_guest_user ){
					update_post_meta($post_id, '_appointment_guest_name', $customer_obj->name);
					update_post_meta($post_id, '_appointment_guest_email', $customer_obj->email);
				}else{
					update_post_meta($post_id, '_appointment_user', $user_id);
				}				

				update_post_meta($post_id, '_booked_wc_appointment_order_id', $order_id);
				update_post_meta($post_id, '_booked_wc_time_created', $current_timestamp);

				update_post_meta($post_id, '_booking_attendance_status', $attendence_status);					

				//if (apply_filters('booked_update_cf_meta_value', true)) {
					update_post_meta($post_id, '_cf_meta_value', $cf_meta_value);
				//}

		        //if (apply_filters('booked_update_appointment_calendar', true)) {
					//if (isset($calendar_id) && $calendar_id):  

						wp_set_object_terms($post_id,$calendar_id,'booked_custom_calendars');

					//endif;
				//}

			}			

		}

  		/*
  		<p class="cf-meta-value"><strong>Class</strong><br>&#036;34 - OVERTHROW BOXING CLASS<!-- product_id::29793 --></p>
  		*/

		/*
  		//$date = $_POST['date'];
		$date = '22-9-2016';

  		//$timestamp = $_POST['timestamp'];
  		$timestamp = '1474914600';

  		$current_timestamp = current_time('timestamp');

  		$time_format = get_option('time_format');
		$date_format = get_option('date_format');
		
		//$timeslot = $_POST['timeslot'];
		$timeslot = '1830-1930';

		$user_id = 513;

		$cf_meta_value = '<p class="cf-meta-value"><strong>Class</strong><br>&#036;34 - OVERTHROW BOXING CLASS<!-- product_id::29793 --></p>';

		// Create a new appointment post for this new customer
		$new_post = apply_filters('booked_new_appointment_args', array(
			'post_title' => date_i18n($date_format,$current_timestamp).' @ '.date_i18n($time_format,$current_timestamp).' (User: '.$user_id.')',
			'post_content' => '',
			'post_status' => 'publish',
			'post_date' => date_i18n('Y',strtotime($date)).'-'.date_i18n('m',strtotime($date)).'-01 00:00:00',
			'post_author' => $user_id,
			'post_type' => 'booked_appointments'
		));
		$post_id = wp_insert_post($new_post);

		echo '<pre>';
		print_r($post_id);
		echo '</pre>';

		$calendar_id = 371;

		//update_post_meta($post_id, '_appointment_title', $title);
		update_post_meta($post_id, '_appointment_timestamp', $timestamp);
		update_post_meta($post_id, '_appointment_timeslot', $timeslot);
		update_post_meta($post_id, '_appointment_user', $user_id);

		update_post_meta($post_id, '_booked_wc_appointment_order_id', 84169);
		update_post_meta($post_id, '_booked_wc_time_created', $current_timestamp);		

		//if (apply_filters('booked_update_cf_meta_value', true)) {
			update_post_meta($post_id, '_cf_meta_value', $cf_meta_value);
		//}

        //if (apply_filters('booked_update_appointment_calendar', true)) {
			//if (isset($calendar_id) && $calendar_id):  

				wp_set_object_terms($post_id,$calendar_id,'booked_custom_calendars');

			//endif;
		//}
		*/

	?>

</div>

<?php get_footer(); ?>