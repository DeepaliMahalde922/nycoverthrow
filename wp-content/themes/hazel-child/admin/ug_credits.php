<?php
	/*Membership*/

	global $woocommerce;
	$accountcredits = $mddate = $add_date = $deadate = $expire = $membership_info = '';
	$user_memberships          = wc_memberships()->get_user_memberships_instance()->get_user_memberships( get_current_user_id() );
	$can_edit_user_memberships = current_user_can( 'manage_woocommerce' );

	if ( ! empty( $user_memberships ) ) : $plan_links = array(); 

		$credits_log = $credits_arr = array();
		$new_credits 	= 	3;
		$cur_id 		= 	get_current_user_id();
		$today_date = date("Y-m-d");
		$accountcredits =   get_user_meta( $cur_id,  'fwc_total_credit_amount', true );
		
		$inc = $dec = '';

		foreach ( $user_memberships as $user_membership ) : 
			
			if ( $user_membership->get_plan() ) :

				$statuses = wc_memberships_get_user_membership_statuses();
				$status   = 'wcm-' . $user_membership->get_status();
				$status   = isset( $statuses[ $status ]['label'] ) ? '(' . esc_html( $statuses[ $status ]['label'] ) . ')' : '';
				$membership_plane = $user_membership->get_plan()->get_name();
				$plan_id  = $user_membership->plan_id;

				if($plan_id == 98434 && $status == '(Active)'){

					$pid = 98434;
					global $wpdb;
					$tablename = $wpdb->prefix.'credits_log';


					$retrieve_data = $wpdb->get_results( "SELECT * FROM $tablename WHERE class_packs = '0' AND user_id = $cur_id AND expire='false' AND package_details = $pid " );
					
					if(!empty($retrieve_data)){
						//echo 'present';
						foreach ($retrieve_data as $retrieved_data){ 

							$id 			    =  $retrieved_data->id;
							$user_id 			=  $retrieved_data->user_id;
							$end_date 			=  $retrieved_data->end_date;
							$current_credits 	=  $retrieved_data->current_credits;
							$affected_credits 	=  $retrieved_data->affected_credits;
							$expire 			=  $retrieved_data->expire;
							$package_details 	=  $retrieved_data->package_details;
							$class_packs 		=  $retrieved_data->class_packs;
							$new_credit 		=  $retrieved_data->new_credit;

								$credits_used = $accountcredits - $affected_credits;

								//if ( (int)$credits_used == $credits_used && (int)$credits_used >= 0 ){

								if ( (int)$credits_used == $credits_used && (int)$credits_used >= 0 ){
									$credits_used = $credits_u;			
								}else{
									$credits_used = $affected_credits - $accountcredits;
								}
								//echo $credits_used;

									if( $credits_used >= $new_credits ){
										//echo 'Credits used';
									}else{

										//echo "Doesn't used all credits";
										$fwc_effective_user_camount = $accountcredits - $new_credit;


										if($end_date <= $today_date){

											$result = $wpdb->update(
											    $tablename, 
											    array( 
											        'modified_date' 	=> $today_date, 
											        'current_credits' 	=> $accountcredits, 
											        'affected_credits' 	=> $fwc_effective_user_camount,
											        'total_credits' 	=> $fwc_effective_user_camount,
											        'increment' 		=> 0, 
											        'expire' 			=> 'true' 
											    ), 
											    array(
											        "id" => $id
											    ) 
											);

											if($result == 1){
												update_user_meta(get_current_user_id(), 'fwc_total_credit_amount', $fwc_effective_user_camount);
											}

										}

									}
							
								
							

						}

					}else{

						//echo "none";
						$inc = 1;
						$expire = 'false';

						$add_date_date 	= 	date("Y-m-j");
						$deadate 		= 	strtotime(date('Y-m-j', strtotime('30 days')));
						$deadate_date 	= 	date('Y-m-j', $deadate);
						$fwc_effective_user_camount = $accountcredits + $new_credits;

						$result = $wpdb->insert( $tablename , array(
						    'user_id' 			=> $cur_id, 
						    'created_date' 		=> $add_date_date,
						    'modified_date' 	=> '',
						    'end_date' 			=> $deadate_date,
						    'current_credits' 	=> $accountcredits, 
						    'affected_credits' 	=> $fwc_effective_user_camount,
						    'increment' 		=> $inc, 
						    'new_credit' 		=> $new_credits,
						    'class_packs' 		=> 0,
						    'package_details'	=> $pid,
						    'total_credits' 	=> $fwc_effective_user_camount,
						    'expire' 			=> $expire
						));

						if($result == 1){
							update_user_meta(get_current_user_id(), 'fwc_total_credit_amount', $fwc_effective_user_camount);
						}

					}

				}

			endif;

		endforeach;
		
	endif;	
?>