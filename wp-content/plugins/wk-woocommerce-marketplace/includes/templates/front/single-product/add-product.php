<?php


	global $wpdb;
	$wpmp_obj4=new MP_Form_Handler();
 	/*MP_Form_Handler::marketplace_media_fix($post_id);*/
 	$wpmp_obj4->marketplace_media_fix();
?>

<h2><?php echo _e("Add Product", "marketplace"); ?></h2>

<div class="form">

	<?php if( isset( $_POST['product_cate'] ) && isset( $_POST['product_type'] )  && isset( $_POST['add_product_cat_type'] ) ) : ?>

	  <form action="<?php echo get_permalink().'product/edit';?>" method="post" enctype="multipart/form-data" id="product-form">

	    <fieldset>

				<table>

					<tbody>

	        <tr>

											<td>

												<label for="product_name"><?php echo _e("Product Name", "marketplace");?><span class="required">*</span>&nbsp;&nbsp;:</label>
											</td>

											<td>

												<input class="wkmp_product_input" type="text" name="product_name" id="product_name" size="54" value="" />

												<div id="pro_name_error" class="error-class"><div>

											</td>

	                  </tr>

					<tr>



				 		<td><label for="product_desc"><?php echo _e("About Product", "marketplace");?>&nbsp;&nbsp;:</label></td>



	         	<td><?php



							$settings = array(



									'media_buttons' => true, // show insert/upload button(s)



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

							if(isset($content)){
								echo wp_editor($content,'product_desc',$settings);
							}else{
								echo wp_editor('','product_desc',$settings);
							}
							?>


							<div id="long_desc_error" class="error-class"><div>
	          </td>
	        </tr>

	          <!--<tr>



	              <td><label for="product_type"><?php echo _e("Product Type");?><span class="required">*</span>&nbsp;&nbsp;:</label></td>



	              <td><select size="1" name="product_type" id="product_type">



	                      <option value="no"><?php echo _e("Select Product Type");?></option>



	                      <option value="_downloadable"><?php echo _e("Downloadable");?></option>



	                      <option value="_virtual"><?php echo _e("Virtual");?></option>



	                  </select>



	              </td>



	          </tr>-->

					<tr>
						<td>
						</td>

	          <td>
								<?php
										if( array_key_exists( '1', $_POST['product_cate'] ) ){
												$product_cat = implode(',', $_POST['product_cate']);
										}
										else{
												$product_cat = $_POST['product_cate'][0];
										}
								?>
								<input type = "hidden" name = "product_cate" value = "<?php echo $product_cat; ?>" >
								<input type = "hidden" name = "product_type" value = "<?php echo $_POST['product_type']; ?>" >
	          </td>
					</tr>

					<tr>
						<td>
							<label for="fileUpload"><?php echo _e("Product Thumbnail", "marketplace");?>&nbsp;&nbsp;:</label>
						</td>

						<td>
							<div id="product_image"></div>
							<input type="hidden"  id="product_thumb_image_mp" name="product_thumb_image_mp" />
							<a class="upload mp_product_thumb_image btn" href="javascript:void(0);" /><?php _e('Upload Thumb', 'marketplace');?></a>
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

	          <td>
	            	<input class="wkmp_product_input" type="text" name="product_sku" id="product_sku" value="" />
							<div id="pro_sku_error" class="error-class"><div>
						</td>
	        </tr>

					<tr>
	          <td>
	          	<label for="regu_price"><?php echo _e("Regular Price", "marketplace");?><span class="required">*</span>&nbsp;&nbsp;:</label>
	          </td>
	          <td>
	          	<input class="wkmp_product_input" type="text" name="regu_price" id="regu_price" value="" />
	          	<div id="regl_pr_error" class="error-class"><div>
	          </td>
	        </tr>

					<tr>
	          <td>
	          	<label for="sale_price"><?php echo _e("Sale Price", "marketplace");?>&nbsp;&nbsp;:</label>
	          </td>

	          <td>
	            <input class="wkmp_product_input" type="text" name="sale_price" id="sale_price" value="" />
							<div id="sale_pr_error" class="error-class"><div>
						</td>
	        </tr>

					<tr>
					 	<td>
					 		<label for="short_desc"><?php echo _e("Product Short Descr", "marketplace");?>&nbsp;&nbsp;:</label>
					 	</td>

	          <td><?php

							$settings = array(



									'media_buttons' => false, // show insert/upload button(s)



									'textarea_name' =>'short_desc',



									'textarea_rows' => get_option('default_post_edit_rows', 10),



									'tabindex' => '',



									'editor_class' => 'frontend',



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

							if(isset($short_content)){
								echo wp_editor($short_content,'short_desc',$settings);
							}else{
								echo wp_editor("",'short_desc',$settings);
							}

						?>



							<div id="short_desc_error" class="error-class"></div>
						</td>

	        </tr>

	        <tr>
						<td></td>

	          <td>

							<input type="submit" name="add_product_sub" id="add_product_sub" value='<?php echo __("Save", "marketplace"); ?>' class="button"/></td>

	        </tr>

				</tbody>

				</table>



					<?php apply_filters('mp_user_redirect','redirect user')?>

			</fieldset>

	  </form>

	<?php else : ?>

		<?php

				if( isset( $_POST['add_product_cat_type'] ) ){

						wc_print_notice( ' Sorry, Firstly select product category(s) and type. ', 'error' );

				}

		?>

		<form action = "<?php echo get_permalink().'add-product'; ?>" method = "post" >

			<table>

					<tbody>

							<tr>

									<td>

											<label for="mp_seller_product_categories">Product categories</label>

									</td>

									<td>
											<select id="mp_seller_product_categories" name="product_cate[]" style="width: 50%;" class="wc-enhanced-select select2-hidden-accessible enhanced" multiple="" data-placeholder="Any category" tabindex="-1" aria-hidden="true">

													<?php
															$product_categories = get_terms( 'product_cat', array('hide_empty' => false));

															if(!empty($product_categories)){

																	foreach ($product_categories as $key => $value) {
																		?>
																				<option value = "<?php echo $value->slug; ?>"><?php echo $value->name; ?></option>
																		<?php
																	}

															}
													?>

											</select>

									</td>

							</tr>

							<tr>

									<td>

											<label for="product_type">Product Type</label>

									</td>

									<td>

											<select name="product_type" id="product_type" class="mp-toggle-select">

												<?php

													$mp_product_type = wc_get_product_types();

													$pro_term_relation=$wpdb->get_var("select wtr.term_taxonomy_id from {$wpdb->prefix}term_relationships as wtr join {$wpdb->prefix}term_taxonomy wtt on  wtr.term_taxonomy_id=wtt.term_taxonomy_id where wtt.taxonomy='product_type' and wtr.object_id=$wk_pro_id");

													foreach($mp_product_type as $key=>$pro_type)
													{ ?>
														<option value="<?php echo $key;?>" <?php $p_term = get_term_by('slug',$key,'product_type'); if($p_term->term_id==$pro_term_relation) echo 'selected="selected"'; ?> ><?php echo $pro_type;?></option>

														<?php
													}
												?>

											</select>

									</td>

							</tr>

							<tr>

								<td></td>

			          <td>

									<input type="submit" name="add_product_cat_type" id="add_product_cat_type" value='<?php echo __("Next", "marketplace"); ?>' class="button"/></td>

			        </tr>

					</tbody>

			</table>

		</form>

	<?php endif; ?>

</div>
