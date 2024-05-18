<?php

namespace LightCommerce\Front;

use LightCommerce\Admin\Database;

if (!defined('ABSPATH')) {
    exit;
}

class Shortcode {
    
    public function __construct() {
        add_shortcode('lightcommerce_shop', [$this, 'render_shop']);
        add_action('template_redirect', [$this, 'handle_single_product_page']);
        add_shortcode('lightcommerce_cart', [$this,'lightcommerce_cart_shortcode']);
        add_shortcode('lightcommerce_checkout', [$this, 'render_checkout']);
    }

    public function render_shop() {
        ob_start();
        
        $db = new Database();
        $products = $db->get_all_products();
        
        if ($products) {
            echo '<div class="lightcommerce-products">';
            foreach ($products as $product) {
                echo '<div class="lightcommerce-product">';
                echo '<h2>' . esc_html($product->name) . '</h2>';
                echo '<p>' . esc_html($product->description) . '</p>';
                echo '<p>' . __('Price: ', 'light-commerce') . esc_html($product->price) . '</p>';
                echo '<button class="add-to-cart-btn" data-product-id="' . esc_attr($product->id) . '">' . __('Add to Cart', 'light-commerce') . '</button>';
                echo '<a href="' . get_permalink(get_the_ID()) . '?product_id=' . esc_attr($product->id) . '" class="button">' . __('View Product', 'light-commerce') . '</a>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<p>' . __('No products found.', 'light-commerce') . '</p>';
        }
        
        return ob_get_clean();
    }

    public function handle_single_product_page() {
        if (isset($_GET['product_id'])) {
            $product_id = intval($_GET['product_id']);
            
            $db = new Database();
            $product = $db->get_product($product_id);
            
            if ($product) {
                echo '<div class="lightcommerce-single-product">';
                echo '<h1>' . esc_html($product->name) . '</h1>';
                echo '<p>' . esc_html($product->description) . '</p>';
                echo '<p>' . __('Price: ', 'light-commerce') . esc_html($product->price) . '</p>';
                echo '<a href="' . get_permalink(get_the_ID()) . '?add_to_cart=' . esc_attr($product->id) . '" class="button">' . __('Add to Cart', 'light-commerce') . '</a>';
                echo '</div>';
                exit;
            }
        }
    }

    public function lightcommerce_cart_shortcode() {
        ob_start();
    
        $cart = new Cart();
        $items = $cart->get_cart_items();
    
        if ($items) {
            echo '<div class="lightcommerce-cart">';
            foreach ($items as $item) {
                $product = (new Database())->get_product($item->product_id);
                echo '<div class="lightcommerce-cart-item" data-product-id="' . esc_attr($product->id) . '">';
                echo '<h2>' . esc_html($product->name) . '</h2>';
                echo '<p>' . __('Quantity: ', 'light-commerce') . esc_html($item->quantity) . '</p>';
                echo '<p>' . __('Price: ', 'light-commerce') . esc_html($product->price * $item->quantity) . '</p>';
                echo '<button class="remove-from-cart-btn" data-product-id="' . esc_attr($product->id) . '">' . __('Remove from Cart', 'light-commerce') . '</button>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<p>' . __('Your cart is empty.', 'light-commerce') . '</p>';
        }
    
        return ob_get_clean();
    }

    public function render_checkout() {
        ob_start();
        ?>
        <form id="lightcommerce-checkout-form">
            <label for="customer_name"><?php _e('Name:', 'light-commerce'); ?></label>
            <input type="text" id="customer_name" name="customer_name" required>
            <br>
            
            <label for="customer_address"><?php _e('Address:', 'light-commerce'); ?></label>
            <textarea id="customer_address" name="customer_address" required></textarea>
            <br>
            <label for="customer_email"><?php _e('Email:', 'light-commerce'); ?></label>
            <input type="email" id="customer_email" name="customer_email" required>
            <br>            
            <button type="submit"><?php _e('Place Order', 'light-commerce'); ?></button>
        </form>
        <div id="checkout-message"></div>
        <?php
        return ob_get_clean();
    }
    
    
}
