<?php
	global $wpdb;
	$today_date = date("Y-m-d");
	$table_name = $wpdb->prefix . "credits_log";
	$uid 		= get_current_user_id();
	$accountcredits =   get_user_meta( $uid,  'fwc_total_credit_amount', true );
	
	$retrieve_data = $wpdb->get_results( "SELECT * FROM $table_name WHERE class_packs = '1' AND user_id = ".$uid." AND expire='false' " );
	
	foreach ($retrieve_data as $retrieved_data){ 
		$id 			=  $retrieved_data->id;
		$user_id 			=  $retrieved_data->user_id;
		$end_date 			=  $retrieved_data->end_date;
		$before_credit 	=  $retrieved_data->current_credits;
		$affected_credits 	=  $retrieved_data->affected_credits;
		$expire 			=  $retrieved_data->expire;
		$package_details 	=  $retrieved_data->package_details;
		$class_packs 		=  $retrieved_data->class_packs;
		$new_credit 		=  $retrieved_data->new_credit;
		$total_credits 		=  $retrieved_data->total_credits;

		if($today_date >= $end_date){

			$remaining_credits = $new_credit - $total_credits;
			
			if($remaining_credits > 0){
				$remain = $accountcredits - $remaining_credits;
				update_user_meta( $uid,  'fwc_total_credit_amount', $remain );
			}

			$result = $wpdb->update(
			    $table_name, 
			    array( 
			        'modified_date' 	=> $today_date,
			        'increment' 		=> 0, 
			        'expire' 			=> 'true' 
			    ), 
			    array(
			        "id" => $id
			    ) 
			);
		}


	}
?>
