<?php

namespace LightCommerce\Front;

use LightCommerce\Admin\Database;

class Cart {
    private $db;
    private $session_id;

    public function __construct() {
        $this->db = new Database();
        $this->session_id = $this->get_session_id();
        add_action('template_redirect', [$this, 'lightcommerce_handle_cart_actions']);
    }

    private function get_session_id() {
        if (!isset($_COOKIE['lightcommerce_session_id'])) {
            $session_id = bin2hex(random_bytes(32));
            setcookie('lightcommerce_session_id', $session_id, time() + 3600 * 24 * 30, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
        } else {
            $session_id = $_COOKIE['lightcommerce_session_id'];
        }
        return $session_id;
    }

    public function get_cart_items() {
        return $this->db->get_cart_items($this->session_id);
    }

    public function add_to_cart($product_id, $quantity = 1) {
        $this->db->add_to_cart($this->session_id, $product_id, $quantity);
    }

    public function remove_from_cart($product_id) {
        $this->db->remove_from_cart($this->session_id, $product_id);
    }

    function lightcommerce_handle_cart_actions() {
        $cart = new Cart();
    
        if (isset($_GET['add_to_cart'])) {
            $product_id = intval($_GET['add_to_cart']);
            $cart->add_to_cart($product_id);
            wp_redirect(remove_query_arg('add_to_cart'));
            exit;
        }
    
        if (isset($_GET['remove_from_cart'])) {
            $product_id = intval($_GET['remove_from_cart']);
            $cart->remove_from_cart($product_id);
            wp_redirect(remove_query_arg('remove_from_cart'));
            exit;
        }
    }
    
}
