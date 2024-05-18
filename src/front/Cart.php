<?php
namespace LightCommerce\Front;

use LightCommerce\Admin\Database;
use WP_REST_Request;
use WP_Error;

class Cart {
    private $db;
    private $session_id;

    public function __construct() {
        $this->db = new Database();
        $this->session_id = $this->get_session_id();

    add_action('rest_api_init', function () {
            register_rest_route('lightcommerce/v1', '/add-to-cart/', array(
                'methods' => 'POST',
                'callback' => array($this, 'lightcommerce_add_to_cart_endpoint'),
            ));

            register_rest_route('lightcommerce/v1', '/remove-from-cart/', array(
                'methods' => 'POST',
                'callback' => array($this, 'lightcommerce_remove_from_cart_endpoint'),
            ));
        });

        add_action('template_redirect', array($this, 'lightcommerce_handle_cart_actions'));
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

    public function lightcommerce_add_to_cart_endpoint(WP_REST_Request $request) {
        $product_id = $request->get_param('product_id');
        $quantity = $request->get_param('quantity');

        if (empty($product_id) || empty($quantity)) {
            return new WP_Error('invalid_params', 'Product ID and quantity are required.', array('status' => 400));
        }


        $this->add_to_cart($product_id, $quantity);

        return array('success' => true);
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
        if (isset($_GET['add_to_cart'])) {
            $product_id = intval($_GET['add_to_cart']);
            $this->add_to_cart($product_id);
            wp_redirect(remove_query_arg('add_to_cart'));
            exit;
        }

        if (isset($_GET['remove_from_cart'])) {
            $product_id = intval($_GET['remove_from_cart']);
            $this->remove_from_cart($product_id);
            wp_redirect(remove_query_arg('remove_from_cart'));
            exit;
        }
    }
}
?>
