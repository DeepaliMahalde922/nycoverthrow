<?php

/**
 * Enqueue the child theme styles and scripts
 */

function ovr_enqueue_scripts() {
	wp_register_style( 'child_style', get_stylesheet_directory_uri() . '/css/child_style.css', false, '1.2.7' );
    wp_enqueue_style( 'child_style' );

    wp_register_style( 'owl_carousel_style', get_stylesheet_directory_uri() . '/css/owl.carousel.css', false, '1.2.7' );
    wp_enqueue_style( 'owl_carousel_style' );
	
	wp_register_style( 'owl_theme', get_stylesheet_directory_uri() . '/css/owl.theme.css', false, '1.2.7' );
    wp_enqueue_style( 'owl_theme' );

	wp_register_style( 'owl_transitions', get_stylesheet_directory_uri() . '/css/owl.transitions.css', false, '1.2.7' );
    wp_enqueue_style( 'owl_transitions' );

	
    wp_register_script('owl_carousel_js', get_stylesheet_directory_uri().'/js/owl.carousel.js', array('jquery'),'1.3.8', false);
    wp_enqueue_script('owl_carousel_js');

	wp_register_script('jquery_custom', get_stylesheet_directory_uri().'/js/jquery.custom.js', array('jquery'),'1.3.9', false);
	wp_enqueue_script('jquery_custom');

	$site_url = site_url();

	wp_localize_script( 'jquery_custom', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234, 'site_url' => $site_url ) );	
}
add_action( 'wp_enqueue_scripts', 'ovr_enqueue_scripts', 11);


function ovr_admin_enqueue_scripts() {

	wp_register_style( 'animate_css', get_stylesheet_directory_uri() . '/css/animate.css', false, '1.2.7' );
	wp_enqueue_style( 'animate_css' );

	wp_register_style( 'ovr_admin_css', get_stylesheet_directory_uri() . '/css/ovr_admin.css', false, '1.3.3' );
	wp_enqueue_style( 'ovr_admin_css' );

	wp_register_script('bpopup_jquery', get_stylesheet_directory_uri().'/js/jquery.bpopup.min.js', array('jquery'),'1.6', false);
	wp_enqueue_script('bpopup_jquery');

	wp_register_script('admin_jquery', get_stylesheet_directory_uri().'/js/ovr_admin.js', array('jquery'),'1.6', false);
	wp_enqueue_script('admin_jquery');

	$site_url = site_url();

	wp_localize_script( 'admin_jquery', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234, 'site_url' => $site_url ) );
	
}
add_action( 'admin_enqueue_scripts', 'ovr_admin_enqueue_scripts', 11);

/**
 * Add random query string variable in js & css files url to avoid caching
 */
function ovr_css_js_random_version($url) {
	$random_ver = substr( md5(microtime()), rand(0, 26), 5 );
    return add_query_arg(array($random_ver => time()), $url);
}
add_filter( 'style_loader_src', 'ovr_css_js_random_version' );
add_filter( 'script_loader_src', 'ovr_css_js_random_version' );


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

				//$booking_cancel = '<th class="booking-cancel" scope="col"></th>';
				$booking_cancel = '';
				$booking_title = 'My Bookings';

			//}

			$table_html.= '<h2 id="ovr-my-bookings">'.$booking_title.'</h2><table class="'.$table_class.'">

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

					

					if( $display == 'backend-sub-menu' ){

						if( isset($booking_order_id) ){

							$order = new WC_Order($booking_order_id);			
						}
						
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
								<a href="javascript:void(0);">#'.$booking_order_id.'</a>
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

					//$table_html.= '<td class="action column-action booking-cancel">';

					$calendar_button_array = array(
						'atc_date_startend' => $atc_date_startend,
						'atc_time_start' => $atc_time_start,
						'atc_date_startend_end' => $atc_date_startend_end,
						'atc_time_end' => $atc_time_end,
					);
					booked_add_to_calendar_button($calendar_button_array,$cf_meta_value);

					//if ( apply_filters('booked_shortcode_appointments_allow_cancel', true, $appt['post_id']) && !get_option('booked_dont_allow_user_cancellations',false) ) {

						if ( $appt_date_time >= $date_to_compare ) {

							//$table_html.= '<a href="javascript:void(0)" data-appt-id="'.$appt['post_id'].'" class="button cancel">'.__('Cancel','booked').'</a>';

						}else{
							//$table_html.= 'can not cancel';
						}

						//do_action('booked_shortcode_appointments_buttons', $appt['post_id']);

					//$table_html.= '</td>';

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

	if( is_account_page() ):
		echo ovr_account_credit_html();

		wc_get_template( 'myaccount/my-address.php' );
	endif;

	ovr_show_appointmenst_html($user->data->ID, false, true, 'backend');

	return true;

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

	$current_user = wp_get_current_user();

	$response_status = 'null';

	if( get_current_user_id() == $appt_author || current_user_can('manage_options') || in_array('shop_manager', $current_user->roles) || in_array('booked_booking_agent', $current_user->roles) ):		

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

		if( $appt_author != 0 && (is_user_logged_in() && ( get_current_user_id() == $appt_author || current_user_can('manage_options') ) ) || in_array('shop_manager', $current_user->roles) || in_array('booked_booking_agent', $current_user->roles) ){

			/**
			 * Update customer account fund
			 */
			if( isset($_POST['refund']) && $_POST['refund'] === 'yes' ){
				/*
				$booked_calendars = wp_get_post_terms($appt_id, 'booked_custom_calendars', array('fields' => 'all'));

				$fwc_pro_cvalue = $fwc_user_camount = 0;

				$calendar_id = $booked_calendars[0]->term_id;

				if($calendar_id == 371){
					$fwc_product_id = 65021; // Ring Work
				}else{
					$fwc_product_id = 95547; //Underground Boxing
				}

				$fwc_pro_cvalue = get_post_meta( $fwc_product_id, 'fwc_credit_value', true );

				echo $fwc_pro_cvalue.'#Class Value';
				*/

				$fwc_pro_cvalue = 1;
				$fwc_user_camount = 0;

				$fwc_user_camount = get_user_meta( $appt_author, 'fwc_total_credit_amount', true );

				//echo $fwc_user_camount.'#User current credit';

				$fwc_effective_user_camount = $fwc_user_camount + $fwc_pro_cvalue;

				//echo $fwc_effective_user_camount.'#User effective credit';

				update_user_meta($appt_author, 'fwc_total_credit_amount', $fwc_effective_user_camount);

				$response_status = 'cancelled and refund';
			}else{
				//do nothing
				$response_status = 'cancelled';
			}

			wc_add_notice( apply_filters( 'woocommerce_booking_cancelled_notice', __( 'Your booking was cancelled and refunded amount is added to your account.', 'woocommerce-bookings' ) ), apply_filters( 'woocommerce_booking_cancelled_notice_type', 'notice' ) );

		}else{
			$response_status = 'issues';
		}

		do_action('booked_appointment_cancelled',$appt_id);
		wp_delete_post($appt_id,true);

	endif;

	echo $response_status;

	wp_die();
}

add_action("wp_ajax_ovr_cancel_appointment", "ovr_cancel_appointment_callback");
add_action("wp_ajax_nopriv_ovr_cancel_appointment", "ovr_cancel_appointment_callback");

/**
 * Get products linked to a callender
 */
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

			/*$postss = query_posts( array( 'post_type' => 'booked_appointments', 'category__and' => array(370), 'posts_per_page' => -1 ) );

			echo '<pre>';
			print_r($postss);
			echo '</pre>';*/

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

/* Sort posts in wp_list_table by column in ascending or descending order. */
function custom_post_order($query){
    /* 
        Set post types.
        _builtin => true returns WordPress default post types. 
        _builtin => false returns custom registered post types. 
    */
    $post_types = get_post_types(array('_builtin' => false), 'names');
    /* The current post type. */
    $post_type = $query->get('post_type');
    /* Check post types. */
    /*echo '<pre>';
    print_r($post_type);
    echo '</pre>';*/
    if($post_type == 'booked_appointments'){
        /* Post Column: e.g. title */
        //if($query->get('orderby') == ''){
            $query->set('orderby', 'ID');
        //}
        /* Post Order: ASC / DESC */
        //if($query->get('order') == ''){
            $query->set('order', 'DESC');
        //}
    }
}
add_action('pre_get_posts', 'custom_post_order');


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
					echo $guest_user; echo '<br/>';
					echo get_post_meta($post_id, '_appointment_guest_email', true);
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
//add_action( 'woocommerce_before_calculate_totals', 'ovr_subscription_discount' );

/**
 * Get portfolio by trainer name
 */
function ovr_get_portfolio_page_by_trainer_name($name = ''){
	
	global $wpdb;

	$available_trainers = array();

	$likenames = "SELECT *
	FROM $wpdb->posts
	WHERE 1=1
	  AND ((($wpdb->posts.post_title LIKE '%$name%')
	        OR ($wpdb->posts.post_content LIKE '%$name%')))
	  AND $wpdb->posts.post_type = 'portfolio_page'
	  AND (($wpdb->posts.post_status = 'publish'))
	ORDER BY $wpdb->posts.post_date DESC LIMIT 0,5 ";

	$results = $wpdb->get_results( $likenames );

	if( $results ){
		foreach ($results as $key => $value) {
			
			$available_trainers[$value->ID] = get_the_permalink($value->ID);
		}
	}

	return $available_trainers;
}

/**
 * Update Guest booking info when order is placed
 */
function ovr_update_booking_user_info($order_id){
    
    if (is_user_logged_in()){

	}else{

		$args = array(
			'post_type' => 'booked_appointments',
			'posts_per_page' => -1,
			'post_status' => 'any',
			'meta_key' => '_booked_wc_appointment_order_id',
			'meta_value' => $order_id,
		);

		$appointments_array = array();

		$bookedAppointments = new WP_Query($args);	

		if($bookedAppointments->have_posts()){

			while ($bookedAppointments->have_posts()):

				$bookedAppointments->the_post();

				//$booking_order_id = get_post_meta($post_id, '_booked_wc_appointment_order_id', true);

				$booking_order_id = $order_id;

				$post_id = get_the_ID();

				if( $booking_order_id ){

					$order = new WC_Order($booking_order_id);

					$customer_name = $order->billing_first_name.' '.$order->billing_last_name;
					$customer_email = $order->billing_email;

					if( isset($customer_name) && !empty($customer_name) && isset($customer_email) && !empty($customer_email) ){
						update_post_meta($post_id,'_appointment_guest_name', $customer_name);
						update_post_meta($post_id,'_appointment_guest_email', $customer_email);
					}else{
						update_post_meta($post_id,'_appointment_guest_name', 'not_found');
						update_post_meta($post_id,'_appointment_guest_email', 'not_found@not_found.com');
					}
				}else{
					update_post_meta($post_id,'_appointment_guest_name', 'order_not_found');
					update_post_meta($post_id,'_appointment_guest_email', 'order_not_found@order_not_found.com');
				}

			endwhile;

		}
		
		wp_reset_postdata();	
	}
}

add_action('woocommerce_thankyou', 'ovr_update_booking_user_info', 10, 1);

/**
 * Change Attendance status for orders
 */
if (!function_exists('ovr_get_attendance_status_callback')){
	function ovr_get_attendance_status_callback(){
		if( isset($_POST['booking_id']) )
		{
			$booking_id = $_POST['booking_id'];
			//$attandance_status = $_POST['attendace_status'];
			$status = get_post_meta($booking_id, '_booking_attendance_status',true);
			print_r($status);
		}
		wp_die();
	}
}
add_action( 'wp_ajax_ovr_get_attendance_status', 'ovr_get_attendance_status_callback');
add_action( 'wp_ajax_nopriv_ovr_get_attendance_status', 'ovr_get_attendance_status_callback');

/**
 * Backend options for booked
 */
function ovr_on_off_button( $calendar_id, $appt_id, $status ){

	$post_id = $appt_id;

	$status = get_post_meta($appt_id, '_booking_attendance_status',true);

	$checked = "";

	if( $status == 'yes' ){
	    $checked = " checked='checked' ";
	}

	$response = '<div class="ovr-btnwrap"><div class="ovr-btnwrap-inner onoffswitch admin_appointments">
                    <input'.$checked.' type="checkbox" booking-id="'.$appt_id.'" id="myonoffswitch-'.$appt_id.'" class="onoffswitch-checkbox" name="onoffswitch">
                    <label for="myonoffswitch-'.$appt_id.'" class="onoffswitch-label">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>';

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

		//$response .= '<div class="ovr-btnwrap-inner"><a href="javascript:void(0)" data-appt-id="'.$post_id.'" class="ovr_cancel_backend">'.__('Cancel Booking','booked').'</a></div>';
	}

	//$response .= '</div>';

	$response .= '<div class="ovr-btnwrap-inner">
		<a href="javascript:void(0)" data-appt-id="'.$post_id.'" data-refund="no" class="ovr_cancel_backend">'.__('Cancel Class','booked').'</a>
		<a href="javascript:void(0)" data-appt-id="'.$post_id.'" data-refund="yes" class="ovr_cancel_backend">'.__('Cancel and Refund','booked').'</a>
	</div></div>';

    echo $response;
}
add_action('booked_admin_calendar_buttons_before', 'ovr_on_off_button', 10, 3 );

/**
 * Covert change email notification with woocommerce template
 */
function ovr_email_change_email($email_change_email, $user, $userdata){
	$blog_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	
	$email_change_email['message'] = str_replace( '###USERNAME###', $user['user_login'], $email_change_email['message'] );
	$email_change_email['message'] = str_replace( '###ADMIN_EMAIL###', get_option( 'admin_email' ), $email_change_email['message'] );
	$email_change_email['message'] = str_replace( '###EMAIL###', $user['user_email'], $email_change_email['message'] );
	$email_change_email['message'] = str_replace( '###SITENAME###', $blog_name, $email_change_email['message'] );
	$email_change_email['message'] = str_replace( '###SITEURL###', home_url(), $email_change_email['message'] );

	global $woocommerce;

	$mailer = $woocommerce->mailer();

	$to = $email_change_email['to'];

	$blog_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

	$subject = sprintf( $email_change_email['subject'], $blog_name );

	$message_body = $email_change_email['message'];

	$message = $mailer->wrap_message( $subject, $message_body );

	/*= Client email, email subject and message */
	$mailer->send( $to, $subject, $message );

	return false;
}
add_filter( 'email_change_email', 'ovr_email_change_email', 99, 3 );

/**
 * Covert change password notification with woocommerce template
 */
function ovr_password_change_email($pass_change_email, $user, $userdata){
	$blog_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

	$pass_change_email['message'] = str_replace( '###USERNAME###', $user['user_login'], $pass_change_email['message'] );
	$pass_change_email['message'] = str_replace( '###ADMIN_EMAIL###', get_option( 'admin_email' ), $pass_change_email['message'] );
	$pass_change_email['message'] = str_replace( '###EMAIL###', $user['user_email'], $pass_change_email['message'] );
	$pass_change_email['message'] = str_replace( '###SITENAME###', $blog_name, $pass_change_email['message'] );
	$pass_change_email['message'] = str_replace( '###SITEURL###', home_url(), $pass_change_email['message'] );

	global $woocommerce;

	$mailer = $woocommerce->mailer();

	$to = $pass_change_email['to'];

	$subject = sprintf( $pass_change_email['subject'], $blog_name );

	$message_body = $pass_change_email['message'];

	$message = $mailer->wrap_message( $subject, $message_body );

	/*= Client email, email subject and message */
	$mailer->send( $to, $subject, $message );

	return false;
}
add_filter( 'password_change_email', 'ovr_password_change_email', 99, 3 );

/**
 * Alter booking prevent date after
 */
function ovr_booked_prevent_appointments_after(){
	$prevent_appointments_after = date('m/d/Y', strtotime('+1 weeks')); //11/25/2016

	return $prevent_appointments_after;
}
add_filter( 'option_booked_prevent_appointments_after', 'ovr_booked_prevent_appointments_after' );

/**
 * Add custom tab under booked profile for old bookings
 */
function ovr_booked_profile_tabs(){
	$tabs = array();

	// $tabs['archived'] = array(
	// 	'title' => esc_html__('Archived Booking', 'booked'),
	// 	'fa-icon' => 'fa-calendar-check-o',
	// 	'class' => 'archived-booking'
	// );

	$tabs['membership'] = array(
		'title' => esc_html__('Membership', 'booked'),
		'fa-icon' => 'fa fa-users',
		'class' => 'membership-booking'
	);

	$tabs['referral'] = array(
		'title' => esc_html__('Refer a friend', 'booked'),
		'fa-icon' => 'fa fa-bullhorn',
		'class' => 'referral-booking'
	);

	foreach($tabs as $slug => $name):
		echo '<li'.($name['class'] ? ' class="'.$name['class'].'"' : '').'><a href="#'.$slug.'"><i class="fa '.$name['fa-icon'].'"></i>'.$name['title'].'</a></li>';
	endforeach;
}
add_action( 'booked_profile_tabs', 'ovr_booked_profile_tabs' );

/**
 * Display old plugin bookings
 */
function ovr_booked_profile_tab_content(){
	/*
	echo '<div id="profile-archived" class="booked-tab-content bookedClearFix">';
		$wc_bookings = new WC_Booking_Order_Manager();
		$wc_bookings->my_bookings();
	echo '</div>';
	*/

	echo '<div id="profile-membership" class="booked-tab-content bookedClearFix">';
		$ovr_member_obj = new WC_Memberships_Members_Area();
		
		global $wp;

		if( $wp->query_vars['pagename'] == 'my-account' && $wp->query_vars['members-area'] ){
			$ovr_member_obj->render_members_area_content();
		}else{
			$ovr_member_obj->my_account_memberships();
		}
	echo '</div>';

	echo '<div id="profile-referral" class="booked-tab-content bookedClearFix">';
		echo "<h3 class='text-center refer-friend-title'>REFER A FRIEND</h3>";
		echo "<p class='text-center'>Get a class on us when your friend book a class!</p>";
		echo do_shortcode(' [WOO_GENS_RAF_ADVANCE guest_text="Login to invite your friends to Overthrow" share_text="Copy and Paste link to your friends!" friends_text="friends have joined."]');
	echo '</div>';

	?>
	<script>
	if(jQuery('#profile-membership h2').length==0){
	jQuery('#profile-membership').html("No membership");
	}
	</script>
	<?php
}
add_action( 'booked_profile_tab_content', 'ovr_booked_profile_tab_content' );

/**
 * Redirect to home page if user wants to open old booking class page
 */
function ovr_redirect2home() {
	if( is_single('47100') ){
		wp_safe_redirect( site_url('book-class'), 301 );
		exit();
	}
}
add_action( 'wp_head', 'ovr_redirect2home' );

/**
 * Add custom link under user listing action column
 */
function ovr_user_action_links($actions, $user_object) {
	$user_id = $user_object->ID;

	$myaccount_pageid = get_option( 'woocommerce_myaccount_page_id' );

	$login_as_link = get_permalink( $myaccount_pageid ).'?action=ovr_user_login_as&user='.$user_id;

	$actions['ovr_login_as'] = '<a href="'.wp_nonce_url($login_as_link, "ovr-switch-user" ).'" title="Login As">Login As</a>';

	return $actions;
}
add_filter('user_row_actions', 'ovr_user_action_links', 10, 2);

function ovr_switch_user() {
	$action = isset( $_GET['action'] ) ? $_GET['action'] : '';
	$user_id = isset( $_GET['user_id'] ) ? $_GET['user_id'] : '';

	switch ( $action ) {
		case 'ovr_user_login_as' :
			check_admin_referer( 'ovr-switch-user' );

			$user = get_user_by( 'id', (int) $_GET['user'] );

			$original_user_id = get_current_user_id();

			wp_set_current_user( $user->ID, $user->user_login );
			wp_set_auth_cookie( $user->ID, false );
			do_action( 'wp_login', $user->user_login );

			$secure = is_ssl();
			$secure = apply_filters( 'secure_auth_cookie', $secure, $user->ID );
			$secure_cookie = apply_filters( 'is_ba_secure_browse_as_cookie', false, $user->ID, $secure );
			setcookie( 'is_ovr_original_user_' . COOKIEHASH, $original_user_id, 0, SITECOOKIEPATH, COOKIE_DOMAIN, $secure_cookie, true );

			$myaccount_page_id = get_option('woocommerce_myaccount_page_id');

			wp_safe_redirect( get_permalink($myaccount_page_id) );
			
			exit();
		break;

		case 'restore_as_user' :
			check_admin_referer( 'is-ovr-restore-user' );

			if ( ! isset( $_COOKIE['is_ovr_original_user_' . COOKIEHASH] ) && $_COOKIE['is_ovr_original_user_' . COOKIEHASH] !== $_GET['user'] )
				die( __( 'Cheatin&#8217; uh?' ) );

			$current_user = wp_get_current_user();

			$user = get_user_by( 'id', (int) $_GET['user'] );

			wp_set_current_user( $user->ID, $user->user_login );
			wp_set_auth_cookie( $user->ID, false );
			do_action( 'wp_login', $user->user_login );

			setcookie( 'is_ovr_original_user_' . COOKIEHASH, ' ', time() - 31536000, SITECOOKIEPATH, COOKIE_DOMAIN );

			wp_safe_redirect( admin_url('users.php#user-'.$current_user->ID) );

			exit();
		break;
	}
}
add_filter( 'init', 'ovr_switch_user' );

function ovr_restore_back_user_notice_css() {
	$current_user = wp_get_current_user();

	if ( ! isset( $_COOKIE['is_ovr_original_user_' . COOKIEHASH] ) )
		return;

	echo '<style type="text/css">
		#browseas-notice {
			padding: 0 0.6em;
			margin: 5px 0 15px;
			border: 1px solid #e6db55;
			background-color: #ffffe0;
			color: #333;
			-moz-border-radius: 3px;
			-khtml-border-radius: 3px;
			-webkit-border-radius: 3px;
			border-radius: 3px;
		}
		#browseas-notice p {
			margin: 0.5em 0;
			padding: 2px;
		}
		#browseas-notice a {
			text-decoration: none;
			padding-bottom: 2px;
		}
	</style>';
}
add_action( 'wp_head', 'ovr_restore_back_user_notice_css', 1000 );

function ovr_restore_back_user_notice() {
	$current_user = wp_get_current_user();

	if ( ! isset( $_COOKIE['is_ovr_original_user_' . COOKIEHASH] ) )
		return;

	$original_user_id = $_COOKIE['is_ovr_original_user_' . COOKIEHASH];

	$original_user = get_user_by( 'id', $original_user_id );

	$back_url = wp_nonce_url( site_url( "?action=restore_as_user&amp;user=$original_user_id" ), 'is-ovr-restore-user' );

	echo "<div id='browseas-notice' class='updated'><p><strong>{$original_user->display_name}, you are browsing the site as {$current_user->display_name}. <a class='button' href='{$back_url}'>Back to your session.</a></strong></p></div>";
}
add_action( 'wp_head', 'ovr_restore_back_user_notice', 1000 );


function ovr_delete_restore() {
	if ( ! isset( $_COOKIE['is_ovr_original_user_' . COOKIEHASH] ) )
		return;
	setcookie( 'is_ovr_original_user_' . COOKIEHASH, "", time()-3600 );

	wp_clear_auth_cookie();
}
add_action( 'wp_logout', 'ovr_delete_restore' );

/**
 * Limit quantity for bookable product i.e. Ring Work[65021] & Underground Boxing[95547]
 */
function ovr_woocommerce_quantity_input_args( $args, $product ) {
	if($product->id == 95547 || $product->id == 65021){
		$args['min_value'] = 1;
		$args['max_value'] = 1;
	}

	return $args;
}
add_filter( 'woocommerce_quantity_input_args', 'ovr_woocommerce_quantity_input_args', 10, 2 );

/**
 * Limit quantity for bookable product i.e. Ring Work[65021] & Underground Boxing[95547]
 */
function ovr_woo_max_qty_update_cart_validation( $passed, $cart_item_key, $values, $quantity ) {
	global $woocommerce;

	$product_id = $values['product_id'];

	if($product_id == 95547 || $product_id == 65021){
		$woocommerce_max_qty = 1;

		$product_title = get_the_title($product_id);

		if( $quantity > $woocommerce_max_qty ) {
			wc_add_notice( sprintf( __( 'You can add a maximum of %1$s %2$s\'s.', 'woocommerce-max-quantity' ), $woocommerce_max_qty, $product_title), 'error');
			
			$passed = false;
		}
	}

	return $passed;

}
add_action( 'woocommerce_update_cart_validation', 'ovr_woo_max_qty_update_cart_validation', 1, 4 );

/**
 * Convert user account fund to fast credit amount
 */
function ovr_account_fund2_credit(){
	$member_users = array('clare.treacy.bohrer@gmail.com', 'andreajjadot@gmail.com', 'benjaminkaya123@gmail.com', 'fleuryvette@gmail.com', 'boncicd1@hotmail.com', 'ocampoemilio15@gmail.com', 'kelly.dabbah@gmail.com', 'vishal.manglani7@gmail.com', 'kydigregorio@gmail.com', 'andrei.i.marin@gmail.com', 'arib@yelp.com', 'kimlindegren@gmail.com', 'sorayastudio@gmail.com', 'ohaileigh@gmail.com', 'rupertmanderstam@gmail.com', 'parker.whipple@gmail.com', 'frankmwolfv@gmail.com', 'tvranish@mail.com', 'seanfinz@gmail.com', 'governor1118@gmail.com', 'maggiesmith182@gmail.com', 'alogen@gmail.com', 'meghan.dilillo@gmail.com', 'rikkileem@gmail.com', 'abennet@estee.com', 'Lindsaymazal@gmail.com', 'georgiawod@gmail.com', 'ginanalbone@yahoo.com', 'ohaileigh@gmail.com', 'paul@paulmaffi.com', 'dcoluccio@gmail.com', 'jonzim13@gmail.com', 'sabrina.dusi@hotmail.com', 'sivirichi.alexandre@gmail.com', 'delgrosso.lindsay@gmail.com', 'levit.leonid@gmail.com', 'lcoleman@students.pitzer.edu', 'willabodman@mac.com', 'maggietook@gmail.com', 'patrick@bullet.tv', 'cbillus@gmail.com', 'stormysuarez@gmail.com', 'elvisp@gmail.com', 'lydia@dazedmedia.com', 'strotherpt@gmail.com', 'anne.eiskowitz@gmail.com', 'jeffreymatisoff@gmail.com', 'sherieweldon@gmail.com', 'rustkm@gmail.com', 'alison.henry737@gmail.com', 'jobanksmusic@yahoo.com', 'cckotzin@gmail.com', 'gishil1207@gmail.com', 'afarmer17@gmail.com');

	$args = array( 
		'meta_query' => array(
			array(
				'key' => 'account_funds',
				'compare' => 'EXISTS',
			),
		) 
	);

	foreach(get_users($args) as $key => $value) {
		$account_funds = get_user_meta( $value->data->ID, 'account_funds', true );

		if( $account_funds && $account_funds > 0 ){
			$fast_credit = 0;

			if( in_array($value->data->user_email, $member_users) ){
				$fast_credit = ceil($account_funds/15);

				echo '<b>Member-</b> '.$value->data->display_name.'#'.$account_funds.'#'.$fast_credit.'<br/><br/>';
			}else{
				$fast_credit = ceil($account_funds/29);

				echo $value->data->display_name.'#'.$account_funds.'#'.$fast_credit.'<br/><br/>';
			}

			//update_user_meta($value->data->ID, 'fwc_total_credit_amount', $fast_credit);
		}
	}

	exit();
}
//add_action( 'wp_head', 'ovr_account_fund2_credit' );

/**
 * 'Use pack' button html on book class popup
 */
function ovr_use_pack_btn(){
	$btnn = '';

	if( is_user_logged_in() ){
		$fwc_credit_amount = get_user_meta( get_current_user_id(), 'fwc_total_credit_amount', true );

		if($fwc_credit_amount > 0){
			$btnn = '<button id="fwc-use-pack" class="btn-use-pack button button-primary" type="button">Use Class Pack</button>';
		}else{
			$btnn = '<button id="buy-pack" class="btn-use-pack button button-primary" type="button" data-href="'.get_permalink(27808).'">Buy Class Packs</button>';
		}
	}

	return $btnn;
}

/**
 * Account credit HTML
 */
function ovr_account_credit_html(){
	$account_funds = get_user_meta( get_current_user_id(),  'fwc_total_credit_amount', true );
	$account_funds = ($account_funds) ? $account_funds : '0';

	$outt = '<div id="profile-fund">
		<h2 class="ac-credit-heading">Class Package Credits</h2>
		<p class="ac-credit-para">You have <strong>'.$account_funds.'</strong> Class Credits in your account. <a href="'.get_permalink(27808).'" title="Buy Class Packs">Buy Class Packs</a></p>
	</div>';

	return $outt;
}

/**
 * Book class with use pack, condition check
 */
function ovr_before_creating_appointment(){
	if( isset($_POST['use_packk']) && $_POST['use_packk'] == 'yes' ){
		$response = ['error' => false];

		if( get_current_user_id() == $_POST['user_id'] ) {
			/*if( isset($_POST['paid-service-label---10424047']) ){
				$fwc_product_id = $_POST['paid-service-label---10424047']; // Underground Boxing
			}else{
				$fwc_product_id = $_POST['paid-service-label---3249416']; // Ring Work
			}*/

			if( isset($_POST['paid-service-label---10424047']) ){
				$fwc_product_id = $_POST['paid-service-label---10424047']; // Underground Boxing NK
			}else if(isset($_POST['paid-service-label---3249416'])){
				$fwc_product_id = $_POST['paid-service-label---3249416']; // Ring Work NK
			}else if(isset($_POST['paid-service-label---1979666'])){
				$fwc_product_id = $_POST['paid-service-label---1979666']; // Underground Boxing BK
			}else if(isset($_POST['paid-service-label---8834967'])){
				$fwc_product_id = $_POST['paid-service-label---8834967']; // Ring Work BK
			}else{
				$fwc_product_id = $_POST['paid-service-label---3249416']; // Ring Work
			}


			$_fwc_credit_purchase = get_post_meta( $fwc_product_id, '_fwc_credit_purchase', true );

			if( $_fwc_credit_purchase == 'yes' ){
				$fwc_pro_cvalue = get_post_meta( $fwc_product_id, 'fwc_credit_value', true );

				$fwc_user_camount = get_user_meta( get_current_user_id(), 'fwc_total_credit_amount', true );

				if( $fwc_pro_cvalue > 0 && ($fwc_user_camount >= $fwc_pro_cvalue) ){
					$fwc_effective_user_camount = $fwc_user_camount - $fwc_pro_cvalue;

					update_user_meta(get_current_user_id(), 'fwc_total_credit_amount', $fwc_effective_user_camount);

					$needed_keys = array (
						'action' => 'booked_add_appt',
						'appoinment' => 0,
						'calendar_id'	=> 370,
						'customer_type' => 'current',
						'date' => '2017-01-20',
						'is_fe_form' => true,
						'timeslot' => '0615-0715',
						'timestamp' => 1484892900,
						'user_id'	=> 513,
						'use_packk' => 'yes'
					);

					foreach ($_POST as $key => $value) {
						if( $needed_keys[$key] ) {
						    //do nothing
						}else{
							unset($_POST[$key]);
						}
					}
					ovr_custom_orders_using_class_packs($fwc_product_id);
				}else{
					$response['error'] =  'Insufficient Credit';
				}
			}else{
				$response['error'] =  'Class not allowed to book with Credit';
			}
		}else{
			$response['error'] =  'Unauthorized access';
			
		}

		if( $response['error'] && $response['error'] !== false ) {
			echo json_encode($response);
			exit();
		}
	}
}
add_action( 'booked_before_creating_appointment', 'ovr_before_creating_appointment' );

/**
 * Add booking meta, if book with Use Pack
 */
function ovr_new_appointment_created($post_id){
	if( isset($_POST['use_packk']) && $_POST['use_packk'] == 'yes' ){
		update_post_meta($post_id, 'ovr_bookedwith_pack', 'yes');

		setcookie('ovr_bookingid', $post_id, time() + (86400 * 30), '/'); // 86400 = 1 day
	}
}
add_action( 'booked_new_appointment_created', 'ovr_new_appointment_created' );

/**
 * Change date button for booking which booked with Use Pack
 */
function ovr_booked_shortcode_appointments_buttons($post_id){
	$ovr_bookedwith_pack = get_post_meta( $post_id, 'ovr_bookedwith_pack', true );

	$term_list = wp_get_post_terms($post_id, 'booked_custom_calendars', array("fields" => "names"));
	
	$red_val = '';

	if( !empty($term_list) ){

		$findNK   = 'NK';
		$findBK   = 'BK';
		$posNK = strpos($term_list[0], $findNK);
		$posBK = strpos($term_list[0], $findBK);

		if ($posNK !== false) {
			$page_id = 38522;
			$red_val = get_page_link($page_id);
		}
		else if ($posBK !== false) {
			$page_id = 119946;
			$red_val = get_page_link($page_id);
		}

	}

	if( $ovr_bookedwith_pack == 'yes' ){

		$edit_url = $red_val.'?app_id='.$post_id.'&app_action=edit&source=booked_wc_extension';
		echo '<a href="'.$edit_url.'" data-app-calendar="'.$edit_url.'" data-appt-id="'.$post_id.'" class="edit">Change Date</a>';
		
	}
}
add_action( 'booked_shortcode_appointments_buttons', 'ovr_booked_shortcode_appointments_buttons' );

/**
 * Booking confimation HTML
 */
function ovr_booking_confirmation(){
	global $current_user;

	$postID = $_COOKIE['ovr_bookingid'];

	if( empty($current_user) || empty($postID) ) {
		return false;
	}

	$firstname = $current_user->user_firstname;
	$lastname = $current_user->user_lastname;

	$out = '<div class="ovr-booking-conf"><h5>Hey '.$firstname.' '.$lastname.'!</h5>';

	$out .= '<h5>Your class has been booked at OVERTHROW NEW YORK!</h5>';

	$time_format = get_option('time_format');
	$date_format = get_option('date_format');

	// Booking day
	$appt_date_value = date_i18n('Y-m-d',get_post_meta($postID, '_appointment_timestamp',true));
	$appt_time_start = date_i18n('H:i:s',strtotime($appt_timeslots[0]));

	$appt_timestamp = strtotime($appt_date_value.' '.$appt_time_start);

	$today = date_i18n($date_format);

	$date_display = date_i18n($date_format, $appt_timestamp);

	if($date_display == $today){
		$day_name = '';
		$date_display = esc_html__('Today','booked');
	}else{
		$day_name = date_i18n('l', $appt_timestamp).', ';
	}

	// Booking time
	$appt_timeslot = get_post_meta($postID, '_appointment_timeslot',true);
	$timeslots = explode('-', $appt_timeslot);

	$time_start = date_i18n($time_format,strtotime($timeslots[0]));
	$time_end = date_i18n($time_format,strtotime($timeslots[1]));

	$timeslotText = 'at '.$time_start.' &ndash; '.$time_end;

	// Booking calendar
	$calendar_id = wp_get_post_terms( $postID, 'booked_custom_calendars' );

	$final_dattime = $day_name.$date_display.' '.$timeslotText;

	$out .= '<h5 class="booking-details"><span class="appt-block bookedClearFix approved">
		<i class="fa fa-calendar-o" aria-hidden="true"></i>
		<strong>Class:</strong> '.$calendar_id[0]->name.'
		<br/>
		<i class="fa fa-clock-o" aria-hidden="true"></i>'.$final_dattime.'</span></h5>';

	$out .= ovr_account_credit_html();

	$out .= '<h5>Make sure you you arrive about 15 minutes before class starts so you can get situated and your hands wrapped. No problem if you don’t have boxing gear, gloves are free to use and wraps are available for purchase.<br/>Can’t wait to see you at Overthrow!</h5>';

	$out .= '</div>';

	return $out;
}
add_shortcode( 'ovr_usepack_confirmation', 'ovr_booking_confirmation' );

/**
 * Order booking by booking date on my account page
 */
function ovr_order_booking_by_bookingdate($query){
	if( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'booked_appointments' && is_page(12037) ) {
		$query->set('meta_key', '_appointment_timestamp');
		$query->set('orderby', 'meta_value_num');
		$query->set('order', 'ASC');
	}
}
add_action( 'pre_get_posts', 'ovr_order_booking_by_bookingdate', 999999 );

// ADDING FILTER SO AS ANY CHANGE(WHILE UPDATING CLASS) IN CALENDAR MAY REFLECT IN MY ACCOUNT SECTION AS WELL
add_filter( 'booked_update_appointment_calendar', function() { return true; }, 999 );



/**
*function which used by plugin Booking to sync with google calendar
**/
function ovr_get_google_calender($year, $month, $day, $calendar_id, $timeslotText){
	

	$option_arr 		= 	get_option( '_caledenr_event_logs');
	$calenderarr 		= 	json_decode($option_arr, true);
	$date_t 			= 	$year.'-'.$month.'-'.$day;
	$available_trainer 	= 	$calenderarr[$calendar_id][$date_t];

	
	$html = '';
	if( isset($available_trainer) && is_array($available_trainer) && count($available_trainer) > 0 ){
		$twenty_four_format = 'not applicable';
		if(strpos($timeslotText, '&ndash;') !== false) {
			$exploded_time_slot = explode(' &ndash; ', $timeslotText);
			if( isset($exploded_time_slot) && is_array($exploded_time_slot) && count($exploded_time_slot) >= 2 ){
				
				$twenty_four_format = date_i18n("G:i",strtotime($exploded_time_slot[0]));
			
			}else{
				$twenty_four_format = 'not applicable';
			}								    
		}else{
			if ( strpos($timeslotText, 'pm') !== false || strpos($timeslotText, 'am') !== false ) {
			
				//$exploded_time_slot = explode(' &ndash; ', $timeslotText);
			    $twenty_four_format = date_i18n("G:i",strtotime($timeslotText));
			}else{
				$twenty_four_format = 'not applicable';
			}
		}
		if( $twenty_four_format != 'not applicable' && array_key_exists($twenty_four_format, $available_trainer) ){
			$trainer_info_exploded = explode('#', $available_trainer[$twenty_four_format]);
			if( isset($trainer_info_exploded) && is_array($trainer_info_exploded) && count($trainer_info_exploded) >= 3 ){
				$html.= '<div class="bookedClearFix trainer_info_wrapper" style="padding-left:15px;">';
				//$html .= 'Traner name: '.$trainer_info_exploded[2];
				$traner_name = $trainer_info_exploded[2];
				$trainer_html = '';
				if(function_exists('ovr_get_portfolio_page_by_trainer_name')){	
				
					if( isset($traner_name) && trim($traner_name) != '' ){
						$availabe_trainer_posts = ovr_get_portfolio_page_by_trainer_name($traner_name);		
						$trainer_img_html = '';
						$trainer_href = 'javascript:void(0)';
						$anchor_target = ' target="_blank" ';
						$cursor_class = ' cursor ';
						if( !empty($availabe_trainer_posts) ){
							$trainer_counter = 0;
							foreach ($availabe_trainer_posts as $key => $value) {
								if( $trainer_counter == 1 ){
									break;
								}
								$trainer_href = $value;
								$post_thumbnail_id = get_post_thumbnail_id( $key );
								if( isset($post_thumbnail_id) && $post_thumbnail_id != '' ){
									$profile_pic_src = wp_get_attachment_thumb_url( $post_thumbnail_id );
									$trainer_img_html.= '<a '.$anchor_target.' class="'.$cursor_class.'trainer_info" href="'.$trainer_href.'">';
									$trainer_img_html.= "<img src='$profile_pic_src' style='height:100px; width:100px;'>";
									$trainer_img_html.= '</a>';
								}else{
									$trainer_img_html.= '<a '.$anchor_target.' class="'.$cursor_class.'trainer_info" href="'.$trainer_href.'">';
									$trainer_img_html.= "<img src='https://www.gravatar.com/avatar/?d=mm' style='height:100px; width:100px;'>";
									$trainer_img_html.= '</a>';
								}
								
								$trainer_counter++;

								$trainer_html = '<div class="trainer_name"><label>';
								$trainer_html.= '<a '.$anchor_target.' class = "'.$cursor_class.'" href="'.$trainer_href.'">'.$traner_name.'</a>';
								$trainer_html.= '</label></div>';
								$trainer_html = $trainer_img_html.$trainer_html;
								$html.= $trainer_html;
								
							}
						}else{
							$cursor_class = ' ';
							$anchor_target = ' ';
							$trainer_img_html.= '<a class="trainer_info" href="'."javascript:void(0);".'">';
							$trainer_img_html.= "<img src='https://www.gravatar.com/avatar/?d=mm' style='height:100px; width:100px;'>";
							$trainer_img_html.= '</a>';
						}
					}
					
				}
				$html.= '</div>';
			}
		}
	}
	return $html;

}

/**
* Create an woocommerce order whenver any user use class pack book class
*/
function ovr_custom_orders_using_class_packs($fwc_product_id){

	$data['first_name'] = get_user_meta( get_current_user_id(), 'billing_first_name', true );
	$data['lat_name'] = get_user_meta( get_current_user_id(), 'billing_last_name', true );
	$data['company'] = get_user_meta( get_current_user_id(), 'billing_company', true );
	$data['address_1'] = get_user_meta( get_current_user_id(), 'billing_address_1', true );
	$data['address_2'] = get_user_meta( get_current_user_id(), 'billing_address_2', true );
	$data['city'] = get_user_meta( get_current_user_id(), 'billing_city', true );
	$data['state'] = get_user_meta( get_current_user_id(), 'billing_state', true );
	$data['postcode'] = get_user_meta( get_current_user_id(), 'billing_postcode', true );
	$data['country'] = get_user_meta( get_current_user_id(), 'billing_country', true );

	$address = apply_filters('woocommerce_my_account_my_address_formatted_address', $data);

	$order = wc_create_order(array('customer_id' => get_current_user_id()));

	// The add_product() function below is located in /plugins/woocommerce/includes/abstracts/abstract_wc_order.php
	$order->add_product( get_product($fwc_product_id), 1); 
	$order->set_address( $address, 'billing' );
	//
	$order->calculate_totals();
	$order->update_status("Completed", 'Class Pack', TRUE);

	/*Order Email*/

	$items 		= $order->get_items(); 
	$order_date = $order->order_date;
	$email_b = $order->billing_email;
	
	$billing_phone = get_user_meta( get_current_user_id(), 'billing_phone', true );
	$account_funds = get_user_meta( get_current_user_id(),  'fwc_total_credit_amount', true );
	$account_funds = ($account_funds) ? $account_funds : '0';

	$postID = $_COOKIE['ovr_bookingid'];
	if($postID){
		$time_format = get_option('time_format');
		$date_format = get_option('date_format');
		// Booking day
		$appt_date_value = date_i18n('Y-m-d',get_post_meta($postID, '_appointment_timestamp',true));
		$appt_time_start = date_i18n('H:i:s',strtotime($appt_timeslots[0]));
		$appt_timestamp = strtotime($appt_date_value.' '.$appt_time_start);
		$today = date_i18n($date_format);
		$date_display = date_i18n($date_format, $appt_timestamp);
		if($date_display == $today){
			$day_name = '';
			$date_display = esc_html__('Today','booked');
		}else{
			$day_name = date_i18n('l', $appt_timestamp).', ';
		}
		// Booking time
		$appt_timeslot = get_post_meta($postID, '_appointment_timeslot',true);
		$timeslots = explode('-', $appt_timeslot);
		$time_start = date_i18n($time_format,strtotime($timeslots[0]));
		$time_end = date_i18n($time_format,strtotime($timeslots[1]));
		$timeslotText = 'at '.$time_start.' &ndash; '.$time_end;
		$final_dattime = $day_name.$date_display.' '.$timeslotText;
	}

	global $woocommerce;
	$currency = get_woocommerce_currency_symbol();

	$outt = '<div id="profile-fund">
		<h2 class="ac-credit-heading">Class Package Credits</h2>
		<p class="ac-credit-para">You have <strong>'.$account_funds.'</strong> Class Credits in your account. <a href="'.get_permalink(27808).'" title="Buy Class Packs">Buy Class Packs</a></p>
	</div>';

	foreach ( $items as $item ) {

		$order_id 	= 	$item['order_id'];
		$total 		= 	$item['total'];
		$product_id = 	$item['product_id'];
		$quantity 	= 	$item['quantity'];
		$name 		= 	$item['name'];
		$_product 	= 	wc_get_product( $product_id );
		$pr_price 	= 	$_product->get_regular_price();


		$messageinner_html .='<tr>
			<td style="vertical-align: middle; padding: 8px; border: 1px solid rgb(221, 221, 221);">
				<p>
					'.$name.'
				</p>
				<ul>
					<li>
						<p><strong> Class: </strong></p>
						<p>
							'.$final_dattime.'
						</p>
					</li>
					<li>
						<p><strong> Calendar:</strong></p>
						<p>
							'.$name.'
						</p>
					</li>
				</ul>
			</td>
			<td style="vertical-align: middle; padding: 8px; border: 1px solid rgb(221, 221, 221);">'.$quantity.'</td>
			<td style="vertical-align: middle; padding: 8px; border: 1px solid rgb(221, 221, 221);">'.$currency.'0</td>
		</tr>';

	}

	$message_html = 'Hi there. Y our recent order at OVER THROW has been completed. Your order details are shown below for your reference:';

	$message_html .= '<h2>Order #'.$order_id.'</h2>';
	$message_html .= $outt;
	$message_html .= '<div class="table-container"><table class="table" style="border: 1px solid rgb(221, 221, 221); width: 100%; border-collapse: collapse;"><thead> <th>Product</th> <th>Quantity</th> <th>Price</th> </thead> <tbody>';

	$message_html .= $messageinner_html.'</tbody> </table></div>';

	$message_html .= '<div class="customer_details">
		<h3 style="font-weight: 700; font-size: 17px;">Customer Details</h3>
		<ul>
			<li>
				<strong>Email address: </strong> <a href="mailto:'.$order->billing_email.'">'.$order->billing_email.'</a>
			</li>
			<li>
				<strong>Phone </strong> <a href="tel:'.$billing_phone.'">'.$billing_phone.'</a>
			</li>
		</ul>
		<div class="billing_address" style="border: 1px solid rgb(221, 221, 221); padding: 30px 20px; margin: 5px 0px 0px; width: 100%;">
			<h3 style="font-weight: 700; font-size: 17px; margin: 0px 0px 10px;">Billing address</h3>
			<div class="address" style="max-width: 150px; margin: 0px; font-size: 14px; line-height: 20px;">
				'.$order->billing_first_name.' '.$order->billing_last_name.'
				'.$order->billing_address_1.' '.$order->billing_address_2.'<br/>
				'.$order->billing_city.' '.$order->billing_state.'<br/>
				'.$order->billing_postcode.' '.$order->billing_country.'
			</div>
		</div>
	</div>';


	
	/*= Create a mailer */
	$mailer = $woocommerce->mailer();
	$message_body = __( $message_html, 'Overthrow' );
	$message = $mailer->wrap_message( sprintf( __('Your OVERTHROW Order', 'Overthrow')), $message_body );

	/*= Client email, subject & message */
	  $result = $mailer->send(
	    $email_b,
	    sprintf( __( 'Order Status Created', 'OVERTHROW'  ) ),
	    $message
	  );

}

/**
*	Adding filter to hide quantity of a product in cart page if product is CLASS
**/
function ovr_wc_cart_item_class($cart_item_class, $cart_item, $cart_item_key){


	$product_id = $cart_item['product_id'];
	$_booked_appointment = get_post_meta($product_id, '_booked_appointment', true);

	if($_booked_appointment && $_booked_appointment == 'yes'){
		$cart_item_class .= ' cart_item_hide_qty';
	}
	return $cart_item_class;
}
add_filter( 'woocommerce_cart_item_class', 'ovr_wc_cart_item_class', 10, 3 );

/**
* Do not allow reschedulling of classes
**/
function ovr_reschedule_appt(){

	$response = array();

	if( isset($_REQUEST['requestFor']) 
		&& $_REQUEST['requestFor'] == 'ovr_reschedule_count' 
		&& isset($_REQUEST['appt_id']) 
		&& $_REQUEST['appt_id'] > 0 ){

		$result = update_post_meta( $_REQUEST['appt_id'], 'ovr_reschedule_count', 1 );

		if($result){
			$response['appt_id'] = $_REQUEST['appt_id'];
			$response['status'] = 'success';
			$response['message'] = 'Meta Updat successfully';
		}else {
			$response['status'] = 'error';
			$response['message'] = 'Error Updating post meta';
		}

	}else {
		$response['status'] = 'error';
		$response['message'] = 'Error in getting data';
	}
}
add_action('wp_ajax_ovr-reschedule-appt', 'ovr_reschedule_appt' ); // executed when logged in
add_action('wp_ajax_nopriv_ovr-reschedule-appt', 'ovr_reschedule_appt' );

/**
* Cancel membership and subscription together - ajax action
**/
function ovr_cancel_membership_subs(){

	if ( isset( $_POST['user_membership_id'] ) ) {

		$user_membership_id = (int) $_POST['user_membership_id'];

		$integration  = wc_memberships()->get_integrations_instance()->get_subscriptions_instance();
		$subscription = $integration->get_subscription_from_membership( $user_membership_id );

		$subscription_id = $subscription->id;
		$subscription_val = 'false';

		if($subscription_id){
			$subscription_val = 'ture';
			$subscription = wcs_get_subscription( $subscription_id );
			WCS_User_Change_Status_Handler::change_users_subscription( $subscription, 'cancelled' );	
		}else{
			$subscription_val = 'false';
		}
		

	}
	echo $subscription_val;
	exit();

}
add_action('wp_ajax_ovr-cancel-membership-subs', 'ovr_cancel_membership_subs' ); // executed when logged in
add_action('wp_ajax_nopriv_ovr-cancel-membership-subs', 'ovr_cancel_membership_subs' );


/**
* Cancel membership and subscription together - create cancel button
**/
function ovr_cancel_membership_and_subscription( $actions, $user_membership ){

	if( $user_membership->post->post_status == 'wcm-active'){
		$actions['ovr_cancel'] = array
	        (
	            'url' => 'javascript:void(0);',
	            'name' => 'Cancel',
	        );
	}

	return $actions;

}
add_filter( 'wc_memberships_my_account_my_memberships_actions', 'ovr_cancel_membership_and_subscription', 2, 10 );

/*Update Product gallery slider images*/

add_action( 'after_setup_theme', 'overthrow_setup' );
 
function overthrow_setup() {
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}


/**
* Check if appopointment already booked - ajax action
**/
function check_booked_new_appointment_form(){

	if ( isset( $_POST['date'] ) || isset( $_POST['timeslot'] ) || isset( $_POST['calendar_id'] ) ) {

		$return 	= 'true';

		if ( WC()->cart->get_cart_contents_count() != 0 ) {
 			
 	        $date 		= $_POST['date'];
 	        $title 		= $_POST['title'];
 	        $timeslot 	= $_POST['timeslot'];
 	        $calendar_id = $_POST['calendar_id'];

 	        $timeslot_parts = explode( '-', $timeslot );
 	        $timestamp = strtotime($date.' '.$timeslot_parts[0]);
 	        $this_timeslot_timestamp = ( isset($this_timeslot_timestamp) ? $this_timeslot_timestamp : false );

 	        $appt_date_time_before = apply_filters('booked_fe_appt_form_date_time_before', '', $this_timeslot_timestamp, $timeslot, $calendar_id );
 	        $appt_date_time_after = apply_filters( 'booked_fe_appt_form_date_time_after', '', $this_timeslot_timestamp, $timeslot, $calendar_id );

 	        $date_format = get_option('date_format');
 	        $time_format = get_option('time_format');

 	        $only_titles = get_option('booked_show_only_titles', false);
 	        $hide_end_titles = get_option('booked_hide_end_times');
 	        $all_day_text = esc_html__( 'All day', 'booked' );

 	        if ( $only_titles && ! $title || ! $only_titles ) {
 	        	if ( $timeslot_parts[0] === '0000' && $timeslot_parts[1] === '2400' ) {
 	        		$timeslotText = $all_day_text;
 	        	} else {
 	        		$timeslotText = date_i18n( $time_format, strtotime( $timeslot_parts[0] ) );

 	        		if ( ! $hide_end_titles ) {
 	        			$timeslotText .= ' &ndash; '. date_i18n( $time_format, strtotime( $timeslot_parts[1] ) );
 	        		}
 	        	}
 	        }

 	        if (!empty($calendar_id)): $calendar_term = get_term_by('id',$calendar_id,'booked_custom_calendars'); 
 	        	$calendar_name = $calendar_term->name;  else: $calendar_name = false; 
 	        endif;
 	        
 	        $appt_timeslot 	= $timeslotText ? $timeslotText : ''; 
 	        $appt_date_name = date_i18n( $date_format, strtotime( $date ) );
 	        $timeshedule 	= 'at '.$appt_timeslot.' on '.$appt_date_name;

 			global $woocommerce;
 		    $items = $woocommerce->cart->get_cart();

 	        foreach($items as $item => $values) { 
 				$cal_name = $values['booked_wc_appointment_cal_name'];
 				$timerange = $values['booked_wc_appointment_timerange'];

 				if($timeshedule == $timerange &&  $calendar_name == $cal_name){
 					$return = 'false';
 				}

 	        } 

 	        echo $return;
		}

	}
	exit();

}
add_action('wp_ajax_check_booked_new_appointment_form', 'check_booked_new_appointment_form' );
add_action('wp_ajax_nopriv_check_booked_new_appointment_form', 'check_booked_new_appointment_form' );


add_action( 'admin_menu', 'register_synclasess_page' );
function register_synclasess_page(){
	//add_menu_page( 'Sync Classes', 'Sync Classes', 'manage_options', 'syncclasses', 'synclass_submenu_page_callback','dashicons-book-alt');
	add_submenu_page('booked-appointments', 'Sync Classes', 'Sync Classes', 'manage_booked_options', 'sync-classes', 'synclass_submenu_page_callback');

}
function synclass_submenu_page_callback(){
	include("admin/syncsettion.php");
}


/**
* Update Events from Google Calender
**/
function update_classes_calender($syncdays){
	
	$return = 'false';

	include(get_stylesheet_directory().'/google-api-php-client/src/Google/autoload.php');
		
	$client = new Google_Client();
	$client->setApplicationName('OVERTHROW TRAINER NAMES');
	$client->setDeveloperKey('AIzaSyBqEOi10v3_Z24QKDZkfWrAUGMNqvSioOE');
	$ovr_cal = new Google_Service_Calendar($client);

	$terms = get_terms( 'booked_custom_calendars', array(
	 	'orderby'    => 'count',
	 	'hide_empty' => 0,
	 ) );

	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){

		$eachterm_arr = array();
		$syncdate 	  = 	date("Y-m-j");
		update_option( '_lastsynced_calender', $syncdate);
	    
	    foreach ( $terms as $term ) {

	    	$count 		= 	$syncdays;
	    	$date 		= 	date("Y-m-j");
	    	$date_key 	= 	date("Y-m-d");
	    	$cur_epch 	= 	strtotime($date);
	    	

	    	$datewise_arr = array();

	    	for ($i=0; $i < $count; $i++) {

	    		if($i != 0){
		    		$cur_epch = $cur_epch + 86400;
		    		$date = date('Y-m-j', $cur_epch);
		    		$date_key = date('Y-m-d', $cur_epch);
	    		}
	    		$available_trainer = array();

	    		$user_pass_date = $date.'T00:00:00+00:00';

	    		$params = array(
	    			'timeMin' => $user_pass_date,
	    			'singleEvents' => true,
	    			'orderBy' => 'startTime',
	    			'maxResults' => 48
	    		);

		     	$calendarId = '';
		     	$calendarId = get_field('_calender_id_custom', 'booked_custom_calendars_'.$term->term_id);
		     	
		     	if($calendarId == ''){
		     		$calendarId = 'overthrownewyork@gmail.com';
		     	}

				$events = $ovr_cal->events->listEvents($calendarId, $params);
				

				$defaultTimeZone = date_default_timezone_get();
				foreach ($events->getItems() as $event) {

					$eventDateStr = $event->start->dateTime;
					$eventEndDateStr = $event->end->dateTime;
					
					$temp_timezone = $event->start->timeZone;
					if( !empty($temp_timezone) ){
						$timezone = $temp_timezone;
					} else {
						$timezone = $events->timeZone;
					}
					$temp_end_timezone = $event->end->timeZone;
					if( !empty($temp_end_timezone) ){
						$timezone = $temp_end_timezone;
					} else {
						$timezone = $events->timeZone;
					}
					date_default_timezone_set($timezone);
					$google_event_time = date('G:i', strtotime($eventDateStr));
					$google_event_date = date('d-m-Y', strtotime($eventDateStr));
					$google_event_end_time = date('G:i', strtotime($eventEndDateStr));
					$google_event_end_date = date('d-m-Y', strtotime($eventEndDateStr));
					$user_pass_date = date('d-m-Y', strtotime($date));
					if($google_event_date == $user_pass_date){
						$available_trainer[$google_event_time] = $google_event_time.'#'.$google_event_end_time.'#'.$event->summary;
					}
				}
				date_default_timezone_set($defaultTimeZone);

				if(!empty($available_trainer)){
					$eachterm_arr[$term->term_id][$date_key] = $available_trainer;
				}

	    	}	
			
	    }

	}

	if(!empty($eachterm_arr)){

		$calender_arr = json_encode($eachterm_arr);
		update_option( '_caledenr_event_logs', $calender_arr);
		update_option( '_lastdate_calender', $date);

		$return = 'true';

	}

	return $return;
	
}
/**
* Update Events from Google Calender End
**/

/**
* Update Events from Google Calender When Booked end date and options end date not Matched
**/
function check_event_synced(){

	$caledenr_event_logs = get_option('_caledenr_event_logs',false); 
	$prevent_after   =  get_option('booked_prevent_appointments_after',false);
	$prevent_before  =  get_option( '_lastsynced_calender',false);

	$diff 	= abs(strtotime($prevent_before) - strtotime($prevent_after));
	$years 	= floor($diff / (365*60*60*24));
	$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
	$days 	= floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
	$syncdays = $days +1;

	$lastdate_bookedsetting = date('Y-m-j', strtotime($prevent_after));
	$lastdate_sync =  get_option( '_lastdate_calender',false);

	if($lastdate_bookedsetting != $lastdate_sync || $caledenr_event_logs == ''){
		$response_handle =  update_classes_calender($syncdays);

		if($response_handle == 'true'){
			update_option( '_synclass_genrate', 'System');
		}
	}
}
add_action('wp_head', 'check_event_synced');
/**
* Update Events from Google Calender When Booked end date and options end date not Matched End
**/


/**
* When user signs up for a membership, receive a separate emai
**/
function so_28348735_category_based_thank_you_message ( $order_id ){
    $order = wc_get_order( $order_id );
    $show = false;

    foreach( $order->get_items() as $item ) {
        // check if a product is in specific category
        if ( has_term( 'membership', 'product_cat', $item['product_id'] ) ) {
            $show = true;
            continue;
        }
    }

    if( $show ){

    	$membership_html = '<html><body> <div style="margin-bottom: 15px;">Welcome to Overthrow</div><br/>
    	<div style="margin-bottom: 15px;">Fighters Membership: You can now access your $15 Classes online, you will receive a 10% discount on personal training and open access to the gym from 6am-6pm or when no classes are taking place. The Overthrow Fight Plan is attached at the bottom of this email</div><br/>
    	<div style="margin-bottom: 15px;">If you would like to cancel your membership please do so 15 days prior to the start of your billing cycle otherwise it will auto renew.</div><br/>
    	<div style="margin-bottom: 15px;">Underground Membership: You will receive 3 class credits in your account. Please email overthrownewyork@gmail.com to schedule your Private Training session with the trainer of your choice after that you will receive a 15% discount on Private Training. Take advantage of open access to the gym from 6am-6pm. The Overthrow Fight Plan is attached at the bottom of this email.</div><br/>
    	<div style="margin-bottom: 15px;">Revolution Membership: You can now take up to two classes per day, will receive 20% off Private Training and open access to the gym from 6am-6pm. The Overthrow Fight Plan is attached at the bottom of this email.</div><br/>

    	<div style="margin-bottom: 15px;">Please click here to download <a href="http://staging.ultra-purpose.flywheelsites.com/wp-content/uploads/2017/09/New-Member-Workout.pdf" target="_blank">New Member Workout</a></div><br/>
    	</body>
    	</html>';


    	/*= Create a mailer */
    	global $woocommerce;
    	$mailer = $woocommerce->mailer();
    	$message_body = __( $membership_html, 'Overthrow' );
    	$message = $mailer->wrap_message( sprintf( __('New OVERTHROW Membership'.$order_id, 'Overthrow') ), $message_body );

		/*= Client email, subject & message */
		$result = $mailer->send(
			$order->billing_email,
			sprintf( __( 'New OVERTHROW Membership', 'OVERTHROW'  ), $order_id ),
			$message
		);

    }
}
add_action( 'woocommerce_thankyou', 'so_28348735_category_based_thank_you_message' );
/**
* End of when user signs up for a membership, receive a separate emai
**/

/*
	Fetch Data of Booking Appintment using curent user
*/
function ovr_booked_appointment($my_id){
	$historic = isset($atts['historic']) && $atts['historic'] ? true : false;

	$time_format = get_option('time_format');
	$date_format = get_option('date_format');
	$appointments_array = booked_user_appointments($my_id,false,$time_format,$date_format,$historic);
	$total_appts = count($appointments_array);
	$appointment_default_status = get_option('booked_new_appointment_default','draft');
	$only_titles = get_option('booked_show_only_titles',false);

	$bookedhtml = '';
	if (!isset($atts['remove_wrapper'])): 
		$bookedhtml = '<div id="booked-profile-page" class="booked-shortcode">'; endif;

		
		$bookedhtml .= '<div class="booked-profile-appt-list">';

			if ($historic):
				if ($total_appts):
					$bookedhtml_sub .= '<h4><span class="count">' . number_format($total_appts) . '</span> ' . _n('Past Appointment','Past Appointments',$total_appts,'booked') . '</h4>';
				else:
					$bookedhtml_sub .= '<p class="booked-no-margin">'.esc_html__('No past appointments.','booked').'</p>';
				endif;
			else:
				if ($total_appts):
					$bookedhtml_sub .= '<h4><span class="count">' . number_format($total_appts) . '</span> ' . _n('Upcoming Appointment','Upcoming Appointments',$total_appts,'booked') . '</h4>';
				else:
					$bookedhtml_sub .= '<p class="booked-no-margin">'.esc_html__('No upcoming appointments.','booked').'</p>';
				endif;
			endif;

			$bookedhtml .= $bookedhtml_sub;
		
			foreach($appointments_array as $appt):

				$today = date_i18n($date_format);
				$date_display = date_i18n($date_format,$appt['timestamp']);
				if ($date_display == $today){
					$date_display = esc_html__('Today','booked');
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
				$status = ($appt['status'] != 'publish' && $appt['status'] != 'future' ? esc_html__('pending','booked') : esc_html__('approved','booked'));
				$status_class = $appt['status'] != 'publish' && $appt['status'] != 'future' ? 'pending' : 'approved';
				$ts_title = get_post_meta($appt['post_id'], '_appointment_title',true);

				if ($timeslots[0] == '0000' && $timeslots[1] == '2400'):
					if ($only_titles && !$ts_title || !$only_titles):
						$timeslotText = esc_html__('All day','booked');
					endif;
					$atc_date_startend_end = date_i18n('Y-m-d',strtotime(date_i18n('Y-m-d',$appt['timestamp']) . '+ 1 Day'));
					$atc_time_end = '00:00:00';
				else :
					if ($only_titles && !$ts_title || !$only_titles):
						$timeslotText = (!get_option('booked_hide_end_times') ? esc_html__('from','booked').' ' : esc_html__('at','booked').' ') . $time_start . (!get_option('booked_hide_end_times') ? ' &ndash; '.$time_end : '');
					endif;
					$atc_date_startend_end = $atc_date_startend;
				endif;
			
					$bookedhtml .= '<span class="appt-block bookedClearFix '.(!$historic ? $status_class : 'approved').'" data-appt-id="'.$appt['post_id'].'">';
					if (!$historic):
						if ($appointment_default_status !== 'publish' && $appt['status'] !== 'future' || $appointment_default_status == 'publish' && $status_class == 'pending'):
							$bookedhtml .= '<span class="status-block">'.($status_class == 'pending' ? '<i class="booked-icon booked-icon-radio-unchecked"></i>' : '<i class="booked-icon booked-icon-radio-checked"></i>').'&nbsp;&nbsp;'.$status.'</span>';
						endif;
					endif;
					$bookedhtml .= (!empty($appt['calendar_id']) ? '<i class="booked-icon booked-icon-calendar"></i><strong>'.esc_html__('Calendar','booked').':</strong> '.$appt['calendar_id'][0]->name.'<br>' : '');

					$bookedhtml .= '<i class="booked-icon booked-icon-clock"></i>'.($ts_title ? '<strong>'.$ts_title.':</strong>&nbsp;&nbsp;' : '').$day_name.$date_display.'&nbsp;&nbsp;' . $timeslotText;

				$bookedhtml .= '</span>';

			endforeach;

		$bookedhtml .= '</div>';

	if (!isset($atts['remove_wrapper'])): $bookedhtml .= '</div>'; endif;

	wp_reset_postdata();

	return $bookedhtml;
}


/*
	User roll back function
*/
function cgc_ub_action_links($actions, $user_object) {

	$membership_info ='';
	$account_funds = get_user_meta( $user_object->ID,  'fwc_total_credit_amount', true );
	$userinfo = get_user_by( 'id', $user_object->ID );
	if($userinfo->first_name){
		$user_name 	= $userinfo->first_name . ' ' . $userinfo->last_name;
	}else if($userinfo->user_nicename){
		$user_name 	= $userinfo->user_nicename;
	}else{
		$user_name 	= $userinfo->user_login;
	}
	$user_email = $userinfo->user_email;
	$credits 	= $account_funds;

	$address_1 	= get_user_meta( $user_object->ID, 'billing_address_1', true );
	$address_2 	= get_user_meta( $user_object->ID, 'billing_address_2', true );
	$city 		= get_user_meta( $user_object->ID, 'billing_city', true );
	$state 		= get_user_meta( $user_object->ID, 'billing_state', true );
	$postcode 	= get_user_meta( $user_object->ID, 'billing_postcode', true );
	$country 	= get_user_meta( $user_object->ID, 'billing_country', true );
	$phone 		= get_user_meta( $user_object->ID, 'billing_phone', true );

	if($address_1 || $address_2){
		$address = $address_1.' '.$address_2.'<br/>';
	}
	if($city || $state){
		$address .= $city.' '.$state.'<br/>';
	}
	if($postcode || $country){
		$address .= $postcode.' '.$country.'<br/>';
	}
	$adrs_html = '';
	if($address){
		$adrs_html = "<div><b>Address:  </b> <span>".$address."</span></div>";
	}

	/*Membership*/
	global $woocommerce;
	$user_memberships          = wc_memberships()->get_user_memberships_instance()->get_user_memberships( $user_object->ID );
	$can_edit_user_memberships = current_user_can( 'manage_woocommerce' );

	if ( ! empty( $user_memberships ) ) : $plan_links = array(); 

		foreach ( $user_memberships as $user_membership ) : 

			if ( $user_membership->get_plan() ) :

				$statuses = wc_memberships_get_user_membership_statuses();
				$status   = 'wcm-' . $user_membership->get_status();
				$status   = isset( $statuses[ $status ]['label'] ) ? '(' . esc_html( $statuses[ $status ]['label'] ) . ')' : '';

				$plan_label   = is_rtl() ? wp_kses_post( $status . ' <strong>' . $user_membership->get_plan()->get_name() . '</strong>' ) : '<strong>' . $user_membership->get_plan()->get_name() . '</strong><span>'.$status.'</span> ' ;
				$plan_links[] = true === $can_edit_user_memberships ? $plan_label : $plan_label;

			endif;
		endforeach; 

		if ( ! empty( $plan_links ) ) :
			$membership_info .=  wc_memberships_list_items( $plan_links, __( 'and', 'woocommerce-memberships' ) );
		else :
		 	$membership_info .= 'This user is already a member of every plan.';
		endif;

	else : 
		 $membership_info .=  'This user has no memberships yet.';
	endif;


	$actions['edit_badges'] = "<a data-user_id=".$user_object->ID." class='cgc_ub_edit_badges' href='javascript:void(0)'>
			Instant View
		</a><div id='".$user_object->ID."_instant-info' class='instant-info' style='display:none;'>
			<p class='booked-title-bar'><small>Instant View</small></p>
			<span class='button b-close'><span>X</span></span>
			<div class='user_info'><b>Username:  </b> <span>".$user_name."</span></div>
			<div class='user_info'><b>Contact Info Email:  </b><span>".$user_email."</span></div>
			<div class='user_info'><b>Phone Number:  </b> <span>".$phone."</div>
			<div class='Credits user_info'><b>Credits:  </b> <div class='detailed-infos'>".ovr_account_credit_html()."</div></div>
			<div class='user_info'><b>Membership:  </b> <div class='detailed-infos'>".$membership_info."</div></div>
			<div class='user_info'><b>My Bookings:  </b> <div class='detailed-infos'>".ovr_booked_appointment($user_object->ID)."</div></div>
			".$adrs_html."
		</div>";

	return $actions;

}
add_filter('user_row_actions', 'cgc_ub_action_links', 10, 3);



function membership_credits() {

	include("admin/ug_credits.php");
	
	/*Expire Class packs after a period of time*/
	include("admin/packages_expire.php");

}
add_action( 'wp_head', 'membership_credits', 1000 );


/*
	Class packs should expire after a period of time only Packages
*/

/*Adding custom field for Expire days*/
function woo_add_custom_general_fields() {

	global $woocommerce, $post;

	$pid = $post->ID;
	$post_type = $post->post_type;

	if( $post_type != 'product' ) {
		return false;
	}
  
	$term_list = wp_get_post_terms($post->ID, 'product_cat');

	$flag = 'false';
	if(!empty($term_list)){
		foreach( $term_list as $term ) {
		  	if($term->slug == 'packages'){
		  		$flag = 'true';
			}
		}
	}
	if($flag == 'false'){
		return false;
	}

	$outhtml = '<div class="options_group">';

	// Number Field
	woocommerce_wp_text_input( 
		array( 
			'id'                => '_trips_package_expired', 
			'label'             => __( 'Expire Days', 'woocommerce' ), 
			'placeholder'       => '', 
			'desc_tip'      => 'true',
			'description'       => __( 'Enter days to expire product.', 'woocommerce' ),
			'type'              => 'number', 
			'custom_attributes' => array(
					'step' 	=> 'any',
					'min'	=> '0'
				) 
		)
	);
	
	$outhtml .= '</div>';
	
}
add_action( 'woocommerce_product_options_general_product_data', 'woo_add_custom_general_fields' );

/*Saving custom field for Expire days Only Packages*/
function fwc_add_custom_general_fields_save( $post_id ){
	
	// Number Field
	$woocommerce_number_field = $_POST['_trips_package_expired'];
	if( !empty( $woocommerce_number_field ) )
		update_post_meta( $post_id, '_trips_package_expired', esc_attr( $woocommerce_number_field ) );
}
add_action( 'woocommerce_process_product_meta', 'fwc_add_custom_general_fields_save' );


/*
	Underground Membership Expire
*/
function ovr_underground_membership_expire($retrieve_data){
	$tablehtml1 = '';

	$classname 	= 'membership-sect';
	$hide 		= 'display-none expire-logs_sect';
	$titlediv 	= 'Membership History';

	$table_class = 'wp-list-table widefat fixed striped posts';
	$tablehtml1 = '<div id="'.$classname.'" class="'.$hide.'"><h2 id="ovr-my-bookings">'.$titlediv.'</h2><table class="'.$table_class.'">
		<thead>
			<tr>
				<th class="booking-id" scope="col">ID</th>
				<th class="booked-product" scope="col">Username</th>
				<th class="booking-attendance-status" scope="col">Class Pack Name</th>
				<th class="order-number" scope="col">Class Pack Start</th>
				<th class="booking-start-date" scope="col">Class Pack End Date</th>
				<th class="booking-attendance-status" scope="col">Before Adding Credit </th>
				<th class="booking-end-date" scope="col">No. of Credit Added </th>
				<th class="booking-end-date" scope="col">Effective Credit </th>
				<th class="booked-product" scope="col">Total Credit</th>
				<th class="booking-status" scope="col">Expire Status</th>
			</tr>
		</thead>
	<tbody>';
	$cnt = 1;
	foreach ($retrieve_data as $retrieved_data){ 
		$id 				=  $retrieved_data->id;
		$user_id 			=  $retrieved_data->user_id;
		$created_date 		=  $retrieved_data->created_date;
		$end_date 			=  $retrieved_data->end_date;
		$before_credit 		=  $retrieved_data->current_credits;
		$affected_credits 	=  $retrieved_data->affected_credits;
		$expire 			=  $retrieved_data->expire;
		$package_details 	=  $retrieved_data->package_details;
		$class_packs 		=  $retrieved_data->class_packs;
		$new_credit 		=  $retrieved_data->new_credit;
		$total_credits 		=  $retrieved_data->total_credits;

		
		$user_info 	= get_userdata($user_id);
		$first_name = $user_info->first_name;
		$last_name 	= $user_info->last_name;
		$accountcredits =   get_user_meta( $user_id,  'fwc_total_credit_amount', true );

		$productname = get_the_title($package_details);

		$tablehtml1 .='<tr><td class="order-number">'.$cnt.'</td>
			<td class="booking-userinfo">'.$first_name.' '.$last_name.'</td>
			<td class="booking-prduct">'.$productname.'</td>
			<td class="booking-start-date">'.$created_date.'</td>
			<td class="booking-end-date">'.$end_date.'</td>
			<td class="booking-prduct">'.$before_credit.'</td>
			<td class="booking-new_credit">'.$new_credit.'</td>
			<td class="booking-affected_credits">'.$affected_credits.'</td>
			<td class="booking-total_credits">'.$total_credits.'</td>
			<td class="booking-status">'.$expire.'</td></tr>';

		$cnt++;
	}

	$tablehtml1 .= '</tbody></table></div>';
	
	return $tablehtml1;
}

/**
* Saving expiring date for class pack
**/
function so_28348735_packages_based_thank_you_message ( $order_id ){
    $order = wc_get_order( $order_id );

    foreach( $order->get_items() as $item ) {
        // check if a product is in specific category
        if ( has_term( 'packages', 'product_cat', $item['product_id'] ) ) {
        	$classpack_credits = '';
        	$classpack_credits = get_post_meta($order_id, '_classpack_credits_added', 'true');

        	if($classpack_credits != 'true'){
	        	$package_log 	 =  array();
	        	$package_expired = '';
	 			$package_expired = 	get_post_meta($item['product_id'], '_trips_package_expired', true); 
	        	$cur_id 		= 	get_current_user_id();
	 			$new_credits 	= 	get_post_meta($item['product_id'], 'fwc_credit_amount', true); 
	 			$accountcredits =   get_user_meta( get_current_user_id(),  'fwc_total_credit_amount', true );
	        	$order_date 	= 	$order->order_date;
	 			$add_date 		= 	$order_date;
	 			//echo $deadate 		= 	date("Y-m-d", strtotime($order_date)) . " +".$package_expired." days";
	 			$_deadate 		= 	strtotime(date("Y-m-d", strtotime($order_date)) . " +".$package_expired." days");
	 			$deadate = date("Y-m-d", $_deadate);
	 			$product_id 	= 	$item['product_id'];

	 			$logcredit 		=   get_user_meta( get_current_user_id(),  'fwc_total_credit_amount_crarr', true );
	 			$logcreditarr 	=   json_decode($logcredit);
	 			
	 			foreach ($logcreditarr->$order_id->$product_id as $key => $value) {
	 				$affected_credits =  $key;
	 				$before_credit =  $key - $value;
	 			}
	 			
	 			if($package_expired != ''){
		        	global $wpdb;
		        	$tablename = $wpdb->prefix.'credits_log';
		        	$wpdb->insert( $tablename , array(
		        	    'user_id' 		=> $cur_id, 
		        	    'created_date'  => $add_date,
		        	    'modified_date' => '',
		        	    'end_date' 		=> $deadate,
		        	    'increment' 	=> 1, 
		        	    'new_credit' 	=> $new_credits,
		        	    'class_packs' 	=> 1,
		        	    'expire' 		=> 'false',
		        	    'current_credits' 	=> $before_credit, 
		        	    'affected_credits' 	=> $affected_credits,
		        	    'total_credits' 	=> 0,
		        	    'package_details' 	=> $product_id
		        	));

				}
        	}

        	
        }
    }

    update_post_meta($order_id, '_classpack_credits_added', 'true');

}
add_action( 'woocommerce_thankyou', 'so_28348735_packages_based_thank_you_message' );
/*
	End Class packs should expire after a period of time
*/


function ovr_class_pack_expire($retrieve_data, $classname, $titlediv, $hide){
	$tablehtml1 = '';
	$classname 	= 'classpack-sect';
	$titlediv 	= 'Class Pack History';
	$hide 		= 'expire-logs_sect';

	$table_class = 'wp-list-table widefat fixed striped posts';
	$tablehtml1 = '<div id="'.$classname.'" class="'.$hide.'"><h2 id="ovr-my-bookings">'.$titlediv.'</h2><table class="'.$table_class.'">
		<thead>
			<tr>
				<th class="booking-id" scope="col">ID</th>
				<th class="booked-product" scope="col">Username</th>
				<th class="booking-attendance-status" scope="col">Class Pack Name</th>
				<th class="order-number" scope="col">Class Pack Start</th>
				<th class="booking-start-date" scope="col">Class Pack End Date</th>
				<th class="booking-end-date" scope="col">Credits Added</th>
				<th class="booked-product" scope="col">Credits Used</th>
			</tr>
		</thead>
	<tbody>';
	$cnt = 1;
	foreach ($retrieve_data as $retrieved_data){ 
		$id 				=  $retrieved_data->id;
		$user_id 			=  $retrieved_data->user_id;
		$created_date 		=  $retrieved_data->created_date;
		$end_date 			=  $retrieved_data->end_date;
		$before_credit 		=  $retrieved_data->current_credits;
		$affected_credits 	=  $retrieved_data->affected_credits;
		$expire 			=  $retrieved_data->expire;
		$package_details 	=  $retrieved_data->package_details;
		$class_packs 		=  $retrieved_data->class_packs;
		$new_credit 		=  $retrieved_data->new_credit;
		$total_credits 		=  $retrieved_data->total_credits;

		$user_info 	= get_userdata($user_id);
		$first_name = $user_info->first_name;
		$last_name 	= $user_info->last_name;
		$accountcredits =   get_user_meta( $user_id,  'fwc_total_credit_amount', true );

		$productname = get_the_title($package_details);

		$tablehtml1 .='<tr><td class="order-number">'.$cnt.'</td>
			<td class="booking-userinfo">'.$first_name.' '.$last_name.'</td>
			<td class="booking-prduct">'.$productname.'</td>
			<td class="booking-start-date">'.$created_date.'</td>
			<td class="booking-end-date">'.$end_date.'</td>
			<td class="booking-new_credit">'.$new_credit.'</td>
			<td class="booking-total_credits">'.$total_credits.'</td></tr>';

		$cnt++;
	}

	$tablehtml1 .= '</tbody></table></div>';
	
	return $tablehtml1;
}

function ovr_admin_enqueue_scripts_datable() {

	wp_register_style( 'bootstrap_css', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', false, '1.2.7' );
	wp_enqueue_style( 'bootstrap_css' );
	
	wp_register_style( 'dataTables_css', get_stylesheet_directory_uri() . '/css/dataTables.bootstrap.min.css', false, '1.2.7' );
	wp_enqueue_style( 'dataTables_css' );

	wp_register_script('dataTables_jquery', get_stylesheet_directory_uri().'/js/jquery.dataTables.min.js', array('jquery'),'1.6', false);
	wp_enqueue_script('dataTables_jquery');

	wp_register_script('dataTables_btjquery', get_stylesheet_directory_uri().'/js/dataTables.bootstrap.min.js', array('jquery'),'1.6', false);
	wp_enqueue_script('dataTables_btjquery');
	
}


/**
 * Create the menu item.
 */
function ovr_credits_expire_create_menu() {
	
	$my_page = add_submenu_page( 'woocommerce', 'Credits Expire History', 'Credits Expire History', 'manage_options', 'credits_expire_history', 'ovr_credits_expire_settings_page');
	
	add_action( 'load-' . $my_page, 'ovr_load_admin_js' );

}
add_action('admin_menu', 'ovr_credits_expire_create_menu', 99);


function ovr_load_admin_js(){
    
    add_action( 'admin_enqueue_scripts', 'ovr_admin_enqueue_scripts_datable');
}



function ovr_credits_expire_settings_page(){

	
	$today_date = date("Y-m-d");
	global $wpdb;
	$table_name = $wpdb->prefix . "credits_log";
	
	$retrieve_data = $wpdb->get_results( "SELECT * FROM $table_name WHERE class_packs = '1'" );
	$tablehtml = '
		

		<div id="expire-packmember-logs">
			<div class="expire-logs" style="margin: 35px 0;">
				<a href="javascript:void(0)" data-rel="classpack-sect" class="button button-primary">ClassPack Logs</a>
				<a href="javascript:void(0)" data-rel="membership-sect" class="button">Underground Membership Logs</a>
			</div>';


	if(!empty($retrieve_data)){
		
		$tablehtml .= ovr_class_pack_expire($retrieve_data);
	}

	$retrieve_data1 = $wpdb->get_results( "SELECT * FROM $table_name WHERE class_packs = '0'" );
	if(!empty($retrieve_data1)){
		$tablehtml .= ovr_underground_membership_expire($retrieve_data1);
	}

	$tablehtml .= '</div>';
	echo $tablehtml;
	
}


/*Primary Location Show on Single Product Page.*/
function primary_location_before_add_to_cart_button(){
	//template for this is in storefront-child/woocommerce/single-product/product-attributes.php
	global $product;
	$has_row    = false;

	$attributes = $product->get_attributes();
	 
	?>
	<div class="product_attributes">
	 
		<?php foreach ( $attributes as $attribute ) :
	 
			if($attribute['name'] == 'Primary Location'){
				
				$values = wc_get_product_terms( $product->get_id(), $attribute['name'], array( 'fields' => 'names' ) );
				$att_val = apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );
				
				if(!empty($attribute['options'])){
					$select_html = '<select name="attribute_primary_location">';
					foreach ($attribute['options'] as $key => $value) {
						$select_html .= '<option value="'.$value.'">'.$value.'</option>';
					}
					$select_html .= '</select>';
				}
				echo '<div class="col">
					<label class="att_label">'.wc_attribute_label( $attribute['name'] ).'</label>
					<div class="att_value">'.$select_html.'</div>
				</div>';
			}
	 
		endforeach; 
	 
	echo '</div>';

	echo ob_get_clean();

}

add_action( 'woocommerce_before_add_to_cart_button', 'primary_location_before_add_to_cart_button' );



// Save the custom product custom field data in Cart item
add_action( 'woocommerce_add_cart_item_data', 'save_in_cart_my_custom_product_field', 10, 2 );
function save_in_cart_my_custom_product_field( $cart_item_data, $product_id ) {
    if( isset( $_POST['attribute_primary_location'] ) ) {
        $cart_item_data[ 'attribute_primary_location' ] = $_POST['attribute_primary_location'];

        // When add to cart action make an unique line item
        $cart_item_data['unique_key'] = md5( microtime().rand() );
        WC()->session->set( 'custom_data', $_POST['attribute_primary_location'] );
    }
    return $cart_item_data;
}



// Render the custom product custom field in cart and checkout
add_filter( 'woocommerce_get_item_data', 'render_custom_field_meta_on_cart_and_checkout', 10, 2 );
function render_custom_field_meta_on_cart_and_checkout( $cart_data, $cart_item ) {

    $custom_items = array();

    if( !empty( $cart_data ) )
        $custom_items = $cart_data;

    if( $custom_field_value = $cart_item['attribute_primary_location'] )
        $custom_items[] = array(
            'name'      => __( 'Primary Location', 'woocommerce' ),
            'value'     => $custom_field_value,
            'display'   => $custom_field_value,
        );

    return $custom_items;
}


// Add the the product custom field as item meta data in the order + email notifications
add_action( 'woocommerce_add_order_item_meta', 'tshirt_order_meta_handler', 10, 3 );
function tshirt_order_meta_handler( $item_id, $cart_item, $cart_item_key ) {
    $custom_field_value = $cart_item['attribute_primary_location'];

    // We add the custom field value as an attribute for this product
    if( ! empty($custom_field_value) )
        wc_update_order_item_meta( $item_id, 'primary_location', $custom_field_value );
}