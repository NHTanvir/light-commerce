<?php

namespace LightCommerce\Common\Traits;

trait OrderTrait {

    private function log_action($message) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log($message);
        }
    }

    private function validate_order_data($customer_name, $total_amount) {
        if (empty($customer_name)) {
            throw new \Exception("Customer name cannot be empty");
        }
        return true;
    }

    private function format_order_details() {
        return sprintf(
            "Order ID: %d\nCustomer: %s\nTotal Amount: %.2f",
            $this->id,
            $this->customer_name,
            $this->total_amount
        );
    }
}
