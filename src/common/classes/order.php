<?php
namespace LightCommerce;

use LightCommerce\Admin\Database;

class Order {
    private $id;
    private $customer_name;
    private $total_amount;
    private $db;

    public function __construct($id = null) {
        $this->db = new Database();
        if ($id) {
            $this->id = $id;
            $this->load_order($id);
        }
    }

    private function load_order($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'lightcommerce_order';
        $order = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));

        if ($order) {
            $this->customer_name = $order->customer_name;
            $this->total_amount = $order->total_amount;
        }
    }

    public function get_id() {
        return $this->id;
    }

    public function get_customer_name() {
        return $this->customer_name;
    }

    public function get_total_amount() {
        return $this->total_amount;
    }

    public function set_customer_name($customer_name) {
        $this->customer_name = $customer_name;
    }

    public function set_total_amount($total_amount) {
        $this->total_amount = $total_amount;
    }

    public function add_order($customer_name, $total_amount) {
        $this->id = $this->db->add_order($customer_name, $total_amount);
        if ($this->id) {
            $this->customer_name = $customer_name;
            $this->total_amount = $total_amount;
            return $this->id;
        }
        return false;
    }

    public function update_order() {
        if ($this->id) {
            return $this->db->update_order($this->id, $this->customer_name, $this->total_amount);
        }
        return false;
    }

    public function delete_order() {
        if ($this->id) {
            $result = $this->db->delete_order($this->id);
            if ($result) {
                $this->id = null;
                $this->customer_name = null;
                $this->total_amount = null;
                return true;
            }
        }
        return false;
    }
}
