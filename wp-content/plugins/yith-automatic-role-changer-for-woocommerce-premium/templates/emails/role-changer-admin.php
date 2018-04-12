<?php
/**
 * Email for admin notification of role granted to user
 *
 * @author  Yithemes
 * @package yith-woocommerce-automatic-role-changer.premium\templates\emails
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$rules = $email->object;
$user_id = $email->user_id;
$order_id = $email->order_id;

?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email );

$user = new WP_User( $user_id );

if ( $rules ) {
	// Count the total number of roles granted.
	$roles_count = 0;
	foreach ( $rules as $rule_id => $rule ) {
		$roles_count = $roles_count + count( $rule['role_selected'] );
	}

	// Get user name and link to order admin page.
	$username = $user->display_name ? $user->display_name : $user->nickname;
	$order_url = get_edit_post_link( $order_id );
	$order_link = '<a href="' . esc_attr( $order_url ) . '">#' . $order_id . '</a>';

	echo '<p>';
	printf( _n(
		sprintf( 'The user %s gained the following role with the order %s: ', $username, $order_link),
		sprintf( 'The user %s gained the following roles with the order %s: ', $username, $order_link ),
		$roles_count, 'yith-automatic-role-changer-for-woocommerce' )
	);
	echo '</p>';

	foreach ( $rules as $rule_id => $rule ) {
		foreach ( $rule['role_selected'] as $role ) {
			$role_name = wp_roles()->roles[$role]['name'];
			echo '<div class="ywarc_metabox_gained_role"><span class="ywarc_metabox_role_name">' .
				$role_name . '</span>';
			do_action( 'ywarc_after_metabox_content', $rule );
			echo '</div>';
		}
	}
}

do_action( 'woocommerce_email_footer' );