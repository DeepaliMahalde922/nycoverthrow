<?php

if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly

}


global $wpdb, $post, $mp_obj;
$wpmp_obj3=new MP_Form_Handler();
$wk_id=get_query_var('pid');
$mainpage = get_query_var('main_page');
$wc_currency=get_woocommerce_currency_symbol(get_option('woocommerce_currency'));
	$user_id = get_current_user_id();
	if(!empty($wk_id))
	{
	$post_id=$wk_pro_id = $wk_id;
	if($_POST){
		if(isset($_POST['add_product_sub']))
		{
		   /*MP_Form_Handler::product_add_update();*/
		   product_add_update();
		}
	}
}
else
{
	if(isset($_POST['add_product_sub']))
	{
	   /*$wk_pro_p=MP_Form_Handler::product_add_update();*/
	   $wk_pro_p=product_add_update();
	   $wk_pro_id=$wk_pro_p[0];
	}
}

/*MP_Form_Handler::marketplace_media_fix($post_id);*/
$wpmp_obj3->marketplace_media_fix();


$product_auth=$wpdb->get_var("select post_author from $wpdb->posts where ID='".$wk_pro_id."'");

// Check if product author is same as of logged in user

if(isset($wk_pro_id) && $product_auth==$user_id) {


		$post_row_data=$wpdb->get_results("select * from $wpdb->posts where ID=".$wk_pro_id);
		$postmeta_row_data=get_post_meta($wk_pro_id);
		/*$product_images= MP_Form_Handler::get_product_image($wk_pro_id,'_thumbnail_id');*/
		$product_images= $wpmp_obj3->get_product_image($wk_pro_id,'_thumbnail_id');
		$meta_arr=array();

	foreach($postmeta_row_data as $key=>$value)
	{

	 $meta_arr[$key]=$value[0];

	}
	$product_attributes=get_post_meta( $wk_pro_id, '_product_attributes', true );
		$display_variation='no';
		if(!empty($product_attributes))
		{
		foreach($product_attributes as $variation)
			{
				if($variation['is_variation']==1)
				{
					$display_variation='yes';
				}
			}
		}

	/*$image_gallary=MP_Form_Handler::get_product_image($wk_pro_id,'_product_image_gallery');*/
	$image_gallary=$wpmp_obj3->get_product_image($wk_pro_id,'_product_image_gallery');

	function marketplace_wp_text_input( $field,$wk_id ) {
		global $post;
		$thepostid              = empty( $wk_id ) ? $post->ID : $wk_id;

		$field['placeholder']   = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
		$field['class']         = isset( $field['class'] ) ? $field['class'] : 'short';
		$field['style']         = isset( $field['style'] ) ? $field['style'] : '';
		$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
		$field['value']         = isset( $field['value'] ) ? $field['value'] : get_post_meta( $thepostid, $field['id'], true );
		$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
		$field['type']          = isset( $field['type'] ) ? $field['type'] : 'text';
		$data_type              = empty( $field['data_type'] ) ? '' : $field['data_type'];

		switch ( $data_type ) {
			case 'price' :
				$field['class'] .= ' wc_input_price';
				$field['value']  = wc_format_localized_price( $field['value'] );
				break;
			case 'decimal' :
				$field['class'] .= ' wc_input_decimal';
				$field['value']  = wc_format_localized_decimal( $field['value'] );
				break;
			case 'stock' :
				$field['class'] .= ' wc_input_stock';
				$field['value']  = wc_stock_amount( $field['value'] );
				break;
			case 'url' :
				$field['class'] .= ' wc_input_url';
				$field['value']  = esc_url( $field['value'] );
				break;

			default :
				break;
		}

		// Custom attribute handling
		$custom_attributes = array();

		if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {

			foreach ( $field['custom_attributes'] as $attribute => $value ){
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
			}
		}

		echo '<p class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '"><label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label><input type="' . esc_attr( $field['type'] ) . '" class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" ' . implode( ' ', $custom_attributes ) . ' /> ';

		if ( ! empty( $field['description'] ) ) {

			if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
				echo wc_help_tip( $field['description'] );
			} else {
				echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
			}
		}
		echo '</p>';
	}
	?>


	<h2><?php echo _e("Edit Product"); ?></h2>
	<input type="hidden" name="var_variation_display" id="var_variation_display" value="<?php echo $display_variation;?>" />

	<ul id='edit_product_tab'>

	    <li><a id='edit_tab'><?php echo _e("Edit", "marketplace"); ?></a></li>

	   	<li><a id='inventorytab'><?php echo _e("Inventory", "marketplace"); ?></a></li>

	   	<li><a id='shippingtab'><?php echo _e("Shipping", "marketplace"); ?></a></li>

	   	<li><a id='linkedtab'><?php echo _e("Linked Products", "marketplace"); ?></a></li>

	    <li><a id='attributestab'><?php echo _e("Attributes", "marketplace"); ?></a></li>

	    <li style="display:none;"><a id='external_affiliate_tab'><?php echo _e("External/Affiliate", "marketplace"); ?></a></li>

	    <li style="display:none;"><a id='avariationtab'><?php echo _e("Variations", "marketplace"); ?></a></li>

			<li><a id='pro_statustab'><?php echo _e("Product Status", "marketplace"); ?></a></li>

			<?php do_action( 'mp_edit_product_tab_links' ); ?>

	</ul>


	<form action="" method="post" enctype="multipart/form-data" id="product-form">


	 <div class="wkmp_container form" id="edit_tabwk">
	 		<table>


				<tbody>

					<tr>
							<td>
									<label for="product_type"><?php echo __('Product Type', 'marketplace') . ':'; ?></label>
							</td>
							<td>
									<select name="product_type" id="product_type" class="mp-toggle-select">

										<?php

											$mp_product_type = wc_get_product_types();

											$product = wc_get_product($wk_pro_id);

											$pro_term_relation=$wpdb->get_var("select wtr.term_taxonomy_id from {$wpdb->prefix}term_relationships as wtr join {$wpdb->prefix}term_taxonomy wtt on  wtr.term_taxonomy_id=wtt.term_taxonomy_id where wtt.taxonomy='product_type' and wtr.object_id=$wk_pro_id");

											foreach($mp_product_type as $key=>$pro_type)
											{
												?>
												<option value="<?php echo $key;?>" <?php if($key==$product->get_type()) echo 'selected="selected"'; ?> ><?php echo $pro_type;?></option>

												<?php
											}
										?>

									</select>
							</td>
					</tr>

					<tr>

						<td><label for="product_name"><?php echo _e("Product Name");?><span class="required">*</span>&nbsp;&nbsp;:</label></td>


						<td><input class="wkmp_product_input" type="text" name="product_name" id="product_name" size="54" value="<?php if(isset($post_row_data[0]->post_title)) echo $post_row_data[0]->post_title;?>" />


						<div id="pro_name_error" class="error-class"></div>


						</td>


					</tr>


					<tr style="display:none;">


							<td>


							<?php if(!empty($wk_pro_id) && !empty($mainpage) && $mainpage=='product')


							{


							?>


							<input type="hidden" value="<?php echo $wk_pro_id;?>" name="sell_pr_id" id="sell_pr_id" />


							<?php } ?>


							<td>


					</tr>


					<tr>


							<td><label for="product_desc"><?php echo _e("About Product", "marketplace");?>&nbsp;&nbsp;:</label></td>


							<td><?php


							$settings = array('media_buttons' => true, // show insert/upload button(s)


											  'textarea_name' => 'product_desc',


											  'textarea_rows' => get_option('default_post_edit_rows', 10),


											  'tabindex' => '',


											  'teeny' => false,


											  'dfw' => false,


											  'tinymce' => true, /* load TinyMCE, can be used to pass settings directly to TinyMCE using an array()*/


											  'quicktags' => false /* load Quicktags, can be used to pass settings directly to Quicktags using an array()*/


											  );


							if(isset($post_row_data[0]->post_content))


							{


							$content=$post_row_data[0]->post_content;


							}


							if(isset($content))
							echo wp_editor("$content",'product_desc',$settings);
							else
							echo wp_editor("",'product_desc',$settings);?>


							<div id="long_desc_error" class="error-class"></div>


							</td>


					</tr>


					<tr>


						<td><label for="product_category"><?php echo _e("Product Category", "marketplace");?>&nbsp;&nbsp;:</label></td>


						<td>
							<?php

								$categories = wp_get_post_terms($wk_pro_id, 'product_cat');

								$cat_slug = array();

								if( ! empty( $categories ) ){

										foreach ($categories as $key => $value) {

												$cat_slug[] = $value->slug;

										}

										$cat_slug = array_flip($cat_slug);

								}

							?>

							<select id="mp_seller_product_categories" name="product_cate[]" style="width: 50%;" class="wc-enhanced-select select2-hidden-accessible enhanced" multiple="" data-placeholder="Any category" tabindex="-1" aria-hidden="true">

									<?php
											$product_categories = get_terms( 'product_cat', array('hide_empty' => false));

											if(!empty($product_categories)){

													foreach ($product_categories as $key => $value) {

																echo '<option value = "' . $value->slug . '"';

																if( !empty($cat_slug) ){

																	if( array_key_exists( $value->slug, $cat_slug ) ){

																			echo 'selected = "selected"';

																	}

																}

																echo '> ' . $value->name . ' </option>';

													}

											}

									?>

							</select>


						</td>


					</tr>


					<tr>


						<td><label for="fileUpload"><?php echo _e("Product Thumbnail", "marketplace");?>&nbsp;&nbsp;:</label></td>


						<td><?php if(isset($meta_arr['image']))


						{?>


						<img src="" width="50" height="50">


						<?php } ?>


						<div id="product_image">


						</div>


						<input type="hidden"  id="product_thumb_image_mp" name="product_thumb_image_mp" value="<?php if(isset($meta_arr['_thumbnail_id'])) echo $meta_arr['_thumbnail_id'];?>" />


						<a class="upload mp_product_thumb_image btn" href="javascript:void(0);" /><?php _e('Upload Thumb', 'marketplace');?></a>


						<?php


						if(!empty($product_images))


						{



								echo "<img style='display:inline;' src='".content_url()."/uploads/".$product_images."' width=50 height=50 />";



						}


						?>


						</td>


					</tr>


					<tr>

						<td>
						 	<label for="product_sku"><?php echo _e("Product SKU", "marketplace");?>
	                        	<span class="required">*</span>: &nbsp;
	                        	<span class="help">
		                        	<div class="wkmp-help-tip-sol"><?php echo _e("SKU refers to a Stock-keeping unit, a unique identifier for each distinct product and service that can be purchased.", "marketplace");?>
		                        	</div>
	                        		<span class="help-tip">[?]</span>
	                        	</span>
	                        </label>
						</td>


						<td><?php
								 if(!isset($meta_arr['_sku']))
										 echo '<input class="wkmp_product_input" type="text" name="product_sku" id="product_sku" value="" />';
								 else
										 echo "<h2>".$meta_arr['_sku']."</h2>";

								 ?>

						<div id="pro_sku_error" class="error-class"></div></td>


					 </tr>

					 <tr id="regularPrice">


						 <td><label for="regu_price"><?php echo _e("Regular Price", "marketplace");?><span class="required">*</span>&nbsp;&nbsp;:</label></td>


						 <td><input class="wkmp_product_input" type="text" name="regu_price" id="regu_price" value="<?php if(isset($meta_arr['_regular_price'])) echo $meta_arr['_regular_price'];?>" <?php echo ($pro_term_relation==4 || $pro_term_relation==3) ? 'disabled':''; ?>/>


						  <div id="regl_pr_error" class="error-class"></div></td>


					 </tr>


					<tr id="salePrice">


						<td>
							<label for="sale_price"><?php echo _e("Sale Price");?>&nbsp;&nbsp;:</label>
						</td>


						 <td><input class="wkmp_product_input" type="text" name="sale_price" id="sale_price" value="<?php if(isset($meta_arr['_sale_price'])) echo $meta_arr['_sale_price'];?>" <?php echo ($pro_term_relation==4) ? 'disabled':''; ?>/>


						<div id="sale_pr_error" class="error-class"></div></td>

					 </tr>
					 <?php /*}*/ ?>


					 <tr>


							<td><label for="short_desc"><?php echo _e("Product Short Desc", "marketplace");?>&nbsp;&nbsp;:</label></td>


							 <td><?php


							$settings = array(


									'media_buttons' => false, // show insert/upload button(s)


									'textarea_name' =>'short_desc',


									'textarea_rows' => get_option('default_post_edit_rows', 10),


									'tabindex' => '',


									'editor_class' => 'backend',


									'teeny' => false,


									'dfw' => false,


									'tinymce' => true,


									'quicktags' => false,


									'drag_drop_upload'=>true


									);


							if(isset($post_row_data[0]->post_excerpt))


							{


								$short_content=$post_row_data[0]->post_excerpt;


							}


							if(isset($short_content))
							echo wp_editor("$short_content",'short_desc',$settings);
							else
							echo wp_editor("",'short_desc',$settings);?>


							<div id="short_desc_error" class="error-class"></div></td>


					</tr>


					</tbody>


					</table>


			  </div>


	       <div class="wkmp_container" id="inventorytabwk">


					<table>


					<tbody>


					<tr>


					  	<td>
					  	 	<label for="wk-mp-stock"><?php echo _e("Manage Stock", "marketplace"). '?';?>&nbsp;:</label>
					  	</td>

	                    <td>
	                    	<input type="checkbox" class="wkmp_stock_management" id="wk_stock_management" name="wk_stock_management" value ="yes" <?php if($meta_arr['_manage_stock']=='yes') echo "checked";?> /><?php _e("Enable stock management at product level", "marketplace");?><br>
						</td>


	                </tr>


						<tr>


	                        <td><label for="wk-mp-stock"><?php echo _e("Stock Qty");?>&nbsp;:</label></td>


	                        <td><input type="text" class="wkmp_product_input" placeholder="0" name="wk-mp-stock-qty" id="wk-mp-stock-qty" value="<?php echo isset($meta_arr['_stock'])?$meta_arr['_stock']:'';?>" /></td>


	                    </tr>


						<tr>


	                        <td><label for="wk-mp-stock"><?php echo _e("Stock Status", "marketplace");?>&nbsp;:</label></td>


	                        <td>


							<select name="_stock_status" id="_stock_status" class="form-control">


								<option value="instock" <?php if(isset($meta_arr['_stock_status']) && $meta_arr['_stock_status']=='instock') echo 'selected="selected"';?> ><?php _e("In Stock", "marketplace");?></option>


								<option value="outofstock" <?php if(isset($meta_arr['_stock_status']) && $meta_arr['_stock_status']=='outofstock') echo 'selected="selected"';?>><?php _e("Out of Stock", "marketplace");?></option>


							</select>


							</td>


	                    </tr>


						<tr>


	                        <td><label for="wk-mp-backorders"><?php echo _e("Allow Backorders", "marketplace");?>&nbsp;:</label></td>


	                        <td>


								<select name="_backorders" id="_backorders" class="form-control">


								<option value="no" <?php if(isset($meta_arr['_backorders']) && $meta_arr['_backorders']=='no') echo 'selected="selected"';?>><?php echo _e("Do not allow", "marketplace");?></option>


								<option value="notify" <?php if( isset($meta_arr['_backorders']) && $meta_arr['_backorders']=='notify') echo 'selected="selected"';?>><?php echo _e("Allow but notify customer", "marketplace");?></option>


								<option value="yes" <?php if( isset($meta_arr['_backorders']) && $meta_arr['_backorders']=='yes') echo 'selected="selected"';?>><?php _e("Allow", "marketplace");?></option>


								</select>


							</td>


	                    </tr>

											<?php do_action( 'mp_edit_product_field', $wk_pro_id ); ?>

					</tbody>


					</table>


			  </div>

			  <div class="wkmp_container" id="shippingtabwk">

			  		<?php

					echo '<div class="options_group">';

						// Weight
						if ( wc_product_weight_enabled() ) {

							marketplace_wp_text_input( array( 'id' => '_weight', 'label' => __( 'Weight', 'marketplace' ) . ' (' . get_option( 'woocommerce_weight_unit' ) . ')', 'placeholder' => wc_format_localized_decimal( 0 ), 'desc_tip' => 'true', 'description' => __( 'Weight in decimal form', 'marketplace' ), 'type' => 'text', 'data_type' => 'decimal' ),$wk_id );
						}

						// Size fields
						if ( wc_product_dimensions_enabled() ) {

							?><p class="form-field dimensions_field">
								<label for="product_length"><?php echo __( 'Dimensions', 'marketplace' ) . ' (' . get_option( 'woocommerce_dimension_unit' ) . ')'; ?></label>
								<span class="wrap">
									<input id="product_length" placeholder="<?php esc_attr_e( 'Length', 'marketplace' ); ?>" class="input-text wc_input_decimal" size="6" type="text" name="_length" value="<?php echo esc_attr( wc_format_localized_decimal( get_post_meta( $wk_id, '_length', true ) ) ); ?>" />
									<input placeholder="<?php esc_attr_e( 'Width', 'marketplace' ); ?>" class="input-text wc_input_decimal" size="6" type="text" name="_width" value="<?php echo esc_attr( wc_format_localized_decimal( get_post_meta( $wk_id, '_width', true ) ) ); ?>" />
									<input placeholder="<?php esc_attr_e( 'Height', 'marketplace' ); ?>" class="input-text wc_input_decimal last" size="6" type="text" name="_height" value="<?php echo esc_attr( wc_format_localized_decimal( get_post_meta( $wk_id, '_height', true ) ) ); ?>" />
								</span>

								<?php echo wc_help_tip( __( 'LxWxH in decimal form', 'marketplace' ) ); ?>

							</p><?php
						}


					echo '</div>';

					echo '<div class="options_group">';

						// Shipping Class
						$classes = get_the_terms( $wk_id, 'product_shipping_class' );
						if ( $classes && ! is_wp_error( $classes ) ) {
							$current_shipping_class = current( $classes )->term_id;
						} else {
							$current_shipping_class = '';
						}

						$args = array(
							'taxonomy'         => 'product_shipping_class',
							'hide_empty'       => 0,
							'show_option_none' => __( 'No shipping class', 'marketplace' ),
							'name'             => 'product_shipping_class',
							'id'               => 'product_shipping_class',
							'selected'         => $current_shipping_class,
							'class'            => 'select short'
						);

						?><p class="form-field dimensions_field"><label for="product_shipping_class"><?php _e( 'Shipping class', 'marketplace' ); ?></label> <?php wp_dropdown_categories( $args ); ?> <?php echo wc_help_tip( __( 'Shipping classes are used by certain shipping methods to group similar products.', 'marketplace' ) ); ?></p><?php

						do_action('marketplace_product_options_shipping',$wk_pro_id);

					echo '</div>';

					?>

			  </div>

			  <div class="wkmp_container" id="linkedtabwk">

			  	<table>
			  		<tbody>
			  		<tr>
			  		<td>
			  		<label for="grouping"><?php echo _e("Grouping", "marketplace");?>&nbsp;&nbsp;:</label>
			  		</td>
			  		<td>
			  		<div class="select-group">
	        	   	<div class="bttn-group group-select">
			            <button type="button" class="btn dropdown-togle btn-default carret" data-toggle="dropdown" role="button" aria-expanded="true" <?php echo ($pro_term_relation==4 || $pro_term_relation==3) ? 'disabled':''; ?>>
			            		<span class="filter-option pull-left"><?php global $wpdb; $table_name = $wpdb->prefix.'posts'; $post_parent = $wpdb->get_results("Select post_parent from $table_name where ID = $wk_pro_id", ARRAY_A); if($post_parent[0]['post_parent']>0){$p_parent = $post_parent[0]['post_parent']; $post_title = $wpdb->get_results("Select post_title from $table_name where ID = $p_parent", ARRAY_A); echo get_post_meta($p_parent, '_sku', true).'-'.$post_title[0]['post_title'];} else esc_attr_e( 'Search for a product&hellip;'); ?></span>&nbsp;

			            		</span>

			            </button>
			            <div class="group-dropdown-menu open" role="combobox">
			            	<div class="grp-searchbox">
			            		<input type="text" class="form-control" autocomplete="off" role="textbox" aria-label="Search" id="check-group">
			            	</div>
			            	<ul class="group-dropdown-menu inner" role="listbox" aria-expanded="true">

			            		<li data-original-index="0" class="selected active">
			            			Search for Group
			            		</li>
			            		<li class="group-selected"></li>

			            	</ul>
			            </div>

			        <div class="checkbox-group">
			       		<input type="hidden" name="group_id" value="<?php if($post_parent[0]['post_parent']>0) echo $post_parent[0]['post_parent']; else ""?>">
			       		<input type="hidden" name="post_id_grp" value="<?php echo $wk_pro_id; ?>">
			       	</div>
			       	</td>
			        </tr>
	  			</div>
	    		</div>

	    		</tbody>
	    		</table>

	 		  </div>



	          <div class="wkmp_container" id="attributestabwk">


			   <div class="input_fields_toolbar">


			<button class="btn btn-success add-variant-attribute"><?php echo "+ "; _e("Add an attribute", "marketplace");?></button>


			<!--<button type="button" class="btn btn-default save_attributes" data-id="1347"><?php echo _e("Save attributes");?></button> -->


			</div>


				<div  class="wk_marketplace_attributes">
				<?php
				if(!empty($product_attributes))
				{$i=0;
				foreach( $product_attributes as $proatt)
				{
				?>
				<div class="wkmp_attributes">
					<div class="box-header attribute-remove">
					<input type="text" class="mp-attributes-name" placeholder="Attribute name" name="pro_att[<?php echo $i;?>][name]" value="<?php echo str_replace('-',' ',$proatt['name']);?>"/>
					<input type="text" class="option" title="<?php echo __('attribue value by seprating comma eg. a|b|c', 'marketplace'); ?>" placeholder=" <?php echo __('Value eg. a|b|c', 'marketplace'); ?>" name="pro_att[<?php echo $i;?>][value]" value="<?php echo $proatt['value'];?>"/>
					<input type="hidden" name="pro_att[<?php echo $i;?>][position]" class="attribute_position" value="<?php echo $proatt['position'];?>"/>


							<span class="mp_actions">
								<button class="mp_attribute_remove btn btn-danger">Remove</button>
							</span>


						</div>


						<div class="box-inside clearfix">


							<div class="wk-mp-attribute-config">


								<div class="checkbox-inline">


									<input type="checkbox" class="checkbox" name="pro_att[<?php echo $i;?>][is_visible]" value="1" <?php if($proatt['is_visible']=='1')echo "checked";?>/><?php echo __('Visible on the product page', 'marketplace'); ?></div>


								<div class="checkbox-inline">


									<input type="checkbox" class="checkbox" name="pro_att[<?php echo $i;?>][is_variation]" value="1" <?php if($proatt['is_variation']=='1')echo "checked";?>/><?php echo __('Used for variations', 'marketplace'); ?>


								</div>


								<input type="hidden" name="pro_att[<?php echo $i;?>][is_taxonomy]" value="<?php echo $proatt['taxonomy'];?>"/>


							</div>


							<div class="attribute-options"></div>


						</div>


					</div>


					<?php $i++;
					}

				}?>


				</div>

		 	 </div>

		  	<div class="wkmp_container" id="external_affiliate_tabwk">

		  	<table>
		  		<tbody>
		  			<tr>

						<td><label for="product_url"><?php echo _e("Product URL", "marketplace");?><span class="required">*</span>&nbsp;&nbsp;:</label></td>


						<td><input class="wkmp_product_input" type="text" name="product_url" id="product_url" size="54" value="<?php if(isset($meta_arr['_product_url'])) echo $meta_arr['_product_url'];?>" />


						<div id="pro_url_error" class="error-class"></div>


						</td>


					</tr>

					<tr>

						<td><label for="button_txt"><?php echo _e("Button Text", "marketplace");?><span class="required">*</span>&nbsp;&nbsp;:</label></td>


						<td><input class="wkmp_product_input" type="text" name="button_txt" id="button_txt" size="54" value="<?php if(isset($meta_arr['_button_text'])) echo $meta_arr['_button_text'];?>" />


						<div id="pro_btn_txt_error" class="error-class"></div>


						</td>


					</tr>
		  		</tbody>
		  	</table>

	 	  	</div>

	<!-- varication attribute of the product -->
		 <div class="wkmp_container" id="avariationtabwk">
			<div id="mp_attribute_variations">
			<?php
				echo marketplace_attributes_variation($wk_pro_id);
			?>
			 </div>
			 <div class="input_fields_toolbar_variation">
				 <div id="mp-loader"></div>
				 <button id="mp_var_attribute_call" class="btn btn-success"><?php echo "+ "; _e("Add Variation", "marketplace");?></button>
			</div>

	 	 </div>

		  <div class="wkmp_container" id="pro_statustabwk">


		 			 	<?php if(get_option('wkmp_seller_allow_publish')){ ?>
		 			 	<div class="mp-sidebar-container">


						<div class="mp_wk-post-status wkmp-toggle-sidebar">


							<label for="post_status"><?php echo _e("Product Status", "marketplace") . " :";?></label>


							<?php

									if ( isset( $post_row_data[0]->post_status ) && !empty( $post_row_data[0]->post_status ) && $post_row_data[0]->post_status == 'publish' ) {
											echo '<span class="mp-toggle-selected-display green">Online</span>';
									}
									else
									{
											echo '<span class="mp-toggle-selected-display">Draft</span>';
									}

							?>


							<a class="mp-toggle-sider-edit label label-success btn btn-default" href="javascript:void(0);" style="display: inline;">Edit</a>

								<div class="wkmp-toggle-select-container mp-hide" style="display: none;">


									<select id="product_post_status" class="wkmp-toggle-select" name="mp_product_status">


									<option value=""><?php echo __("Select status", "marketplace"); ?></option>

									<option value="publish" <?php if($post_row_data[0]->post_status=='publish') echo 'selected="selected"';?>><?php echo _e("Online", "marketplace");?></option>

									<option value="draft"  <?php if($post_row_data[0]->post_status=='draft') echo 'selected="selected"';?>><?php echo _e("Draft", "marketplace");?></option>


									</select>


									<a class="mp-toggle-save btn btn-default btn-sm" href="javascript:void(0);"><?php echo __('OK', 'marketplace'); ?></a>


									<a class="mp-toggle-cancel btn" href="javascript:void(0);"><?php echo __('Cancel', 'marketplace'); ?></a>


								</div>


							</div>





							</div>
							<?php } ?>

							<hr class="mp-section-seperate">

							<!-- downloadable starts -->

							<div class="wkmp-side-head">

								<label class="checkbox-inline">
										<input type="checkbox" id="_ckdownloadable" class="wk-dwn-check" name="_downloadable" value="yes" <?php if(isset($meta_arr['_downloadable']) && $meta_arr['_downloadable']=='yes')echo 'checked';?>/>&nbsp;&nbsp;
										<?php _e("Downloadable Product","marketplace");?>
								</label>

							</div>

							<div class="wk-mp-side-body" style="display:<?php if( isset($meta_arr['_downloadable']) && $meta_arr['_downloadable']=='yes')echo 'block';else echo 'none';?>" >

									<?php

									$mp_downloadable_files = get_post_meta( $wk_pro_id, '_downloadable_files', true );

									?>

									<div class="form-field downloadable_files">

											<label>Downloadable files</label>

											<table class="widefat">

													<thead>
															<tr>
																	<th>Name</th>
																	<th colspan="2">File URL</th>
																	<th>&nbsp;</th>
															</tr>
													</thead>

													<tbody>
															<?php
															
															if ( $mp_downloadable_files ) {
																	foreach ( $mp_downloadable_files as $key => $file )
																	{
																			include( 'wk-html-product-download.php' );
																	}
															}
															?>
													</tbody>

													<tfoot>
															<tr>
																	<th colspan="5">
																			<a href="#" class="button insert" data-row="<?php
																					$key  = '';
																					$file = array(
																							'file' => '',
																							'name' => '',
																					);
																					ob_start();

																					include( 'wk-html-product-download.php' );

																					echo esc_attr( ob_get_clean() );
																					?>">
																					<?php _e( 'Add File', 'woocommerce' ); ?>
																			</a>
																	</th>
															</tr>
													</tfoot>

											</table>

									</div>

									<p class="form-field _download_limit_field ">
											<label for="_download_limit">Download limit</label>
											<input type="number" class="short" style="padding: 3px 5px;" name="_download_limit" id="_download_limit" value="<?php if( isset( $meta_arr['_download_limit'] ) ) { if ( -1 == $meta_arr['_download_limit'] ) { echo ''; } else { echo $meta_arr['_download_limit']; } }?>" placeholder="Unlimited" step="1" min="0" />
											<span class="description">Leave blank for unlimited re-downloads.</span>
									</p>

									<p class="form-field _download_expiry_field ">
											<label for="_download_expiry">Download expiry</label>
											<input type="number" class="short" style="padding: 3px 5px;" name="_download_expiry" id="_download_expiry" value="<?php if( isset( $meta_arr['_download_expiry'] ) ) { if ( -1 == $meta_arr['_download_expiry'] ) { echo ''; } else { echo $meta_arr['_download_expiry']; } }?>" placeholder="Never" step="1" min="0" />
											<span class="description">Enter the number of days before a download link expires, or leave blank.</span>
									</p>

							</div>

							<hr class="mp-section-seperate">

							<!-- downloadable ends -->

							<div class="wkmp-side-head"><label><?php echo __('Image Gallery', 'marketplace'); ?></label></div>

								<div id="wk-mp-product-images">

								<div id="product_images_container">

							<?php


						if(isset($meta_arr['_product_image_gallery']) && $meta_arr['_product_image_gallery']!='')


						{


						$image_id=explode(',',get_post_meta( $wk_pro_id, '_product_image_gallery', true ));


							for($i=0;$i<count($image_id);$i++)


							{
								$image_url=wp_get_attachment_image_src($image_id[$i]);


								echo "<div class='mp_pro_image_gallary'><img src='".$image_url[0]."' width=50 height=50 />";?>


								<ul class="actions" style="list-style:none;">


									<li>


									<a href="javascript:void(0);" id="<?php echo $wk_pro_id.'i_'.$image_id[$i];?>" class="mp-img-delete_gal" title="Delete image"><?php echo __('Delete', 'marketplace'); ?></a>


									</li>


									</ul>


									</div>


							<?php }


							}

						?>


									</div>


									<div id="handleFileSelectgalaray">


									</div>


									<input type="hidden" class="wkmp_product_input" name="product_image_Galary_ids" id="product_image_Galary_ids" value="<?php if(isset($meta_arr['_product_image_gallery'])) echo $meta_arr['_product_image_gallery'];?>" />


									</div>


									<a href="javascript:void(0);" class="add-mp-product-images btn">+ <?php echo __('Add product images', 'marketplace'); ?></a>


						 </p>


						 <?php wp_nonce_field( 'marketplace-edid_product' ); ?>


						 </div>

						 <?php do_action( 'mp_edit_product_tabs_content', $wk_pro_id ); ?>

						 <br>


				<input type="submit" name="add_product_sub" id="add_product_sub" value="Update" class="button"/></td>


	       	</form>


	<?php

	unset($_POST);

	}
	else{

		echo __("<h2>Sorry But you can not edit other products..!</h2>", "marketplace");
	}
