<?php

class PcBuilder {
    public function __construct($registry) {
        $this->session = $registry->get('session');
    }


    public function setProduct($component_id, $product_id) {
        $this->session->data["component_" . $component_id] = $product_id;
    }

    public function getProduct($component_id) {
        return isset($this->session->data["component_" . $component_id]) ? $this->session->data["component_" . $component_id] : null;
    }

    public function clearProduct($component_id) {
        unset($this->session->data["component_" . $component_id]);
    }
}