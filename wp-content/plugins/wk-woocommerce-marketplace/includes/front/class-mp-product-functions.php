<?php

if( ! defined ( 'ABSPATH' ) )

    exit;

function product_add_update()
  {

   global $wpdb,$current_user, $children;

   $variation_att_id=isset($_POST['mp_attribute_variation_name'])?$_POST['mp_attribute_variation_name']:'';

   if(isset($_POST['sale_price'])){

     if($_POST['sale_price']==''){

       unset($_POST['sale_price']);

     }

   }
   $att_val=isset($_POST['pro_att'])?$_POST['pro_att']:'';

   $upload_dir = wp_upload_dir();

   $product_type=isset($_POST['product_type'])?$_POST['product_type']:'simple';

   $min_regu_price = "";

   $max_regu_price = "";

   $min_regu_price_id = "";

   $max_regu_price_id = "";

   $min_sale_price_id = "";

   $max_sale_price_id = "";

   if(!empty($variation_att_id) && !empty($att_val))
   {
     $variation_data=array();

     foreach($variation_att_id as $var_id)
     {
       $var_regu_price[$var_id] = $_POST['wkmp_variable_regular_price'][$var_id];
       $var_sale_price[$var_id] = $_POST['wkmp_variable_sale_price'][$var_id];

       foreach($_POST['mp_attribute_name'][$var_id] as $variation_type)
       {
         $variation_data['attribute_'.sanitize_title($variation_type)][]=trim( $_POST['attribute_'.$variation_type][$var_id] );
       }
       if(isset($_POST['wkmp_variable_is_downloadable']))
         $downloadable_vari=$_POST['wkmp_variable_is_downloadable'][$var_id] =='yes'?'yes':'no';
       else
         $downloadable_vari='no';

       if(isset($_POST['wkmp_variable_is_virtual']) && isset($_POST['wkmp_variable_is_virtual'][$var_id]))
         $virtual_vari=$_POST['wkmp_variable_is_virtual'][$var_id]=='yes'?'yes':'no';

       else
         $virtual_vari='no';

       $down_expiry=$downloadable_vari=='yes'? $_POST['wkmp_variable_download_expiry'][$var_id]:'';

       $down_limit=$downloadable_vari=='yes'? $_POST['wkmp_variable_download_limit'][$var_id]:'';

       $variation_data['_download_expiry'][]=isset( $_POST['wkmp_variable_download_expiry'] ) ? $_POST['wkmp_variable_download_expiry'][$var_id] : '';

       $variation_data['_download_limit'][]=isset( $_POST['wkmp_variable_download_limit'] ) ? $_POST['wkmp_variable_download_limit'][$var_id] : '';

       $variation_data['_sale_price'][]=$_POST['wkmp_variable_sale_price'][$var_id];

       if($_POST['wkmp_variable_sale_price'][$var_id]==''){
         $variation_data['_price'][]=$_POST['wkmp_variable_regular_price'][$var_id];
       }

       else{
         $variation_data['_price'][]=$_POST['wkmp_variable_sale_price'][$var_id];
       }

       $variation_data['_regular_price'][]=$_POST['wkmp_variable_regular_price'][$var_id];

       if(isset($_POST['wkmp_variable_sale_price_dates_to'])){
         $variation_data['_sale_price_dates_to'][]=$_POST['wkmp_variable_sale_price_dates_to'][$var_id];
       }

       if(isset($_POST['wkmp_variable_sale_price_dates_from'])){
         $variation_data['_sale_price_dates_from'][]=$_POST['wkmp_variable_sale_price_dates_from'][$var_id];
       }

       $variation_data['_backorders'][]=$_POST['wkmp_variable_backorders'][$var_id];

       if(isset($_POST['wkmp_variable_manage_stock']) && isset($_POST['wkmp_variable_manage_stock'][$var_id])){
         $manage_stock=$_POST['wkmp_variable_manage_stock'][$var_id]=='yes'?'yes':'no';
       }

       else{
         $manage_stock='no';
       }

       $variation_data['_manage_stock'][]=$manage_stock;

       if($manage_stock=='yes')
         $variation_data['_stock'][]=$_POST['wkmp_variable_stock'][$var_id];

       else
         $variation_data['_stock'][]='';

       $variation_data['_stock_status'][]=$_POST['wkmp_variable_stock_status'][$var_id];

       $variation_data['_sku'][]=$_POST['wkmp_variable_sku'][$var_id];

       $variation_data['_width'][]=$_POST['wkmp_variable_width'][$var_id];

       $variation_data['_height'][]=$_POST['wkmp_variable_height'][$var_id];

       $variation_data['_length'][]=$_POST['wkmp_variable_length'][$var_id];

       $variation_data['_virtual'][]=$virtual_vari;

       $variation_data['_downloadable'][]=$downloadable_vari;

       $thumbnail_id=$_POST['upload_var_img'][$var_id];

       if($thumbnail_id!='')
         $variation_data['_thumbnail_id'][]=$thumbnail_id;

       else
         $variation_data['_thumbnail_id'][]=0;

       $variation_data['_weight'][]=$_POST['wkmp_variable_weight'][$var_id];

       $variation_data['_menu_order'][]=$_POST['wkmp_variation_menu_order'][$var_id];


       /* variation for download able product */
       if($downloadable_vari=='yes'){

         $variation_files=$_POST['_mp_variation_downloads_files_url'][$var_id];

         $variation_names=$_POST['_mp_variation_downloads_files_name'][$var_id];

         $var_downloadable=array();

         $var_down_name=array();

         if(isset($_POST['_mp_variation_downloads_files_url'][$var_id]) && count($_POST['_mp_variation_downloads_files_url'][$var_id])>0)
         {
           $files=array();
           /*$file_url_size = count( $variation_files );*/
           /*foreach ($variation_files as $key => $value) {*/
           if(!empty($variation_files)){
             for ($i=0; $i < count($variation_files); $i++){
               $file_url  = wc_clean( $variation_files[$i]);
               if($file_url!=''){
                 $files[ md5( $file_url ) ] = array(
                 'name' =>$variation_names[$i],
                 'file' => $file_url);
               }
             }
           }
           update_post_meta( $var_id, '_downloadable_files', $files );
         }
       }

     }

     $min_regu_price = min($var_regu_price);

     foreach ($var_regu_price as $key => $value) {
       if($value == $min_regu_price)
         $min_regu_price_id = $key;
     }

     $max_regu_price = max($var_regu_price);

     foreach ($var_regu_price as $key => $value) {
       if($value == $max_regu_price)
         $max_regu_price_id = $key;
     }

     $min_sale_price = min($var_sale_price);

     foreach ($var_sale_price as $key => $value) {
       if($value == $min_sale_price)
         $min_sale_price_id = $key;
     }

     $max_sale_price = max($var_sale_price);

     foreach ($var_sale_price as $key => $value) {
       if($value == $max_sale_price)
         $max_sale_price_id = $key;
     }


     $variation_data_key = array_keys($variation_data);
     $variations_values = array_values($variation_data);

     for($i=0;$i<count($variation_data);$i++)
     {
       for($x=0;$x<count($variation_att_id);$x++)
       {
         update_post_meta($variation_att_id[$x],$variation_data_key[$i],$variations_values[$i][$x]);
         if($variation_data_key[$i]=='_sale_price' && $variations_values[$i][$x]==''){
           delete_post_meta($variation_att_id[$x],'_sale_price');
         }
       }
     }

   }

   if(isset($_POST['pro_att'])) {
     $attrib=$_POST['pro_att'];
   }

   else {
     $attrib=array();
   }

   $att=array();

   if(!empty($attrib))
   {
     foreach($attrib as $attribute)
     {

       if(empty($attribute['name']) || empty($attribute['value']))
       {
         continue;
       }

       $rep_str=$attribute['value'];

       $rep_str=preg_replace('/\s+/', ' ', $rep_str);

       $attribute['name']=	str_replace(' ','-',$attribute['name']);

       $attribute['value']=str_replace("|","|",$rep_str);

       if(isset($attribute['is_visible']))
         $attribute['is_visible']=(int)$attribute['is_visible'];

       else
         $attribute['is_visible']=0;

       if(isset($attribute['is_variation']))
         $attribute['is_variation']=(int)$attribute['is_variation'];

       else
         $attribute['is_variation']=0;

       $attribute['is_taxonomy']=(int)$attribute['is_taxonomy'];

       $att[str_replace(' ','-',$attribute['name'])]=$attribute;

     }
   }

   $user_id = get_current_user_id();

   if(isset($_POST['sell_pr_id']))
     $product_auth=$wpdb->get_var("select post_author from $wpdb->posts where ID='".$_POST['sell_pr_id']."'");

   if(!empty($_POST['product_name']) && isset($_POST['product_name']))
   {
     $product_name=$_POST['product_name'];

     $product_dsc=$_POST['product_desc'];

     $downloadable=isset($_POST['_downloadable'])?$_POST['_downloadable']:'';

     $virtual=$product_type=='virtual'?'yes':'no';

     $simple=$product_type=='simple'?'yes':'no';

     if(isset($_POST['product_type']))
       $protype=$_POST['product_type'];

     $backorder=isset($_POST['_backorders'])?$_POST['_backorders']:'no';

     $stock=isset($_POST['_stock_status'])?$_POST['_stock_status']:'instock';

     $SKU= isset($_POST['product_sku']) ? $_POST['product_sku'] : '';

     $price=isset($_POST['regu_price'])?$_POST['regu_price']:'';

     if(isset($_POST['sale_price'])){
       $sales_price=$_POST['sale_price'];
     }

     $product_short_desc = $_POST['short_desc'];

     $limit = isset($_POST['_download_limit']) && $_POST['_download_limit'] ? $_POST['_download_limit'] : '-1';

     $expiry = isset($_POST['_download_expiry']) && $_POST['_download_expiry'] ? $_POST['_download_expiry'] : '-1';

     $mang_stock=isset($_POST['wk_stock_management'])? $_POST['wk_stock_management'] : 'no';

     $stock_qty=($mang_stock=='yes')?$_POST['wk-mp-stock-qty']:'';

     $usere_downloadable_file_name = isset( $_POST['_mp_dwnld_file_names'] ) ? $_POST['_mp_dwnld_file_names'] : '';

     $usere_downloadable_file_hashes = isset( $_POST['_mp_dwnld_file_hashes'] ) ? $_POST['_mp_dwnld_file_hashes'] : '';

     $seller_downloadable_file_url = isset( $_POST['_mp_dwnld_file_urls'] ) ? $_POST['_mp_dwnld_file_urls'] : '';

     if(isset($_POST['product_image_Galary_ids']))
       $product_galary_images=implode(',',array_unique(explode(',',$_POST['product_image_Galary_ids'])));
     else
       $product_galary_images='';

     //$sale_from=$_POST['sale_from'];

     $sale_from=isset($_POST['sale_from'])?$_POST['sale_from']:'';

     //$sale_to=$_POST['sale_to'];

     $sale_to=isset($_POST['sale_to'])?$_POST['sale_to']:'';

     $product_status=isset($_POST['mp_product_status'])?$_POST['mp_product_status']:'';

     $product_data=array('post_author'=>get_current_user_id(),

        'post_date'=>'',

        'post_date_gmt'=>'',

        'post_content'=>$product_dsc,

        'post_content_filtered'=>$product_short_desc,

        'post_title'=>$product_name,

        'post_excerpt'=>$product_short_desc,

        'post_status'=>$product_status,

        'post_type'=>'product',

        'comment_status'=>'open',

        'ping_status'=>'open',

        'post_password'=>'',

        'post_name'=>wp_strip_all_tags($product_name),

        'to_ping'=>'',

        'pinged'=>'',

        'post_modified'=>'',

        'post_modified_gmt'=>'',

        'post_parent'=>'',

        'menu_order'=>'',

        'guid'=>''

     );
     if(isset($_POST['sell_pr_id'])&& $product_auth==$user_id && isset($_POST['add_product_sub']) && $_POST['add_product_sub']=='Update' && !empty( $_POST['_wpnonce'] ))
     {
       $product_type=$_POST['product_type'];

       wp_verify_nonce( $_POST['_wpnonce'], 'marketplace-edid_product' );

       // Add mp shipping per product addon data

       do_action('marketplace_process_product_meta',$_POST['sell_pr_id']);

       $product_shipping_class = $_POST['product_shipping_class'] > 0 && $product_type != 'external' ? absint( $_POST['product_shipping_class'] ) : '';

         wp_set_object_terms( $_POST['sell_pr_id'], $product_shipping_class, 'product_shipping_class');

       $product_data['ID']=$_POST['sell_pr_id'];

       if(wp_update_post($product_data))
       {

         wc_print_notice( __( 'Product Updated Successfully.', 'woocommerce' ) );

         $role = $wpdb->prefix . 'capabilities';

         $current_user->role = array_keys($current_user->$role);

         $role = $current_user->role[0];

         if($product_status=='publish' && $role=='wk_marketplace_seller')
         {
           update_post_meta($_POST['sell_pr_id'],'_visibility', 'visible');
         }
         else
         {
           update_post_meta($_POST['sell_pr_id'],'_visibility', '');
         }

         update_post_meta($_POST['sell_pr_id'],'_regular_price', $price);

         if($protype!='variable') {
           if(isset($sales_price)){
             update_post_meta($_POST['sell_pr_id'],'_sale_price', $sales_price);

             update_post_meta($_POST['sell_pr_id'],'_price', $sales_price);

           }else{
             update_post_meta($_POST['sell_pr_id'],'_sale_price', '');

             update_post_meta($_POST['sell_pr_id'],'_price', $price);
           }
         }
         else {

           $meta_key = '_transient_wc_product_children_' . $_POST['sell_pr_id'];
           $var_data['all'] = $variation_att_id;
           $var_data['visible'] = $variation_att_id;

           $transient_name = 'wc_product_children_' . $_POST['sell_pr_id'];
           $children = array_filter( array_map( 'absint', (array) get_transient( $transient_name ) ) );

           $args = apply_filters( 'woocommerce_grouped_children_args', array(
            'post_parent' 	=> $_POST['sell_pr_id'],
            'post_type'		=> 'product',
            'orderby'		=> 'menu_order',
            'order'			=> 'ASC',
            'fields'		=> 'ids',
            'post_status'	=> 'publish',
            'numberposts'	=> -1,
           ) );

           $children = get_posts( $args );

           set_transient( $transient_name, $children, DAY_IN_SECONDS * 30 );

           if(!empty($min_sale_price) && !empty($max_sale_price)) {

             update_post_meta($_POST['sell_pr_id'], '_min_variation_price', $min_sale_price);

             update_post_meta($_POST['sell_pr_id'], '_max_variation_price', $max_sale_price);

             update_post_meta($_POST['sell_pr_id'], '_min_price_variation_id', $min_sale_price_id);

             update_post_meta($_POST['sell_pr_id'], '_max_price_variation_id', $max_sale_price_id);

             update_post_meta($_POST['sell_pr_id'], '_min_variation_regular_price', $min_regu_price);

             update_post_meta($_POST['sell_pr_id'], '_max_variation_regular_price', $max_regu_price);

             update_post_meta($_POST['sell_pr_id'], '_min_regular_price_variation_id', $min_regu_price_id);

             update_post_meta($_POST['sell_pr_id'], '_max_regular_price_variation_id', $max_regu_price_id);

             update_post_meta($_POST['sell_pr_id'], '_min_variation_sale_price', $min_sale_price);

             update_post_meta($_POST['sell_pr_id'], '_max_variation_sale_price', $max_sale_price);

             update_post_meta($_POST['sell_pr_id'], '_min_sale_price_variation_id', $min_sale_price_id);

             update_post_meta($_POST['sell_pr_id'], '_max_sale_price_variation_id', $max_sale_price_id);

             delete_post_meta($_POST['sell_pr_id'],'_price');

             add_post_meta($_POST['sell_pr_id'], '_price', $min_sale_price);

             add_post_meta($_POST['sell_pr_id'], '_price', $max_sale_price);



           }
           else {

             update_post_meta($_POST['sell_pr_id'], '_min_variation_price', $min_regu_price);

             update_post_meta($_POST['sell_pr_id'], '_max_variation_price', $max_regu_price);

             update_post_meta($_POST['sell_pr_id'], '_min_price_variation_id', $min_regu_price_id);

             update_post_meta($_POST['sell_pr_id'], '_max_price_variation_id', $max_regu_price_id);

             update_post_meta($_POST['sell_pr_id'], '_min_variation_regular_price', $min_regu_price);

             update_post_meta($_POST['sell_pr_id'], '_max_variation_regular_price', $max_regu_price);

             update_post_meta($_POST['sell_pr_id'], '_min_regular_price_variation_id', $min_regu_price_id);

             update_post_meta($_POST['sell_pr_id'], '_max_regular_price_variation_id', $max_regu_price_id);

             update_post_meta($_POST['sell_pr_id'], '_min_variation_sale_price', NULL);

             update_post_meta($_POST['sell_pr_id'], '_max_variation_sale_price', NULL);

             update_post_meta($_POST['sell_pr_id'], '_min_sale_price_variation_id', NULL);

             update_post_meta($_POST['sell_pr_id'], '_max_sale_price_variation_id', NULL);

             delete_post_meta($_POST['sell_pr_id'],'_price');

             add_post_meta($_POST['sell_pr_id'], '_price', $min_regu_price);

             add_post_meta($_POST['sell_pr_id'], '_price', $max_regu_price);



           }
         }

         // update_post_meta($_POST['sell_pr_id'],'_sku', $SKU);

         update_post_meta($_POST['sell_pr_id'],'_backorders',$backorder);

         update_post_meta($_POST['sell_pr_id'],'_stock_status',$stock);

         update_post_meta($_POST['sell_pr_id'],'_manage_stock',$mang_stock);

         // if ($mang_stock=='yes') {
         // 	update_post_meta($_POST['sell_pr_id'],'_downloadable',$downloadable);

         // }else{
         // 	delete_post_meta($_POST['sell_pr_id'],'_downloadable');
         // }

         update_post_meta($_POST['sell_pr_id'],'_virtual',$virtual);

         update_post_meta($_POST['sell_pr_id'],'_simple',$simple);

        //  update_post_meta( $_POST['sell_pr_id'], '_downloadable_files','');

         if(isset($_POST['my-virtual'])){
           update_post_meta($_POST['sell_pr_id'], '_weight', '' );
           update_post_meta($_POST['sell_pr_id'], '_length', '' );
           update_post_meta($_POST['sell_pr_id'], '_width', '' );
           update_post_meta($_POST['sell_pr_id'], '_height', '' );
         }
         else{

           if ( isset( $_POST['_weight'] ) ) {
             update_post_meta($_POST['sell_pr_id'], '_weight', ( '' === $_POST['_weight'] ) ? '' : wc_format_decimal( $_POST['_weight'] ) );
           }

           if ( isset( $_POST['_length'] ) ) {
             update_post_meta($_POST['sell_pr_id'], '_length', ( '' === $_POST['_length'] ) ? '' : wc_format_decimal( $_POST['_length'] ) );
           }

           if ( isset( $_POST['_width'] ) ) {
             update_post_meta($_POST['sell_pr_id'], '_width', ( '' === $_POST['_width'] ) ? '' : wc_format_decimal( $_POST['_width'] ) );
           }

           if ( isset( $_POST['_height'] ) ) {
             update_post_meta($_POST['sell_pr_id'], '_height', ( '' === $_POST['_height'] ) ? '' : wc_format_decimal( $_POST['_height'] ) );
           }
         }

         if($protype=='external') {
           if(isset($_POST['product_url']) && isset($_POST['button_txt'])) {

             $pro_url = $_POST['product_url'];

             $btn_txt = $_POST['button_txt'];

             update_post_meta($_POST['sell_pr_id'], '_product_url', $pro_url);

             update_post_meta($_POST['sell_pr_id'], '_button_text', $btn_txt);
           }
         }

         if(!empty($_POST['group_id'])) {

           $group_id = $_POST['group_id'];

           $args = array(
             'ID'         => $_POST['sell_pr_id'],
             'post_parent' => $group_id
             );
           wp_update_post($args);

           if ( ! is_array( $children ) || empty( $children ) ) {
             $transient_name = 'wc_product_children_' . $group_id;
             $children = array_filter( array_map( 'absint', (array) get_transient( $transient_name ) ) );

             if ( empty( $children ) ) {

               $args = apply_filters( 'woocommerce_grouped_children_args', array(
                 'post_parent' 	=> $group_id,
                 'post_type'		=> 'product',
                 'orderby'		=> 'menu_order',
                 'order'			=> 'ASC',
                 'fields'		=> 'ids',
                 'post_status'	=> 'publish',
                 'numberposts'	=> -1,
               ) );

               $children = get_posts( $args );

               set_transient( $transient_name, $children, DAY_IN_SECONDS * 30 );
             }
           }
         }

         if ( $downloadable == 'yes' ) {

            $upload_file_url = '';

            $file_hashes = isset( $_POST['_mp_dwnld_file_hashes'] ) ? $_POST['_mp_dwnld_file_hashes'] : array();
            update_post_meta( $_POST['sell_pr_id'], '_downloadable', $downloadable );

            update_post_meta( $_POST['sell_pr_id'], '_virtual', 'yes' );

            $dwnload_url  = $seller_downloadable_file_url ? wc_clean( $seller_downloadable_file_url ) : array();

            foreach ( $dwnload_url as $key => $value )
            {
                $dw_file_name = ( !empty( $usere_downloadable_file_name[$key] ) ) ? $usere_downloadable_file_name[$key] : '';

                $upload_file_url[ md5( $value ) ] = array(
                    'id'   => md5( $value ),
                    'name' => $dw_file_name,
                    'file' => $value,
                    'previous_hash' => wc_clean( $file_hashes[ $key ] )
                );
            }

            $data_store = WC_Data_Store::load( 'customer-download' );

            if ( $upload_file_url ) {
        			foreach ( $upload_file_url as $download ) {
                $new_hash = md5( $download['file'] );

        				if ( $download['previous_hash'] && $download['previous_hash'] !== $new_hash ) {
        					// Update permissions.
        					$data_store->update_download_id( $_POST['sell_pr_id'], $download['previous_hash'], $new_hash );
        				}
        			}
        		}

            update_post_meta( $_POST['sell_pr_id'], '_downloadable_files', $upload_file_url );

         }
         else
         {
           update_post_meta( $_POST['sell_pr_id'], '_downloadable', 'no' );
         }

         if(!empty($att))
         {
           update_post_meta($_POST['sell_pr_id'],'_product_attributes',$att);
         }

         else{
           update_post_meta($_POST['sell_pr_id'],'_product_attributes',array());
         }

         if($stock_qty!=''){
           update_post_meta($_POST['sell_pr_id'],'_stock',$stock_qty);

         }else{
           delete_post_meta($_POST['sell_pr_id'],'_stock');
         }

         update_post_meta($_POST['sell_pr_id'],'_download_limit',$limit);

         update_post_meta($_POST['sell_pr_id'],'_download_expiry',$expiry);

         update_post_meta($_POST['sell_pr_id'],'_product_image_gallery',$product_galary_images);

         update_post_meta($_POST['sell_pr_id'],'_thumbnail_id',$_POST['product_thumb_image_mp']);

       }

       $download_product=$product_image_gal=$_FILES;

       $p_category=isset($_POST['product_cate'])?$_POST['product_cate']:'';

       MP_Form_Handler::update_pro_category($p_category,$_POST['sell_pr_id']);

       $product_id[0]=$_POST['sell_pr_id'];

       $product_id[1]=wp_set_object_terms( $_POST['sell_pr_id'], $protype, 'product_type',false);

     }

       else
       {
       $postid = wp_insert_post($product_data);

       add_post_meta($postid,'_thumbnail_id',$_POST['product_thumb_image_mp']);

       $data = array(
          'ID' => $postid,
          'guid' => get_option('siteurl') .'/?post_type=ai1ec_event&p='.$postid.'&instance_id='
       );

       $field = '';

       if(isset($_POST['base_product_id'])){
         $field = $_POST['base_product_id'];
       }

       if(wp_update_post( $data ))
       {
         do_action('marketplace_insert_product_meta', $postid, $field);

         add_post_meta($postid,'_sku',$SKU);

         add_post_meta($postid,'_regular_price',$price);

         if(isset($sales_price)){
           add_post_meta($postid,'_sale_price',$sales_price);

           add_post_meta($postid,'_price',$sales_price);
         }else{
           add_post_meta($postid,'_sale_price','');

           add_post_meta($postid,'_price',$price);
         }

         add_post_meta($postid,'_manage_stock',$mang_stock);

         add_post_meta($postid,'_sale_price_dates_from',$sale_from);

         add_post_meta($postid,'_sale_price_dates_to',$sale_to);

         add_post_meta($postid,'_downloadable',$downloadable);

         add_post_meta($postid,'_virtual',$virtual);

         add_post_meta($postid,'_simple',$simple);

         if(isset($_POST['product_type'])){

						$protype=$_POST['product_type'];

						if( $protype == 'variable' ){

								update_post_meta($postid, '_min_variation_price', '');

								update_post_meta($postid, '_max_variation_price', '');

								update_post_meta($postid, '_min_price_variation_id', '');

								update_post_meta($postid, '_max_price_variation_id', '');

								update_post_meta($postid, '_min_variation_regular_price', '');

								update_post_meta($postid, '_max_variation_regular_price', '');

								update_post_meta($postid, '_min_regular_price_variation_id', '');

								update_post_meta($postid, '_max_regular_price_variation_id', '');

								update_post_meta($postid, '_min_variation_sale_price', NULL);

								update_post_meta($postid, '_max_variation_sale_price', NULL);

								update_post_meta($postid, '_min_sale_price_variation_id', NULL);

								update_post_meta($postid, '_max_sale_price_variation_id', NULL);

						}

						wp_set_object_terms( $postid, $protype, 'product_type',false);

					}

       }

       $p_category=isset($_POST['product_cate'])?$_POST['product_cate']:'';

       add_pro_category($p_category,$postid);

       $product_id[0]=$postid;
       }

     return $product_id;
   }
  }

  //add product category
function add_pro_category($cat_id, $postid)
{
		if( strpos( $cat_id, ',' ) ){

				$cat_id = explode( ',', $cat_id );

				wp_set_object_terms($postid, $cat_id, 'product_cat');

		}
		else{

				$term = get_term_by('slug', $cat_id, 'product_cat');

				wp_set_object_terms($postid, $term->term_id, 'product_cat');

		}

}
