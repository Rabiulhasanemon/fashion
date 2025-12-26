<?php
class ModelInformationNewsletter extends Model {
	public function addNewsletter($email) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "newsletter SET  email = '" . $this->db->escape($email) . "', date_added = NOW()");
	}

    public function getTotalNewsletterSubscriberByEmail($email) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "newsletter WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row['total'];
    }
}
