<?php

namespace LightCommerce\Common\Classes;

use LightCommerce\Admin\Database;
use LightCommerce\Common\Interfaces\CommerceItemInterface;
use LightCommerce\Common\Traits\OrderTrait;

class Order implements CommerceItemInterface {
    use OrderTrait;

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
        $order = $this->db->get_order($id);

        if ($order) {
            $this->customer_name = $order->customer_name;
            $this->total_amount = $order->total_amount;
        }
    }

    public function get_id() {
        return $this->id;
    }

    public function get_name() {
        return $this->customer_name;
    }

    public function get_price() {
        return $this->total_amount;
    }
    public function set_name($customer_name) {
        $this->validate_order_data($customer_name, $this->total_amount);
        $this->customer_name = $customer_name;
    }

    public function set_price($total_amount) {
        $this->validate_order_data($this->customer_name, $total_amount);
        $this->total_amount = $total_amount;
    }

    public function add_order($customer_name, $total_amount) {
        $this->validate_order_data($customer_name, $total_amount);
        $this->id = $this->db->add_order($customer_name, $total_amount);
        if ($this->id) {
            $this->customer_name = $customer_name;
            $this->total_amount = $total_amount;
            $this->log_action("Added new order with ID: $this->id");
            return $this->id;
        }
        return false;
    }

    public function update_order() {
        $this->validate_order_data($this->customer_name, $this->total_amount); // Using the trait's validation
        if ($this->id) {
            $result = $this->db->update_order($this->id, $this->customer_name, $this->total_amount);
            if ($result) {
                $this->log_action("Updated order with ID: $this->id"); // Using the trait's logging
            }
            return $result;
        }
        return false;
    }

    public function delete_order() {
        if ($this->id) {
            $result = $this->db->delete_order($this->id);
            if ($result) {
                $this->log_action("Deleted order with ID: $this->id"); 
                $this->id = null;
                $this->customer_name = null;
                $this->total_amount = null;
                return true;
            }
        }
        return false;
    }

    public function get_formatted_order_details() {
        return $this->format_order_details(); 
    }
}
