<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( isset( $new_rule ) && $new_rule ) {
	$new_rule = true;
	$rule = false;
} else {
    $new_rule = false;
}
?>
<div class="rule_block" data-rule_id="<?php echo $rule_id; ?>">
	<div class="rule_head">
		<label class="rule_title"><?php
			if ( $new_rule ) {
				echo $title;
			} else {
				echo $rule['title'];
			} ?></label>
		<button type="button" class="arrow_button">
			<span class="toggle-indicator"></span>
		</button>
	</div>
	<div class="rule_options">
		<div class="role_selector_block block">
			<p>
				<b><?php echo __( 'The user will gain the role:', 'yith-automatic-role-changer-for-woocommerce' ); ?></b>
			</p>
			<select multiple class="ywarc_role_selector" name="ywarc_role_selected[<?php echo $rule_id; ?>]" ><?php
				if ( $new_rule ) {
					wp_dropdown_roles();
				} else {
					wp_dropdown_roles( $rule['role_selected'][0] );
				}
				?></select>
		</div>

		<?php do_action( 'ywarc_before_specific_product_block', $new_rule, $rule, $rule_id ); ?>


		<div class="specific_product_block block">
			<p>
				<b><?php echo __( 'Choose a product: ', 'yith-automatic-role-changer-for-woocommerce' ); ?></b>
            </p><?php
			$data_selected = '';
			$product_id = '';
			if ( ! $new_rule ) {
				$product_id = $rule['product_selected'];
				if ( $product_id ) {
					$product = wc_get_product( $product_id );
					if ( is_object( $product ) ) {
						$product_name = wp_kses_post( html_entity_decode(
							$product->get_formatted_name(), ENT_QUOTES,
							get_bloginfo( 'charset' ) ) );
						$data_selected = version_compare( WC()->version, '2.7', '<' )
							? $product_name
							: array( $product_id => esc_attr( $product_name ) );
					}
				}
			}

			$search_product_array = array(
				'type'              => 'hidden',
				'class'             => 'wc-product-search',
				'id'                => 'ywarc_product_selector[' . $rule_id . ']',
				'name'              => '',
				'data-placeholder'  => __( 'Search for a product&hellip;', 'woocommerce' ),
				'data-allow_clear'  => false,
				'data-selected'     => $data_selected,
				'data-multiple'     => false,
				'data-action'       => 'woocommerce_json_search_products_and_variations',
				'value'             => $product_id,
				'style'             => ''
			);
			yit_add_select2_fields( $search_product_array );
            ?></div>

		<?php do_action( 'ywarc_after_specific_product_block', $new_rule, $rule, $rule_id ); ?>


		<div class="submit_block block">
			<input class="button button-primary button-large" type="submit"
				   value="<?php echo __( 'Save rule', 'yith-automatic-role-changer-for-woocommerce' ); ?>"/>
			<span class="test">
				<a class="delete_rule" href="#"><?php
					echo __( 'Delete', 'yith-automatic-role-changer-for-woocommerce' ); ?>
				</a>
			</span>
		</div>
	</div>
</div>