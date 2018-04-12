<?php

/**
 * Enqueue the child theme styles and scripts
 */
function ovr_enqueue_scripts() {

	wp_register_style( 'child_style', get_stylesheet_directory_uri() . '/css/child_style.css', false, '1.0.0' );
    wp_enqueue_style( 'child_style' );

	wp_register_script('jquery_custom', get_stylesheet_directory_uri().'/js/jquery.custom.js', array('jquery'),'1.3', false);
	wp_enqueue_script('jquery_custom');

	$site_url = site_url();

	wp_localize_script( 'jquery_custom', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234, 'site_url' => $site_url ) );
	
}
add_action( 'wp_enqueue_scripts', 'ovr_enqueue_scripts', 11);

function ovr_admin_enqueue_scripts() {

	wp_register_script('admin_jquery', get_stylesheet_directory_uri().'/js/ovr_admin.js', array('jquery'),'1.5', false);
	wp_enqueue_script('admin_jquery');

	$site_url = site_url();

	wp_localize_script( 'admin_jquery', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234, 'site_url' => $site_url ) );
	
}
add_action( 'admin_enqueue_scripts', 'ovr_admin_enqueue_scripts', 11);


/**
 * Redirect user after successful login.
 */
function ovr_login_redirect_callback( $redirect, $user ) {

	$redirect = $_REQUEST['referer'] ? $_REQUEST['referer'] : site_url().'/my-account';

	return $redirect;
}
add_filter( 'woocommerce_login_redirect', 'ovr_login_redirect_callback', 10, 2 );


/**
 * Redirect user after successful logout.
 */
function ovr_logout_redirect_callback(){
  	  	
}
//add_action('wp_logout','ovr_logout_redirect_callback');


/**
 * Get Appoitnments Array
 */
function ovr_get_appointments($user_id = false, $only_count = false, $historic = false, $display = 'frontend'){

	if( $user_id === false && $display == 'frontend' ){

		$booked_current_user = wp_get_current_user();
		$user_id = $booked_current_user->ID;

	}

	$time_format = get_option('time_format');
	$date_format = get_option('date_format');

	$order = $historic ? 'DESC' : 'ASC';
	$count = $historic ? 50 : -1;

	$args = array(
		'post_type' => 'booked_appointments',
		'posts_per_page' => $count,
		'post_status' => 'any',
		'author' => $user_id,
		/*'meta_key' => '_appointment_timestamp',
		'orderby' => 'meta_value_num',
		'order' => $order,*/
		'order' => 'desc',
		'orderby' => 'ID',
	);

	$appointments_array = array();

	$bookedAppointments = new WP_Query($args);	

	if($bookedAppointments->have_posts()):

		while ($bookedAppointments->have_posts()):

			$bookedAppointments->the_post();

			global $post;

			$appt_date_value = date_i18n('Y-m-d',get_post_meta($post->ID, '_appointment_timestamp',true));
			$appt_timeslot = get_post_meta($post->ID, '_appointment_timeslot',true);
			$appt_timeslots = explode('-',$appt_timeslot);
			$appt_time_start = date_i18n('H:i:s',strtotime($appt_timeslots[0]));

			$appt_timestamp = strtotime($appt_date_value.' '.$appt_time_start);
			$current_timestamp = current_time('timestamp');

			$day = date_i18n('d',$appt_timestamp);
			$calendar_id = wp_get_post_terms( $post->ID, 'booked_custom_calendars' );

			//if (!$historic && $appt_timestamp >= $current_timestamp || $historic && $appt_timestamp < $current_timestamp){

				$appointments_array[$post->ID]['post_id'] = $post->ID;
				$appointments_array[$post->ID]['timestamp'] = $appt_timestamp;
				$appointments_array[$post->ID]['timeslot'] = $appt_timeslot;
				$appointments_array[$post->ID]['calendar_id'] = $calendar_id;
				$appointments_array[$post->ID]['status'] = $post->post_status;

			//}

		endwhile;

		$appointments_array = apply_filters('booked_appointments_array', $appointments_array);
		/*echo '<pre>';
		print_r($appointments_array);
		echo '</pre>';*/

	endif;

	wp_reset_postdata();

	if ($only_count):

		return count($appointments_array);

	else :

		return $appointments_array;

	endif;

}


/**
 * Get Appoitnments html for frontend and backend
 */
function ovr_show_appointmenst_html($user_id = false, $only_count = false, $historic = false, $display = 'frontend'){

	if (is_user_logged_in()){

		$appointments_array = ovr_get_appointments($user_id, $only_count, $historic, $display);

		if( $appointments_array === false ){
			return false;
		}

		$time_format = get_option('time_format');
		$date_format = get_option('date_format');

		$total_appts = count($appointments_array);
		$appointment_default_status = get_option('booked_new_appointment_default','draft');
		$only_titles = get_option('booked_show_only_titles',false);

		//echo '<div class="booked-profile-appt-list">';

		$table_html = '';

		if( $display == 'backend-sub-menu' ){

			$form_action = get_admin_url();
			$form_action.="admin.php?page=ovr-bookings";

			$table_html.= '<form action="'.$form_action.'" method="get"><p class="search-box">
			<label for="post-search-input" class="screen-reader-text">Search Bookings:</label>
			<input type="search" value="" name="s" id="post-search-input">
			<input type="submit" value="Search Bookings" class="button" id="search-submit"></form></p>';
		}

		//<div class="wrap">

		if( !empty($appointments_array) ){

			if( $display == 'backend-sub-menu' ){

				$table_class = 'wp-list-table widefat fixed striped posts';
				$extra_column_status = '<th>Order Status</th>';
				$extra_column_author = '<th>Booked By</th>';

			}else{

				$table_class = 'form-table shop_table my_account_bookings';
				$extra_column_status = '';
				$extra_column_author = '';

			}

			/*if (strpos($display, 'backend') !== false) {

				$booking_cancel = '';
				$booking_title = 'Bookings';

			}else{*/

				$booking_cancel = '<th class="booking-cancel" scope="col"></th>';
				$booking_title = 'My Bookings';

			//}

			$table_html.= '<h2>'.$booking_title.'</h2><table class="'.$table_class.'">

			<thead>
				<tr>
					'.$extra_column_status.'
					<th class="booking-id" scope="col">ID</th>
					<th class="booked-product" scope="col">Booked</th>
					'.$extra_column_author.'
					<th class="order-number" scope="col">Order</th>
					<th class="booking-start-date" scope="col">Start Date</th>
					<th class="booking-end-date" scope="col">End Date</th>
					<th class="booking-status" scope="col">Status</th>
					<th class="booking-attendance-status" scope="col">Attendance</th>
					'.$booking_cancel.'
				</tr>
			</thead>
			<tbody>';
		}		
		
			foreach($appointments_array as $appt):

				$today = date_i18n($date_format);
				$date_display = date_i18n($date_format,$appt['timestamp']);

				if ($date_display == $today){

					$date_display = __('Today','booked');
					$day_name = '';

				} else {

					$day_name = date_i18n('l',$appt['timestamp']).', ';

				}

				$date_to_convert = date_i18n('Y-m-d',$appt['timestamp']);

				$cf_meta_value = get_post_meta($appt['post_id'], '_cf_meta_value',true);

				$timeslots = explode('-',$appt['timeslot']);
				$time_start = date_i18n($time_format,strtotime($timeslots[0]));
				$time_end = date_i18n($time_format,strtotime($timeslots[1]));

				$appt_date_time = strtotime($date_to_convert.' '.date_i18n('H:i:s',strtotime($timeslots[0])));

				$atc_date_startend = date_i18n('Y-m-d',$appt['timestamp']);
				$atc_time_start = date_i18n('H:i:s',strtotime($timeslots[0]));
				$atc_time_end = date_i18n('H:i:s',strtotime($timeslots[1]));

				$current_timestamp = current_time('timestamp');
				$cancellation_buffer = get_option('booked_cancellation_buffer',0);

				if ($cancellation_buffer):

					if ($cancellation_buffer < 1){

						$time_type = 'minutes';
						$time_count = $cancellation_buffer * 60;

					} else {

						$time_type = 'hours';
						$time_count = $cancellation_buffer;

					}

					$buffered_timestamp = strtotime('+'.$time_count.' '.$time_type,$current_timestamp);

					$date_to_compare = $buffered_timestamp;

				else:

					$date_to_compare = current_time('timestamp');

				endif;
				
				$timeslotText = '';

				$status = ($appt['status'] != 'publish' && $appt['status'] != 'future' ? __('pending','booked') : __('approved','booked'));
				
				$status_class = $appt['status'] != 'publish' && $appt['status'] != 'future' ? 'pending' : 'approved';
				
				$ts_title = get_post_meta($appt['post_id'], '_appointment_title',true);

				if ($timeslots[0] == '0000' && $timeslots[1] == '2400'):

					if ($only_titles && !$ts_title || !$only_titles):
						$timeslotText = __('All day','booked');
					endif;

					$atc_date_startend_end = date_i18n('Y-m-d',strtotime(date_i18n('Y-m-d',$appt['timestamp']) . '+ 1 Day'));

					$atc_time_end = '00:00:00';

				else :

					if ($only_titles && !$ts_title || !$only_titles):
						
						$timeslotText = (!get_option('booked_hide_end_times') ? __('from','booked').' ' : __('at','booked').' ') . $time_start . (!get_option('booked_hide_end_times') ? ' &ndash; '.$time_end : '');

					endif;

					$atc_date_startend_end = $atc_date_startend;

				endif;

					if (!$historic){						


						if ($appt_date_time >= $date_to_compare){
						
							$calendar_button_array = array(
								'atc_date_startend' => $atc_date_startend,
								'atc_time_start' => $atc_time_start,
								'atc_date_startend_end' => $atc_date_startend_end,
								'atc_time_end' => $atc_time_end,
							);
						}
					}
					
				//echo '</span>';
				
				$booking_order_id = get_post_meta($appt["post_id"], '_booked_wc_appointment_order_id', true);

				if( isset($booking_order_id) ){

					$booking_order_link = site_url('/my-account/view-order/'.$booking_order_id);

				}else{

					$booking_order_link = "javascript:void(0);";

				}

				$table_html.='<tr>';

					if( isset($booking_order_id) ){

						$order = new WC_Order($booking_order_id);			
					}

					if( $display == 'backend-sub-menu' ){
						
						$table_html.= '<td>'. ucfirst($order->get_status()).'</td>';

					}

				$table_html.='<td class="booking-id">'.$appt["post_id"].'</td>
							<td class="booked-product">
													<a href="javascript:void(0);">
									OVERTHROW BOXING CLASS</a> ('.$appt["calendar_id"][0]->name.')
												</td>';

				if( $display == 'backend-sub-menu' ){

					$booked_by_id = get_post_meta($appt["post_id"],'_appointment_user', true);

					if( isset($booked_by_id) ){

						$user_info = get_userdata($booked_by_id);

						if( $user_info !== false ){

							//$username = $user_info->user_login;
							$first_name = $user_info->first_name;
							$last_name = $user_info->last_name;

							$table_html.= '<td>'.ucfirst($first_name.' '.$last_name).$author_page_link.'</td>';

						}

					}												
				}

				$table_html.='<td class="order-number">
								<a target="_blank" href="'.$booking_order_link.'">'.$booking_order_id.'</a>
							</td>
							<td class="booking-start-date">'.$atc_date_startend.' '.$time_start.'</td>
							<td class="booking-end-date">'.$atc_date_startend_end.' '.$time_end.'</td>
							<td class="booking-status"><span data-tip="Complete" class="status-complete tips">'.ucfirst($status).'</span></td>';
							
							// Code for attendance column

							$id = $appt["post_id"];

							$attendence_status = get_post_meta($id, '_booking_attendance_status');
	
							$checked = '';
							$checked_label = 'OUT';

							if( isset($attendence_status) && !empty($attendence_status))
							{
								if($attendence_status[0] == 'yes')
								{
									$checked = 'checked';
									$checked_label = 'IN';
								}
								if($attendence_status[0] == 'no')
								{
									$checked = '';
									$checked_label = 'OUT';
								}				
							}
							else
							{
								$checked = '';
								$checked_label = 'OUT';
							}

							if( $display == 'backend-sub-menu' ){

								$attendance_column = '<td data-colname="Attendance" class="attendance column-attendance">
									<div class="onoffswitch">
								        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch-'.$id.'" booking-id="'.$id.'"'. $checked .'>
								        <label class="onoffswitch-label" for="myonoffswitch-'.$id.'" >
								            <span class="onoffswitch-inner"></span>
								            <span class="onoffswitch-switch"></span>
								        </label>
								    </div>
							    </td>';

							}else{
								$attendance_column = '<td data-colname="Attendance" class="attendance column-attendance">'
									.$checked_label.
								'</td>';
							}

				$table_html.= $attendance_column;

				//if (strpos($display, 'backend') === false) {

					$table_html.= '<td class="action column-action booking-cancel">';

					$calendar_button_array = array(
						'atc_date_startend' => $atc_date_startend,
						'atc_time_start' => $atc_time_start,
						'atc_date_startend_end' => $atc_date_startend_end,
						'atc_time_end' => $atc_time_end,
					);
					booked_add_to_calendar_button($calendar_button_array,$cf_meta_value);

					//if ( apply_filters('booked_shortcode_appointments_allow_cancel', true, $appt['post_id']) && !get_option('booked_dont_allow_user_cancellations',false) ) {

						if ( $appt_date_time >= $date_to_compare ) {

							$table_html.= '<a href="javascript:void(0)" data-appt-id="'.$appt['post_id'].'" class="button cancel">'.__('Cancel','booked').'</a>';

						}else{
							//$table_html.= 'can not cancel';
						}

						//do_action('booked_shortcode_appointments_buttons', $appt['post_id']);

					$table_html.= '</td>';

				//}										

				$table_html.= '</tr>';

			endforeach;

		//echo '</div>';

		if( count($total_appts) > 0 ){

			$table_html.= '</tbody></table><br/>';
			
		}		

		print_r($table_html);

	}

	wp_reset_postdata();		

}


/**
 * Function to show Appoitnments / Bookings
 */
function ovr_display_bookings(){

	ovr_show_appointmenst_html(false,false,true);	

}
//add_action( 'woocommerce_before_account_orders', 'ovr_display_bookings' );
add_action( 'woocommerce_account_dashboard', 'ovr_display_bookings' );


/**
 * Woocommerce order history in backend
 */
function ovr_order_history_in_user_profile($user){
	
	global $woocommerce;

	ovr_show_appointmenst_html($user->data->ID, false, true, 'backend');

}
add_action( 'show_user_profile', 'ovr_order_history_in_user_profile' );
add_action( 'edit_user_profile', 'ovr_order_history_in_user_profile' );


/**
 * Add new sub menu under "Appointments" to show appointments / booking listings
 */
//add_action('admin_menu', 'ovr_register_appointments_sub_menu');

function ovr_register_appointments_sub_menu() {

    add_submenu_page( 'booked-appointments', 'Bookings', 'Bookings', 'manage_options', 'ovr-bookings', 'ovr_bookings_callback' ); 

}


/**
 * Callback function for new sub menu under "Appointments" to show appointments / booking listings
 */
function ovr_bookings_callback() {

	ovr_show_appointmenst_html(false,false,true, 'backend-sub-menu');

}


/**
 * Add Styles and Script for Attendance on-off button
 */
function ovr_attendance_scripts() {
	wp_register_style( 'attendance_style', get_stylesheet_directory_uri() . '/css/attendance_style.css', false, '1.0.0' );
    wp_enqueue_style( 'attendance_style' );
	
	wp_register_script('attendance_js', get_stylesheet_directory_uri() . '/js/attendance.js', array('jquery'),'1.1', false);
	wp_enqueue_script('attendance_js');

	wp_localize_script( 'attendance_js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234 ) );

}
add_action('admin_enqueue_scripts', 'ovr_attendance_scripts');


/**
 * Change Attendance status for orders
 */
if (!function_exists('ovr_update_attendance_callback')){
	function ovr_update_attendance_callback(){
		if(isset($_POST['booking_id']) && isset($_POST['attendace_status']))
		{
			$booking_id = $_POST['booking_id'];
			$attandance_status = $_POST['attendace_status'];
			$status = update_post_meta($booking_id, '_booking_attendance_status', $attandance_status); 			
		}
		wp_die();
	}
}
add_action( 'wp_ajax_ovr_update_attendance', 'ovr_update_attendance_callback');
add_action( 'wp_ajax_nopriv_ovr_update_attendance', 'ovr_update_attendance_callback');

/**
 * Cancel Appoitnemnt and give back funds to user account if account fund method is used
 */
function ovr_cancel_appointment_callback() {
	
	$appt_id = $_POST['appt_id'];	
	$title = get_post_meta($appt_id,'_appointment_title',true);
	$timeslot = get_post_meta($appt_id,'_appointment_timeslot',true);
	$timestamp = get_post_meta($appt_id,'_appointment_timestamp',true);
	$cf_meta_value = get_post_meta($appt_id,'_cf_meta_value',true);
	$timeslots = explode('-',$timeslot);
	$time_format = get_option('time_format');
	$date_format = get_option('date_format');
	$hide_end_times = get_option('booked_hide_end_times',false);

	$timestamp_start = strtotime(date_i18n('Y-m-d',$timestamp).' '.$timeslots[0]);
	$timestamp_end = strtotime(date_i18n('Y-m-d',$timestamp).' '.$timeslots[1]);
	$current_timestamp = current_time('timestamp');

	if ($timeslots[0] == '0000' && $timeslots[1] == '2400'):
		$timeslotText = __('All day','booked');
	else :
		$timeslotText = date_i18n($time_format,$timestamp_start).(!$hide_end_times ? '&ndash;'.date_i18n($time_format,$timestamp_end) : '');
	endif;

	$appt = get_post( $appt_id );
	$appt_author = $appt->post_author;

	$appointment_calendar_id = get_the_terms( $appt_id,'booked_custom_calendars' );
	if (!empty($appointment_calendar_id)):
		foreach($appointment_calendar_id as $calendar):
			$calendar_id = $calendar->term_id;
			break;
		endforeach;
	else:
		$calendar_id = false;
	endif;
			
	if (!empty($calendar_id)): $calendar_term = get_term_by('id',$calendar_id,'booked_custom_calendars'); $calendar_name = $calendar_term->name; else: $calendar_name = false; endif;

	$day_name = date('D',$timestamp);
	$timeslotText = apply_filters('booked_emailed_timeslot_text',$timeslotText,$timestamp,$timeslot,$calendar_id);

	if (get_current_user_id() == $appt_author || current_user_can( 'manage_options' ) ):

		// Send an email to the Admin?
		if ($timestamp_start >= $current_timestamp):

			$email_content = get_option('booked_admin_cancellation_email_content');
			$email_subject = get_option('booked_admin_cancellation_email_subject');
			if ($email_content && $email_subject):
				$admin_email = booked_which_admin_to_send_email($calendar_id);

				if( $appt_author != 0 ){
					$user_name = booked_get_name( $appt_author );
					$user_data = get_userdata( $appt_author );
					$email = $user_data->user_email;
				}else{
					//guest email key: _appointment_guest_email
					$user_name = get_post_meta($appt_id, '_appointment_guest_name', true);
					$email = get_post_meta($appt_id, '_appointment_guest_email', true);
				}				
				$tokens = array('%name%','%date%','%time%','%customfields%','%calendar%','%email%','%title%');
				$replacements = array($user_name,date_i18n($date_format,$timestamp),$timeslotText,$cf_meta_value,$calendar_name,$email,$title);
				$email_content = htmlentities(str_replace($tokens,$replacements,$email_content), ENT_QUOTES | ENT_IGNORE, "UTF-8");
				$email_content = html_entity_decode($email_content, ENT_QUOTES | ENT_IGNORE, "UTF-8");
				$email_subject = str_replace($tokens,$replacements,$email_subject);
				booked_mailer( $admin_email, $email_subject, $email_content );
			endif;

		endif;

		/**
		 * Added by Rahul
		 */

		$appointment_order_id = get_post_meta($appt_id)['_booked_wc_appointment_order_id'][0];

		$order = new WC_Order($appointment_order_id);

		$used_coupons = $order->get_used_coupons();

		$account_funds_used = false;

		if( isset($used_coupons) ){
			foreach ($used_coupons as $key => $value) {
				if( stripos($value, 'wc_account_funds') !== false ){
					$account_funds_used = true;
					break;				
				}
			}
		}

		$order_cost = $order->order_total;

		if( $appt_author != 0 && !empty($order_cost) && (is_user_logged_in() && ( get_current_user_id() == $appt_author || current_user_can( 'manage_options' ) ) ) && $account_funds_used ){

			$items = $order->get_items();

			$order_cost = 0;

			foreach( $items as $item ){
				if( $item['item_meta']['Booking ID'][0] == $booking_id ){
					$order_cost = $item['item_meta']['_line_total'][0];
				}
			}

			/**
			 * Get current custom fund
			 */
			$previous_fund = get_user_meta( $appt_author,  'account_funds', true );
			
			/**
			 * Calculate total fund i.e. curent + refunded
			 */
			$total_fund = $previous_fund + $order_cost;

			/**
			 * Update customer account fund
			 */
			update_user_meta( $appt_author, 'account_funds', $total_fund );

			wc_add_notice( apply_filters( 'woocommerce_booking_cancelled_notice', __( 'Your booking was cancelled and refunded amount is added to your account.', 'woocommerce-bookings' ) ), apply_filters( 'woocommerce_booking_cancelled_notice_type', 'notice' ) );

		}else{
			//print_r("inside else");
		}

		do_action('booked_appointment_cancelled',$appt_id);
		wp_delete_post($appt_id,true);

	endif;
	wp_die();
}

add_action("wp_ajax_ovr_cancel_appointment", "ovr_cancel_appointment_callback");
add_action("wp_ajax_nopriv_ovr_cancel_appointment", "ovr_cancel_appointment_callback");

function ovr_booked_custom_fields($calendar_id = false){
	
	if ($calendar_id):
		$custom_fields = json_decode(stripslashes(get_option('booked_custom_fields_'.$calendar_id)),true);
		if (empty($custom_fields)): $custom_fields = json_decode(stripslashes(get_option('booked_custom_fields')),true); endif;
	else:
		$custom_fields = json_decode(stripslashes(get_option('booked_custom_fields')),true);
	endif;

	if (!empty($custom_fields)){
		return $custom_fields;
	}else{
		return false;
	}
}

/**
 * Added by Rahul
 * For improving search to booking
 */
function tinfini_search_where($query){
    global $pagenow, $wpdb, $booking;

	/* = Check search keyword is not empty */
    if( isset($_GET['s']) ){

    	//for custom sub menu page
    	//if ( is_admin() && $pagenow == 'admin.php' && isset($_GET['page']) && $_GET['page'] == 'ovr-bookings' && $_GET['s'] != '') {

    	//for post type listings page
    	if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'booked_appointments' && $_GET['s'] != '') {

    		/**
	    	 * Get Callender ids for ing work and underground boxing
	    	 */
    		$callender_terms = get_terms( array(
			    'taxonomy' => 'booked_custom_calendars',
			    'hide_empty' => false,
			    'fields' => 'ids'
			) );

			$callender_products = array();

			if( isset($callender_terms) && !empty($callender_terms) ){
				foreach ($callender_terms as $key => $value) {
					$callender_products[$value] = ovr_booked_custom_fields($value);
				}
			}

			$callender_product_ids = array();

			/**
	    	 * Get Products assosiated / linked with the callenders
	    	 */
			if( !empty($callender_products) ){
				foreach ($callender_products as $key => $value) {
					foreach ($value as $key => $value_two) {
						$key_name = $value_two['name'];
						//single-paid-service
						if( stripos($key_name, 'single-paid-service') !== false ){
							$callender_product_ids[$value_two['value']] = get_the_title($value_two['value']);
							break;
						}
					}
				}
			}

			$callender_pro_query = '';

    		/**
	    	 * Get Product ids from the linked products whose name matches the search keyword
	    	 */
	    	$resource_ids = array();

	    	if( isset($callender_product_ids) && count($callender_product_ids) > 0 && !empty($callender_product_ids) ){	    		

	    		foreach ($callender_product_ids as $key => $value) {
	    			if( stripos( strtolower($value), $_GET['s']) !== false ){
	    				$resource_ids[] = $key;
	    			}
	    		}

			}

			if( isset($resource_ids) && !empty($resource_ids) ){
				$resource_ids_string = "(".implode(',', $resource_ids).")";
			}else{
				$resource_ids_string = "(-1)";
			}

			/**
	    	 * Get Order Ids of the matched products
	    	 */
			$consulta = "SELECT order_id FROM {$wpdb->prefix}woocommerce_order_itemmeta woim 
			        LEFT JOIN {$wpdb->prefix}woocommerce_order_items oi 
			        ON woim.order_item_id = oi.order_item_id 
			        WHERE meta_key = '_product_id' AND meta_value IN $resource_ids_string
			        GROUP BY order_id;";
			
			$order_ids = $wpdb->get_col( $consulta );
			
			/**
	    	 * Default meta query if no results are found to give no results
	    	 */
			$meta_array = array();
			$meta_array['relation'] = 'AND';
				$meta_array[] = array(
					'key'     => '_booked_wc_appointment_order_id',
					'value'   => array(-1),
					'compare' => 'IN',
				);

			//for custom sub menu page
			//$query->set('meta_query', $meta_array);

			/**
	    	 * Set meta query for product name search and if no result is found set it to User 
	    	   info search meta query in else block
	    	 */
			if( isset($order_ids) && !empty($order_ids) ){

				$meta_array = array();
				$meta_array['relation'] = 'AND';
				$meta_array[] = array(
					'key'     => '_booked_wc_appointment_order_id',
					//'value'   => array( 84147, 84126 ),
					'value'   => $order_ids,
					'compare' => 'IN',
				);

				$order_id_string = implode(',', $order_ids);

				$order_results = $wpdb->get_results( "SELECT DISTINCT post_id FROM $wpdb->postmeta WHERE meta_key = '_booked_wc_appointment_order_id' AND meta_value IN($order_id_string)", ARRAY_A );

				$order_ids = '';

				foreach($order_results as $order_result){
		    		$order_ids .= $order_result['post_id'].',';
		    	}

		    	$order_ids = rtrim($order_ids,',');

				//for custom sub menu page
				//$query->set('meta_query', $meta_array);

				$query .= " OR $wpdb->posts.ID IN($order_ids)";

			}else{

				$meta_array = array();
				/**
				 * Search according to user details i.e. first name, last name, email, phone, address 1 & address 2
				 */
				$user_results = $wpdb->get_results( "SELECT DISTINCT user_id FROM $wpdb->usermeta WHERE (meta_key = 'billing_first_name' OR meta_key = 'billing_last_name' OR meta_key = 'billing_email' OR meta_key = 'billing_phone' OR meta_key = 'billing_address_1' OR meta_key = 'billing_address_2' ) AND LOWER(meta_value) LIKE '%$_GET[s]%'", ARRAY_A );
				$user_ids = null;

				$user_ids = array(-1);

				if( isset($user_results) && !empty($user_results) ){
					$user_ids = array();
					foreach($user_results as $user_result){
			    		$user_ids[] = $user_result['user_id'];
			    	}
				}

				if( $user_ids != null ){
					$meta_array['relation'] = 'AND';
					$meta_array[] = array(
						'key'     => '_appointment_user',
						//'value'   => array( 84147, 84126 ),
						'value'   => $user_ids,
						'compare' => 'IN',
					);

					$user_id_string = implode(',', $user_ids);

					//for custom sub menu page
					//$query->set('meta_query', $meta_array);

					$booking_results = $wpdb->get_results( "SELECT DISTINCT post_id FROM $wpdb->postmeta WHERE (meta_key = '_appointment_user' AND meta_value IN($user_id_string)) OR (meta_key = '_appointment_guest_name' AND meta_value like '%$_GET[s]%' ) ", ARRAY_A );

					$booking_ids = '';

					foreach($booking_results as $booking_result){
			    		$booking_ids .= $booking_result['post_id'].',';
			    	}
			    	$booking_ids = rtrim($booking_ids,',');

			    	$query .= " OR $wpdb->posts.ID IN($booking_ids)";
				}else{
					//$guest_booking_results = $wpdb->get_results( "SELECT DISTINCT post_id FROM $wpdb->postmeta WHERE meta_key = '_appointment_user' AND meta_value IN($user_id_string)", ARRAY_A );
				}				
			}
		}
	}

	return $query;
}
add_filter('posts_where', 'tinfini_search_where');
//posts_where
//pre_get_posts


/**
 * Columns for appointments / booking listings backend
 */
function booked_appointments_callback( $columns ) {

	unset($columns['wpseo-score']);
	unset($columns['wpseo-score-readability']);
	unset($columns['title']);

	unset($columns['author']);
	unset($columns['taxonomy-booked_custom_calendars']);
	unset($columns['date']);

	$new_columns = array(
		'cb' => '<input type="checkbox" />',
		'id' => __( 'ID' ),
		'booking_status' => __( 'Booking Status' ),
		'booked_product' => __( 'Booked Product' ),
		'booked_by' => __( 'Booked By' ),
		'taxonomy-booked_custom_calendars' => __( 'Custom Calendars' ),
		'order' => __( 'Order' ),
		'start_date' => __( 'Start Date' ),
		'attendance' => __( 'Attendance' ),
		'end_date' => __( 'End Date' ),
		'action' => __( 'Action' ),
		//'wpseo-score' => __( 'SEO' ),
		//'wpseo-score-readability' => __( 'Readability' ),
	);

	$merge_columns = array_merge($columns,$new_columns);

	return $merge_columns;
}
add_filter( 'manage_edit-booked_appointments_columns', 'booked_appointments_callback' ) ;
//booked_appointments

add_action( 'manage_booked_appointments_posts_custom_column', 'my_manage_movie_columns', 10, 2 );

function my_manage_movie_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {		

		/* If displaying the 'order_status' column. */
		case 'booking_status' :

			//$booking_order_id = get_post_meta($post_id, '_booked_wc_appointment_order_id', true);

			/*if( $booking_order_id ){

				$the_order = new WC_Order($booking_order_id);

				printf( '<mark class="%s tips" data-tip="%s">%s</mark>', sanitize_title( $the_order->get_status() ), wc_get_order_status_name( $the_order->get_status() ), wc_get_order_status_name( $the_order->get_status() ) );
			}*/

			$booking_post = get_post($post_id);

			$booking_status = $booking_post->post_status;

			//echo $booking_status; echo '<br/>';

			$status = ($booking_status != 'publish' && $booking_status != 'future' ? __('pending','booked') : __('approved','booked'));
				
			$status_class = $booking_status != 'publish' && $booking_status != 'future' ? 'pending' : 'approved';

			echo $status;		

			break;		

		/* If displaying the 'id' column. */
		case 'id' :

			echo $post_id;

			break;

		/* If displaying the 'booked product' column. */
		case 'booked_product' :

			$categories = get_the_terms($post_id, 'booked_custom_calendars');

			if( isset($categories) && !empty($categories) ){
				
				$callender_id = $categories[0]->term_id;

				$callender_products = ovr_booked_custom_fields($callender_id);

				if( isset($callender_products) ){

					if( !empty($callender_products) ){
						foreach ($callender_products as $key => $value) {
							/*echo '<pre>';
							print_r($value);
							echo '</pre>';*/
							$key_name = $value['name'];
							if( stripos($key_name, 'single-paid-service') !== false ){
								echo "Overthrow Boxing Class (".get_the_title($value['value']).")";
								break;
							}
						}
					}

				}
			}		

			break;

		/* If displaying the 'booked by' column. */
		case 'booked_by' :

			$user_id = get_post_meta($post_id, '_appointment_user', true);

			if( $user_id ){
				$user_info = get_userdata($user_id);
				$username = $user_info->user_login;
				$first_name = $user_info->first_name;
				$last_name = $user_info->last_name;
				echo $first_name.' '.$last_name;
			}else{
				//guest email key: _appointment_guest_email
				$guest_user = get_post_meta($post_id, '_appointment_guest_name', true);
				if( $guest_user ){
					echo $guest_user;
				}
			}
			break;

		/* If displaying the 'order' column. */
		case 'order' :

			$booking_order_id = get_post_meta($post_id, '_booked_wc_appointment_order_id', true);

			if( $booking_order_id ){

				$order = new WC_Order($booking_order_id);
				$order_status = $order->get_status();
				$booking_order_link = get_edit_post_link($booking_order_id);
				
				if( isset($order_status) && !empty($order_status) ){
					$order_status = ' - '.$order_status;
				}else{
					$order_status = '';
				}

				echo "<a target='_blank' href='$booking_order_link'>".$booking_order_id.$order_status."</a>";
			}			

			break;

		/* If displaying the 'start date' column. */
		case 'start_date' :

			$appt_date_value = date_i18n('Y-m-d',get_post_meta($post_id, '_appointment_timestamp',true));
			$appt_timeslot = get_post_meta($post_id, '_appointment_timeslot',true);
			$appt_timeslots = explode('-',$appt_timeslot);
			$appt_time_start = date_i18n('h:i A',strtotime($appt_timeslots[0]));

			echo $appt_date_value.', '.$appt_time_start;

			break;

		/* If displaying the 'attendance' column. */
		case 'attendance' :

			//$booking_order_id = get_post_meta($post_id, '_booked_wc_appointment_order_id', true);

			$id = $post_id;

			$attendence_status = get_post_meta($id, '_booking_attendance_status');

			$checked = '';

			if( isset($attendence_status) && !empty($attendence_status))
			{
				if($attendence_status[0] == 'yes')
				{
					$checked = 'checked';
					$checked_label = 'IN';
				}
				if($attendence_status[0] == 'no')
				{
					$checked = '';
					$checked_label = 'OUT';
				}				
			}
			else
			{
				$checked = '';
				$checked_label = 'OUT';
			}

			$attendance_column = '
				<div class="onoffswitch">
			        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch-'.$id.'" booking-id="'.$id.'"'. $checked .'>
			        <label class="onoffswitch-label" for="myonoffswitch-'.$id.'" >
			            <span class="onoffswitch-inner"></span>
			            <span class="onoffswitch-switch"></span>
			        </label>
			    </div>';

			echo $attendance_column;

			break;

		/* If displaying the 'end date' column. */
		case 'end_date' :

			//$booking_order_id = get_post_meta($post_id, '_booked_wc_appointment_order_id', true);

			$appt_date_value = date_i18n('Y-m-d',get_post_meta($post_id, '_appointment_timestamp',true));

			$appt_timeslot = get_post_meta($post_id, '_appointment_timeslot',true);
			$appt_timeslots = explode('-',$appt_timeslot);
			$appt_time_end = date_i18n('h:i A',strtotime($appt_timeslots[1]));

			echo $appt_date_value.', '.$appt_time_end;

			break;

		/* If displaying the 'cancel' column. */
		case 'action' :

			//$booking_order_id = get_post_meta($post_id, '_booked_wc_appointment_order_id', true);
			$current_timestamp = current_time('timestamp');
						
			$cancellation_buffer = get_option('booked_cancellation_buffer',0);
			
			if ($cancellation_buffer):

				if ($cancellation_buffer < 1){

					$time_type = 'minutes';
					$time_count = $cancellation_buffer * 60;

				} else {

					$time_type = 'hours';
					$time_count = $cancellation_buffer;

				}

				$buffered_timestamp = strtotime('+'.$time_count.' '.$time_type,$current_timestamp);

				$date_to_compare = $buffered_timestamp;

			else:

				$date_to_compare = current_time('timestamp');

			endif;

			$appt_date_value = date_i18n('Y-m-d',get_post_meta($post_id, '_appointment_timestamp',true));
			
			$appt_timeslot = get_post_meta($post_id, '_appointment_timeslot',true);
			
			$appt_timeslots = explode('-',$appt_timeslot);
			$appt_time_start = date_i18n('H:i:s',strtotime($appt_timeslots[0]));

			$appt_timestamp = strtotime($appt_date_value.' '.$appt_time_start);

			$date_to_convert = date_i18n('Y-m-d',$appt_timestamp);

			$appt_date_time = strtotime($date_to_convert.' '.date_i18n('H:i:s',strtotime($timeslots[0])));

			if ( $appt_date_time >= $date_to_compare ) {

				$table_html = '<a href="javascript:void(0)" data-appt-id="'.$post_id.'" class="button cancel">'.__('Cancel Appointment','booked').'</a>';

				echo $table_html;

			}

			break;

		/* Just break out of the switch statement for everything else. */
		default :

			//echo 'example';

			break;
	}
}

/**
 * Validate if user has subscription of monthly peroid
 */
function ovr_check_user_subscription($user_id){

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

/**
 * Check whether product is of subscription type
 */
function ovr_check_if_product_is_subscription($product_obj){

	if( $product_obj->get_type() == 'subscription' ){
		return true;
	}

	return false;
}

/**
 * Price discount for subscriptions
 */
function ovr_subscription_discount( $cart_object ) {
  global $woocommerce;

  if ( is_user_logged_in() ) {
    
    global $current_user;
    $user_id = $current_user->ID;

    //$user_id = 3044;

    if( ovr_check_user_subscription($user_id) ){
    	foreach($cart_object->cart_contents as $key => $value) {
    		if(!ovr_check_if_product_is_subscription($value['data'])){
    			$value['data']->price = $value['data']->price - $value['data']->price*0.1;
    		}			
	    }
    }    

  }
}
add_action( 'woocommerce_before_calculate_totals', 'ovr_subscription_discount' );

/**
 * Add random query string variable in js & css files url to avoid caching
 */
function ovr_css_js_random_version($url) {
	$random_ver = substr( md5(microtime()), rand(0, 26), 5 );
    return add_query_arg(array($random_ver => time()), $url);
}
add_filter( 'style_loader_src', 'ovr_css_js_random_version' );
add_filter( 'script_loader_src', 'ovr_css_js_random_version' );