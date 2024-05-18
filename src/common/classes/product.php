<?php
namespace LightCommerce\Common\Classes;

use LightCommerce\Admin\Database;
use LightCommerce\Common\Interfaces\CommerceItemInterface;
use LightCommerce\Common\Traits\ProductTrait;

class Product implements CommerceItemInterface {
    use ProductTrait;

    private $id;
    private $name;
    private $price;
    private $description;
    private $db;

    public function __construct($id = null) {
        $this->db = new Database();
        if ($id) {
            $this->id = $id;
            $this->load_product($id);
        }
    }

    private function load_product($id) {
        $product = $this->db->get_product($id);

        if ($product) {
            $this->name = $product->name;
            $this->price = $product->price;
            $this->description = $product->description;
        }
    }

    public function get_id() {
        return $this->id;
    }

    public function get_name() {
        return $this->name;
    }

    public function get_price() {
        return $this->price;
    }

    public function set_name($name) {
        $this->name = $name;
    }

    public function set_price($price) {
        $this->price = $price;
    }

    public function save() {
        $this->validate_product_data($this->name, $this->price);

        if ($this->id) {
            // Update existing product
            $this->db->update_product($this->id, $this->name, $this->price, $this->description);
            $this->log_action("Product {$this->id} updated.");
        } else {
            // Add new product
            $this->id = $this->db->add_product($this->name, $this->price, $this->description);
            $this->log_action("Product {$this->id} added.");
        }

        return true;
    }

    public function delete() {
        if ($this->id) {
            $this->db->delete_product($this->id);
            $this->log_action("Product {$this->id} deleted.");
            $this->id = null;
            $this->name = null;
            $this->price = null;
            $this->description = null;
            return true;
        }
        return false;
    }

    public function update($name, $price, $description = null) {
        $this->validate_product_data($name, $price);

        $this->db->update_product($this->id, $name, $price, $description);
        $this->log_action("Product {$this->id} updated.");

        $this->name = $name;
        $this->price = $price;
        $this->description = $description;

        return true;
    }

    public function get_formatted_product_details() {
        return $this->format_product_details();
    }
}
