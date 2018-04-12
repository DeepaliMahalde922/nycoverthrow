<?php
if(!function_exists('mp_price')){
		function mp_price($vlaue){
			if(!function_exists('woocommerce_price') || 'WC_IS_MIS_WC_ACITVE' == false){
				return apply_filters( 'mp_currency_symbol', '&#36;', 'USD').$vlaue;
			}
			else
			{
				return wc_price($vlaue);
			}
		}
	}


function product_list()
{
?>
<div id="main_container">
    <h2><?php echo _e("Products List", "marketplace"); ?></h2>
               <?php
				global $wpdb;
				$user_id = get_current_user_id();

				$wpmp_pid='';

				$mainpage = get_query_var('main_page');

				$p_id = get_query_var('pid');

				$action = get_query_var('action');

				if(!empty($p_id))
					$wpmp_pid=$p_id;

				$product_auth=$wpdb->get_var("select post_author from $wpdb->posts where ID='".$wpmp_pid."'");
				if(!empty($mainpage)&& !empty($action))
				{
					if($mainpage='product-list' && $action=='delete' && $product_auth==$user_id)
					{
						if(delete_post_meta($wpmp_pid,'_sku'))
						{
							delete_post_meta($wpmp_pid,'_regular_price');
							delete_post_meta($wpmp_pid,'_sale_price');
							delete_post_meta($wpmp_pid,'_price');
							delete_post_meta($wpmp_pid,'_sale_price_dates_from');
							delete_post_meta($wpmp_pid,'_sale_price_dates_to');
							delete_post_meta($wpmp_pid,'_downloadable');
							delete_post_meta($wpmp_pid,'_virtual');
							wp_delete_post($wpmp_pid);
						}
					}
				}
	$product = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = 'product' and ( post_status = 'draft' or post_status = 'publish' ) and post_author='".$user_id."'");
			?>
<table class="productlist">
    <thead>
    	<tr>
            <th><?php echo _e("Product Name", "marketplace"); ?></th>
            <th><?php echo _e("Details", "marketplace"); ?></th>
            <th><?php echo _e("Stock", "marketplace"); ?></th>
            <th><?php echo _e("Product Status", "marketplace"); ?></th>
            <th><?php echo _e("Price", "marketplace"); ?></th>
            <th><?php echo _e("Image", "marketplace"); ?></th>
            <th><?php echo _e("Action", "marketplace"); ?></th>
        </tr>

    </thead>
    <tbody>
	<?php
	//echo "SELECT ID FROM $wpdb->posts WHERE post_title = '".get_option('wkmp_seller_page_title')."' and post_type='page'"
	$page_name = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title = '".get_option('wkmp_seller_page_title')."' and post_type='page'");
	$wpmp_obj2=new MP_Form_Handler();
	foreach($product as $pro)
	{
			$prod = wc_get_product( $pro->ID );
			$symbol = get_woocommerce_currency_symbol();
			$product_price= get_post_meta( $pro->ID, '_price', true);
			$product_stock=get_post_meta( $pro->ID, '_stock_status', true);
			$stock_remain=get_post_meta( $pro->ID, '_stock', true);
			/*$product_image=MP_Form_Handler::get_product_image($pro->ID,'_thumbnail_id');*/
			$product_image=$wpmp_obj2->get_product_image($pro->ID,'_thumbnail_id');

			if( $prod->is_type( 'variable' ) ){

				$symbol = get_woocommerce_currency_symbol();
				$product_price = '';

				if( ! empty( get_post_meta( $pro->ID, '_min_variation_price', true ) ) && ! empty( get_post_meta( $pro->ID, '_max_variation_price', true ) ) )
				$product_price = $symbol . get_post_meta( $pro->ID, '_min_variation_price', true ) . ' - ' . $symbol . get_post_meta( $pro->ID, '_max_variation_price', true );

			}

	?>
	<tr>
            <td><a href="<?php echo get_permalink($pro->ID); ?>"><?php echo $pro->post_title; ?></a></td>
            <td><?php echo $pro->post_excerpt; ?></td>
			<td><?php echo isset($product_stock)?  $product_stock :'';?> </td>

          	<td><?php echo $pro->post_status;?> </td>
            <td>
							<?php
								if( $prod->is_type( 'variable' ) ){
									echo $product_price;
								}
								else{
						 			echo mp_price($product_price);
								}
						 	?>
					 </td>
            <td><img class="wkmp_productlist_img" alt="<?php echo $pro->post_title; ?>" title="<?php echo $pro->post_title; ?>" src="<?php if($product_image!='') { echo content_url().'/uploads/'.$product_image;} else { echo WK_MARKETPLACE.'assets/images/placeholder.png';} ?>" width="50" height="50"></td>
            <td><a id="editprod" href="<?php echo home_url("seller/product/edit/".$pro->ID);?>"><?php echo __("edit", "marketplace"); ?></a>
            <a id="delprod" href="<?php echo home_url("seller/product-list/delete/".$pro->ID);?>" class="ask"><?php echo __("delete", "marketplace"); ?></a></td>
        </tr>
        <?php }?>
    </tbody>
</table>
    </div>
<?php
}
