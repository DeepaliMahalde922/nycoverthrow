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
 * @class      YITH_Role_Changer
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Your Inspiration Themes
 *
 */

if ( ! class_exists( 'YITH_Role_Changer' ) ) {
	/**
	 * Class YITH_Role_Changer
	 *
	 * @author Carlos Mora <carlos.eugenio@yourinspiration.it>
	 */
	class YITH_Role_Changer {
        /**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0
		 */
		protected $version = YITH_WCARC_VERSION;

        /**
		 * Main Instance
		 *
		 * @var YITH_Role_Changer
		 * @since 1.0
		 * @access protected
		 */
		protected static $_instance = null;

		/**
		 * Main Admin Instance
		 *
		 * @var YITH_Role_Changer_Admin
		 * @since 1.0
		 */
		protected $admin = null;


        /**
         * YITH_Role_Changer_Set_Roles Instance
         *
         * @var YITH_Role_Changer_Set_Roles
         * @since 1.0
         */
        protected $set_roles = null;
		

        /**
         * Construct
         *
         * @author Carlos Mora <carlos.eugenio@yourinspiration.it>
         * @since 1.0
         */
        protected function __construct(){

			/* === Require Main Files === */

			require_once( YITH_WCARC_PATH . 'includes/class.yith-ywarc-plugin-fw-loader.php' );
			require_once( YITH_WCARC_PATH . 'includes/class.yith-role-changer-admin.php' );
			require_once( YITH_WCARC_PATH . 'includes/class.yith-role-changer-set-roles.php' );
			require_once( YITH_WCARC_PATH . 'includes/class.yith-role-changer-roles-manager.php' );

            /* == Plugins Init === */
            add_action( 'init', array( $this, 'init' ) );
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
				$this->admin = new YITH_Role_Changer_Admin();
				if ( ! function_exists( 'members_plugin' ) ) {
					$this->roles_manager = new YITH_Role_Changer_Roles_Manager();
				}
			}

			$this->set_roles = new YITH_Role_Changer_Set_Roles();

		}
    }
}