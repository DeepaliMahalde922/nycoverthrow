<div class="col two-col">
  <p><?php _e( 'You can see form history on form page or remove data by link at bottom.', 'vc_cfb' );?></p>
  <ul>
  <?php
    $bool = false; 
    foreach( wp_load_alloptions() as $name => $value )
      if( stristr( $name, VC_CFB_Element_Form::__history_db_name('') ) ):
        $value = json_decode( htmlspecialchars_decode( $value ), TRUE );
        echo '<li>'.__( 'Remove data for form:', 'vc_cfb' ).' <a href="'.menu_page_url( 'vc-cfb', false ).'&tab=history&vc_cfb_about_page_history='.$value['name'].'"><strong>'.$value['name'].'</strong></a></li>';
        $bool = true;
      endif;

      if( !$bool )
        _e( 'Your history list is empty.', 'vc_cfb' );
  ?>
  </ul>
</div>