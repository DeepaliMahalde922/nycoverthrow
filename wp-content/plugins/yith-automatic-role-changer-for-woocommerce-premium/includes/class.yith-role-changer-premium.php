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
 * @class      YITH_Role_Changer_Premium
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Your Inspiration Themes
 *
 */

if ( ! class_exists( 'YITH_Role_Changer_Premium' ) ) {
	/**
	 * Class YITH_Role_Changer_Premium
	 *
	 * @author Carlos Mora <carlos.eugenio@yourinspiration.it>
	 */
	class YITH_Role_Changer_Premium extends YITH_Role_Changer {

        /**
         * Construct
         *
         * @author Carlos Mora <carlos.eugenio@yourinspiration.it>
         * @since 1.0
         */
        protected function __construct() {
			
			parent::__construct();

			/* === Require Main Files === */
			require_once( YITH_WCARC_PATH . 'includes/class.yith-role-changer-admin-premium.php');
            require_once( YITH_WCARC_PATH . 'includes/class.yith-role-changer-set-roles-premium.php');

		}

        /**
		 * Main plugin Instance
		 *
		 * @return YITH_Role_Changer Main instance
		 * @author Carlos Mora <carlos.eugenio@yourinspiration.it>
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}



        /**
		 * Class Initialization
		 *
		 * Instance the admin or frontend classes
		 *
		 * @author Carlos Mora <carlos.eugenio@yourinspiration.it>
		 * @since  1.0
		 * @return void
		 * @access protected
		 */

		public function init() {

            if ( is_admin() ) {
				$this->admin = new YITH_Role_Changer_Admin_Premium();
				if ( ! function_exists( 'members_plugin' ) ) {
					$this->roles_manager = new YITH_Role_Changer_Roles_Manager();
				}
			}

			$this->set_roles = new YITH_Role_Changer_Set_Roles_Premium();

		}
    }
}