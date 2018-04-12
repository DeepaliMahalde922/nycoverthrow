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
<?php
global $wpdb;
$wpmp_obj10=new MP_Form_Handler();
// Include the required dependencies.
require_once( WK_MARKETPLACE_DIR . 'includes/front/facebookv5/src/Facebook/autoload.php' );

$page_name = $wpdb->get_var("SELECT post_name FROM $wpdb->posts WHERE post_title ='".get_option('wkmp_seller_page_title')."'");
// include('facebookv5/src/facebook.php');

/********************************************************faceboook*********************************************************/
if(isset($_GET['check']))
{
	$store_name=get_query_var('info');
    wp_redirect(home_url('/'.$page_name.'/feedback/').$store_name);
    exit();
}

if(isset($_GET['checkpoint']))
{
    $checkpoint=$_GET['checkpoint'];

    $appid=get_option('wkfb_mp_key_app_ID');

    $secretkey=get_option('wkfb_mp_app_secret_key');

    if($checkpoint && $appid && $secretkey ){
    $key=$_GET['key'];
    // Initialize the Facebook PHP SDK v5.
    $facebook = new Facebook\Facebook([
      'app_id' => $appid,
      'app_secret' => $secretkey,
      'default_graph_version' => 'v2.5',
     ]);

     $helper = $facebook->getRedirectLoginHelper();

     $permissions = ['id', 'email', 'name']; // optional

     try {
        if (isset($_SESSION['localhost_app_token'])) {
            $accessToken = $_SESSION['localhost_app_token'];
        } else {
              $accessToken = $helper->getAccessToken();
        }
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
         // When Graph returns an error
         echo __('Graph returned an error: ', 'marketplace') . $e->getMessage();
          exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
         // When validation fails or other local issues
        echo __('Facebook SDK returned an error: ', 'marketplace') . $e->getMessage();
          exit;
     }
     $accessToken=$key;
    if (isset($accessToken)) {
        if (isset($_SESSION['localhost_app_token'])) {
            $facebook->setDefaultAccessToken($_SESSION['localhost_app_token']);
        }
        else {
            // getting short-lived access token
            $_SESSION['localhost_app_token'] = (string) $accessToken;
              // OAuth 2.0 client handler
            $oAuth2Client = $facebook->getOAuth2Client();
            // Exchanges a short-lived access token for a long-lived one
            $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['localhost_app_token']);
            $_SESSION['localhost_app_token'] = (string) $longLivedAccessToken;
            // setting default access token to be used in script
            $facebook->setDefaultAccessToken($_SESSION['localhost_app_token']);
        }

     }



    // $loginUrl = $facebook->getLoginUrl(array('canvas'=> 1,'fbconnect' => 0,'req_perms' => $req_perms));

    $user_info = null;
    if (!empty($_SESSION['localhost_app_token']))
    {
       try {
        $profile_request = $facebook->get('/me?fields=id,name,email');
            $user_info = $profile_request->getGraphNode()->asArray();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo __('Graph returned an error: ', 'marketplace') . $e->getMessage();
            session_destroy();
            // redirecting user back to app login page
            header("Location: ./");
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo __('Facebook SDK returned an error: ', 'marketplace') . $e->getMessage();
            exit;
        }
    $wk_user_name=$user_info['name'];
    // var_dump($wk_user_name);
    $registerDate= date('Y-m-d H:i:s');
    $first_name=$user_info['first_name'];
    $last_name=$user_info['last_name'];
    $wk_email= $user_info['email'];
    $login_name=explode('@',$user_info['email']);

    $user_url=$user_info['link'];
    $wk_random_password= wp_generate_password();
        if(!email_exists( $wk_email ))
        {
            $user_id = wp_create_user( $wk_email, $wk_random_password, $wk_email );
            update_user_meta( $user_id,'first_name', $first_name);
            update_user_meta( $user_id,'last_name', $last_name);
            /*MP_Form_Handler::mp_user_welcome($user_id,$wk_random_password);*/

            if (!empty($user_id)) :

                $wpmp_obj10->mp_user_welcome($user_id,$wk_random_password);

            endif;

            if(!is_wp_error($user_id))
            {
                wp_set_current_user($user_id); // set the current wp user
                wp_set_auth_cookie($user_id);
                /*$page_id=MP_Form_Handler::get_page_id(get_option('wkmp_seller_page_title'));*/
                $store_name=get_query_var('info');
			    wp_redirect(home_url('/'.$page_name.'/feedback/').$store_name);
                exit;
            }
        }
        else
        {
            $user = get_user_by('email', $wk_email );
            $user_id = (int)$user->data->ID;
            $user_id = wp_update_user(array('ID'=>$user_id,'user_pass'=>$wk_random_password));
            if(!is_wp_error($user_id))
            {
                wp_set_current_user($user_id); // set the current wp user
                wp_set_auth_cookie($user_id);
                /*$page_id=MP_Form_Handler::get_page_id(get_option('wkmp_seller_page_title'));*/
                 $store_name=get_query_var('info');
			    wp_redirect(home_url('/'.$page_name.'/feedback/').$store_name);
                exit;
            }

        }

    }

}
}

/********************************************************faceboook*********************************************************/




$sellerurl=urldecode(get_query_var('info'));

global $wpdb;

$user =get_users(
  array(
   'meta_key' => 'shop_address',
   'meta_value' => $sellerurl
    )
);
if( ! empty( $user ) ) :
	foreach ($user as $value) {

		$sellerid=$value->ID;
	}

	$currency=get_woocommerce_currency_symbol(get_option('woocommerce_currency'));

	/*$sell_data=MP_Form_Handler::spreview($sellerid);*/
	$sell_data=$wpmp_obj10->spreview($sellerid);

	/*$seller_product=MP_Form_Handler::seller_product($sellerid);*/
	$seller_product=$wpmp_obj10->seller_product($sellerid);

	$lenghtProduct=count($seller_product);

	if (is_array($sell_data)) {

		foreach($sell_data as $key=>$value) {

		 	$seller_all[$value->meta_key]=$value->meta_value;

		}

	}

	else {
		$seller_all[] = '';
	}
	/*$selleravatar=MP_Form_Handler::get_user_avatar($sellerid,'avatar');*/
	$selleravatar=$wpmp_obj10->get_user_avatar($sellerid,'avatar');
	if(is_user_logged_in()){
		$user_value=$sellerurl;
	}
?>

	<div id="seller">
	<div class="wkmp_main_left">
<div style="margin-bottom:10px;">
<?php if(isset($selleravatar[0]->meta_value)) {
		echo '<img src="'.content_url().'/uploads/'.$selleravatar[0]->meta_value.'">';
		}
		else
		{
		echo '<div class="wkmp_editmp_img" id="mp_seller_image"><img src="'.WK_MARKETPLACE.'assets/images/genric-male.png" /></div>';
		}
		?>
</div>
<div style="float:left;width:100%;">
<?php $varsid=get_query_var('sid');
				if(empty($varsid))
				{
					$varsid=$sellerid;
				}?>
<?php
if(strchr(get_permalink(),'?'))
		$icon='&';
	else
		$icon='?';
?>
<!--<a class="button btn btn-default button-medium" href="<?php echo get_permalink().$icon; ?>page=sprod&sid=<?php echo $varsid;?>">
<span>View Collection</span>
</a>-->
<a class="button btn btn-default button-medium" href="<?php echo get_permalink().'seller-product/'.$sellerurl;?>"><span><?php echo __('View Collection', 'marketplace'); ?></span></a>
</div>
</div>
	<div class="wkmp_main_right">
	<div class="wkmp-page-title">
		<div class="wkmp_sell_head"><?php echo __('Seller Profile', 'marketplace'); ?></div>
		</div>
	<div class="wkmp-box-account">
<div class="wkmp-box-head">
<h2 class='about'><?php _e('About Shop', 'marketplace');?></h2>
<p><?php echo isset($seller_all['about_shop'])?$seller_all['about_shop']:'N/A';?></p>
<div class="wkmp_border_line"></div>
</div>
<div class="wkmp-box-content" style="background-color:#F6F6F6;border-bottom: 3px solid #D5D3D4;">
<div class="seller_name"><?php echo isset($seller_all['nickname'])?$seller_all['nickname']:'N/A';?></div>
<div class="wkmp-left-label">
<div class="wkmp_row">
<label class="wkmp-mail-icon"><?php echo __('Business Email -', 'marketplace'); ?></label>
<span><?php echo isset($seller_all['billing_email'])?(isset($seller_all['billing_email'])?$seller_all['billing_email']:'N/A') :'N/A';?></span>
</div>
<div class="wkmp_row">
<label class="wkmp-phone-icon"><?php echo __('Phone -', 'marketplace'); ?></label>
<span><?php echo isset($seller_all['billing_phone'])?(isset($seller_all['billing_phone'])?$seller_all['billing_phone']:'N/A'):'N/A';?></span>
</div>
<div class="wkmp_row">
<label class="wkmp-address-icon"><?php echo __('Address', 'marketplace'). '- '; ?></label>
<span><?php echo isset($seller_all['shop_address'])? (isset($seller_all['shop_address'])?$seller_all['shop_address']:'N/A') :'N/A'; ?></span>
</div>
<div class="wkmp_row">
	<?php if(!empty($seller_all['social_facebook'])||!empty($seller_all['social_twitter'])||!empty($seller_all['social_gplus'])||!empty($seller_all['social_linkedin'])||!empty($seller_all['social_youtube'])){?>
		<label class="wkmp-share-icon"><?php echo __('Social Profile -', 'marketplace'); ?></label>
		<span class="wkmp-social-icon">
			<?php //if(isset($seller_all['social_facebook'])){?>
			<?php if(!empty($seller_all['social_facebook'])){?>
			<a id="mp_facebook" href="<?php echo $seller_all['social_facebook'];?>" target='_blank'></a>
			<?php }
			//if(isset($seller_all['social_twitter'])){
			if(!empty($seller_all['social_twitter'])){?>
			<a id="mp_twitter" href="<?php echo $seller_all['social_twitter'];?>" target='_blank'></a>
			<?php }
			//if(isset($seller_all['social_gplus'])){
			if(!empty($seller_all['social_gplus'])){?>
			<a id="mp_gplus" href="<?php echo $seller_all['social_gplus'];?>" target='_blank'></a>
			<?php }
			//if(isset($seller_all['social_linkedin'])){
			if(!empty($seller_all['social_linkedin'])){?>
			<a id="mp_linkedin" href="<?php echo $seller_all['social_linkedin'];?>" target='_blank'></a>
			<?php }
			//if(isset($seller_all['social_youtube'])){
			if(!empty($seller_all['social_youtube'])){?>
			<a id="mp_youtube" href="<?php echo $seller_all['social_youtube'];?>" target='_blank'></a>
			<?php }?>
		</span>
	<?php } ?>
</div>
<div class="wkmp_row">
<label class="wkmp-rating-icon"><?php echo __('Seller Rating -', 'marketplace'); ?></label>
<span class="avg_rating" title="good" style="width: 100px;">
<?php if($sell_data[0]->user_id='1'){
						/*$Result=MP_Form_Handler::original_review($sellerid);*/
						$Result=get_review($sellerid);
						//print_r($Result);
						$num_of_stars=0;
						$total_feedback=0;
						foreach($Result as $item)
						{
						$num_of_stars=$num_of_stars+$item->price_r;
						$num_of_stars=$num_of_stars+$item->value_r;
						$num_of_stars=$num_of_stars+$item->quality_r;
						$total_feedback++;
						}
						if($num_of_stars!=0)
						{
						$review=($num_of_stars/(15*$total_feedback))*100;
						$quality=($review/100)*5;
						}
						else
						{
						$quality=0;
						}
						for($i=0; $i<=4; $i++)
						{
							if($i<$quality)
							{
							echo '<div class="wkmp_ystar"></div>';
							}
							else
							{
							echo '<div class="wkmp_gstar"></div>';
							}
						}
					}
						?>
						<input type="hidden" name="score" value="<?php echo $quality;?>" readonly="readonly">
				</span>
				<?php if(get_current_user_id())
				{
					?>
				<div class="wk_write_review">
					<a class="btn btn-default button button-small open-review-form forloginuser wk_mpsocial_feedback" href="#wk_review_form">
						<span><?php echo __('Write a Review !', 'marketplace'); ?></span>
					</a>
				</div>
				<?php
				}
				else
				{?>
				<div class="wk_write_review">
					<a class="btn btn-default button button-small open-review-form forloginuser wk_mpsocial_feedback" href="javascript:void(0);">
						<span><?php echo __('Write a Review !', 'marketplace'); ?></span>
					</a>
				</div>
				<?php }
				?>
				</div>
				</div>
			</div>
			</div>
			<div class="wkmp-box-account">
				<div class="wkmp-box-head">
					<h2><?php echo __('Recent Products', 'marketplace'); ?></h2>
					<div class="wkmp_border_line"></div>
				</div>
				<?php
				if($lenghtProduct>0){
				?>
				<div class="wkmp-box-content wk_slider_padding">
					<div class="wkmp-product-slider">
						<?php
						if($lenghtProduct>3){
						?>
							<div class="wkmp-bx-next-slider"></div>
							<div class="wkmp-bx-prev-slider"></div>
							<div class="view-port-mp-slider">
								<div class="view-port-mp-slider-absolute">
									<?php
									foreach ($seller_product as $item)
									{
										$product_object=wc_get_product($item->ID);
										$product_price=$wpdb->get_var("select meta_value from $wpdb->postmeta where post_id=$item->ID and meta_key='_price'");
										/*$product_image=MP_Form_Handler::get_product_image($item->ID,'_thumbnail_id');*/
										$product_image=$wpmp_obj10->get_product_image($item->ID,'_thumbnail_id');
											?>
										<a class="product_img_link" title="<?php echo $item->post_title; ?>" href="<?php echo home_url('product/'.$item->post_name);?>">
										<div class="wkmp-box-slider">
											<div class="wkmp-box-slider-img-data">
												<div class="wkmp-box-slider-img-hidden">
												<?php if($product_image!='')
												{
													?>
													<img style="margin:0;padding:0;" class="wkmp-box-slider-img" alt="<?php echo $item->post_title; ?>" src="<?php echo content_url().'/uploads/'.$product_image; ?>">
													<?php
													}else{ ?>
													<img style="margin:0;padding:0;" class="wkmp-box-slider-img" alt="<?php echo $item->post_title; ?>" src="<?php echo WK_MARKETPLACE.'assets/images/placeholder.png'; ?>">
													<?php }
													?>
												</div>
												<div><?php echo $item->post_title; ?></div>
												<div>
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
														<span class="amount"><?php echo wcrice($product_object->get_price()); ?></span>
														<?php
													}else if( $product_object->is_type( 'grouped' ) ){
													}
													?>
												</div>
											</div>
										</div>
										</a>
										<?php
									}
									?>
								</div>
							</div>
							<?php
							}else{
								?>

							<div class="view-port-mp-slider">
								<div class="view-port-mp-slider-absolute">
									<?php
									foreach ($seller_product as $item)
									{
										// $product_price=$wpdb->get_var("select meta_value from $wpdb->postmeta where post_id=$item->ID and meta_key='_price'");
										$product_price=get_post_meta($item->ID,'_price',true);
										/*$product_image=MP_Form_Handler::get_product_image($item->ID,'_thumbnail_id');*/
										$product_image=$wpmp_obj10->get_product_image($item->ID,'_thumbnail_id');
											?>
										<a class="product_img_link" title="<?php echo $item->post_title; ?>" href="<?php echo get_permalink($item->ID);?>">
										<div class="wkmp-box-slider">
											<div class="wkmp-box-slider-img-data">
												<div class="wkmp-box-slider-img-hidden">
												<?php if($product_image!='')
												{
													?>
													<img style="margin:0;padding:0;" class="wkmp-box-slider-img" alt="<?php echo $item->post_title; ?>" src="<?php echo content_url().'/uploads/'.$product_image; ?>">
													<?php
													}else{ ?>
													<img style="margin:0;padding:0;" class="wkmp-box-slider-img" alt="<?php echo $item->post_title; ?>" src="<?php echo WK_MARKETPLACE.'assets/images/placeholder.png'; ?>">
													<?php }
													?>
												</div>
												<div><?php echo $item->post_title; ?></div>
												<div><?php echo wc_price($product_price);?></div>
											</div>
										</div>
										</a>
										<?php
									}
									?>
								</div>
							</div>
							<?php
							}
							?>
					</div>
				</div>
				<?php
				}else{
					?>
					<div class="wkmp-box-content wk_slider_padding">
						<p>
							<?php echo __('Sorry, No Product Available.', 'marketplace'); ?>
						</p>
					</div>
					<?php
				}
				?>
			</div>
			<?php if($total_feedback>0)
			{
			?>
			<div class="wkmp-box-account">
			<?php $seller_review_last=$Result[$total_feedback-1];?>
				<div class="wkmp-box-head">
					<div class="wk_review_head">
						<h2><?php echo __('Reviews about seller', 'marketplace'); ?></h2>
						</div>

									<div class="wkmp_border_line"></div>
									</div>
									<div class="wkmp-box-content">
									<div class="wkmp-reviews">
									<div class="wkmp-writer-info">
									<div class="wkmp-writer-details">
									<ul>
									<li class="wkmp-person-icon"><?php echo $seller_review_last->nickname;?></li>
									<!-- <li class="wkmp-mail-icon">dheeraj@webkul.com</li> -->
									<li class="wkmp-watch-icon"><?php echo $seller_review_last->review_time;?></li>
									</ul>
									</div>
									<div class="wkmp-seller-rating">
									<?php
									$oneu_of_stars=0;
									$oneu_of_stars=$oneu_of_stars+$seller_review_last->price_r;
									$oneu_of_stars=$oneu_of_stars+$seller_review_last->value_r;
									$oneu_of_stars=$oneu_of_stars+$seller_review_last->quality_r;
									$last_user_review=$oneu_of_stars/3;
									for($i=0; $i<5; $i++)
									{
										if($i<$last_user_review)
										{
										echo '<div class="wkmp_ystar"></div>';
										}
										else
										{
										echo '<div class="wkmp_gstar"></div>';
										}
									}
									?>
									</div>
									</div>
									<div class="wkmp_review_content"><?php echo $seller_review_last->review_desc;?> </div>
									</div>
										<div class="wkmp_border_line"></div>
										<a class="btn btn-default button button-small forloginuser wk_mpsocial_feedback" href="javascript:void(0);">
										<span><?php echo __('View all reviews', 'marketplace'); ?></span>
										</a>
				</div>
			</div>
			<?php }?>

		</div>

</div>
<div class="wkmp_feedback_popup">
<input type='hidden' value="<?php if(!empty($user_value)) echo $user_value;?>" id="feedbackloged_in_status" />
<input type='hidden' value="<?php echo site_url();?>" id="base_url" />

<div id="fb-root"></div>
<div class="wkmp_cross_login"></div>
<?php
$feedback_url='';
$shop_address=get_user_meta($sellerid,'shop_address',true);
?>
<div id='feedback_form'>
		<?php wc_print_notices(); ?>
		<form method="post" class="login">
			<p class="form-row form-row-wide">
			<label for="username"><?php _e( 'Username or email address', 'marketplace' ); ?> <span class="required">*</span></label>
			<input type="text" class="input-text" name="wkmp_username" id="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
			</p>
			<p style="display:none;">
			<input type="hidden" name="wkfb_mp_key_app_idID" id="wkfb_mp_key_app_idID" value="<?php echo get_option('wkfb_mp_key_app_ID'); ?>" />
			<input type="hidden" name="wkfb_mp_app_secret_kekey" id="wkfb_mp_app_secret_kekey" value="<?php echo get_option('wkfb_mp_app_secret_key'); ?>" />
			<input type="hidden" name="wkfacebook_login_page_id" id="wkfacebook_login_page_id" value="<?php echo $wpmp_obj10->get_page_id(get_option('wkmp_seller_page_title')); ?>" />
		</p>

		<p class="form-row form-row-wide">
			<label for="password"><?php _e( 'Password', 'marketplace' ); ?> <span class="required">*</span></label>
			<input class="input-text" type="password" name="password" id="password" />
		</p>
			<!-- <label for="rememberme" class="inline">
			<input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php _e( 'Remember me', 'marketplace' ); ?>
			</label> -->
			<input type="hidden" value="<?php echo get_permalink().'/store/'.$shop_address; ?>" name="_wp_http_referer">
			<p class="form-row wkmp-login-button">
				<?php wp_nonce_field('marketplace-user'); ?>

				 <input type="submit" class="button" id='submit-btn-feedback' name="login" value="<?php _e( 'Login', 'marketplace' ); ?>" /> <!--<a href="<?php echo wp_registration_url();?>" class="button"><?php _e("Register"); ?></a> -->
				 <a href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><?php _e( 'Lost your password?', 'marketplace' ); ?></a>

				<a href="javascript:void(0);"><img border="0" id='mp-fb-login-btn'/></a>

			</p>

		</form>
		</div>
</div>

<?php else : ?>

	<h1>Oops! That page can’t be found.</h1>
	<p>Nothing was found at this location.</p>

<?php endif; ?>
