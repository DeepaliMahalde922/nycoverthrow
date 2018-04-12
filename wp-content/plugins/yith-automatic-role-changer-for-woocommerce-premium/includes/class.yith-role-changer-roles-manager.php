<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
if ( ! defined( 'YITH_WCARC_VERSION' ) ) {
    exit( 'Direct access forbidden.' );
}

/**
 *
 *
 * @class      YITH_Role_Changer_Roles_Manager
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Carlos Mora <carlos.eugenio@yourinspiration.it>
 *
 */

if ( !class_exists( 'YITH_Role_Changer_Roles_Manager' ) ) {
    /**
     * Class YITH_Role_Changer_Roles_Manager
     *
     * @author Carlos Mora <carlos.eugenio@yourinspiration.it>
     */
    class YITH_Role_Changer_Roles_Manager {
        /**
         * Construct
         *
         * @author Carlos Mora <carlos.eugenio@yourinspiration.it>
         * @since 1.0.0
         */
        public function __construct() {
            add_action( 'admin_head', array( $this, 'hide_role_select' ) );
            add_action( 'show_user_profile', array( $this, 'profile_fields' ) );
            add_action( 'edit_user_profile', array( $this, 'profile_fields' ) );
            add_action( 'profile_update',  array( $this, 'role_update' ) );
        }

        public function hide_role_select() { 
            ?><style type="text/css">.user-role-wrap{ display: none !important; }</style><?php
        }

        public function profile_fields( $user ) {
            global $wp_roles;

            if ( ! current_user_can( 'promote_users' ) || ! current_user_can( 'edit_user', $user->ID ) )
                return;

            $user_roles = (array) $user->roles;

            $editable_roles = get_editable_roles();

            wp_nonce_field( 'ywarc_roles', 'ywarc_roles_nonce' ); ?>

            <h3><?php esc_html_e( 'Roles', 'members' ); ?></h3>

            <table class="form-table">

                <tr>
                    <th><?php esc_html_e( 'User Roles', 'members' ); ?></th>

                    <td>
                        <ul>
                            <?php foreach ( $editable_roles as $role => $details ) : ?>
                                <li>
                                    <label>
                                        <input type="checkbox" name="ywarc_user_roles[]" value="<?php echo esc_attr( $role ); ?>" <?php checked( in_array( $role, $user_roles ) ); ?> />
                                        <?php echo esc_html( $details['name'] ); ?>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                </tr>

            </table><?php
        }

        public function role_update( $user_id ) {
            if ( ! current_user_can( 'promote_users' ) || ! current_user_can( 'edit_user', $user_id ) )
                return;

            if ( ! isset( $_POST['ywarc_roles_nonce'] ) || ! wp_verify_nonce( $_POST['ywarc_roles_nonce'], 'ywarc_roles' ) )
                return;

            $user = new WP_User( $user_id );

            if ( ! empty( $_POST['ywarc_user_roles'] ) ) {
                $old_roles = (array) $user->roles;
                $new_roles = $_POST['ywarc_user_roles'];
                foreach ( $new_roles as $new_role ) {
                    if ( ! in_array( $new_role, (array) $user->roles ) )
                        $user->add_role( $new_role );
                }
                foreach ( $old_roles as $old_role ) {
                    if (  ! in_array( $old_role, $new_roles ) )
                        $user->remove_role( $old_role );
                }
            } else {
                foreach ( (array) $user->roles as $old_role ) {
                        $user->remove_role( $old_role );
                }
            }
        }



    }
}