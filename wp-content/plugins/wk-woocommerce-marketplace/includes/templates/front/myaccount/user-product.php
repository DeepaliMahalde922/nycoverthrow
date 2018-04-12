<?php
function product_categories($cat_id)
{

	$args = array(
						'type'                     => 'product_cat',
						'child_of'                 => 0,
						'parent'                   => $cat_id,
						'orderby'                  => 'name',
						'order'                    => 'ASC',
						'hide_empty'               => 0,
						'hierarchical'             => 1,
						'exclude'                  => '',
						'include'                  => '',
						'number'                   => '',
						'taxonomy'                 => 'product_cat',
						'pad_counts'               => true
					);
	$categories=get_categories( $args );
	foreach($categories as $product_cat)
		{		if(!isset($cat_id))
				{
					$cat_id=0;
				}
			if($product_cat->parent==$cat_id )
					{
					echo '<div class="wkmp_left_details"><a href="#">'.$product_cat->cat_name.'</a></div>';
					}

					//  if($product_cat->parent!=$cat_id )
					// {
					// 	echo '123';
					// 	echo $product_cat_display.=product_categories($product_cat->parent);
					// }

		}

	return $product_cat_display;
}

	$str='';
	$sellerurl=get_query_var('info');
	$user =get_users(
	  array(
	   'meta_key' => 'shop_address',
	   'meta_value' => $sellerurl
	    )
	);

if( ! empty( $user ) ){

	foreach ($user as $value) {

		$sid=$value->ID;
	}

	if(isset($_REQUEST['str'])){
		$str = $_REQUEST['str'];
	}
	if(!empty($sid)){
		$sellerid=$sid;
	}
	else{
		$sellerid='';
	}
	$wpmp_obj11=new MP_Form_Handler();
?>
<style type="text/css">
header h1
{
display:none;
}
aside
{
	display:none;
}
#main
{
	width:100%;
}
</style>
<div id="productlist">
<h1 class="wkmp-seller-collection"><?php echo _e('Collection', 'marketplace');?></h1>
		<div id="seller_product_list_left">

		<div class="wkmp-Advertisement" style="border-style:none;">
			<?php
			/*$shop_logo=MP_Form_Handler::get_user_avatar($sellerid,'company_logo'); */
			$shop_logo=$wpmp_obj11->get_user_avatar($sellerid,'company_logo');
			if(isset($shop_logo[0]->meta_value)) {

		echo '<img src="'.content_url().'/uploads/'.$shop_logo[0]->meta_value.'">';
		}
		else
		{
		echo '<img src="'.WK_MARKETPLACE.'assets/images/shop-logo.png" />';
		}?>
		</div>
			<div class="wkmp-Advertisement" style="display:none;">
				<div  id='mp_left_details_first'><?php echo _e('Seller Category List', 'marketplace');?></div>
				<?php

					echo product_categories('');
					$seller_id=$sid;

				?>
			</div>
			<div class="wkmp-Advertisement">
				<div  id='mp_left_details_first'><?php echo _e('View Collection', 'marketplace');?></div>

				<?php
					$varsid=$sid;
					if(strchr(get_permalink(),'?'))
						$icon='&';
					else
						$icon='?';
				?>
				<div class="wkmp_left_details">

					<a href='<?php echo get_permalink()."store/".$sellerurl;?>'><?php echo _e('View Profile', 'marketplace');?></a>
				</div>
			</div>
		</div>
<?php
if($seller_id)
{ ?>		<div id="seller_product_list_right">
		<div id='wk_banner'>
		<?php
		$banner=$wpmp_obj11->get_user_avatar($seller_id,'shop_banner');
		 if(!isset($banner[0]->meta_value))
		{
		?>
		<img src="<?php echo WK_MARKETPLACE.'assets/images/woocommerce-marketplace-banner.png';?>" />
		<?php
		}
		else
		{?>
		<img src="<?php echo content_url().'/uploads/'.$banner[0]->meta_value;?>" class="wkmp-collection-banner"/>
		<?php
		}
		?>
			<!-- <div class='wkmp-seller_grid'><?php echo _e('Sort By', 'marketplace');?> -->
			<!-- <select class='mp_value_asc'>
			<?php if($str=='price_l')
			{
			?>
			<option selected="selected" value="price_l" >&nbsp;<?php echo _e('Price', 'marketplace');?>&nbsp;(<?php echo _e('Low To High', 'marketplace');?>)&nbsp;</option>
			<?php
			}
			else
			{ ?>
			<option value="price_l" >&nbsp;<?php echo _e('Price', 'marketplace');?>&nbsp;(<?php echo _e('Low To High', 'marketplace');?>)&nbsp;</option>
			<?php
			}
			if($str=='price_h')
			{
			?>
		<option selected="selected" value="price_h">&nbsp;<?php echo _e('Price', 'marketplace');?>&nbsp;(<?php echo _e('High To Low', 'marketplace');?>)&nbsp;</option>
			<?php
			}
			else
			{
			?>
			<option value="price_h">&nbsp;<?php echo _e('Price', 'marketplace');?>&nbsp;(<?php echo _e('High To Low', 'marketplace');?>)&nbsp;</option>
			<?php
			}
			if($str=='productname_l')
			{
			?>
			<option selected="selected" value="productname_l" >&nbsp;<?php echo _e('Name', 'marketplace');?>&nbsp;(<?php echo _e('Asc To Desc', 'marketplace');?>)&nbsp;</option>
			<?php
			}
			else
			{
			?>
			<option value="productname_l" >&nbsp;<?php echo _e('NAME', 'marketplace');?>&nbsp;(<?php echo _e('Asc To Desc', 'marketplace');?>)&nbsp;</option>
			<?php
			}
			if($str=='productname_h')
			{
			?>
			<option selected="selected" value="productname_h" >&nbsp;<?php echo _e('Name', 'marketplace');?>&nbsp;(<?php echo _e('Desc To Asc', 'marketplace');?>)&nbsp;</option>
			<?php
			}
			else
			{
			?>
			<option value="productname_h" >&nbsp;<?php echo _e('Name', 'marketplace');?>&nbsp;(<?php echo _e('Desc To Asc', 'marketplace');?>)&nbsp;</option>
			<?php
			}
			?>
			</select> -->
			<!-- </div> -->
		</div>
		<div class="wkmp-box-content">
			<?php
			/*$products = MP_Form_Handler::produt_by_seller_ID($seller_id,$str);
			$banner=MP_Form_Handler::get_user_avatar($seller_id,'shop_banner');*/
			 $products = $wpmp_obj11->produt_by_seller_ID($seller_id,$str);
			 $banner=$wpmp_obj11->get_user_avatar($seller_id,'shop_banner');
			foreach($products as $product)
			{
				$currency=get_woocommerce_currency_symbol(get_option('woocommerce_currency'));
				/*$product_image=MP_Form_Handler::get_product_image($product->ID,'_thumbnail_id');*/
				$product_image=$wpmp_obj11->get_product_image($product->ID,'_thumbnail_id');
				$product_object=wc_get_product($product->ID);
				/*echo "<pre>";
				print_r($product_object->product_type);
				echo "</pre>";*/
				/*
				if($product_object->post_status=='publish'){
				}
				*/
				$link=get_permalink( $product->ID );
				?>
						<div class="wkmp_seller_product_row">

								<li>
								<a class="product_img_link" title="<?php echo $product->post_title; ?>" href="<?php echo $link; ?>">
									<div class="wkmp-slider-product-img"><?php if($product_image!='') {?>
										<img class="product_image" alt="<?php echo $product->post_title; ?>" src="<?php echo content_url().'/uploads/'.$product_image; ?>">
									<?php }
									else
									{?>
										<img class="product_image" alt="<?php echo $product->post_title; ?>" src="<?php echo WK_MARKETPLACE.'assets/images/placeholder.png'; ?>">
									<?php }
									?>
									</div>
									<div class="wkmp-slider-product-info">
										<h3><div style="margin-bottom:5px;"><?php echo $product->post_title; ?></div></h3>
										<!-- <div style="font-weight:bold;"><?php /*echo woocommerce_price($product->regular_price);*/?></div> -->
										<!-- <span><?php /*echo 'product_type= '.$product_object->product_type;*/ ?></span><br> -->
										<?php if ( $product_object->is_type( 'simple' ) ){
											?>
											<span class="amount"><?php echo wc_price($product_object->get_price()); ?></span>
											<?php
										}else if( $product_object->is_type( 'variable' ) && !empty($product_object->get_variation_prices()['price']) ){
											?>
											<span class="price">
											<span class="amount"><?php echo wc_price(min($product_object->get_variation_prices()['price'])); ?></span>
											&ndash;
											<span class="amount"><?php echo wc_price(max($product_object->get_variation_prices()['price'])); ?></span>
											</span>
											<?php
										}else if( $product_object->is_type( 'external' ) ){
											?>
											<span class="amount"><?php echo wc_price($product_object->get_price()); ?></span>
											<?php
										}else if( $product_object->is_type( 'grouped' ) ){
											?>
											<span class="amount"><?php echo '~' ?></span>
											<?php
										}
										?>
									</div>
									</a>
								<!-- <div style="text-align:center;"> -->
								<!-- <form class="cart" enctype="multipart/form-data" method="post">
								<input type="hidden" value="<?php /*echo $product->ID;*/?>" name="add-to-cart">
								<button class="single_add_to_cart_button button alt" type="submit">Add to cart</button>
								</form> -->
								<!-- </div> -->
								</li>
						</div>
			<?php
				}
	?>
	</div>
	</div>
	<?php
	}

}else{?>

	<h1>Oops! That page can’t be found.</h1>
	<p>Nothing was found at this location.</p>

<?php } ?>
