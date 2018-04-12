<?php

if( ! defined ( 'ABSPATH' ) )

    exit;


/*----------*/ /*---------->>> Product Seller Selection <<<----------*/ /*----------*/

wp_nonce_field( 'blog_save_meta_box_data', 'blog_meta_box_nonce' );

global $wpdb;

    $sql =  "SELECT user_id from {$wpdb->prefix}mpsellerinfo where seller_value = 'seller'";

    $result = $wpdb->get_results($sql);

?>
<div class="return-seller">
    <!--<input type="text" name="seller-name" id="search_seller_name" placeholder="Search Seller">-->
    	   <div class="btn-group bootstrap-select">
            <button type="button" class="btn dropdown-toggle btn-default caret" data-toggle="dropdown" role="button" aria-expanded="true">
            		<span class="filter-option pull-left"><?php echo __('Select Seller', 'marketplace'); ?></span>&nbsp;<span class="bs-caret">
            		</span>
            </button>
            <div class="dropdown-menu open" role="combobox">
            	<div class="bs-searchbox">
            		<input type="text" class="form-control" autocomplete="off" role="textbox" aria-label="Search" id="check-seller">
            	</div>
            	<ul class="dropdown-menu inner" role="listbox" aria-expanded="true">

            		<li data-original-index="0" class="selected active">

            				<?php foreach ($result as $ke) {
       								?>
       								<a tabindex="0" data-seller-id="<?php echo $ke->user_id; ?>" role="option" aria-disabled="false" aria-selected="true">
        							<span class="text" ><?php echo get_user_meta($ke->user_id, 'first_name',true); ?></span>
        							</a>
        							<?php
    							}?>

            		</li>
            		<li class="search-selected"></li>

            	</ul>
            </div>
        <div class="checkbox-seller">
       		<input type="hidden" name="seller_id">
       		<input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">
       	</div>
		</div>
</div>
