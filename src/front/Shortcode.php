<?php

namespace LightCommerce\Front;

use LightCommerce\Admin\Database;
use LightCommerce\Common\Classes\Order;
use Exception;

if (!defined('ABSPATH')) {
    exit;
}

class Shortcode {

    private $db;
    
    public function __construct() {
        $this->db = new Database();

        add_shortcode('lightcommerce_shop', [$this, 'render_shop']);
        add_action('template_redirect', [$this, 'handle_single_product_page']);
        add_shortcode('lightcommerce_cart', [$this,'lightcommerce_cart_shortcode']);
        add_shortcode('lightcommerce_checkout', [$this, 'render_checkout']);
        add_action('rest_api_init', [$this, 'register_custom_routes']);
    }
    
    public function register_custom_routes() {
        register_rest_route('lightcommerce/v1', '/create-payment-intent', [
            'methods' => 'POST',
            'callback' => [$this, 'create_payment_intent_rest'],
        ]);
    }

    public function render_shop() {
        $transient_key = 'lightcommerce_shop_cache'; 

        $cached_output = get_transient($transient_key);
    
        if ($cached_output !== false) {
            return $cached_output;
        }
    
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
                echo "<p class='success-message'></p>";
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<p>' . __('No products found.', 'light-commerce') . '</p>';
        }
    
        $output = ob_get_clean();
        set_transient($transient_key, $output, MINUTE_IN_SECONDS); 
    
        return $output;
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
        $cart = new Cart();
        $items = $cart->get_cart_items();
        if (!$items) {
            echo '<p>' . __('Your cart is empty.', 'light-commerce') . '</p>';
            return;
        }
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
    
            <label><?php _e('Payment Method:', 'light-commerce'); ?></label>
            <input type="radio" name="payment_method" value="cod" required> <?php _e('Cash on Delivery', 'light-commerce'); ?>
            <input type="radio" name="payment_method" value="stripe" required> <?php _e('Credit Card', 'light-commerce'); ?>
            <br>
    
            <div id="stripe-card-element" style="display: none;">
                <div id="card-element"></div>
            </div>
    
            <button type="submit" id="submit-payment"><?php _e('Place Order', 'light-commerce'); ?></button>
            <div id="checkout-message"></div>
        </form>
    
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            var stripe = Stripe('<?php echo esc_js(get_option('stripe_publishable_key')); ?>');
            var elements = stripe.elements();
            var cardElement = elements.create('card');
            cardElement.mount('#card-element');
        </script>
        <?php
        return ob_get_clean();
    }
    

    public function create_payment_intent_rest($request) {
        $payment_method_id = $request->get_param('payment_method_id');
        
        \Stripe\Stripe::setApiKey(get_option('stripe_secret_key'));
        
        $cart = new Cart();
        $cart_items = $cart->get_cart_items();
        $total_amount = 0;
        
        if ($cart_items) {
            foreach ($cart_items as $item) {
                $product = $this->db->get_product($item->product_id);
                if ($product) {
                    $total_amount += $product->price * $item->quantity;
                }
            }
        }
        
        // Convert total amount to cents
        $total_amount_cents = $total_amount * 100;
        
        try {
            $intent = \Stripe\PaymentIntent::create([
                'payment_method' => $payment_method_id,
                'amount' => $total_amount_cents,
                'currency' => 'usd',
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => get_site_url(),
            ]);
    
            // Add order
            $params = $request->get_params();
            $customer_name = sanitize_text_field($params['customer_name']);
            $customer_address = sanitize_textarea_field($params['customer_address']);
            $customer_email = sanitize_email($params['customer_email']);
            $payment_method = sanitize_text_field($params['payment_method']);
    
            $order = new Order();
            $order_id = $order->add_order($customer_name, $customer_address, $customer_email, $payment_method, $total_amount, 'completed');
            if ($order_id) {
                $cart->clear_cart();
            }
            return new \WP_REST_Response(['success' => true, 'order_id' => $order_id], 200);
        } catch (Exception $e) {
            // Error occurred while creating Payment Intent
            error_log('Error creating Payment Intent: ' . $e->getMessage());
            return new \WP_REST_Response(['message' => $e->getMessage()], 500);
        }
    }
    
     
}