<?php

class ConfectionWPOptions {

    private $menu_slug;

    private $options;

    public $notices = array(
        'ok' => array(),
        'error' => array()
    );

    public function __construct() {

        $this->menu_slug = 'confection-options-page';

        $this->options = array(
            'confection_account_id' => array( 
                'type' => 'text',
                'sanitize' => 'integer',
                'label' => __('Account ID', 'confection'),
                'default' => 0,
                'description' => __('Your Account ID can be found found at Confection Dashboard at section Install Confection -> API Keys.', 'confection')
            ),
            'confection_write_key' => array( 
                'type' => 'text',
                'sanitize' => 'text',
                'label' => __('Write Key', 'confection'),
                'default' => 0,
                'description' => __('Your Write Key can be found found at Confection Dashboard at section Install Confection -> API Keys.', 'confection')
            ),
            'confection_show_footer' => array( 
                'type' => 'true',
                'sanitize' => 'boolean',
                'label' => __('Install Sitewide?', 'confection'),
                'default' => 0,
                'description' => __('Check this to install Confection on all your site’s pages and posts. If you’d prefer to install Confection selectively, use the following shortcode:', 'confection') . ' <code>[confection-plugin-scripts]</code>'. __(' Add that to any page or post on which you want Confection to run.<br /><br />Note, some themes don\'t include the native WordPress footer function. If, after selecting the above checkbox and saving this page, Confection isn\'t active on your site\'s front end, this is probably the issue.<br /><br /> 
                Like many plugins, Confection\'s script files and functions are bound to the  <code>wp_footer</code> hook. If your theme doesn\'t include the WP footer function, and you want Confection to run sitewide, you\'ll need to add the above shortcode to some universal element. For example, you could add it to a text widget (Appearance > Widgets) and drag it into your sidebar, header, or footer widget area.<br /><br />Confused? Still having issues? Email us at <a href="mailto:get@confection.io">get@confection.io</a>', 'confection')
            ),
            'confection_wp_ajax' => array( 
                'type' => 'true',
                'sanitize' => 'boolean',
                'label' => __('Use WordPress Ajax?', 'confection'),
                'default' => 0,
                'description' => __('Check this to use Confection Cable inside WordPress Ajax context. Keep this option unchecked if you are having performance issues. If you are using Security plugins like WordFence or if your Server blocks code execution in subfolders, you may need to check this box. This will also fix 403 errors. <strong>Default: Unchecked.</strong>', 'confection')
            ),
            'confection_custom_cable_url' => array( 
                'type' => 'text',
                'sanitize' => 'text',
                'label' => __('Custom Cable URL', 'confection'),
                'default' => '',
                'description' => __('Fill this if you want to use a Custom Cable URL, useful if you want to use a Cloudflare Worker or similar solution. <strong>Default: Empty.</strong>', 'confection')
            ),
            'confection_inline_script' => array( 
                'type' => 'true',
                'sanitize' => 'boolean',
                'label' => __('Inline Confection Script?', 'confection'),
                'default' => 1,
                'description' => __('Check this to use our script inlined. This can get better results in PageSpeed score and will make Confection trigger faster. <strong>Default: Checked.</strong>', 'confection')
            ),
            'confection_disable_analytics' => array( 
                'type' => 'true',
                'sanitize' => 'boolean',
                'label' => __('Disable Confection Analytics?', 'confection'),
                'default' => 0,
                'description' => __('Check this to disable Confection Analytics system, which will track common analytics data like pageviews and present them on our Dashboard. If you preffer to disable it, just check this box. <strong>Default: Unchecked.</strong>', 'confection')
            ),
            'confection_enable_community' => array( 
                'type' => 'true',
                'sanitize' => 'boolean',
                'label' => __('Enable Community Integration?', 'confection'),
                'default' => 0,
                'description' => __('Check this to save UUID on user details and send user email to Confection when the user do a login in your site. <strong>Default: Unchecked.</strong>', 'confection')
            ),
            'confection_enable_woocommerce' => array( 
                'type' => 'true',
                'sanitize' => 'boolean',
                'label' => __('Enable WooCommerce Integration?', 'confection'),
                'default' => 0,
                'description' => __('Check this to save UUID on orders details and send order value event to Confection automatically when an order is completed. <strong>Default: Unchecked.</strong>', 'confection')
            ),
            'confection_privacy' => array( 
                'type' => 'select',
                'sanitize' => 'text',
                'label' => __('Select Privacy Approach', 'confection'),
                'default' => 'none',
                'options' => array(
                    'none' => __('None', 'confection'),
                    'gdpr' => __('GDPR', 'confection'),
                    'ccpa' => __('CCPA', 'confection'),
                    'lgpd' => __('LGPD', 'confection'),
                ),
                'top_description' => __('Confection allows users to collect, store, and distribute data in accordance with the data protection law of their choice. Make your selection below. <strong>Default: None.</strong>', 'confection')
            ),
            'confection_banner' => array( 
                'type' => 'select',
                'sanitize' => 'text',
                'label' => __('Select Privacy Banner Position', 'confection'),
                'default' => 'none',
                'options' => array(
                    'none' => __('None', 'confection'),
                    'left' => __('Left', 'confection'),
                    'center' => __('Center', 'confection'),
                    'right' => __('Right', 'confection'),
                ),
                'top_description' => __('Confection uses a compact, minimally-invasive banner to get user consent. Unlike other consent banners, ours only appears when necessary. Select the position of your banner below. <strong>Default: None.</strong>', 'confection')
            ),
            'confection_additional_code' => array( 
                'type' => 'textarea',
                'sanitize' => 'text',
                'label' => __('Additional Initialization Code', 'confection'),
                'default' => '',
                'description' => __('<strong>For Advanced Users:</strong> If you want to execute custom javascript code during Confection initialization, you can put the code here.', 'confection')
            ),
            'import_code' => array( 
                'type' => 'import_code',
                'sanitize' => 'text',
                'label' => __('Import from Code Builder', 'confection'),
                'default' => '',
                'description' => __('If you preffer to use the Code Builder, you can paste the code output from the Code Builder here. Attention, all the settings will be replaced by the information provided by the Code Builder.')
            )
        );

        add_action( 'admin_menu', array( $this, 'create_options_menu_page' ) );

        add_action( 'admin_init', array( $this, 'save_options' ) );

    }


    /*
     * Register the Options Menu
     */
    public function create_options_menu_page() {
        
        add_menu_page(
            'Confection', 
            'Confection', 
            'manage_options', 
            $this->menu_slug, 
            array( $this, 'create_options_page_render' ),
            CONFECTION_URL . '/assets/img/confection.svg'
        );

    }


    /*
     * Save the options submitted
     */
    public function save_options() {

        if (!isset($_GET['page']) || $_GET['page'] != $this->menu_slug)
            return;

        if (empty($_POST))
            return;

        $any_saved = $this->import_options();

        foreach($this->options as $key => $atts) {

            if ($atts['type'] == 'import_code')
                continue;

            if (isset($_POST[$key])) {

                $value = $_POST[$key];

                if ($atts['sanitize'] == 'integer') {
                    $value = intval($value);
                } elseif ($atts['sanitize'] == 'boolean') {
                    $value = intval($value);
                    if ($value != 0 && $value != 1)
                        $value = 0;
                }

                if (get_option($key, '') != $value) {
                    update_option($key, $value);
                    $any_saved = true;
                }

            } elseif ($atts['type'] == 'true') {
                update_option($key, '0');
                $any_saved = true;
            }

        }

        if ($any_saved)
            $this->notices['ok'][] = __('Saved!', 'confection');

        $this->update_bridge_file();

    }


    public function import_options() {

        if (isset($_POST['import_code']) && !empty($_POST['import_code'])) {

            $sent_data = str_replace('\"', '"', $_POST['import_code']);

            //Account ID
            $_POST['confection_account_id'] = '';
            if ( ($start = strpos($sent_data, 'confection_account_id = "')) !== false) {
                $start += 25;
                if ( ($end = strpos($sent_data, '"', $start)) !== false) {
                    $value = intval(substr($sent_data, $start, $end-$start));
                    if ($value > 0) {
                        $_POST['confection_account_id'] = $value;
                    }
                }
            }

            if (empty($_POST['confection_account_id'])) {
                $this->notices['error'][] = __('Import failed. Please copy and paste the entire code from the Code Builder.', 'confection');
                return false;
            }

            //Disable Analytics
            if (($start = strpos($sent_data, 'confection.analytics = true')) !== false) {
                $_POST['confection_disable_analytics'] = 1;
            } else {
                unset($_POST['confection_disable_analytics']);
            }

            //Privacy
            $_POST['confection_privacy'] = '';
            if (($start = strpos($sent_data, 'confection.setPrivacy("')) !== false) {
                $start += 23;
                if (($end = strpos($sent_data, '"', $start)) !== false) {
                    $value = substr($sent_data, $start, $end-$start);
                    $_POST['confection_privacy'] = $value;
                }
            }

            //Banner Position
            $_POST['confection_banner'] = '';
            if (($start = strpos($sent_data, 'confection.setBannerPosition("')) !== false) {
                $start += 30;
                if (($end = strpos($sent_data, '"', $start)) !== false) {
                    $value = substr($sent_data, $start, $end-$start);
                    $_POST['confection_banner'] = $value;
                }
            }

            $_POST['confection_additional_code'] = '';
            if (($start = strpos($sent_data, 'confection.setCustomLogo("')) !== false) {
                if (($end = strpos($sent_data, '");', $start+26)) !== false) {
                    $value = substr($sent_data, $start, $end+3-$start);
                    $_POST['confection_additional_code'] .= $value;
                }
            }
            if (($start = strpos($sent_data, 'confection.addStyle("')) !== false) {
                if (($end = strpos($sent_data, '");', $start+21)) !== false) {
                    $value = substr($sent_data, $start, $end+3-$start);
                    $_POST['confection_additional_code'] .= $value;
                }
            }
            $end = 0;
            while (($start = strpos($sent_data, 'confection.i18n.', $end)) !== false) {
                if (($end = strpos($sent_data, '";', $start+16)) !== false) {
                    $value = substr($sent_data, $start, $end+2-$start);
                    $_POST['confection_additional_code'] .= $value;
                }
            }
            $_POST['confection_additional_code'] = wp_slash($_POST['confection_additional_code']);
            
            $this->notices['ok'][] = __('Imported with success!', 'confection');
            return true;
        }

        return false;
    }

    /*
     * Update the cable file with the new write key
     */
    public function update_bridge_file() {

        $wk = get_option('confection_write_key', '0');
        $origin_wk = '$write_key = 0;';
        $new_wk = '$write_key = "'. $wk .'";';

        //Get original file
        $bridge = file_get_contents( CONFECTION_DIR . '/bridge-original.php' );
        $bridge = str_replace($origin_wk, $new_wk, $bridge, $count);

        if ($count == 1) {
            //Get names
            $filenames = confection_get_names();
            $anyerror = 0;
            foreach ($filenames as $filename) {
                if (file_put_contents( CONFECTION_DIR . '/'. $filename['name'] .'.php' , $bridge) === false) {
                    $anyerror++;
                }
            }
            
            if ($anyerror > 0) {
                update_option('confection_writed_key', '0', false);
                $this->notices['error'][] = __('Error while saving the Write Key on Cable file. Please try again, if this error keeps occuring, please use the option "Use WordPress Ajax"!', 'confection');
                return false;
            } else {
                update_option('confection_writed_key', '1', false);
                return true;
            }
        } else {
            update_option('confection_writed_key', '0', false);
            $this->notices['error'][] = __('Error while saving the Write Key on Cable file. Please try again, if this error keeps occuring, please use the option "Use WordPress Ajax"!', 'confection');
            return false;
        }

    }


    /*
     * Render the output of admin page
     */
    public function create_options_page_render() {

        if (get_option('confection_writed_key', '1') == '0' && get_option('confection_wp_ajax', '0') != '1') {
            if ($this->update_bridge_file() === false) {
                $this->notices['error'][] = __('Your write key was not saved to your Cable File. Please try reloading the page, or activate the option "Use WordPress Ajax"!', 'confection');
            }
        }
        
        ?>
        <div class="wrap">
            <h1><?php echo __('Confection Options', 'confection'); ?></h1>

            <style>
                .button.purple, .button.green { color:#eee; border:1px solid #23282d; margin:15px 10px 0 0 } 
                .button.purple { background-color:#927297 } 
                .button.green { background-color:#37A965 }
                .dashicons { margin-top:4px }
            </style>
                        
            <a href="https://dashboard.confection.io/" target="_blank" class="button purple"><div class="dashicons dashicons-migrate"></div> Log In to Your Confection Account</a>
            <a href="https://dashboard.confection.io/register/" target="_blank" class="button green"><div class="dashicons dashicons-plus-alt"></div> Need an Account? Set One Up. (It's Free.)</a>

            <?php
            if (!empty($this->notices['ok'])) {
                foreach($this->notices['ok'] as $ok) {
                    ?>
                    <div class="notice notice-success is-dismissible">
                        <p><?php echo $ok; ?></p>
                    </div>
                    <?php
                }
            }
            if (!empty($this->notices['error'])) {
                foreach($this->notices['error'] as $ok) {
                    ?>
                    <div class="notice notice-error is-dismissible">
                        <p><?php echo $ok; ?></p>
                    </div>
                    <?php
                }
            }
            ?>

            <form method="post" action="<?php echo admin_url('admin.php?page=' . $this->menu_slug); ?>">

                <table class="form-table permalink-structure">
                    <tbody>
                        <?php foreach($this->options as $key => $atts) : ?>
                            <tr>
                                <th scope="row">
                                    <label for="<?php echo $key; ?>"><?php echo $atts['label']; ?></label>
                                </th>
                                <td>
                                    <?php if (!empty($atts['top_description'])) echo '<p><em>'. $atts['top_description'] .'</em></p>'; ?>

                                    <p>
                                    <?php if ($atts['type'] == 'text') : ?>
                                        <input type="text" name="<?php echo $key; ?>" value="<?php echo get_option($key, $atts['default']); ?>" id="<?php echo $key; ?>">
                                    <?php elseif ($atts['type'] == 'true') : ?>
                                        <input type="checkbox" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="1" <?php checked('1', get_option($key, $atts['default']), true); ?>> <?php echo __('Yes', 'confection'); ?>
                                    <?php elseif ($atts['type'] == 'textarea') : ?>
                                        <textarea name="<?php echo $key; ?>" id="<?php echo $key; ?>" style="width: 100%; height: 150px;"><?php echo esc_attr(wp_unslash(get_option($key, $atts['default']))); ?></textarea>
                                    <?php elseif ($atts['type'] == 'select') : ?>
                                        <?php $current = get_option($key, $atts['default']); ?>
                                        <select name="<?php echo $key; ?>" id="<?php echo $key; ?>">
                                            <?php foreach($atts['options'] as $option_value => $option_label) : ?>
                                                <option <?php selected($option_value, $current, true); ?> value="<?php echo $option_value; ?>"> <?php echo $option_label; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php elseif ($atts['type'] == 'import_code') : ?>
                                        <a href="https://dashboard.confection.io/code-builder/" target="_blank" class="button green" style="margin: 0 0 15px;"><span class="dashicons dashicons-media-code"></span> Open Code Builder</a>
                                        <textarea name="<?php echo $key; ?>" id="<?php echo $key; ?>" style="width: 100%; height: 150px;"><?php echo esc_attr(get_option($key, $atts['default'])); ?></textarea>
                                    <?php endif; ?>
                                    </p>

                                    <?php if (!empty($atts['description'])) echo '<p><em>'. $atts['description'] .'</em></p>'; ?>
                                    
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php submit_button(); ?>

            </form>
        </div>

        <style>
        select + span {
            display: block;
            margin-top: 10px;
        }
        select + span strong {
            display: inline-block;
            background: #ddd;
            padding: 10px 15px;
            margin-top: 10px;
        }
        select + span em {
            display: block;
        }
        </style>
        <script>
        jQuery(document).ready( function($){
            $('#confection_privacy').change( function(){
                var msg_a = '',
                    msg_b = '';

                $('#confection_banner').closest('tr').show();

                switch($('#confection_privacy').val()) {
                    case 'none':
                        $('#confection_banner').closest('tr').hide();
                        msg_a = '<?php _e('I don’t want Confection to get a user’s consent to collect his/her data.', 'confection'); ?>';
                        msg_b = '<?php _e('Confection will collect all the data it can without waiting for user permission.', 'confection'); ?>';
                        break;
                    case 'gdpr':
                        msg_a = '<?php _e('I want Confection to collect, store, and distribute user data in accordance with the European Union’s General Data Protection Regulation.', 'confection'); ?>';
                        msg_b = '<?php _e('Until Confection gets consent from a user, it will only collect non-personally-identifying information.', 'confection'); ?>';
                        break;
                    case 'ccpa':
                        msg_a = '<?php _e('I want Confection to collect, store, and distribute user data in accordance with the California Consumer Privacy Act.', 'confection'); ?>';
                        msg_b = '<?php _e('Until Confection gets consent from a user, it will only collect non-personally-identifying information.', 'confection'); ?>';
                        break;
                    case 'lgpd':
                        msg_a = '<?php _e('I want Confection to collect, store, and distribute user data in accordance with Brazil’s Lei Geral de Proteção de Dados Pessoais.', 'confection'); ?>';
                        msg_b = '<?php _e('Until Confection gets consent from a user, it will not collect any information, personally identifying or otherwise. Confection will get a second consent from the user when it begins collecting personally identifying information.', 'confection'); ?>';
                        break;
                }

                $('#confection_privacy + span').remove();
                $('#confection_privacy').after('<span><strong>'+ msg_a +'</strong><em>' + msg_b +'</em></span>');
            });
            $('#confection_privacy').change();

            
            $('#confection_banner').change( function(){
                var msg_a = '',
                    msg_b = '';

                switch($('#confection_banner').val()) {
                    case 'none':
                        msg_a = '<?php _e('I will use other means to collect user consent.', 'confection'); ?>';
                        msg_b = '<?php _e('The Confection banner will not appear. Use the JavaScript function <code>confection.setConsent(2)</code> to start collecting data.', 'confection'); ?>';
                        break;
                    case 'left':
                        msg_a = '<?php _e('I want the banner to appear in the bottom left corner of the browser', 'confection'); ?>';
                        msg_b = '';
                        break;
                    case 'right':
                        msg_a = '<?php _e('I want the banner to appear in the bottom right corner of the browser', 'confection'); ?>';
                        msg_b = '';
                        break;
                    case 'center':
                        msg_a = '<?php _e('I want the banner to appear in at the bottom of the browser, in the middle of the screen.', 'confection'); ?>';
                        msg_b = '';
                        break;
                }

                $('#confection_banner + span').remove();
                $('#confection_banner').after('<span><strong>'+ msg_a +'</strong><em>' + msg_b +'</em></span>');
            });
            $('#confection_banner').change();
        });
        </script>
        <?php
    }

}
