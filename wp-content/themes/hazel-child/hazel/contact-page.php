<?php 
/*
Template Name: Contact Page
*/ 
?>

<?php
global $wp_query;
$id = $wp_query->get_queried_object_id();
get_header();

$hide_contact_form_website = "";
if (isset($qode_options['hide_contact_form_website'])) $hide_contact_form_website = $qode_options['hide_contact_form_website'];

if(get_post_meta($id, "qode_page_background_color", true) != ""){
	$background_color = get_post_meta($id, "qode_page_background_color", true);
}else{
	$background_color = "";
}

if($qode_options['enable_google_map'] == "yes"){
	$container_class= " full_map";
} else {
	$container_class= "";
}
$show_section = "yes";
if(isset($qode_options['section_between_map_form'])) {
	$show_section = $qode_options['section_between_map_form'];
}
?>
	
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
			
		<?php if(get_post_meta($id, "qode_page_scroll_amount_for_sticky", true)) { ?>
			<script>
			var page_scroll_amount_for_sticky = <?php echo get_post_meta($id, "qode_page_scroll_amount_for_sticky", true); ?>;
			</script>
		<?php } ?>
		
		<?php get_template_part( 'title' ); ?>

		<div class="container"<?php if($background_color != "") { echo " style='background-color:". $background_color ."'";} ?>>
		<div class="container_inner<?php echo $container_class; ?> clearfix default_template_holder">
				<div class="contact_detail">
					<?php if($show_section == "yes") { ?>
						<div class="contact_section">
							<h2><?php if(isset( $qode_options['contact_section_above_form_title']) && $qode_options['contact_section_above_form_title'] != "") {
							echo $qode_options['contact_section_above_form_title'];  } else { ?><?php _e('Say Hello', 'qode'); ?><?php } ?></h2>
							<div class="separator small center"></div>
							<h4><?php if(isset( $qode_options['contact_section_above_form_subtitle']) && $qode_options['contact_section_above_form_subtitle'] != "") {
							echo $qode_options['contact_section_above_form_subtitle'];  } else { ?><?php _e('We are a fairly small, flexible design studio that designs for print and web. We work flexibly with clients to fulfil their design needs. Whether you need to create a brand from scratch, including marketing materials and a beautiful and functional website or whether you are looking for a design refresh we are confident you will be pleased with the results.', 'qode'); ?><?php } ?></h4>
						</div>
					<?php } ?>
					<?php if($qode_options['enable_contact_form'] == "yes"){ ?>
					<div class="two_columns_33_66 clearfix grid2">
						<div class="column1">
							<div class="column_inner">
								<div class="contact_info">
									<?php the_content(); ?>
								</div>	
							</div>
						</div>
						<div class="column2">
							<div class="column_inner">
								<div class="contact_form">
                                    <?php if($qode_options['contact_heading_above'] != "") { ?><h5> <?php echo $qode_options['contact_heading_above']; ?> </h5> <?php } ?>
									<form id="contact-form" method="post" action="">
										<div class="two_columns_50_50 clearfix">
											<div class="column1">
												<div class="column_inner">
													<input type="text" class="requiredField" name="fname" id="fname" value="" placeholder="<?php _e('First Name *', 'qode'); ?>" />
													
												</div>
											</div>
											<div class="column2">
												<div class="column_inner">
													<input type="text" class="requiredField" name="lname" id="lname" value="" placeholder="<?php _e('Last Name *', 'qode'); ?>" />
												</div>
											</div>
										</div>
										<?php if ($hide_contact_form_website == "yes") { ?>
											<input type="text" class="requiredField email" name="email" id="email" value="" placeholder="<?php _e('Email *', 'qode'); ?>" />
											<input type="hidden" name="website" id="website" value="" />
										<?php } else { ?>
										<div class="two_columns_50_50 clearfix">
											<div class="column1">
												<div class="column_inner">
													<input type="text" class="requiredField email" name="email" id="email" value="" placeholder="<?php _e('Email *', 'qode'); ?>" />
													
												</div>
											</div>
											<div class="column2">
												<div class="column_inner">
													<input type="text" name="website" id="website" value="" placeholder="<?php _e('Website', 'qode'); ?>" />	
												</div>
											</div>
										</div>
										<?php }?>
										<textarea name="message" id="message" rows="10" placeholder="<?php _e('Message', 'qode'); ?>"></textarea>
										
										<?php
										if($qode_options['use_recaptcha'] == "yes") :
											require_once('includes/recaptchalib.php');
											if($qode_options['recaptcha_public_key']) {
												$publickey = $qode_options['recaptcha_public_key'];
											} else {
												$publickey = "6Ld5VOASAAAAABUGCt9ZaNuw3IF-BjUFLujP6C8L";
											}
											if($qode_options['recaptcha_private_key']) {
												$privatekey = $qode_options['recaptcha_private_key'];
											} else {
												$privatekey = "6Ld5VOASAAAAAKQdKVcxZ321VM6lkhBsoT6lXe9Z";
											}

											if($qode_options['page_transitions'] != ""){ ?>
												<script type="text/javascript">
													var RecaptchaOptions = {theme: 'clean'};
													Recaptcha.create("<?php echo $publickey; ?>","captchaHolder",{theme: "clean",callback: Recaptcha.focus_response_field});
												</script>
											<?php } ?>
											<p id="captchaHolder"><?php echo recaptcha_get_html($publickey); ?></p>
											<p id="captchaStatus">&nbsp;</p>
										<?php endif; ?>
										
										<span class="submit_button_contact">
											<input class="qbutton" type="submit" value="<?php _e('Contact Us', 'qode'); ?>" />
										</span>
									</form>	
								</div>
	
							</div>
						</div>
					</div>
					<?php }  else { ?>
						<div class="contact_info">
							<?php the_content(); ?>
						</div>
					<?php } ?>
				</div>	
		</div>	
	    </div>
        <?php if($qode_options['enable_google_map'] == "yes"){ ?>
            <div class="google_map_holder">
                <?php
                $google_maps_scroll_wheel = false;
                if(isset($qode_options['google_maps_scroll_wheel'])){
                    if ($qode_options['google_maps_scroll_wheel'] == "yes")
                        $google_maps_scroll_wheel = true;
                }
                if(!$google_maps_scroll_wheel){
                    ?>
                    <div class="google_map_ovrlay"></div>
                <?php } ?>
                <div class="google_map" id="map_canvas"></div>
            </div>
        <?php } ?>
		
<?php endwhile; ?>
<?php endif; ?>
<script type="text/javascript">
jQuery(document).ready(function($){
    $j('form#contact-form').submit(function(){
        $j('form#contact-form .contact-error').remove();
        var hasError = false;
        $j('form#contact-form .requiredField').each(function() {
            if(jQuery.trim($j(this).val()) == '' || jQuery.trim($j(this).val()) == jQuery.trim($j(this).attr('placeholder'))){
                var labelText = $j(this).prev('label').text();
                $j(this).parent().append('<strong class="contact-error"><?php _e('This is a required field', 'qode'); ?></strong>');
                $j(this).addClass('inputError');
                hasError = true;
            } else { //else 1 
                if($j(this).hasClass('email')) { //if hasClass('email')
                    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                    if(!emailReg.test(jQuery.trim($j(this).val()))){
                        var labelText = $j(this).prev('label').text();
                        $j(this).parent().append('<strong class="contact-error"><?php _e('Please enter a valid email address.', 'qode'); ?></strong>');
                        $j(this).addClass('inputError');
                        hasError = true;
                    } 
                } //end of if hasClass('email')

            } // end of else 1 
        }); //end of each()
        
        if(!hasError){
			challengeField = $j("input#recaptcha_challenge_field").val();
			responseField = $j("input#recaptcha_response_field").val();
			name =  $j("input#fname").val();
			lastname =  $j("input#lname").val();
			email =  $j("input#email").val();
			website =  $j("input#website").val();
			message =  $j("textarea#message").val();
			
			var form_post_data = "";
			
			var html = $j.ajax({
			type: "POST",
			url: "<?php echo QODE_ROOT; ?>/includes/ajax_mail.php",
			data: "recaptcha_challenge_field=" + challengeField + "&recaptcha_response_field=" + responseField + "&name=" + name + "&lastname=" + lastname + "&email=" + email + "&website=" + website + "&message=" + message,
			async: false
			}).responseText;
			
			if(html == "success"){
				var formInput = $j(this).serialize();
				
				$j("form#contact-form").before('<div class="contact-success"><strong><?php _e('THANK YOU!', 'qode'); ?></strong><p><?php _e('Your email was successfully sent. We will contact you as soon as possible.', 'qode'); ?></p></div>');
				$j("form#contact-form").hide();
				$j.post($j(this).attr('action'),formInput);
				hasError = false;
				return false; 
			} else {
				<?php
				if ($qode_options['use_recaptcha'] == "yes"){
				?>
					$j("#recaptcha_response_field").parent().append('<span class="contact-error extra-padding"><?php _e('Invalid Captcha', 'qode'); ?></span>');
					Recaptcha.reload();
				<?php
				} else {
				?>
					$j("form#contact-form").before('<div class="contact-success"><strong><?php _e("Email server problem", 'qode'); ?></strong></p></div>');
				<?php    
				}
				?>
				return false;
			}
        }
        return false;
    });
});
</script>   
<script type="text/javascript">
function ajaxSubmitCommentForm(){"use strict";
var e={success:function(){$j("#commentform textarea").val(""),$j("#commentform .success p").text("Comment has been sent!")}};$j("#commentform").submit(function(){return $j(this).find('input[type="submit"]').next(".success").remove(),$j(this).find('input[type="submit"]').after('<div class="success"><p></p></div>'),$j(this).ajaxSubmit(e),!1})}
function initialize(){"use strict";
var color =  "<?php if(isset($qode_options[google_maps_color])) { echo $qode_options[google_maps_color]; } ?>";
var saturation =  "<?php if(isset($qode_options[google_maps_saturation])) { echo $qode_options[google_maps_saturation]; } ?>";
var lightness =  "<?php if(isset($qode_options[google_maps_lightness])) { echo $qode_options[google_maps_lightness]; } ?>";
var zoomlevel =  "<?php if(isset($qode_options[google_maps_zoom])) { echo $qode_options[google_maps_zoom]; } ?>";
var e=[{stylers:[{hue:color},{saturation:saturation},{lightness:lightness},{gamma:1.51}]}],o=new google.maps.StyledMapType(e,{name:"Qode Map"});
geocoder=new google.maps.Geocoder;
var t=new google.maps.LatLng(-34.397,150.644),
a={zoom:12,scrollwheel:!1,center:t,zoomControl:!0,zoomControlOptions:{style:google.maps.ZoomControlStyle.SMALL,position:google.maps.ControlPosition.RIGHT_CENTER},scaleControl:!1,scaleControlOptions:{position:google.maps.ControlPosition.LEFT_CENTER},streetViewControl:!1,streetViewControlOptions:{position:google.maps.ControlPosition.LEFT_CENTER},panControl:!1,panControlOptions:{position:google.maps.ControlPosition.LEFT_CENTER},mapTypeControl:!1,mapTypeControlOptions:{mapTypeIds:[google.maps.MapTypeId.ROADMAP,"qode_style"],style:google.maps.MapTypeControlStyle.HORIZONTAL_BAR,position:google.maps.ControlPosition.LEFT_CENTER},mapTypeId:"qode_style"};map=new google.maps.Map(document.getElementById("map_canvas"),a),map.mapTypes.set("qode_style",o)}function codeAddress(e){"use strict";if(""!==e){var o='<div id="content"><div id="siteNotice"></div><div id="bodyContent"><p>'+e+"</p></div></div>",t=new google.maps.InfoWindow({content:o});geocoder.geocode({address:e},function(o,a){if(a===google.maps.GeocoderStatus.OK){map.setCenter(o[0].geometry.location);

var pinImg =  "<?php if(isset($qode_options[google_maps_pin_image])) { echo $qode_options[google_maps_pin_image]; } ?>";
var s=new google.maps.Marker({map:map,position:o[0].geometry.location,icon:pinImg,title:e.store_title});google.maps.event.addListener(s,"click",function(){t.open(map,s)})}})}
}
function showContactMap(){	
	var  addr = "<?php if(isset($qode_options['google_maps_address'])) { echo $qode_options['google_maps_address']; } ?>"; 
	var  addr2 = "<?php if(isset($qode_options['google_maps_address'])) { echo $qode_options['google_maps_address2']; } ?>"; 
	var  addr3 = "<?php if(isset($qode_options['google_maps_address'])) { echo $qode_options['google_maps_address3']; } ?>"; 
	var  addr4 = "<?php if(isset($qode_options['google_maps_address'])) { echo $qode_options['google_maps_address4']; } ?>"; 
	var  addr5 = "<?php if(isset($qode_options['google_maps_address'])) { echo $qode_options['google_maps_address5']; } ?>"; 
	"use strict";
	$j("#map_canvas").length>0&&(initialize(),codeAddress(addr),codeAddress(addr2),codeAddress(addr3),codeAddress(addr4),
	codeAddress(addr5))}
var header_height=100,min_header_height_scroll=57,min_header_height_sticky=60,scroll_amount_for_sticky=85,content_line_height=60,header_bottom_border_weight=1,add_for_admin_bar=0,logo_height=130,logo_width=280;logo_height=200,logo_width=232,header_top_height=0;var loading_text;loading_text="Loading new posts...";var finished_text;finished_text="No more posts";var piechartcolor;piechartcolor="#ecae80";var geocoder,map,$j=jQuery.noConflict();$j(document).ready(function(){"use strict";showContactMap()});var no_ajax_pages=[],qode_root="http://demo.select-themes.com/hazel/",theme_root="http://demo.select-themes.com/hazel/wp-content/themes/hazel/",header_style_admin="";no_ajax_pages.push("http://demo.select-themes.com/hazel/product/sunglasses-case/"),no_ajax_pages.push("http://demo.select-themes.com/hazel/product/madrid-weekend-bag/"),no_ajax_pages.push("http://demo.select-themes.com/hazel/product/leather-vanity-case/"),no_ajax_pages.push("http://demo.select-themes.com/hazel/product/tablet-sleeve/"),no_ajax_pages.push("http://demo.select-themes.com/hazel/product/leather-weekend-bag/"),no_ajax_pages.push("http://demo.select-themes.com/hazel/product/vintage-handbag/"),no_ajax_pages.push("http://demo.select-themes.com/hazel/product/travel-leather-bag/"),no_ajax_pages.push("http://demo.select-themes.com/hazel/product/vintage-travel-bag/"),no_ajax_pages.push("http://demo.select-themes.com/hazel/product/smartphone-cover/"),no_ajax_pages.push("http://demo.select-themes.com/hazel/product/leather-briefcase/"),no_ajax_pages.push("http://demo.select-themes.com/hazel/product/laptop-leather-sleeve/"),no_ajax_pages.push("http://demo.select-themes.com/hazel/product/leather-wallet/"),no_ajax_pages.push("http://demo.select-themes.com/hazel/shop/"),no_ajax_pages.push("http://demo.select-themes.com/hazel/cart/"),no_ajax_pages.push("http://demo.select-themes.com/hazel/checkout/"),no_ajax_pages.push(""),no_ajax_pages.push(""),no_ajax_pages.push("http://demo.select-themes.com/hazel/my-account/"),no_ajax_pages.push(""),no_ajax_pages.push(""),no_ajax_pages.push("");124
</script>
<?php get_footer(); ?>			