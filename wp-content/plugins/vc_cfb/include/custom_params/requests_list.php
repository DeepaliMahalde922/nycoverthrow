<?php
  class VC_CFB_Custom_Param_RequestsList extends VC_CFB_Custom_Param
  {
    function __construct()
    {
      $this->name = 'requests_list';
      $this->css = VC_CFB_Manager::$url.'/css/vc/requests_list.css';
      $this->script = VC_CFB_Manager::$url.'/js/vc/requests_list.js';
      
      parent::__construct();
    }

    public static function __render( $settings, $value )
    {
      $string = '';

      ob_start();
?>
      <div class="rl-wrap cfix" data-url="<?= admin_url( 'admin-ajax.php?action='.wp_create_nonce( VC_CFB_Manager::$action.'_requests_history_list' ) );?>">
        <input type="hidden" class="rl-hidden wpb_vc_param_value wpb-textinput <?= $settings['param_name'].' '.$settings['type'];?>_field" name="<?= $settings['param_name'];?>" value="<?= $value;?>"/>
        <div class="rl-list cfix">
          <div class="rl-list-empty"><?= __( 'Your history list is empty.', 'vc_cfb' );?></div>
        </div>
      </div>
<?php
      $string = ob_get_contents();
      ob_end_clean(); 

      return $string;
    }
  }