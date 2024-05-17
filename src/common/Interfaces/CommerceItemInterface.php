<?php

namespace LightCommerce\Common\Interfaces;

interface CommerceItemInterface {
    public function get_id();
    public function get_name();
    public function get_price();

    public function set_name($name);
    public function set_price($price);
}
