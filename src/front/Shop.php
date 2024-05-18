<?php
namespace LightCommerce\Front;

class Shop {
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this,'enqueue_lightcommerce_scripts']);
        
    }

    public function enqueue_lightcommerce_scripts() {
        // Enqueue jQuery script
        wp_enqueue_script('lightcommerce-front-js', LIGHT_COMMERCE_PLUGIN_URL . '/assets/js/front.js', array('jquery'), time(), true);
        wp_enqueue_style('lightcommerce-front-css', LIGHT_COMMERCE_PLUGIN_URL . '/assets/css/front.css', '', time());
    }

}
?>
