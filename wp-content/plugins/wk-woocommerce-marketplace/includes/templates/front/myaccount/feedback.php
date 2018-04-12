<?php

$wpmp_obj13=new MP_Form_Handler();
if(isset($_POST) && !empty($_POST))

	{

	set_reviews();

	unset($_POST);

	}

$current_time = date('Y-m-d H:i:s') ;
$sellerurl=get_query_var('info');
global $wpdb;

$user =get_users(
  array(
   'meta_key' => 'shop_address',
   'meta_value' => $sellerurl
    )
);
foreach ($user as $value) {

	$seller_id=$value->ID;
}

$user_id=get_current_user_id();

/*$Result=mp_form_handler::original_review($seller_id);*/
$Result=get_review($seller_id);

?>

<div class="Give_feedback wkmp_login_to_feedback"><?php echo _e("GIVE FEEDBACK", "marketplace");?></div>

<div class="mp_paging">

<?php foreach($Result as $item)

{

?>

<div class="wk_feedback_main">

	<div class='wkmp_feedback_price_out'>

		<div class="wkmp_feedback_out"><strong><?php echo _e("Price", "marketplace");?> :&nbsp;&nbsp;&nbsp;</strong></div>

		<?php for($i=0; $i<$item->price_r; $i++)

		{?>

		<div class="wkmp_ystar"></div>

		<?php }?>

		<?php for($i=0; $i<5-$item->price_r; $i++)

		{?>

		<div class="wkmp_gstar"></div>

		<?php }

		?>



	</div>

	<div class='wkmp_feedback_price_out'>

		<div class="wkmp_feedback_out"><strong><?php echo _e("VALUE", "marketplace");?>  :&nbsp;&nbsp;</strong></div>

		<?php for($i=0; $i<$item->value_r; $i++)

		{?>

		<div class="wkmp_ystar"></div>

		<?php } for($i=0; $i<5-$item->value_r; $i++)

		{?>

		<div class="wkmp_gstar"></div>

		<?php }?>


	</div>

	<div class='wkmp_feedback_price_out'>

		<div class="wkmp_feedback_out"><strong><?php echo _e("QUALITY", "marketplace");?> :</strong></div>

		<?php for($i=0; $i<$item->quality_r; $i++)

		{?>

		<div class="wkmp_ystar"></div>

		<?php } for($i=0; $i<5-$item->quality_r; $i++) {?>

		<div class="wkmp_gstar"></div>

		<?php } 	?>



	</div>
	<div class="wkmp_feedback_con">
		<div class="wkmp_feedback_name"><strong><?php echo _e('Review Description', 'marketplace');?>:</strong></div>
		<div class="wkmp_feedback_value"><?php echo $item->review_desc;?></div>
	</div>
	<div class="wkmp_feedback_con">
		<div class="wkmp_feedback_name"><strong><?php echo _e('Review Summary', 'marketplace');?>:</strong></div>
		<div class="wkmp_feedback_value"><?php echo $item->review_summary;?></div>
	</div>
	<div class="wkmp_feedback_con">
		<div class="wkmp_feedback_name"><strong><?php echo _e("Feed Back By", "marketplace");?>:</strong></div>
		<div class="wkmp_feedback_value"><?php echo $item->nickname;?></div>
	</div>
	<div class="wkmp_feedback_con">
		<div class="wkmp_feedback_name"><strong><?php echo _e("DATE", "marketplace");?>:</strong></div>
		<div class="wkmp_feedback_value"><?php echo $item->review_time; ?></div>
	</div>

</div>



<?php } ?></div>

<div id="Mp_feedback" style="display:none;">

<h1><?php echo _e("Write your own feedback", "marketplace");?></h1>

<div><h2><?php echo _e("How do you rate this Store", "marketplace"). '? *';?></h2></div>

<form action="" method="post" enctype="multipart/form-data">

	<div class="wkmp_feedback_main_in">

		<div class="wkmp_mp_feedback_in">

		<div class="wkmp_feedback_header_in wkmp_filter_tag_in"><strong><?php echo _e("NAME", "marketplace");?></strong></div>

		<div class="wkmp_feedback_header_in"><strong><?php echo _e("1STAR", "marketplace");?></strong></div>

		<div class="wkmp_feedback_header_in"><strong><?php echo _e("2STAR", "marketplace");?></strong></div>

		<div class="wkmp_feedback_header_in"><strong><?php echo _e("3STAR", "marketplace");?></strong></div>

		<div class="wkmp_feedback_header_in"><strong><?php echo _e("4STAR", "marketplace");?></strong></div>

		<div class="wkmp_feedback_header_in"><strong><?php echo _e("5STAR", "marketplace");?></strong></div>

		</div>

		<div class="wkmp_mp_feedback_in">

		<div class="wkmp_feedback_price_in wkmp_filter_tag_value_in"><strong><?php echo _e("Price", "marketplace");?></strong></div>

		<div class="wkmp_feedback_price_in"><input type="radio" name="feed_price" value="1" required></div>

		<div class="wkmp_feedback_price_in"><input type="radio" name="feed_price" value="2" required></div>

		<div class="wkmp_feedback_price_in"><input type="radio" name="feed_price" value="3" required></div>

		<div class="wkmp_feedback_price_in"><input type="radio" name="feed_price" value="4" required></div>

		<div class="wkmp_feedback_price_in"><input type="radio" name="feed_price" value="5" required></div>

		</div>



		<div class="wkmp_mp_feedback_in ">

		<div class="wkmp_feedback_price_in wkmp_filter_tag_value_in" ><strong><?php echo _e("VALUE", "marketplace");?></strong></div>

		<div class="wkmp_feedback_price_in"><input type="radio" name="feed_value" value="1" required></div>

		<div class="wkmp_feedback_price_in"><input type="radio" name="feed_value" value="2" required></div>

		<div class="wkmp_feedback_price_in"><input type="radio" name="feed_value" value="3" required></div>

		<div class="wkmp_feedback_price_in"><input type="radio" name="feed_value" value="4" required></div>

		<div class="wkmp_feedback_price_in"><input type="radio" name="feed_value" value="5" required></div>

		</div>



		<div class="wkmp_mp_feedback_in">

		<div class="wkmp_feedback_price_in wkmp_filter_tag_value_in"><strong><?php echo _e("QUALITY", "marketplace");?></strong></div>

		<div class="wkmp_feedback_price_in"><input type="radio" name="feed_quality" value="1" required></div>

		<div class="wkmp_feedback_price_in"><input type="radio" name="feed_quality" value="2" required></div>

		<div class="wkmp_feedback_price_in"><input type="radio" name="feed_quality" value="3" required></div>

		<div class="wkmp_feedback_price_in"><input type="radio" name="feed_quality" value="4" required></div>

		<div class="wkmp_feedback_price_in"><input type="radio" name="feed_quality" value="5" required></div>

		</div>

		<div class="wkmp_feedback_fields_in">

			<div class="wkmp_feedback_fields_in"><?php echo _e("Nickname", "marketplace");?></div>

			<div class=""><input type="text" name="feed_nickname" size="35" required></div>

		</div>

		<div class="wkmp_feedback_fields_in">

			<div class="wkmp_feedback_fields_in"><?php echo _e("Review Summary", "marketplace");?></div>

			<div class=""><input type="text" name="feed_summary" size="35" required></div>

		</div>

		<div class="wkmp_feedback_fields_in">

			<div class="wkmp_feedback_fields_in"><?php echo _e("Review", "marketplace");?></div>

			<div class=""><textarea rows='4' cols='50' name='feed_review' required></textarea></div>

		</div>

		<input type="submit" id="wk_mp_reviews_user" value="<?php echo _e("Submit Review", "marketplace");?>" style="width:120px" class="btn btn-primary">

	</div>

	<input type="hidden" name="create_date" value="<?php echo $current_time; ?>" />

	<input type="hidden" name="mp_wk_seller" value="<?php echo $seller_id; ?>" />

	<input type="hidden" name="mp_wk_user" value="<?php echo $user_id; ?>" />

</form>

</div>
