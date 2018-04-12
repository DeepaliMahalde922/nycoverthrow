<?php

if( ! defined ( 'ABSPATH' ) )

    exit;

function add_favourite_seller_btn(){

 // Add new Favourite seller button after add to cart button

   global $wpdb,$product;

   $btn_txt = 'Add As Favourite Seller';

   $table = $wpdb->prefix . 'posts'; //Good practice

   $product_id = $product->get_id();

   $pro_author = $wpdb->get_row( "SELECT $table.post_author FROM $table WHERE $table.ID =".$product_id);


   if( isset( $pro_author->post_author) && !empty( $pro_author->post_author ) ) {

     $product_author = $pro_author->post_author;

   }
   else {

     $product_author = 1;

   }
   $sellers = get_user_meta(get_current_user_id(),'favourite_seller',false);


 ?>

   <div class='fav-seller'>

     <form action="<?php echo site_url().'/my-account/favourite-seller';?>" method="post">

       <?php wp_nonce_field( 'fv_sel_action', 'fv_sel_nonce_field' ); ?>

       <input type="hidden" value="<?php echo $product_author;?>" name="seller">

       <?php

         $favourite_seller_c = get_users(array(
              'meta_key'   =>'favourite_seller',
              'meta_value' => get_current_user_id()
           ));

           $favourite_seller_count=count($favourite_seller_c);

       if($favourite_seller_count>0) :	?>

         <div class="favourite-count">

           <?php echo "<p><strong>Favourited By</strong> : ".$favourite_seller_count." Peoples</p>"; ?>

         </div>

     <?php endif; ?>

     <?php

     if(!empty($sellers)){

         if(in_array($product_author, $sellers)) {

           echo '<button type="submit" name="submit_favourite" disabled>Added As Favourite Seller</button>';

         }
         else{

           echo '<button type="submit" name="submit_favourite">Add As Favourite Seller</button>';

         }
       }
       else{

         echo '<button type="submit" name="submit_favourite">Add As Favourite Seller</button>';

       } ?>

     </form>

   </div>

<?php
}
