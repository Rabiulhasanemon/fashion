<?php

class ModelPaymentSSLCommerce extends Model
{
    public function getMethod($address, $total, $isEMICart)
    {
        $this->load->language('payment/ssl_commerce');

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('ssl_commerce_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

        if ($this->config->get('ssl_commerce_total') > $total) {
            $status = false;
        } elseif (!$this->config->get('ssl_commerce_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }

        if (!$status || (!$this->config->get('ssl_commerce_emi') && $isEMICart)) {
            return array();
        }

        if (!$isEMICart) {
            return array(
                'name' => 'ssl_commerce',
                'code' => 'ssl_commerce',
                'terms' => '',
                'title' => $isEMICart ? $this->language->get('text_emi_title') : $this->language->get('text_title'),
                'sort_order' => $this->config->get('ssl_commerce_sort_order')
            );
        }


        $banks = array("Bank Asia Limited", "Brac Bank Limited", "Dhaka Bank Limited", "Dutch Bangla Bank", "Eastern Bank Limited", "Jamuna Bank Limited", "Lankabangla Finance", "Mutual Trust Bank Limited",
            "NCC Bank Limited", "Southeast Bank Limited", "Standard Bank Limited", "Standard Chartered Bank Limited", "NRB Bank Limited",
            "Meghna Bank Limited", "SBAC Bank Limited", "Midland Bank Limited");

        $method_data['methods'] = array();

        foreach ($banks as $bank) {
            $name = strtolower(preg_replace('/(.)(?=[A-Z])/', '$1_', $bank));
            $method_data["methods"][] = array(
                'title' => $bank,
                'name' => $name,
                'code' => 'ssl_commerce',
                'terms' => '',
                'sort_order' => $this->config->get('ssl_commerce_sort_order')
            );
        }
        return $method_data;
    }
}

?>
