<div class="wrap vc-page-welcome about-wrap">
  <h1><?= sprintf( __( 'Welcome to Custom Forms Builder %s', 'vc_cfb' ), preg_replace( '/^(\d+)(\.\d+)?(\.\d)?(\.\d)?/', '$1$2$3$4', VC_CFB_Manager::$version ) ) ?></h1>

  <div class="about-text">
    <?= sprintf( __( 'Congratulations! You use most time saver form builder Custom Forms Builder by %s for Visual Composer.', 'vc_cfb' ), '<a href="http://codecanyon.net/user/morfi?ref=morfi" target="_blank">Morfi</a>' ) ?>
  </div>
  <div class="wp-badge vc-page-logo">
    <?php echo sprintf( __( 'Tested on %s', 'vc_cfb' ), '5.*.*' ) ?>
  </div>
  <?php 
    $tab = ( !isset($_GET['tab']) ? 'css' : htmlspecialchars($_GET['tab']) );
    vc_include_template( '/pages/partials/_tabs.php',
      array(
        'slug' => 'vc-cfb',
        'active_tab' => $tab,
        'tabs' => array( 'css' => __( 'Custom CSS', 'vc_cfb' ), 'developer' => __( 'For developers', 'vc_cfb' ), 'history' => __( 'History forms', 'vc_cfb' ) )
      ) 
    );

    VC_CFB_Page::vc_cfb_page_tab_content( 'about', $tab );
  ?>
</div>