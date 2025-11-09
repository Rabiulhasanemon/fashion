<?php
class ModelExtensionShipping extends Model {
	public function getShipping($shipping_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "shipping WHERE shipping_id = '" . (int)$shipping_id . "'");
		
		if ($query->row) {
			return unserialize($query->row['setting']);
		} else {
			return array();	
		}
	}

    public function getShippingsByCode($code) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "shipping` WHERE `code` = '" . $this->db->escape($code) . "' ORDER BY `name`");

        $shippings = array();
        foreach ($query->rows as $result) {
            $shippings[] = array(
                'shipping_id' => $result['shipping_id'],
                'name' => $result['name'],
                'code' => $result['code'],
                'setting' => unserialize($result['setting'])
            );
        }
        return $shippings;
    }
}