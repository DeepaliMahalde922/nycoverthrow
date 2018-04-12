<?php
  if ( class_exists( 'WPBakeryShortCodesContainer' ) ) 
  {
    class WPBakeryShortCode_CFB_Form extends WPBakeryShortCodesContainer {}
  }

  class VC_CFB_Element_Form extends VC_CFB_Element
  { 

    /* List of instances of form fields */
    /* name = instance of field */
    protected $fields = array();

    protected $action;
    protected $popup;
    protected $method;
    protected $target;
    protected $enctype;
    protected $subject;
    protected $recipients;
    public $ajax;
    protected $sender = array();
    protected $charset;
    protected $loader = FALSE;
    protected $template;
    protected $re = FALSE;
    protected $smtp = FALSE;
    protected $request = FALSE;
    protected $after_submitting = FALSE;
    protected $email_from_subscriber = FALSE;
    protected $requests_history = FALSE;
    protected $resubmit = FALSE;

    public $validation = array();

    protected $has_invalid_fields = FALSE;

    /* original content with child shortcodes */
    protected $content;

    /* TRUE if form was sent */
    protected $sent = FALSE;

    function __construct( $name ) 
    {
      parent::__construct( $name );
      
      $this->type = 'form';
    }

    public function __init_vc()
    {
      $this->settings['name'] = __( 'Form', 'vc_cfb' );
      $this->settings['icon'] = VC_CFB_Manager::$url.'/images/elements/'.$this->type.'.png';
      $this->settings['params'] = VC_CFB_Shortcode::__params( 'form' );
      $this->settings['is_container'] = false;
      $this->settings['js_view'] = 'VcColumnView';
      $this->settings['category'] = __( 'Forms Builder', 'vc_cfb' );
      $this->settings['description'] = __( 'Wrapper for all fields. You cannot use form in form.', 'vc_cfb' );
      
      $childs = VC_CFB_Manager::__elements_titles();
      $childs[] = 'vc_row';  
      $childs[] = 'vc_row_inner';  
      $this->settings['as_parent'] = array( 'only' => implode( ',', $childs ) );
      
      parent::__init_vc();
    }

    public function __init_hooks_description()
    {
      VC_CFB_Manager::__register_hook_description( 'cfb_before_form_sent', VC_CFB_Manager::$path.'/hooks/form/cfb_before_form_sent.php', __( 'Form', 'vc_cfb' ) );
      VC_CFB_Manager::__register_hook_description( 'cfb_before_form', VC_CFB_Manager::$path.'/hooks/form/cfb_before_form.php', __( 'Form', 'vc_cfb' ) );
      VC_CFB_Manager::__register_hook_description( 'cfb_before_message', VC_CFB_Manager::$path.'/hooks/form/cfb_before_message.php', __( 'Form', 'vc_cfb' ) );
      VC_CFB_Manager::__register_hook_description( 'cfb_befor_form_classes', VC_CFB_Manager::$path.'/hooks/form/cfb_befor_form_classes.php', __( 'Form', 'vc_cfb' ) );
      VC_CFB_Manager::__register_hook_description( 'cfb_form_classes', VC_CFB_Manager::$path.'/hooks/form/cfb_form_classes.php', __( 'Form', 'vc_cfb' ) );
      VC_CFB_Manager::__register_hook_description( 'cfb_form_styles', VC_CFB_Manager::$path.'/hooks/form/cfb_form_styles.php', __( 'Form', 'vc_cfb' ) );
      VC_CFB_Manager::__register_hook_description( 'cfb_form_custom_attribute', VC_CFB_Manager::$path.'/hooks/form/cfb_form_custom_attribute.php', __( 'Form', 'vc_cfb' ) );
      VC_CFB_Manager::__register_hook_description( 'cfb_email_body', VC_CFB_Manager::$path.'/hooks/form/cfb_email_body.php', __( 'Form', 'vc_cfb' ) );
      VC_CFB_Manager::__register_hook_description( 'cfb_email_line_template', VC_CFB_Manager::$path.'/hooks/form/cfb_email_line_template.php', __( 'Form', 'vc_cfb' ) );
      VC_CFB_Manager::__register_hook_description( 'cfb_validate_messages', VC_CFB_Manager::$path.'/hooks/form/cfb_validate_messages.php', __( 'Form', 'vc_cfb' ) );
    }

    static function __form_default_classes( $classes, $form )
    {
      if( !in_array( 'vc_cfb_form', $classes) )
        $classes[] = 'vc_cfb_form';
      return $classes;
    }

    protected function __send()
    {
      require_once VC_CFB_Manager::$path.'modules/phpmailer/PHPMailerAutoload.php';

      $mail = new PHPMailer;
      $mail->isHTML(true);

      $mail->Subject = $this->subject;
      $mail->CharSet = $this->charset;

      do_action( 'cfb_before_form_sent', $this );

      $this->__send_to_admin( $mail );
      if( $this->re != FALSE )
        $this->__send_to_recipient( $mail );
 
      if( $this->requests_history !== FALSE )
        $this->__add_request_to_history();

      if( $this->sent === TRUE && $this->after_submitting !== FALSE && $this->after_submitting['action'] == 'redirect' ):
        wp_redirect( $this->after_submitting['value'] );
        exit;
      endif;
    }

    protected function __time_last_saved_by_ip( $ip )
    {
      $data = get_option( self::__history_db_name( $this->name ) );
      if( empty($data) )
        return 0;

      $data = json_decode( htmlspecialchars_decode( $data ), TRUE );
      foreach( $data['requests'] as $request )
        if( $request['IP'] == $ip )
          return $request['date'];
      
      return 0;
    }

    public static function __history_db_name( $name )
    {
      return VC_CFB_Manager::$action.'_form_'.$name;
    }

    protected static function __ajax_requests_history_list_download_logic( $name )
    {
      if( !isset($_REQUEST['download']) )
        return;

      $data = get_option( $name ); 
      if( empty($data) )
        return;
      $data = json_decode( htmlspecialchars_decode( $data ), TRUE );

      $type = htmlspecialchars($_REQUEST['download']) == 'zip' ? 'zip' : 'csv';

      if( $type == 'zip' )
        self::__history_list_zip( $data );
      else
        self::__history_list_csv( $data );
      
      die();
    }

    protected static function __ajax_requests_history_list_remove_logic( $name )
    {
      if( !isset($_REQUEST['remove']) )
        return;

      if( htmlspecialchars($_REQUEST['remove']) == 'all' ):
        delete_option( $name );
        VC_CFB_FGeneral::__remove_files( VC_CFB_Manager::$upload_path.'/'.htmlspecialchars($_REQUEST['form']) );
      endif;

      if( htmlspecialchars($_REQUEST['remove']) == 'item' ):
        if( !isset($_REQUEST['item']) )
          die();

        $data = get_option( $name );
        if( !empty($data) ):
          $data = json_decode( htmlspecialchars_decode( $data ), TRUE );
          $i = 0;
          foreach( $data['requests'] as $request ):
            $key = htmlspecialchars( $_REQUEST['item'] );
            if( $request['key'] == $key ):
              unset($data['requests'][$i]);
              VC_CFB_FGeneral::__remove_files( VC_CFB_Manager::$upload_path.'/'.htmlspecialchars($_REQUEST['form']).'/'.$key );
              break;
            endif;
            $i ++;
            unset($key);
          endforeach;
          $data['requests'] = array_values( $data['requests'] );
          if( sizeof( $data['requests'] ) != 0 )
            update_option( $name, htmlspecialchars( json_encode( $data ) ) );
          else
            delete_option( $name );
        endif;
        
        die();
      endif;
    }

    protected function __history_list_zip( $data )
    {
      if( !class_exists('ZipArchive') )
        return;

      $file = tempnam( "tmp", "zip" );
      $zip = new ZipArchive();
      $zip->open( $file, ZipArchive::OVERWRITE );

      $csv = '';
      ob_start(); 
        self::__history_list_csv( $data );
      $csv = ob_get_contents();  
      ob_end_clean(); 
      
      //  CSV
      $zip->addFromString( $data['name'].'.csv', $csv );
      //  FILES
      foreach( $data['requests'] as $request )
        foreach( $request['fields'] as $field )
          if( $field['type'] == 'file' && !empty($field['value']) ):
            $files = explode( ',', $field['value'] );
            foreach( $files as $f ):
              $path = VC_CFB_Manager::$upload_path.'/'.$data['name'].'/'.$request['key'].'/'.trim( $f );
              if( file_exists( $path ) )
                $zip->addFile( $path, $request['key'].'/'.$f );
              unset($path);
            endforeach;
          endif;
      
      $zip->close();
      header( 'Content-Type: application/zip' );
      header( 'Content-Length: ' . filesize($file) );
      header( 'Content-Disposition: attachment; filename="'.$data['name'].'.zip"' );

      readfile($file);
      unlink($file); 
    }

    protected function __history_list_csv( $data )
    {
      $output = fopen( "php://output", 'w' );
      header( "Content-Type:application/csv" ); 
      header( 'Content-Disposition:attachment;filename=data_'.$data['name'].'.csv' );
      $fields = array( 'ID', 'IP', 'Language', 'OS', 'Browser', 'User Agent', 'Date', 'Key' );
      
      $add_fields = array();
      foreach( $data['requests'] as $request )
        foreach( $request['fields'] as $field )
          if( !in_array( $field['name'], $add_fields ) )
            $add_fields[] = $field['name'];
      
      fputcsv( $output, array_merge( $fields, $add_fields ) );
      $i = 1;
      foreach( $data['requests'] as $request ):
        foreach( $add_fields as $key ):
          $v = '';
          foreach( $request['fields'] as $field )
            if( $field['name'] == $key ):
              $v = $field['value'];
              continue;
            endif;

          $line[] = $v;
        endforeach; 

        fputcsv( $output, array_merge( array( $i, $request['IP'], $request['language'], $request['OS'], $request['browser'], $request['user_agent'], date( 'j-m-Y G:i:s', $request['date'] ), $request['key'] ), $line ) );
        $line = null;
        $i ++;
      endforeach;
      fclose( $output );
    }

    public static function __ajax_requests_history_list_answer()
    {
      if( !wp_verify_nonce( $_REQUEST['action'], VC_CFB_Manager::$action.'_requests_history_list' ) )
        die();

      if( !isset($_REQUEST['form']) )
        die();
      
      if( !current_user_can( 'edit_posts' ) )
        die();

      $name = self::__history_db_name( htmlspecialchars($_REQUEST['form']) );

      self::__ajax_requests_history_list_remove_logic( $name );
      self::__ajax_requests_history_list_download_logic( $name );
      
      $data = get_option( $name );
      
      if( empty($data) )
        echo '<div class="rl-list-empty">'.__( 'Your history list is empty.', 'vc_cfb' ).'</div>';
      else{
        echo '<div class="rl-download-links">';
        echo '<a href="'.admin_url( 'admin-ajax.php?action='.wp_create_nonce( VC_CFB_Manager::$action.'_requests_history_list' ) ).'&download=csv&form='.htmlspecialchars($_REQUEST['form']).'" target="_blank" class="vc_btn vc_btn-primary vc_btn-sm vc_navbar-btn">'.__( 'Download as CSV', 'vc_cfb' ).'</a>';
        if( class_exists('ZipArchive') )
          echo '<a href="'.admin_url( 'admin-ajax.php?action='.wp_create_nonce( VC_CFB_Manager::$action.'_requests_history_list' ) ).'&download=zip&form='.htmlspecialchars($_REQUEST['form']).'" target="_blank" class="vc_btn vc_btn-primary vc_btn-sm vc_navbar-btn">'.__( 'Download as ZIP archive' ).'</a>';
        echo '<a href="#" target="_blank" class="vc_btn vc_btn-primary vc_btn-primary-warning vc_btn-sm vc_navbar-btn">'.__( 'Remove all data', 'vc_cfb' ).'</a>';
        echo '</div>';
        $data = json_decode( htmlspecialchars_decode( $data ), TRUE );
        $i = 0;
        foreach( $data['requests'] as $request ):
          $i ++;
?>
      <div class="rl-item cfix" <?= $i > 10  ? 'style="display: none;"' : '' ;?> data-id="<?= $request['key'];?>">
        <div class="rl-num"><span><?= $i;?></span></div>
        <div class="rl-inner cfix">
          <div>
            <input type="text" readonly class="rl-input" value="<?= $request['IP'];?>"/>
            <input type="text" readonly class="rl-input" value="<?= date( 'j-m-Y G:i:s', $request['date'] );?>"/>
          </div>
          <div class="rl-num"><a href="#" class="rl-button rl-remove"></a></div>
          <div class="rl-num rl-num-margin"><a href="#" class="rl-button rl-see-current"></a></div>
        </div>
        <div class="rl-item-full rl-inner cfix" style="display:none;">
          <?php foreach( $request['fields'] as $field ): ?>
          <div>
            <div class="rl-item-label">
              <?= $field['name'];?><br/>
              <span><?= $field['type'];?></span>
            </div>
            <div class="rl-item-saved-values">
              <?php
                if( $field['type'] == 'file' )
                {
                  $data = array();
                  $field['value'] = explode( ',', $field['value'] );
                  foreach( $field['value'] as $file )
                    $data[] = '<a href="'.VC_CFB_Manager::$upload_url.'/'.htmlspecialchars($_REQUEST['form']).'/'.$request['key'].'/'.trim($file).'" target="_blank">'.trim($file).'</a>';
                  $field['value'] = implode( ',', $data );
                }
                echo $field['value'];
              ?>
            </div>
            <div class="clearfix"></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
<?php
        endforeach;
      }
      die();
    }

    protected function __add_request_to_history()
    {
      $request = get_option( self::__history_db_name( $this->name ) );
      $data = !empty($request) ? json_decode( htmlspecialchars_decode( $request ), TRUE ) : array( 'name' => $this->name, 'requests' => array(), 'count_files' => 1 );

      $value = array( 'date' => time(), 'fields' => array() );

      $value['key'] = md5( $value['date'].'-'.sizeof($data['requests']) );
      $value['IP'] = $this->requests_history['ip'] === TRUE ? VC_CFB_FGeneral::get_client_ip() : '';
      $value['OS'] = $this->requests_history['os'] === TRUE ? VC_CFB_FGeneral::get_os() : '';
      $value['browser'] = $this->requests_history['browser'] === TRUE ? VC_CFB_FGeneral::get_browser() : '';
      $value['language'] = $this->requests_history['language'] === TRUE ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : '';
      $value['user_agent'] = $this->requests_history['user_agent'] === TRUE ? $_SERVER['HTTP_USER_AGENT'] : '';

      foreach( $this->fields as $key => $field ):
        if( !in_array( $field->type, array( 'file', 'button' ) ) )
          $value['fields'][] = array( 'name' => $key, 'type' => $field->type, 'value' => $field->__get_value() );
        if( $field->type == 'file' ):
          $files = $field->__get_files();
          if( !empty($files) && $this->requests_history['files'] === TRUE ):
            $titles = array();
            foreach( $files as $file ):
              $info = pathinfo($file['name']);
              $title = '_'.$key.'_'.$data['count_files'].'.'.$info['extension'];
              $titles[] = $title;
              $folder = VC_CFB_Manager::$upload_path.'/'.$this->name.'/'.$value['key'];
              if ( !file_exists( $folder ) )
                wp_mkdir_p( $folder );
              move_uploaded_file( $file['tmp_name'], $folder.'/'.$title );
              
              $data['count_files'] ++;

              unset($info); 
              unset($title); 
            endforeach;
          else:
            $titles = array();
          endif;
          $value['fields'][] = array( 'name' => $key, 'type' => $field->type, 'value' => implode( ', ', $titles ) );
          unset($files);
        endif;
      endforeach;
      array_unshift( $data['requests'], $value );

      update_option( self::__history_db_name( $this->name ), htmlspecialchars( json_encode( $data ) ) );
    }

    protected function __send_set_from_email( $mail, $subscriber = false )
    {
      $mail->From = $this->sender['email'];
      $mail->FromName = $this->sender['sender'];

      if( $subscriber && $this->email_from_subscriber !== FALSE && isset($this->fields[$this->email_from_subscriber['email']]) ):
        $email_email = $this->fields[$this->email_from_subscriber['email']]->__get_value();
        if( !empty($email_email) ):
          $mail->From = $email_email;
          if( isset($this->fields[$this->email_from_subscriber['name']]) ):
            $email_name = $this->fields[$this->email_from_subscriber['name']]->__get_value();
            $mail->FromName = !empty($email_name) ? $email_name : '';
            unset( $email_name );
          endif;
        endif;
        unset( $email_email );
      endif;

      if( $this->smtp !== FALSE && ( ( $this->email_from_subscriber === FALSE && $subscriber == true ) || $subscriber == false ) ):
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = $this->smtp['smtp_host'];               // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $this->smtp['smtp_username'];       // SMTP username
        $mail->Password = $this->smtp['smtp_password'];       // SMTP password
        if( !empty($this->smtp['smtp_secure']) )
          $mail->SMTPSecure = $this->smtp['smtp_secure'];
        $mail->Port = $this->smtp['smtp_port'];  
      endif;

      return $mail;
    }

    protected function __send_to_admin( $mail )
    {
      if( !empty($this->recipients) )
        foreach( explode(',', $this->recipients) as $recipient )
          if( !empty($recipient) )
            $mail->addAddress( $recipient );

      $mail = $this->__send_set_from_email( $mail, true );

      add_filter( 'cfb_email_body', array( $this, '__add_email_body' ), 5, 3 );
      add_filter( 'cfb_email_body', array( $this, '__add_email_attachment' ), 5, 3 );

      $mail = apply_filters( 'cfb_email_body', $mail, $this->fields, $this->template );

      if( $mail->send() ) 
        $this->sent = TRUE;
      else
        $this->sent = FALSE;
    }

    protected function __send_to_recipient( $mail )
    {
      if( empty($this->re['email']) && isset($this->fields[$this->re['email']]) )
        return;

      $mail = $this->__send_set_from_email( $mail );
      $mail->ClearAddresses();
      $mail->Body = '';
      $mail->clearAttachments();

      $mail->addAddress( $this->fields[$this->re['email']]->__get_value() );

      add_filter( 'cfb_re_email_body', array( $this, '__add_email_body' ), 5, 3 );
      if( $this->re['attachment'] == TRUE )
        add_filter( 'cfb_re_email_body', array( $this, '__add_email_attachment' ), 5, 3 );
      
      $mail = apply_filters( 'cfb_re_email_body', $mail, $this->fields, $this->re['template'] );

      if( $mail->send() ) 
        $this->sent = TRUE;
      else
        $this->sent = FALSE;
    }

    private function __hooks()
    {
      add_filter( 'cfb_form_classes', array( 'VC_CFB_Element_Form', '__form_default_classes' ), 10 , 2 );
      add_filter( 'cfb_before_form', array( 'VC_CFB_Element_Form', '__before_form' ), 10, 2 );
      add_filter( 'cfb_after_form', array( 'VC_CFB_Element_Form', '__after_form' ) );

      add_filter( 'cfb_before_message', array( 'VC_CFB_Element_Form', '__before_message' ), 10, 3 );
      add_filter( 'cfb_after_message', array( 'VC_CFB_Element_Form', '__after_message' ), 10, 3 );
    }

    public function __add_email_body( $mail, $fields, $template )
    {
      if( empty($fields) )
        return $mail;
      
      $default = '';
      
      foreach( $fields as $key => $field )
        if( !empty($template) )
          $template = str_replace( '%'.$key.'%', $field->__get_value(), $template );
        else{
          $value = $field->__get_value();
          if( $value !== NULL )
            $default .= sprintf( apply_filters( 'cfb_email_line_template', '<li><strong>%s</strong> %s</li>' ), $field->__get_label(), $value );
          unset($value);
        }

      if( !empty($template) )
        $mail->Body .= $template;
      else
        $mail->Body .= '<ul>'.$default.'</ul>';

      return $mail;
    }

    public function __add_email_attachment( $mail, $fields, $template )
    {
      if( empty($fields) )
        return $mail;
      
      foreach( $fields as $key => $field )
        if( $field->type == 'file' ):
          $files = $field->__get_files();
          if( !empty($files) )
            foreach( $files as $file )
              $mail->addAttachment( $file['tmp_name'], $file['name'] ); 
          
          unset($files);
        endif;

      return $mail;
    }

    static function __before_form( $string, $form )
    {
      return '<div class="'.implode( ' ', apply_filters( 'cfb_befor_form_classes', array( 'vc_cfb_form_wrapper' ), $form ) ).'">';
    }

    static function __after_form( $string )
    {
      return '</div>';
    }

    static function __init( $name, $attr, $content )
    {
      if( !isset(VC_CFB_Manager::$forms[$name]) ):
        VC_CFB_Manager::$forms[$name] = clone VC_CFB_Manager::$elements['form'];
        VC_CFB_Manager::$forms[$name]->__set_attr( $attr, $content );
        if( isset($_REQUEST['cfb_form']) && wp_verify_nonce( $_REQUEST['cfb_form'], $name ) )
          VC_CFB_Manager::$forms[$name]->__validate();

        if( VC_CFB_Manager::$forms[$name]->request === TRUE )
          if( VC_CFB_Manager::$forms[$name]->valid === TRUE || ( VC_CFB_Manager::$forms[$name]->valid === NULL && VC_CFB_Manager::$forms[$name]->validation['type'] == 'without' ) )
            VC_CFB_Manager::$forms[$name]->__send();

        VC_CFB_Manager::$forms[$name]->__hooks();
        VC_CFB_Manager::$forms[$name]->__enqueue_custom_element_styles();
        VC_CFB_Manager::$forms[$name]->__enqueue_custom_element_scripts();

        foreach( VC_CFB_Manager::$forms[$name]->fields as $field ):
          if( method_exists($field, '__hooks') )
            $field->__hooks();
            $field->__enqueue_custom_element_styles();
            $field->__enqueue_custom_element_scripts();
        endforeach;
      endif;
      
      VC_CFB_Manager::$current_form = $name;
    }

    static function __before_message( $code, $element, $type )
    {
      if( $element->type != 'form' )
        return $code;

      switch ($type) {
        case 'success':
          $code = '<div class="vc_cfb_message vc_cfb_type_success"><div class="vc_cfb_icon"><i class="fa fa-check"></i></div><p>';
          break;
        case 'error':
          $code = '<div class="vc_cfb_message vc_cfb_type_error"><div class="vc_cfb_icon"><i class="fa fa-times"></i></div><ul>';
          break;  
        default:
          $code = '<div class="vc_cfb_message vc_cfb_type_warning"><div class="vc_cfb_icon"><i class="fa fa-exclamation-triangle"></i></div><p>';
          break;
      }
      return $code;
    }

    static function __after_message( $code, $element, $type )
    {
      if( $element->type != 'form' )
        return $code;

      switch ($type) {
            case 'success':
              $code = '</p></div>';
              break;
            case 'error':
              $code = '</ul></div>';
              break;  
            default:
              $code = '</p></div>';
              break;
          }
      return $code;
    }

    public static function vc_cfb_submit_request()
    {
      if( !isset($_REQUEST['action']) || !isset($_REQUEST['cfb_form_page']) || !isset($_REQUEST['cfb_form']) )
        return;
      
      $ajax = wp_verify_nonce( $_REQUEST['action'], VC_CFB_Manager::$action.'_ajax_form_submit' );

      if( !$ajax && !wp_verify_nonce( $_REQUEST['action' ], VC_CFB_Manager::$action.'_form_submit' ) )
        return;

      $object = (int)$_REQUEST['cfb_form_page'];
      if( empty($object) )
        return;

      $object = get_post( $object );

      if( $object == NULL )
        return;

      VC_CFB_Manager::$object_id = $object->ID;
      
      self::__forms_from_tree( VC_CFB_Shortcode::__shortcodes( $object->post_content ) );

      if( !$ajax )
        return;

      WPBMap::addAllMappedShortcodes();

      VC_CFB_Manager::$current_form = self::__name_current_form_from_request();
      VC_CFB_Manager::$forms[ VC_CFB_Manager::$current_form ]->__render();

      wp_die();
    }

    static private function __name_current_form_from_request()
    {
      foreach( VC_CFB_Manager::$forms as $key => $element )
        if( wp_verify_nonce( $_REQUEST['cfb_form'], $key ) )
          return $key;
    }

    protected function __validate()
    {
      $this->request = TRUE;

      if( $this->validation['type'] == 'without' ):
        $this->valid = NULL; 
        return;
      endif;

      $this->valid = TRUE;
      foreach( $this->fields as $key => $field ):
        $field->__validate();
        $field->__after_validate();
        $this->valid = ($field->valid AND $this->valid);
      endforeach;

      if( $this->valid === FALSE )
        $this->has_invalid_fields = TRUE;

      $this->valid = $this->__check_resubmit() ? $this->valid : FALSE;
    }

    protected function __check_resubmit()
    {
      if( $this->resubmit !== FALSE )
        if( $this->__time_after_last_submit() <= $this->resubmit ):
          $this->validation_messages[] = 'resubmit';
          return FALSE;
        endif;

      return TRUE;
    }

    protected function __time_after_last_submit()
    {
      return time() - $this->__time_last_saved_by_ip( VC_CFB_FGeneral::get_client_ip() );
    }

    public static function __processing( $attr, $content )
    {
      if( empty($attr['name']) )
        return;

      self::__init( VC_CFB_Shortcode::__sanitize_name( $attr['name'] ), $attr, $content );
      
      VC_CFB_Manager::$object_id = empty(VC_CFB_Manager::$object_id) ? get_the_ID() : VC_CFB_Manager::$object_id;      

      $string = '';
        ob_start(); 
          VC_CFB_Manager::$forms[ VC_CFB_Manager::$current_form ]->__render();
        $string = ob_get_contents();  
        ob_end_clean(); 

      VC_CFB_Manager::$current_form = NULL;
      return $string;
    } 

    protected function __show_messages()
    {
      if( $this->valid === FALSE ):
        if( !empty($this->validation_messages) ):
          echo apply_filters( 'cfb_before_message', '', $this, 'error' );
          if( in_array( 'resubmit', $this->validation_messages ) )
            echo '<li>';
            echo '<script type="text/javascript">!function(a){a(document).ready(function(){function b(){var c=document.getElementById("vc_cfb_time_before_resubmit");c.innerHTML--,clearTimeout(window.cfbResubmitTimer),c.innerHTML>0?window.cfbResubmitTimer=setTimeout(b,1e3):(clearTimeout(window.cfbResubmitTimer),a("#"+c.id).closest(".vc_cfb_message").hide("slow"))}window.cfbResubmitTimer=setTimeout(b,1e3)})}(jQuery);</script>';
            echo sprintf( $this->validation['messages']['resubmit'], $this->resubmit, '<span id="vc_cfb_time_before_resubmit">'.( $this->resubmit - $this->__time_after_last_submit() ).'</span>' ).'</li>';
          echo apply_filters( 'cfb_after_message', '', $this, 'error' );
        endif;
        if( in_array( $this->validation['position'], array( 'form', 'all' ) ) && $this->has_invalid_fields ):
          echo apply_filters( 'cfb_before_message', '', $this, 'error' );
          foreach( $this->fields as $key => $field )
            if( $field->valid == FALSE )
              $field->__show_error_message( array( 'form', 'all' ) ); 
          echo apply_filters( 'cfb_after_message', '', $this, 'error' );
        endif;
      endif;

      if( $this->request === TRUE && ( $this->valid === NULL || $this->valid === TRUE ) ):
        $tm = ( $this->sent === TRUE ? 'success' : 'warning' );
        echo apply_filters( 'cfb_before_message', '', $this, $tm );
          echo $this->validation['messages'][$tm];
        echo apply_filters( 'cfb_after_message', '', $this, $tm );
        unset($tm);
      endif;
    }

    protected function __render()
    {
      $classes = apply_filters( 'cfb_form_classes', $this->classes, $this );
      $styles = apply_filters( 'cfb_form_styles', array(), $this );
      ?>
        <?= apply_filters( 'cfb_before_form', '', $this ); ?>
        <?= $this->ajax ? '<div class="'.$this->loader['icon'].'" style="background-color: '.$this->loader['background'].'; display:none;"></div>' : ''; ?>
        <?php
          $this->__show_messages();
          
          if( $this->sent === TRUE && $this->after_submitting !== FALSE && $this->after_submitting['action'] == 'hide' ):
            // form submitted
          else:
        ?>
          <form action="<?= $this->action;?>" method="<?= $this->method;?>" target="<?= $this->target;?>"
            <?= ( !empty($this->popup) ? 'onsubmit="return confirm(\''.$this->popup.'\');"' : '' );?>
            <?= ( $this->method == 'post' ? 'enctype="'.$this->enctype.'"' : '' );?>
            <?= sizeof($classes) > 0 ? 'class="'.implode( ' ', $classes ).'"' : '';?>
            <?= sizeof($styles) > 0 ? 'style="'.implode( '', $styles ).'"' : '';?>
            <?= !empty($this->id) ? 'id="'.$this->id.'"' : ''; ?>
            <?= apply_filters( 'cfb_form_custom_attribute', '', $this ); ?>
            <?= in_array( $this->validation['type'], array( 'without', 'server' ) ) ? 'novalidate' : '';?>
            <?= $this->ajax ? 'data-ajax="true"' : ''; ?>
          >
            <input type="hidden" value="<?= wp_create_nonce( VC_CFB_Manager::$action.'_'.( (bool)$this->ajax ? 'ajax_' : '' ).'form_submit' );?>" name="action" />
            <input type="hidden" value="<?= wp_create_nonce( VC_CFB_Shortcode::__sanitize_name( $this->name ) );?>" name="cfb_form"/>
            <input type="hidden" value="<?= VC_CFB_Manager::$object_id;?>" name="cfb_form_page"/>
            <?= do_shortcode( $this->content ); ?>
          </form>
        <?php
          endif;
          echo apply_filters( 'cfb_after_form', '', $this );
    }

    protected function __default_attr( $attr )
    {
      return shortcode_atts( array(
        'name'                                  => '',
        'action'                                => '',
        'method'                                => 'post',
        'enctype'                               => 'application/x-www-form-urlencoded',
        'validation'                            => 'without',
        'validation_position'                   => 'form',
        'smtp'                                  => false,
        'smtp_host'                             => 'smtp.gmail.com',
        'smtp_port'                             => '587',
        'smtp_username'                         => '',
        'smtp_password'                         => '',
        'smtp_secure'                           => 'tls',
        'subject'                               => '',
        'recipients'                            => '',
        'sender'                                => '',
        'sender_email'                          => get_bloginfo( 'admin_email' ),
        'charset'                               => 'UTF-8',
        'template'                              => '',
        're'                                    => false,
        're_attachment'                         => false,
        're_email'                              => '',
        're_template'                           => '',
        'after_successfully_submitting'         => 'none',
        'after_successfully_submitting_link'    => '',
        'email_from_subscriber'                 => false,
        'email_from_subscriber_name'            => '',
        'email_from_subscriber_email'           => '',
        'id'                                    => '',
        'classes'                               => '',
        'css'                                   => '',
        'messages_success'                      => __( 'Your request was successful sent.', 'vc_cfb' ),
        'messages_warning'                      => __( 'Something going wrong.', 'vc_cfb' ),
        'messages_invalid'                      => __( 'Invalid value for the field %s.', 'vc_cfb' ),
        'messages_required'                     => __( '%s is required field.', 'vc_cfb' ),
        'messages_resubmit'                     => __( 'You can submit form each %s seconds.', 'vc_cfb' ),
        'messages_captcha_enter'                => __( 'Please, enter the code captcha.', 'vc_cfb' ),
        'messages_captcha'                      => __( 'Incorrect captcha code.', 'vc_cfb' ),
        'messages_file_size'                    => __( 'Invalid file size.', 'vc_cfb' ),
        'messages_file_accept'                  => __( 'Invalid file format.', 'vc_cfb' ),
        'popup'                                 => __( 'You are sure?', 'vc_cfb' ),
        'loader'                                => 'vc_cfb_animation vc_cfb_animation-gear',
        'loader_background'                     => 'rgba(153,153,153,0.49)',
        'ajax'                                  => false,
        'requests_history'                      => false,
        'requests_files'                        => false,
        'requests_history_ip'                   => false,
        'requests_history_os'                   => false,
        'requests_history_browser'              => false,
        'requests_history_language'             => false,
        'requests_history_user_agent'           => false,
        'resubmit_protected'                    => false,
        'resubmit_protected_time'               => 120,
        'target'                                => '_self'
      ), $attr );
    }

    protected function __set_attr( $attr, $content )
    {
      $attr = self::__default_attr( $attr );

      parent::__set_attr( $attr, $content );

      $this->content = $content;
      $this->popup = $attr['popup'];
      $this->method = $attr['method'];
      $this->enctype = $attr['enctype'];
      $this->subject = $attr['subject'];
      $this->recipients = $attr['recipients'];
      $this->template = urldecode( $attr['template'] );
      if( $attr['re'] != false )
        $this->re = array( 'email' => $attr['re_email'], 'template' => urldecode( $attr['re_template'] ), 'attachment' => $attr['re_attachment'] );
      $this->charset = $attr['charset'];
      $this->loader = array( 'icon' => $attr['loader'], 'background' => $attr['loader_background'] );
      $this->ajax = $attr['ajax'];
      $this->action = $this->ajax ? admin_url( 'admin-ajax.php' ) : $attr['action'];
      $this->validation = array( 'type' => $attr['validation'], 'position' => $attr['validation_position'] );
      $this->validation['messages'] = apply_filters( 'cfb_validate_messages', array(
                        'success'        =>  $attr['messages_success'],
                        'warning'        =>  $attr['messages_warning'],
                        'invalid'        =>  $attr['messages_invalid'],
                        'required'       =>  $attr['messages_required'],
                        'captcha_enter'  =>  $attr['messages_captcha_enter'],
                        'captcha'        =>  $attr['messages_captcha'],
                        'file_size'      =>  $attr['messages_file_size'],
                        'file_accept'    =>  $attr['messages_file_accept'],
                        'resubmit'       =>  $attr['messages_resubmit'],
                        ), $this->name );
      $this->sender = array( 'sender' => $attr['sender'], 'email' => $attr['sender_email'] );
      if( $attr['smtp'] != false )
        $this->smtp = array(
                            'smtp_host'       =>  $attr['smtp_host'],
                            'smtp_port'       =>  $attr['smtp_port'],
                            'smtp_username'   =>  $attr['smtp_username'],
                            'smtp_password'   =>  $attr['smtp_password'],
                            'smtp_secure'     =>  $attr['smtp_secure']
                          );
      else
        $this->smtp = false;
      if( $attr['after_successfully_submitting'] != 'none' )
        $this->after_submitting = array( 'action' => $attr['after_successfully_submitting'], 'value' => $attr['after_successfully_submitting_link'] );
      if( $attr['email_from_subscriber'] != false )
        $this->email_from_subscriber = array( 'name' => $attr['email_from_subscriber_name'], 'email' => $attr['email_from_subscriber_email'] );

      if( $attr['requests_history'] != false )
        $this->requests_history = array(
                                          'ip'          =>  (bool)$attr['requests_history_ip'],
                                          'os'          =>  (bool)$attr['requests_history_os'],
                                          'browser'     =>  (bool)$attr['requests_history_browser'],
                                          'language'    =>  (bool)$attr['requests_history_language'],
                                          'user_agent'  =>  (bool)$attr['requests_history_user_agent'],
                                          'files'       =>  (bool)$attr['requests_files']
                                        );

      if( $this->requests_history !== FALSE && $attr['resubmit_protected'] != false )
        $this->resubmit = $attr['resubmit_protected_time'];

      $this->target = $attr['target'];

      $this->__fields_from_tree( VC_CFB_Shortcode::__shortcodes( $this->content ) );
    }

    private function __fields_from_tree( $shortcodes = NULL )
    {
      if( $shortcodes === NULL || !is_array($shortcodes) )
        return;

      foreach( $shortcodes as $element )
        if( in_array( $element['name'], VC_CFB_Manager::__elements_titles() ) )
          if( empty($element['atts']['name']) )
            continue;
          else{
            $name = VC_CFB_Shortcode::__sanitize_name($element['atts']['name']);
            $this->fields[$name] = clone VC_CFB_Manager::$elements[VC_CFB_Shortcode::__type_by_title($element['name'])];
            $this->fields[$name]->__set_attr( $element['atts'], $element['content'] );
            unset($name);
          }
        else
          $this->__fields_from_tree( $element['content'] );
    }

    static private function __forms_from_tree( $shortcodes = NULL )
    {
      if( $shortcodes === NULL || !is_array($shortcodes) )
        return;
      
      foreach( $shortcodes as $element )
        if( $element['name'] == 'cfb_form' )
          if( empty($element['atts']['name']) )
            continue;
          else
            self::__init( VC_CFB_Shortcode::__sanitize_name( $element['atts']['name'] ), $element['atts'], $element['original_content'] );
        else
          self::__forms_from_tree( $element['content'] );
    }

    public function __field_by_name( $name )
    {
      foreach( $this->fields as $key => $value )
        if( $key == $name )
          return $value;

      return NULL;
    }

    protected function __enqueue_custom_element_scripts()
    {
      if( !$this->ajax )
        return;

      wp_enqueue_script('jquery');    
    
      wp_register_script( 'vc_cfb_form_ajax', VC_CFB_Manager::$url.'/js/form_ajax.js', array( 'jquery', 'wpb_composer_front_js' ), VC_CFB_Manager::$version );   
      wp_enqueue_script( 'vc_cfb_form_ajax' );
    }

    public static function frontend_admin_enqueue_styles()
    {
      wp_register_style( 'vc_cfb_animations', VC_CFB_Manager::$url.'/css/elements/form/animations.css', array( 'vc_cfb_backend' ), VC_CFB_Manager::$version );    
      wp_enqueue_style( 'vc_cfb_animations' ); 

      wp_register_style( 'vc_cfb_backend', VC_CFB_Manager::$url.'/css/backend.css', array(), VC_CFB_Manager::$version );    
      wp_enqueue_style( 'vc_cfb_backend' );   
    }

    protected function __enqueue_custom_element_styles()
    {
      if( $this->ajax ):
        wp_register_style( 'vc_cfb_animations', VC_CFB_Manager::$url.'/css/elements/form/animations.css', array( 'vc_cfb_frontend' ), VC_CFB_Manager::$version );    
        wp_enqueue_style( 'vc_cfb_animations' );  
      endif;

      foreach( $this->fields as $field ):
        $icon = $field->__get_icon();
        if( $icon !== FALSE ):
          vc_iconpicker_base_register_css();
          vc_icon_element_fonts_enqueue( $icon['type'] );
        endif;
      endforeach;

      wp_register_style( 'font-awesome', vc_asset_url( 'lib/bower/font-awesome/css/font-awesome.min.css' ), false, WPB_VC_VERSION, 'screen' );
      wp_enqueue_style( 'font-awesome' );

      wp_register_style( 'vc_cfb_frontend', VC_CFB_Manager::$url.'/css/frontend.css', array( 'js_composer_front' ), VC_CFB_Manager::$version );    
      wp_enqueue_style( 'vc_cfb_frontend' );  
    }
  }