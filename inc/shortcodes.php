<?php

//Shortcode for sending events
add_shortcode( 'confection-event', function($atts){

    $atts = shortcode_atts( array(
        'event' => 'pageview',
        'value' => '',
        'js_value' => ''
    ), $atts );

    if ($atts['js_value'] == '') {
        $atts['js_value'] = '"'. $atts['value'] . '"';
    }

    $return = confection_footer_scripts_shortcode();
    
    $return .= '<script>
    window.addEventListener("ConfectionReady", function () {
        confection.submitEvent("'. $atts['event'] .'", '. $atts['js_value'] .');
    });
    </script>';

    return $return;

});


//Shortcode for calling scripts
global $confection_called_scripts;
$confection_called_scripts = false;
add_shortcode('confection-plugin-scripts', 'confection_footer_scripts_shortcode');
function confection_footer_scripts_shortcode() {

    global $confection_called_scripts;

    if ($confection_called_scripts)
        return '';

    //Translate strings
    $confection_strings = array(
        'banner_none' => __('This site isn’t collecting your personal information. Any information you submitted before opting out is still in our system. To manage this information, please ', 'confection'),
        'banner_base' => __('The authors of this site care about your personal data. That’s why they use Confection. Our privacy-first data management app helps people like you take control of the information you share online.', 'confection'),
        'banner_strict_base' => __('At the moment, this site would like permission to use basic data to improve the content it offers you. This would include information like your IP address. We won’t collect more sensitive information such as your name or email address without asking you first.', 'confection'),
        'banner_collecting' => __('You’ve given this site permission to collect information like your IP address, name, and email.', 'confection'),
        'banner_collecting_basic' => __('Collecting Basic Data', 'confection'),
        'banner_collecting_full' => __('Fully Authorized', 'confection'),
        'banner_collecting_not' => __('Not Collecting Your Data', 'confection'),
        'banner_strict' => __('Hi, it’s Confection again. We noticed that you’re about to share information like your name and email with this site. Do we have your permission to do so?', 'confection'),
        'button_more' => __('Learn More', 'confection'),
        'button_accept' => __('Accept', 'confection'),
        'button_deny' => __('Not now', 'confection'),
        'button_stop' => __('Stop Collecting', 'confection'),
        'button_resume' => __('Resume Data Sharing', 'confection'),
        'button_close' => __('Close', 'confection'),
        'button_click' => __('click here', 'confection'),
    );

    $analytics = (get_option('confection_disable_analytics', 0) == '1') ? 'confection.analytics = false;' : 'confection.analytics = true;';

    if ( ($url = get_option( 'confection_custom_cable_url', '' )) == '') {
        $action_name = confection_get_last_name();
        if (get_option('confection_wp_ajax', '') == '1') {
            $url = admin_url('admin-ajax.php?action='. $action_name .'&');
        } else {
            $url = CONFECTION_URL . $action_name . '.php';
        }
    }

    $origin_script = false;
    if (get_option('confection_inline_script', '1') == '1') {
        $origin_script = file_get_contents(CONFECTION_DIR . '/assets/js/client.js');
    }
    if ($origin_script)
        $origin_script = '<script>' . $origin_script .'</script>';
    else
        $origin_script = '<script src="'. CONFECTION_URL .'/assets/js/client.js?ver='. CONFECTION_VERSION .'"></script>';

    $additional_code = wp_unslash(get_option('confection_additional_code', ''));

    $script = '<script>
    var confection_url = "'. $url .'",
        confection_account_id = '. get_option('confection_account_id', 0) .';
        
    window.addEventListener("ConfectionReady", function () {

        //Set Consent Mode
        confection.setPrivacy("'. get_option('confection_privacy', 'none') .'");
        confection.setBannerPosition("'. get_option('confection_banner', 'none') .'");

        //Translate strings
        confection.i18n = '. json_encode($confection_strings) .';

        //Set analytics
        '. $analytics .'

        //Additional Code
        '. $additional_code . '

    });
    </script>' . $origin_script;

    $confection_called_scripts = true;

    return $script;

}


function confection_footer_scripts() {
    echo confection_footer_scripts_shortcode();
}


add_action('init', function(){

    if (get_option('confection_show_footer', '0') == '1') {
        add_action('wp_footer', 'confection_footer_scripts', 100);
    }

}, 10);