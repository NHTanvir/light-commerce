<?php
namespace LightCommerce\Front;

class Shop {
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this,'enqueue_lightcommerce_scripts']);
        
    }

    public function enqueue_lightcommerce_scripts() {

        wp_enqueue_script('lightcommerce-front-js', LIGHT_COMMERCE_PLUGIN_URL . '/assets/js/front.js', array('jquery'), time(), true);
        wp_enqueue_style('lightcommerce-front-css', LIGHT_COMMERCE_PLUGIN_URL . '/assets/css/front.css', '', time());

        wp_localize_script('lightcommerce-front-js', 'lightcommerce_vars', array(
            'create_payment_intent_url' => esc_url_raw(rest_url('lightcommerce/v1/create-payment-intent')),
            'place_order_url' => esc_url_raw(rest_url('lightcommerce/v1/place_order')),
        ));
    }

}
?>
