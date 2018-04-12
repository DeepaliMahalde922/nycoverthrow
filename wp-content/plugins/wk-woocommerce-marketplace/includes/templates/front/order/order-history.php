<div id="main_container">
   <h2>Order History</h2>
	<table class="orderhistory">
   <thead>
   		<tr>
			<th width="20%"><?php echo _e("Order", "marketplace"); ?></th>
			<th width="20%"><?php echo _e("Status", "marketplace"); ?></th>
    	<th width="20%"><?php echo _e("Date", "marketplace"); ?></th>
			<th width="20%"><?php echo _e("Total", "marketplace"); ?></th>
			<th width="20%"><?php echo _e("View Order", "marketplace"); ?></th>
        </tr>
    </thead>
	<tbody class="pagination">
               <?php
				global $wpdb;
				$wpmp_obj5 = new MP_Form_Handler();
				$user_id = get_current_user_id();
				/*$page_id=MP_Form_Handler::get_page_id(get_option('wkmp_seller_page_title'));*/
				$page_id = $wpmp_obj5->get_page_id(get_option('wkmp_seller_page_title'));
				$order_detail = $wpdb->get_results("select DISTINCT woitems.order_id from {$wpdb->prefix}woocommerce_order_itemmeta woi join {$wpdb->prefix}woocommerce_order_items woitems on woitems.order_item_id=woi.order_item_id join {$wpdb->prefix}posts post on woi.meta_value=post.ID where woi.meta_key='_product_id' and post.ID=woi.meta_value and post.post_author='".$user_id."' order by woitems.order_id DESC");

				$all_order_details = array();
				$order_id_list = array();
				foreach ($order_detail as $order_dtl) {
					$order_id = $order_dtl->order_id;
					$order_id_list[] = $order_id;
					$order = new WC_Order( $order_id );
          $cur_symbol = get_woocommerce_currency_symbol($order->get_currency());
					$get_item = $order->get_items();
					foreach ($get_item as $key => $value) {
						$product_id = $value->get_product_id();
						$variable_id = $value->get_variation_id();
						$post = get_post($product_id,ARRAY_A);
						if ( $post['post_author'] == $user_id ) {
							$price_id=$product_id;
							$type='simple';
							$qty = $value->get_quantity();
              $product = new WC_Product($price_id);
							if($variable_id != 0){
								$price_id=$variable_id;
								$type='variable';
                $product = new WC_Product_Variation($price_id);
							}
							$product_price = $product->get_price();
							$all_order_details[$order_id][]=array('order_date'=>date_format($order->get_date_created(), "Y-m-d H:i:s"),'order_status'=>$order->get_status(),'product_price'=>$product_price,'qty'=>$qty);
						}
					}
				}
				/*
				$billing_full_name		= $order->billing_first_name.' '. $order->billing_last_name;
				$billing_address_1		= $order->billing_address_1;
				$billing_city			= $order->billing_city;
				$billing_state			= $order->billing_state;
				$billing_postcode		= $order->billing_postcode;
				$billing_country		= $order->billing_country;
				$billing_phone			= $order->billing_phone;
				$billing_email			= $order->billing_email;*/
				$order_by_table=array();
				for ($counter=0; $counter < count($order_id_list); $counter++) {
					$order_id= $order_id_list[$counter];
					foreach ($all_order_details as $key => $value) {

						if($order_id==$key){
              $order = new WC_Order( $order_id );
              $cur_symbol = get_woocommerce_currency_symbol($order->get_currency());
							foreach ($value as $index => $val) {
								$qty=$val['qty'];
								$total_price=$order->get_total();
								$status=$val['order_status'];
								$date=$val['order_date'];
								if (isset($order_by_table[$key])) {
									$total_price=$order_by_table[$key]['total_price']+$total_price;
									$total_qty=$order_by_table[$key]['total_qty']+$qty;
									$order_by_table[$key]=array('symbol'=>$cur_symbol,'status'=>$status,'date'=>$date,'total_price'=>$total_price,'total_qty'=>$total_qty);
								}else{
									$order_by_table[$key]=array('symbol'=>$cur_symbol,'status'=>$status,'date'=>$date,'total_price'=>$total_price,'total_qty'=>$qty);
								}
							}

						}
					}
				}
				foreach ($order_by_table as $key => $value) {
					?>
					<tr>
						<td width="20%"><?php echo '#'.$key; ?></td>
						<td width="20%"><?php echo $value['status']; ?></td>
						<td width="20%"><?php echo $value['date'] ?></td>
						<td width="20%"><?php echo $value['symbol'].$value['total_price']." for ".$value['total_qty'].' items'; ?></td>
						<td width="20%"><a href="<?php echo home_url("seller/order-history/".$key)?>" class="button"><?php echo _e("View", "marketplace"); ?></a></td>
					</tr>
			<?php } ?>
    </tbody>
	</table>
 </div>
