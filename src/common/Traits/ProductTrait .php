<?php

namespace LightCommerce\Common\Traits;

trait ProductTrait {
    private function log_action($message) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log($message);
        }
    }

    private function validate_product_data($name, $price) {
        if (empty($name)) {
            throw new \Exception("Product name cannot be empty");
        }
        if ($price <= 0) {
            throw new \Exception("Price must be greater than zero");
        }
        return true;
    }

    private function format_product_details() {
        return sprintf(
            "Product ID: %d\nName: %s\nPrice: %.2f\nDescription: %s",
            $this->id,
            $this->name,
            $this->price,
            $this->description
        );
    }
}
