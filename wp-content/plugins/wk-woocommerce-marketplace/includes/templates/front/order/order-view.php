<?php
// view order


				global $wpdb;
				$order_id = get_query_var('order_id');
				$user_id = get_current_user_id();
				/*$order_detail=$wpdb->get_results("select woitems.order_item_name,woitems.order_id,post.* from {$wpdb->prefix}woocommerce_order_itemmeta woi join {$wpdb->prefix}woocommerce_order_items woitems on woitems.order_item_id=woi.order_item_id join {$wpdb->prefix}posts post on woi.meta_value=post.ID where woi.meta_key='_product_id' and post.ID=woi.meta_value and post.post_author='".$user_id."' and woitems.order_id='".$_GET['post']."'");
				//$term_list = wp_get_post_terms($order_detail[0]->order_id, 'shop_order_status', array("fields" => "all"));
				$order_status = new WC_Order($order_detail[0]->order_id);
				$posts_meta_data=array();
				$posts_meta=$wpdb->get_results("select meta_key,meta_value from $wpdb->postmeta where post_id='".$_GET['post']."'");
				foreach($posts_meta as $value)
				{
					$posts_meta_data[$value->meta_key]=$value->meta_value;
				}
				$user_meta=$wpdb->get_results("select u.user_email,um.meta_value as f_name,umt.meta_value as l_name from {$wpdb->prefix}users u join {$wpdb->prefix}usermeta um on u.ID=um.user_id  join {$wpdb->prefix}usermeta umt on u.ID=umt.user_id where um.meta_key='first_name' and  umt.meta_key='last_name' and u.ID='".$posts_meta_data['_customer_user']."'");*/
				$order = new WC_Order( $order_id );
				$order_detail_by_order_id=array();
				$get_item = $order->get_items();
				$cur_symbol = get_woocommerce_currency_symbol($order->get_currency());
				$order_detail_by_order_id = array();
				foreach ($get_item as $key => $value) {
					$product_id = $value->get_product_id();
					$variable_id = $value->get_variation_id();
					$product_total_price = $value->get_data()['total'];
					$qty = $value->get_data()['quantity'];
					$post=get_post($product_id);
					if($post->post_author==$user_id){
						// echo "<pre>";
						// print_r($value);
						// echo "</pre>";
						$order_detail_by_order_id[$product_id][]=array('product_name'=>$value['name'],'qty'=>$qty,'variable_id'=>$variable_id,'product_total_price'=>$product_total_price);
					}
				}
				$shipping_method = $order->get_shipping_method();
				$payment_method = $order->get_payment_method_title();
				$total_payment=0;

	if( ! empty( $order_detail_by_order_id ) ) :

	?>
<!--<button onclick="javascript:window.print()">Print This Webpage</button>-->
	<div id="order_data_details">
		<a href="<?php echo site_url().'/seller/invoice/'.base64_encode($order_id); ?>" target="_blank" class="button print-invoice"><?php echo __("Print Invoice", "marketplace"); ?></a>
		<h2><?php echo __('Order', 'marketplace').' #'.$order_id; ?></h2>
		<!-- <p class="order-info">Order #<mark class="order-number">233</mark> was placed on <mark class="order-date">May 6, 2015</mark> and is currently <mark class="order-status">On Hold</mark>.</p> -->
		<div class="wkmp_order_data_detail">
			<table>
				<thead>
					<tr>
						<th class="product-name"><?php echo _e("Product", "marketplace"); ?></th>
						<th class="product-total"><?php echo _e("Total", "marketplace"); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					/*foreach ($order_detail_by_order_id as $product_id => $details) {
						for ($i=0; $i < count($details); $i++) {
							if($details[$i]['variable_id']==0){
								?>
								<tr class="order_item alt-table-row">
									<td class="product-name">
										<a href=""><?php echo $details[$i]['product_name']; ?></a>
										<strong class="product-quantity">× <?php echo $details[$i]['qty']; ?></strong>
									</td>
									<td class="product-total">
										<?php echo $details[$i]['product_total_price']; ?>
									</td>
								</tr>
								<?php
							}
						}
					}*/
					foreach ($order_detail_by_order_id as $product_id => $details) {
						for ($i=0; $i < count($details); $i++) {
							$total_payment=$total_payment + intval($details[$i]['product_total_price']);
							if($details[$i]['variable_id']==0){
								?>
								<tr class="order_item alt-table-row">
									<td class="product-name">
										<a href=""><?php echo $details[$i]['product_name']; ?></a>
										<strong class="product-quantity">× <?php echo $details[$i]['qty']; ?></strong>
									</td>
									<td class="product-total">
										<?php echo $cur_symbol.$details[$i]['product_total_price']; ?>
									</td>
								</tr>
								<?php
							}else{
								$product=new WC_Product($product_id);
								$attribute=$product->get_attributes();
								/*echo "<pre>";
								print_r($attribute);
								echo "</pre>";*/
								$attribute_name='';
								foreach ($attribute as $key => $value) {
									$attribute_name= $value['name'];
								}
								$variation=new WC_Product_Variation($details[$i]['variable_id']);
								$aaa=$variation->get_variation_attributes();
								// echo "<pre>";
								// print_r($aaa);
								// echo "</pre>";
								$attribute_prop= strtoupper($aaa['attribute_'.strtolower($attribute_name)]);
								?>
								<tr class="order_item alt-table-row">
									<td class="product-name">
										<a href="#"><?php echo $details[$i]['product_name']; ?></a>
										<strong class="product-quantity">× <?php echo $details[$i]['qty']; ?></strong>
										<dl class="variation">
											<dt class="variation-size"><?php echo $attribute_name.': '; ?></dt>
											<dd class="variation-size">
												<p><?php echo $attribute_prop; ?></p>
											</dd>
										</dl>
									</td>
									<td class="product-total">
										<?php echo $cur_symbol.$details[$i]['product_total_price']; ?>
									</td>
								</tr>
								<?php
							}
						}
					}
					?>
				</tbody>
				<tfoot>
					<!-- <tr class="alt-table-row">
						<th scope="row">Subtotal:</th>
						<td>
							<span class="amount"><?php /*echo $subtotal;*/ ?></span> <small>(ex. VAT)</small>
						</td>
					</tr> -->
					<tr>
						<th scope="row"><?php echo _e("Shipping", "marketplace"); ?>:</th>
						<td><?php echo $shipping_method; ?></td>
					</tr>
					<tr>
						<th scope="row"><?php echo _e("Payment Method", "marketplace"); ?>:</th>
						<td><?php echo $payment_method; ?></td>
					</tr>
					<tr class="alt-table-row">
						<th scope="row"><?php echo _e("Total", "marketplace"); ?>:</th>
						<td>
							<span class="amount"><?php echo $cur_symbol.$total_payment; ?></span>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
	<header><h2><?php echo _e("Customer details", "marketplace"); ?></h2></header>
	<table class="shop_table shop_table_responsive customer_details">
		<tbody>
			<tr>
				<th><?php echo _e("Email", "marketplace"); ?>:</th>
				<td data-title="Email"><?php echo $order->get_billing_email(); ?></td>
			</tr>
			<tr class="alt-table-row">
				<th><?php echo _e("Telephone", "marketplace"); ?>:</th>
				<td data-title="Telephone"><?php echo $order->get_billing_phone(); ?></td>
			</tr>
		</tbody>
	</table>
	<div class="col2-set addresses">
		<div class="col-1">
			<header class="title">
				<h3><?php echo _e("Billing Address", "marketplace"); ?></h3>
			</header>
			<address>
				<?php echo $order->get_billing_first_name().' '.$order->get_billing_last_name().'<br>'.$order->get_billing_address_1().'<br>';
				if($order->get_billing_address_2()!=''){
					echo $order->get_billing_address_2().'<br>';
				}
				echo $order->get_billing_city().' - '.$order->get_billing_postcode().'<br>'.$order->get_billing_state().', '.WC()->countries->countries[$order->get_billing_country()]; ?>
			</address>
		</div><!-- /.col-1 -->
		<div class="col-2">
			<header class="title">
				<h3><?php echo _e("Shipping Address", "marketplace"); ?></h3>
			</header>
			<address>
				<?php echo $order->get_shipping_first_name().' '.$order->get_shipping_last_name().'<br>'.$order->get_shipping_address_1().'<br>';
				if($order->get_shipping_address_2()!=''){
					echo $order->get_shipping_address_2().'<br>';
				}
        if($order->get_shipping_country())
				echo $order->get_shipping_city().' - '.$order->get_shipping_postcode().'<br>'.$order->get_shipping_state().', '.WC()->countries->countries[$order->get_shipping_country()]; ?>
			</address>
		</div><!-- /.col-2 -->
	</div>

<?php else : ?>

	<h1>Cheating huh ???</h1>
	<p>Sorry, You can't access other seller's orders.</p>

<?php endif; ?>
