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
                'param_name'    =>  'showmonthsshort',
                "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                'heading'       =>  __( 'Show months short?', 'vc_cfb' ),
                'value'         =>  'false',
                'description'   =>  __( 'Element\'s will be translated automatically.', 'vc_cfb' ),
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                'type'          =>  'checkbox',
                'param_name'    =>  'showweekdaysfull',
                "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                'heading'       =>  __( 'Show week days full?', 'vc_cfb' ),
                'value'         =>  'false',
                'description'   =>  __( 'Element\'s will be translated automatically.', 'vc_cfb' ),
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                'type'          =>  'checkbox',
                'param_name'    =>  'selectmonths',
                "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                'heading'       =>  __( 'Choose months as select?', 'vc_cfb' ),
                'value'         =>  'false',
                'description'   =>  '',
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                'type'          =>  'checkbox',
                'param_name'    =>  'selectyears',
                "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                'heading'       =>  __( 'Choose years as select?', 'vc_cfb' ),
                'value'         =>  'false',
                'description'   =>  '',
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                'type'          =>  'textfield',
                'param_name'    =>  'total_years',
                "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                'heading'       =>  __( 'Number of years to show in the dropdown', 'vc_cfb' ),
                'value'         =>  '4',
                'dependency'   => array(
                                          'element' => 'selectyears',
                                          'value'   => 'true',
                                        ),
                'description'   =>  '',
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
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
                'param_name'    =>  'min',
                "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                'heading'       =>  __( 'Min date', 'vc_cfb' ),
                'value'         =>  '',
                'description'   =>  __( 'Set the minimum selectable dates on the picker.<br/>For example: 2015-7-14', 'vc_cfb' ),
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                'type'          =>  'textfield',
                'param_name'    =>  'max',
                "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                'heading'       =>  __( 'Max date', 'vc_cfb' ),
                'value'         =>  '',
                'description'   =>  __( 'Set the maximum selectable dates on the picker.<br/>For example: 2015-7-14', 'vc_cfb' ),
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
                'value'         =>  '',
                'description'   =>  __( 'If value is empty will be using default value for each language.', 'vc_cfb' ).'<table>
      <thead>
        <tr>
          <th>Rule</th>
          <th>Description</th>
          <th>Result</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><code>d</code></td>
          <td>Date of the month</td>
          <td>1 – 31</td>
        </tr>
        <tr>
          <td><code>dd</code></td>
          <td>Date of the month with a leading zero</td>
          <td>01 – 31</td>
        </tr>
        <tr>
          <td><code>ddd</code></td>
          <td>Day of the week in short form</td>
          <td>Sun – Sat</td>
        </tr>
        <tr>
          <td><code>dddd</code></td>
          <td>Day of the week in full form</td>
          <td>Sunday – Saturday</td>
        </tr>
      </tbody>
      <tbody>
        <tr>
          <td><code>m</code></td>
          <td>Month of the year</td>
          <td>1 – 12</td>
        </tr>
        <tr>
          <td><code>mm</code></td>
          <td>Month of the year with a leading zero</td>
          <td>01 – 12</td>
        </tr>
        <tr>
          <td><code>mmm</code></td>
          <td>Month name in short form</td>
          <td>Jan – Dec</td>
        </tr>
        <tr>
          <td><code>mmmm</code></td>
          <td>Month name in full form</td>
          <td>January – December</td>
        </tr>
      </tbody>
      <tbody>
        <tr>
          <td><code>yy</code></td>
          <td>Year in short form <b>*</b></td>
          <td>00 – 99</td>
        </tr>
        <tr>
          <td><code>yyyy</code></td>
          <td>Year in full form</td>
          <td>2000 – 2999</td>
        </tr>
      </tbody>
    </table>',
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                "type"          =>  "dropdown",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_select vc_cfb_hide_field",
                "heading"       =>  __( 'First day of week', 'vc_cfb' ),
                "param_name"    =>  "firstday",
                "value"         =>  array( 
                                            __( 'Monday', 'vc_cfb' )    => '1', 
                                            __( 'Sunday', 'vc_cfb' )    => '7', 
                                          ),
                "description"   =>  __( 'Select content type of field.', 'vc_cfb' ),
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                "type"          =>  "custom_options",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_select vc_cfb_hide_field",
                "heading"       =>  __( 'Disable dates', 'vc_cfb' ),
                "param_name"    =>  "disable_dates",
                "value"         =>  '',
                "description"   =>  __( 'Disable a specific or arbitrary set of dates selectable on the picker. One rule per line.', 'vc_cfb' ),
                "format"        =>  '<div><input type="text" name="from" placeholder="'.__( 'From', 'vc_cfb' ).'"/><input type="text" name="to" placeholder="'.__( 'To', 'vc_cfb' ).'"/></div>',
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