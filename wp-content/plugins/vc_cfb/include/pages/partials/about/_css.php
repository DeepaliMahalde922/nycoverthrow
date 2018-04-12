<form action="" method="post">
  <textarea name="vc_cfb_custom_css"><?= base64_decode(get_option( 'vc_cfb_custom_css' ));?></textarea>
  <div id="custom_css"></div>
  <p class="description indicator-hint"><?= __( 'Add custom CSS code to the plugin without modifying files.', 'vc_cfb' );?></p>
  <script>
    (function($) {
      $(document).ready(function() {
        var editor = ace.edit("custom_css");
        var textarea = $('textarea[name="vc_cfb_custom_css"]').hide();
        editor.getSession().setMode("ace/mode/css");
        editor.setTheme("ace/theme/chrome");
        editor.getSession().setValue(textarea.val());
        editor.getSession().on('change', function(){
          textarea.val(editor.getSession().getValue());
        });
      });
    })(jQuery);
  </script>
  <p class="submit">
    <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Submit' );?>">
  </p>
</form>