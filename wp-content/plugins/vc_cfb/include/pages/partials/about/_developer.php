<div class="col two-col">
  <div>
    <p><?= __( 'You can use this hooks for more precise settings code generated by CFB. If hook have "before" in title, it has synchronous hook with "after".', 'vc_cfb' );?></p>
    <?php foreach( array_reverse( VC_CFB_Manager::__sort_hooks_by_cateogries() ) as $category => $hooks ): ?>
      <h4><?= $category;?></h4>
      <div class="col two-col list-actions">
      <?php 
          foreach( $hooks as $name => $hook )
            echo '<a href="'.admin_url( 'admin.php?page=vc-cfb&tab=developer&hook='.$name ).'" '.( isset($_REQUEST['hook']) && htmlspecialchars($_REQUEST['hook']) == $name ? 'class="active_hook"' : '' ).'>'.$name.'</a><br/>';
      ?>
      </div>
      <div style="clear:both;"></div>
    <?php endforeach; ?>
  </div>
  <div>
    <?php
      if( isset($_REQUEST['hook']) ) 
        VC_CFB_Manager::__get_hook_description( htmlspecialchars($_REQUEST['hook']) );
    ?>
  </div>
</div>