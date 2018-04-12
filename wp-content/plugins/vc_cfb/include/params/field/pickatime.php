<?php
  $params = array( 
              'name',
              'label',
              'placeholder',
              'default',
              'required',
              'autofocus',
              array(
                'type'          =>  'checkbox',
                'param_name'    =>  'closeonselect',
                "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                'heading'       =>  __( 'Close popup on select?', 'vc_cfb' ),
                'value'         =>  'true',
                'description'   =>  '',
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                'type'          =>  'checkbox',
                'param_name'    =>  'closeonclear',
                "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                'heading'       =>  __( 'Close popup on clear?', 'vc_cfb' ),
                'value'         =>  'true',
                'description'   =>  '',
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                'type'          =>  'textfield',
                'param_name'    =>  'interval',
                "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                'heading'       =>  __( 'Intervals', 'vc_cfb' ),
                'value'         =>  '30',
                'description'   =>  __( 'Choose the minutes interval between each time in the list.', 'vc_cfb' ),
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                'type'          =>  'textfield',
                'param_name'    =>  'min',
                "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                'heading'       =>  __( 'Min time', 'vc_cfb' ),
                'value'         =>  '',
                'description'   =>  __( 'Set the minimum selectable times on the picker.<br/>For example: 19:30', 'vc_cfb' ),
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                'type'          =>  'textfield',
                'param_name'    =>  'max',
                "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                'heading'       =>  __( 'Max time', 'vc_cfb' ),
                'value'         =>  '',
                'description'   =>  __( 'Set the maximum selectable times on the picker.<br/>For example: 5:30', 'vc_cfb' ),
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                "type"          =>  "dropdown",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_select vc_cfb_hide_field",
                "std"       =>  get_locale(),
                "heading"       =>  __( 'Language', 'vc_cfb' ),
                "param_name"    =>  "locale",
                "value"         =>  array( 
                                        'العربية'                        =>  'ar',
                                        'Български'                      =>  'bg_BG',
                                        'Bosanski jezik'                 =>  'bs_BA',
                                        'Català'                         =>  'ca_ES',
                                        'Čeština'                        =>  'cs_CZ',
                                        'Dansk'                          =>  'da_DK',
                                        'Deutsch'                        =>  'de_DE',
                                        'Ελληνικά'                       =>  'el_GR',
                                        'English (US)'                   =>  'en_US', 
                                        'English (GB)'                   =>  'en_GB', 
                                        'Español'                        =>  'es_ES',
                                        'Eesti keel'                     =>  'et_EE',
                                        'Euskara'                        =>  'eu_ES',
                                        'فارسی'                          =>  'fa_IR',
                                        'Suomi'                          =>  'fi_FI',
                                        'Français'                       =>  'fr_FR',
                                        'Galego'                         =>  'gl_ES',
                                        'עברית'                          =>  'he_IL',
                                        'हिन्दी'                        =>  'hi_IN',
                                        'Hrvatski'                       =>  'hr_HR',
                                        'Magyar'                         =>  'hu_HU',
                                        'Bahasa Indonesia'               =>  'id_ID',
                                        'Íslenska'                       =>  'is_IS',
                                        'Italiano'                       =>  'it_IT',
                                        '日本語'                          =>  'ja_JP',
                                        '한국어'                           =>  'ko_KR',
                                        'Lietuvių'                       =>  'lt_LT',
                                        'Latviešu'                       =>  'lv_LV',
                                        'Norsk'                          =>  'nb_NO',
                                        'नेपाली'                        =>  'ne_NP',
                                        'Nederlands'                     =>  'nl_NL',
                                        'Polski'                         =>  'pl_PL',
                                        'Português brasileiro'           =>  'pt_BR',
                                        'Português (Português)'          =>  'pt_PT',
                                        'Română'                         =>  'ro_RO',
                                        'Русский'                        =>  'ru_RU',
                                        'Slovenčina'                     =>  'sk_SK',
                                        'Slovenščina'                    =>  'sl_SI',
                                        'Svenska'                        =>  'sv_SE',
                                        'ภาษาไทย'                        =>  'th_TH',
                                        'Türkçe'                         =>  'tr_TR',
                                        'Українська мова'                =>  'uk_UA',
                                        'Tiếng Việt'                     =>  'vi_VN',
                                        '简体中文'                        =>  'zh_CN',
                                        '繁體中文'                        =>  'zh_TW',
                                          ),
                "description"   =>  '',
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                'type'          =>  'textfield',
                'param_name'    =>  'format',
                "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                'heading'       =>  __( 'Format', 'vc_cfb' ),
                'value'         =>  'h:i A',
                'description'   =>  '<table>
      <thead>
        <tr>
          <th>Rule</th>
          <th>Description</th>
          <th>Result</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><code>h</code></td>
          <td>Hour in 12-hour format</td>
          <td>1 – 12</td>
        </tr>
        <tr>
          <td><code>hh</code></td>
          <td>Hour in 12-hour format with a leading zero</td>
          <td>01 – 12</td>
        </tr>
        <tr>
          <td><code>H</code></td>
          <td>Hour in 24-hour format</td>
          <td>0 – 23</td>
        </tr>
        <tr>
          <td><code>HH</code></td>
          <td>Hour in 24-hour format with a leading zero</td>
          <td>00 – 23</td>
        </tr>
      </tbody>
      <tbody>
        <tr>
          <td><code>i</code></td>
          <td>Minutes</td>
          <td>00 – 59</td>
        </tr>
      </tbody>
      <tbody>
        <tr>
          <td><code>a</code></td>
          <td>Day time period</td>
          <td>a.m. / p.m.</td>
        </tr>
        <tr>
          <td><code>A</code></td>
          <td>Day time period in uppercase</td>
          <td>AM / PM</td>
        </tr>
      </tbody>
    </table>',
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              'label_position',
              'label_width',
              'add_icon',
              'icon_align',
              'icon_type',
              'icon_fa',
              'icon_oi',
              'icon_ti',
              'icon_ei',
              'icon_li',
              'icon_color',
              'id',
              'classes',
                  );