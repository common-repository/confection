<?php


function confection_random_string($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}



add_action('confection_name_cron', 'confection_generate_name');
function confection_generate_name() {

    if (! wp_next_scheduled ( 'confection_name_cron' ) )
        wp_schedule_event( time(), 'twicedaily', 'confection_name_cron' );

    $function_names = get_option('confection_ajax_name', array());

    $now = time();
    
    $function_name = array( 
        'expiry' => $now + 86400,
        'remove' => $now + 604800,
        'name' => confection_random_string(20)
    );

    foreach($function_names as $key => $value) {
        if ($value['remove'] <= time()) {
            unlink(CONFECTION_DIR . '/'. $value['name'] .'.php');
            unset($function_names[$key]);
        }
    }

    array_unshift( $function_names, $function_name );

    update_option('confection_ajax_name', $function_names, true);

    $options = new ConfectionWPOptions();
    $options->update_bridge_file();

    return $function_names;

}


function confection_get_last_name() {

    $function_names = get_option('confection_ajax_name', array());

    if (empty($function_names))
        $function_names = confection_generate_name();

    return $function_names[0]['name'];

}


function confection_get_names() {

    $function_names = get_option('confection_ajax_name', array());

    if (empty($function_names))
        $function_names = confection_generate_name();

    return $function_names;

}





$function_names = confection_get_names();
foreach($function_names as $name) {
    add_action('wp_ajax_'. $name['name'], 'confection_send_data', 1);
    add_action('wp_ajax_nopriv_'. $name['name'], 'confection_send_data', 1);
}


add_action('wp_ajax_confection_send_data', 'confection_send_data');
add_action('wp_ajax_nopriv_confection_send_data', 'confection_send_data');
function confection_send_data() {
    $action_name = confection_get_last_name();
    include_once(CONFECTION_DIR . '/'. $action_name .'.php');
}







//Submit Custom UUID
function confection_send_custom_uuid( $uuid, $custom_uuid) {

    $write_key = get_option('confection_write_key', 0);

    $account_id = get_option('confection_account_id', 0);
    if ($account_id == 0)
        return false;

    $listener_url = 'https://substation.confection.io/';
    $domain = parse_url(home_url());
    $url = $listener_url .'?account_id='. $account_id .'&key='. $write_key .'&uuid='. $uuid . '&custom_uuid='. urlencode($custom_uuid) . '&browser=confection&domain='. $domain['host'];

    $result = false;

    if (function_exists('curl_version')) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 2000); //Set timeout to avoid hanging customer server
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, CONFECTION_DIR . '/assets/data/cacert.pem');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, CONFECTION_DIR . '/assets/data/cacert.pem');
        
        curl_exec($ch);
    
        curl_close($ch);
    
    } elseif (ini_get('allow_url_fopen')) {
    
        file_get_contents($url, false, stream_context_create(["http"=>["timeout"=>2]])); //Set timeout to avoid hanging customer server
    
    } else {
        return false;
    }

    return true;
}



//Manually submit an event
function confection_send_event( $uuid, $event_name, $event_value = '') {

    $write_key = get_option('confection_write_key', 0);

    $account_id = get_option('confection_account_id', 0);
    if ($account_id == 0)
        return false;

    $listener_url = 'https://substation.confection.io/';
    $domain = parse_url(home_url());
    $url = $listener_url .'?account_id='. $account_id .'&key='. $write_key .'&uuid='. $uuid . '&event='. urlencode($event_name) . '&value='. urlencode($event_value) . '&browser=confection&domain='. $domain['host'];

    $result = false;

    if (function_exists('curl_version')) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 2000); //Set timeout to avoid hanging customer server
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, CONFECTION_DIR . '/assets/data/cacert.pem');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, CONFECTION_DIR . '/assets/data/cacert.pem');
        
        curl_exec($ch);
    
        curl_close($ch);
    
    } elseif (ini_get('allow_url_fopen')) {
    
        file_get_contents($url, false, stream_context_create(["http"=>["timeout"=>2]])); //Set timeout to avoid hanging customer server
    
    } else {
        return false;
    }

    return true;
}



//Manually submit a field
function confection_send_field( $uuid, $field_name, $field_value) {

    $write_key = get_option('confection_write_key', 0);

    $account_id = get_option('confection_account_id', 0);
    if ($account_id == 0)
        return false;

    $listener_url = 'https://substation.confection.io/';
    $domain = parse_url(home_url());
    $url = $listener_url .'?account_id='. $account_id .'&key='. $write_key .'&uuid='. $uuid . '&name='. urlencode($field_name) . '&value='. urlencode($field_value) . '&browser=confection&domain='. $domain['host'];

    $result = false;

    if (function_exists('curl_version')) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 2000);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, CONFECTION_DIR . '/assets/data/cacert.pem');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, CONFECTION_DIR . '/assets/data/cacert.pem');
        $result = curl_exec($ch);
        curl_close($ch);

    }
    
    if ($result !== false)
        return true;

    if (ini_get('allow_url_fopen'))
        $result = @file_get_contents($url);

    if ($result !== false)
        return true;

    return false;
}
