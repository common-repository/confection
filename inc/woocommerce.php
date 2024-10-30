<?php


//Save UUID on Order Creation
add_action( 'woocommerce_checkout_order_created', function($order){

    if (isset($_COOKIE['confection_uuid'])) {
        update_post_meta($order->get_id(), '_confection_uuid', $_COOKIE['confection_uuid']);
    }

}, 10, 1);


//Send event on Order Payment Complete
add_action( 'woocommerce_order_status_changed', function($order_id, $old, $new){

    if ($old == 'completed')
        return;

    if ($new != 'completed')
        return;
    
    if (get_post_meta($order_id, '_confection_uuid', true) != '' && get_post_meta($order_id, '_confection_uuid_sent', true) == '') {

        $order = wc_get_order( $order_id );
        $order_object = array(
            'value' => $order->get_total(), 
            'id' => $order_id,
            'currency' => $order->get_currency(),
            'shipping' => $order->get_shipping_total(),
            'tax' => $order->get_total_tax(),
            'items' => array()
        );
        foreach ( $order->get_items() as $item_id => $item ) {
            $order_object['items'][] = array(
                'id' => $item->get_product_id(),
                'name' => $item->get_name(),
                'variation' => $item->get_variation_id(),
                'quantity' => $item->get_quantity(),
                'value' =>$item->get_total()
            );
        }

        $uuid = get_post_meta($order_id, '_confection_uuid', true);
        confection_send_field($uuid, 'email', $order->get_billing_email());
        confection_send_event($uuid, 'purchased', json_encode($order_object));

        update_post_meta($order->get_id(), '_confection_uuid_sent', '1');

    }

}, 10, 3);