<?php

if( ! defined ( 'ABSPATH' ) )

    exit;


/*----------*/ /*---------->>> User Profile Fields <<<----------*/ /*----------*/

foreach ($user->roles as $key => $value) {
	if ($value == 'wk_marketplace_seller') {
		$style = "";
	}
	else {
		$style = "style=display:none;";
	}
}

?>

<div class="mp-seller-details" <?php echo $style; ?>>

	<h3 class="heading"><?php _e("Marketplace Seller Details", "marketplace"); ?></h3>

	<table class="form-table">

		<tr>

	        <th><label for="company-name"><?php _e( 'Shop Name', 'marketplace' ); ?> <span class="required">*</span></label></th>

	        <td><input type="text" class="input-text form-control" name="shopname" id="org-name" value="<?php echo get_user_meta( $user->ID,'shop_name', true );  ?>" required="required"/>
	        </td>

	    </tr>

	    <tr>

	        <th><label for="seller-url" class="pull-left"><?php _e( 'Shop URL', 'marketplace' ); ?> <span class="required">*</span></label></th>

	        <td><input type="text" class="input-text form-control" name="shopurl" placeholder="eg- webkul" id="seller-shop" value="<?php echo get_user_meta( $user->ID,'shop_address', true ); ?>" required="required">
	        	<p><strong id="seller-shop-alert-msg" class="pull-right"></strong></p>
	        </td>

	    </tr>

	</table>

</div>
