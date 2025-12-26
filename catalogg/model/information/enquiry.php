<?php
class ModelInformationEnquiry extends Model {
	public function addEnquiry($data) {
       $this->db->query("INSERT INTO " . DB_PREFIX . "enquiry SET name = '" . $this->db->escape($data['name']) . "', phone = '" . $this->db->escape($data['phone']) . "', email = '" . $this->db->escape($data['email']) . "', subject = '" . $this->db->escape($data['subject']) . "', customer_id = '" . (int)$this->customer->getId() . "', enquiry = '" . $this->db->escape($data['enquiry']) . "', date_added = NOW()");
        $enquiry_id = $this->db->getLastId();
        $data['enquiry_id'] = $enquiry_id;
        $this->event->trigger('post.enquiry.add', $data);
	}
}