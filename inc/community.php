<?php


//Send login action
add_action('wp_login', function( $user_login, $user ){

    if (isset($_COOKIE['confection_uuid'])) {

        $uuids = get_user_meta($user->ID, '_confection_uuid', true);
        if ($uuids == '')
            $uuids = array();
        elseif (!is_array($uuids))
            $uuids = array($uuids);

        if (!in_array($_COOKIE['confection_uuid'], $uuids)) {
            $uuids[] =  $_COOKIE['confection_uuid'];
            update_user_meta($user->ID, '_confection_uuid', $uuids);
            confection_send_field($_COOKIE['confection_uuid'], 'email', $user->user_email);
        }

    }

}, 10, 2);



add_action( 'show_user_profile', 'confection_show_user_uuid_on_profile' );
add_action( 'edit_user_profile', 'confection_show_user_uuid_on_profile' );
function confection_show_user_uuid_on_profile( $user ) { ?>

    <h3><?php _e("Confection Information", "blank"); ?></h3>

    <table class="form-table">
        <tr>
            <th><label for="confection-uuid"><?php _e("Confection UUID", 'confection'); ?></label></th>
            <td>
                <?php 
                $uuids = get_user_meta($user->ID, '_confection_uuid', true);
                if ($uuids == '')
                    $uuids = array();
                elseif (!is_array($uuids))
                    $uuids = array($uuids);
                    
                if (empty($uuids))
                    echo 'No UUID for this user.';
                else {
                    echo '<ul>';
                    foreach ($uuids as $uuid) {
                        echo apply_filters('confection_uuid_profile_screen', '<li>'. $uuid .'</li>', $user->ID);
                    }
                    echo '</ul>';
                }
                ?>
            </td>
        </tr>
    </table>

    <?php 
}