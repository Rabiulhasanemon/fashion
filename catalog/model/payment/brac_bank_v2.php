<?php
class ModelPaymentBracBankV2 extends Model {
    private $emi_banks = array("bank_asia", "brac_bank", 'dbbl', 'dbl', 'ebl', 'lbf', 'mtb', 'sebl', 'scb');

    public function getEMIBanks() {
        return $this->emi_banks;
    }

    public function getMaxEmiTenure() {
        return 12;
    }

    public function getMethod($address, $total, $isEMICart) {
        $this->load->language('payment/brac_bank_v2');

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('brac_bank_v2_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

        if (!$this->config->get('brac_bank_v2_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }

        if(!$status) return array();

        if (!$isEMICart) {
            return array(
                'code'       => 'brac_bank_v2',
                'title'      => $this->language->get('text_title'),
                'terms'      => '',
                'sort_order' => $this->config->get('brac_bank_v2_sort_order')
            );
        }

        $method_data = array("methods" => array());
        $methods = array(
            'bank_asia' => $this->language->get('text_bank_asia'),
            'brac_bank' => $this->language->get('text_brac_bank'),
            'dbbl' => $this->language->get('text_dbbl'),
            'dbl' => $this->language->get('text_dbl'),
            'ebl' => $this->language->get('text_ebl'),
            'lbf' => $this->language->get('text_lbf'),
            'mtb' => $this->language->get('text_mtb'),
            'sebl' => $this->language->get('text_sebl'),
            'scb' => $this->language->get('text_scb')
        );

        $sort_order = (int) $this->config->get('brac_bank_v2_sort_order');
        foreach ($methods as $key => $value) {
            $method_data["methods"][] = array(
                'code'       => 'brac_bank_v2.' . $key,
                'bank'       => $key,
                'title'      => $value,
                'terms'      => '',
                'sort_order' =>  $sort_order
            );
            $sort_order += 1;
        }

        return $method_data;

    }
}