<?php
class ModelCatalogEnquiry extends Model {
	public function addEnquiry($data) {
		$this->event->trigger('pre.admin.enquiry.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "enquiry SET name = '" . $this->db->escape($data['name']) . "', phone = '" . $this->db->escape($data['phone']) . "', email = '" . $this->db->escape($data['email']) . "',subject = '" . $this->db->escape($data['subject']) . "', enquiry = '" . $this->db->escape(strip_tags($data['enquiry'])) . "', reply = '" . $this->db->escape(strip_tags($data['reply'])) . "', date_added = NOW()");

		$enquiry_id = $this->db->getLastId();
		$this->event->trigger('post.admin.enquiry.add', $enquiry_id);
		return $enquiry_id;
	}

	public function editEnquiry($enquiry_id, $data) {
		$this->event->trigger('pre.admin.enquiry.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "enquiry SET name = '" . $this->db->escape($data['name']) . "', phone = '" . $this->db->escape($data['phone']) . "', email = '" . $this->db->escape($data['email']) . "',subject = '" . $this->db->escape($data['subject']) . "', enquiry = '" . $this->db->escape(strip_tags($data['enquiry'])) . "', reply = '" . $this->db->escape(strip_tags($data['reply'])) . "', is_replied = 1, date_modified = NOW() WHERE enquiry_id = '" . (int)$enquiry_id . "'");

		$this->cache->delete('product');


        $this->load->language('mail/enquiry');

        $subject = sprintf($this->language->get('text_subject'), $data['name'], $this->config->get('config_name'));
        $message = html_entity_decode($data['reply'], ENT_QUOTES, 'UTF-8');

        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $mail->setTo($data['email']);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
        $mail->setSubject($subject);
        $mail->setText($message);
        $mail->send();

		$this->event->trigger('post.admin.enquiry.edit', $enquiry_id);
	}

	public function deleteEnquiry($enquiry_id) {
		$this->event->trigger('pre.admin.enquiry.delete', $enquiry_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "enquiry WHERE enquiry_id = '" . (int)$enquiry_id . "'");

		$this->cache->delete('product');

		$this->event->trigger('post.admin.enquiry.delete', $enquiry_id);
	}

	public function getEnquiry($enquiry_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "enquiry r WHERE r.enquiry_id = '" . (int)$enquiry_id . "'");
		return $query->row;
	}

    public function getEnquiries($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "enquiry r";

		$implode = array();
        if (!empty($data['filter_name'])) {
            $implode[] = " r.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_date_start'])) {
            $implode[] = "DATE(r.date_added) >= DATE('" . $this->db->escape($data['filter_date_start']) . "')";
        }

        if (!empty($data['filter_date_end'])) {
            $implode[] = "DATE(r.date_added) <= DATE('" . $this->db->escape($data['filter_date_end']) . "')";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }


		$sort_data = array(
			'pd.name',
			'r.name',
			'r.status',
			'r.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY r.date_added";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalEnquiries($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "enquiry r";

        $implode = array();

        if (!empty($data['filter_date_start'])) {
            $implode[] = "DATE(r.date_added) >= DATE('" . $this->db->escape($data['filter_date_start']) . "')";
        }

        if (!empty($data['filter_date_end'])) {
            $implode[] = "DATE(r.date_added) <= DATE('" . $this->db->escape($data['filter_date_end']) . "')";
        }

        if (!empty($data['filter_name'])) {
            $implode[] = " r.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }


        $query = $this->db->query($sql);

		return $query->row['total'];
	}
}