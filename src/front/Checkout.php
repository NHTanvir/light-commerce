<?php
namespace LightCommerce\Front;

use LightCommerce\Admin\Database;
use LightCommerce\Common\Classes\Order;
use WP_REST_Request;
use WP_REST_Response;

class Checkout {
    private $db;

    public function __construct() {
        $this->db = new Database();

        add_action('rest_api_init', function() {
            register_rest_route('lightcommerce/v1', '/place_order', [
                'methods' => 'POST',
                'callback' => [$this, 'place_order_callback'],
                'permission_callback' => '__return_true',
            ]);
        });
    }

    public function place_order_callback(WP_REST_Request $request) {
        $params = $request->get_params();
        $customer_name = sanitize_text_field($params['customer_name']);
        $customer_address = sanitize_textarea_field($params['customer_address']);
        $customer_email = sanitize_email($params['customer_email']);
        $payment_method = sanitize_text_field($params['payment_method']);

        // Calculate total amount based on cart items
        $cart = new Cart($this->db);
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

        $order = new Order();
        $order_id = $order->add_order($customer_name, $customer_address, $customer_email, $payment_method, $total_amount, 'pending');

        if ($order_id) {
            $cart->clear_cart();
            return new WP_REST_Response(['message' => __('Order placed successfully!', 'light-commerce')], 200);
        } else {
            return new WP_REST_Response(['message' => __('Failed to place order.', 'light-commerce')], 500);
        }
    }
}
