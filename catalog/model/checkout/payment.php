<?php
class ModelCheckoutPayment extends Model {

    public function addPayment($data) {
        $this->db->query("INSERT INTO ". DB_PREFIX . "payment SET order_id = '" . (int) $data['order_id'] . "', status = '" . $this->db->escape($data['status']) . "', gateway_code = '" . $this->db->escape($data['gateway_code']) . "', gateway_title = '" . $this->db->escape($data['gateway_title']) . "', transaction_id = '" . $this->db->escape($data['transaction_id']) . "', total = '" . (float) $data['total'] . "', tracking_no = '" . $this->db->escape($data['tracking_no']) . "', payer_info = '" . $this->db->escape($data['payer_info']) . "', comment = '" . $this->db->escape($data['comment']) . "', date_added = now(), date_modified = now()");
        return $this->db->getLastId();
    }

    public function editPayment($data) {
        $this->db->query("UPDATE ". DB_PREFIX . "payment SET status = '" . $this->db->escape($data['status']) . "', total = '" . (float) $data['total'] . "', transaction_id = '" . $this->db->escape($data['transaction_id']) . "', tracking_no = '" . $this->db->escape($data['tracking_no']) . "', payer_info = '" . $this->db->escape($data['payer_info']) . "', comment = '" . $this->db->escape($data['comment']) . "', date_modified = now() WHERE payment_id = '" . (int) $data['payment_id'] . "'");
    }

    public function getPayment($payment_id) {
        $query = $this->db->query("SELECT * FROM ". DB_PREFIX . "payment WHERE payment_id = '" . (int) $payment_id ."'");
        return $query->row;
    }

    public function getPaymentByRefId($transaction_id) {
        $query = $this->db->query("SELECT * FROM ". DB_PREFIX . "payment WHERE transaction_id = '" . $this->db->escape($transaction_id) ."'");
        return $query->row;
    }

    public function getAmountPaid($order_id) {
        $query = $this->db->query("SELECT SUM(total) as paid FROM " . DB_PREFIX . "payment p  WHERE ( p.status = 'Approved' OR  p.status = 'Refunded') and p.order_id = '" . (int)$order_id . "'");
        return $query->row['paid'];
    }
}