<?php
class ModelCatalogReview extends Model {
	public function addReview($product_id, $data) {
		$this->event->trigger('pre.review.add', $data);

		// Get author name - use provided name or customer name if logged in
		$author = '';
		if (isset($data['name']) && !empty($data['name'])) {
			$author = $data['name'];
		} elseif ($this->customer->isLogged()) {
			$first_name = $this->customer->getFirstName();
			$last_name = $this->customer->getLastName();
			$author = trim($first_name . ' ' . $last_name);
			if (empty($author)) {
				$author = $this->customer->getEmail();
			}
		} else {
			$author = isset($data['name']) ? $data['name'] : 'Guest';
		}

		// Get customer ID - 0 if not logged in
		$customer_id = 0;
		if ($this->customer->isLogged()) {
			$customer_id = (int)$this->customer->getId();
		}

		// Get review text
		$text = isset($data['text']) ? $data['text'] : '';
		
		// Get rating
		$rating = isset($data['rating']) ? (int)$data['rating'] : 0;
		if ($rating < 1 || $rating > 5) {
			$rating = 5; // Default to 5 if invalid
		}

		// Get review status (0 = pending, 1 = approved) - default to 0 for moderation
		$status = isset($data['status']) ? (int)$data['status'] : 0;

		// Insert review - check if status column exists
		$sql = "INSERT INTO " . DB_PREFIX . "review SET ";
		$sql .= "author = '" . $this->db->escape($author) . "', ";
		$sql .= "customer_id = '" . $customer_id . "', ";
		$sql .= "product_id = '" . (int)$product_id . "', ";
		$sql .= "text = '" . $this->db->escape($text) . "', ";
		$sql .= "rating = '" . $rating . "'";
		
		// Check if status column exists in review table
		$check_status = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "review LIKE 'status'");
		if ($check_status && $check_status->num_rows > 0) {
			$sql .= ", status = '" . $status . "'";
		}
		
		$sql .= ", date_added = NOW()";

		// Execute query with error handling
		$result = $this->db->query($sql);
		
		// Check if query failed
		if ($result === false) {
			$error_msg = 'Database query failed';
			if (method_exists($this->db, 'getError')) {
				$error_msg .= ': ' . $this->db->getError();
			}
			error_log('Review INSERT SQL Error: ' . $error_msg);
			error_log('Review INSERT SQL: ' . $sql);
			error_log('Author: ' . $author);
			error_log('Customer ID: ' . $customer_id);
			error_log('Product ID: ' . $product_id);
			error_log('Rating: ' . $rating);
			error_log('Status: ' . $status);
			throw new Exception($error_msg);
		}
		
		$review_id = $this->db->getLastId();
		
		if (!$review_id) {
			error_log('Review INSERT: Query executed but no review_id returned');
			error_log('Review INSERT SQL: ' . $sql);
			// Don't throw error here - some databases might return 0 for insert_id in certain cases
			// Check if review was actually inserted by querying
			$check_query = $this->db->query("SELECT review_id FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "' AND author = '" . $this->db->escape($author) . "' ORDER BY review_id DESC LIMIT 1");
			if ($check_query && $check_query->num_rows > 0) {
				$review_id = $check_query->row['review_id'];
			} else {
				throw new Exception('Failed to insert review - no review_id returned and review not found in database');
			}
		}

		// Send email notification if enabled
		if ($this->config->get('config_review_mail')) {
			try {
				$this->load->language('mail/review');
				$this->load->model('catalog/product');
				
				$product_info = $this->model_catalog_product->getProduct($product_id);
				
				if ($product_info) {
					$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

					$message  = $this->language->get('text_waiting') . "\n";
					$message .= sprintf($this->language->get('text_product'), html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8')) . "\n";
					$message .= sprintf($this->language->get('text_reviewer'), html_entity_decode($author, ENT_QUOTES, 'UTF-8')) . "\n";
					$message .= sprintf($this->language->get('text_rating'), $rating) . "\n";
					$message .= $this->language->get('text_review') . "\n";
					$message .= html_entity_decode($text, ENT_QUOTES, 'UTF-8') . "\n\n";

					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

					$config_email = $this->config->get('config_email');
					if ($config_email) {
						$mail->setTo($config_email);
						$mail->setFrom($config_email);
						$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
						$mail->setSubject($subject);
						$mail->setText($message);
						$mail->send();

						// Send to additional alert emails
						$alert_emails = $this->config->get('config_mail_alert');
						if ($alert_emails) {
							$emails = explode(',', $alert_emails);
							foreach ($emails as $email) {
								$email = trim($email);
								if ($email && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
									$mail->setTo($email);
									$mail->send();
								}
							}
						}
					}
				}
			} catch (Exception $e) {
				// Log email error but don't fail the review submission
				error_log('Review email error: ' . $e->getMessage());
			}
		}

		$this->event->trigger('post.review.add', $review_id);
	}

	public function getReviewsByProductId($product_id, $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		$query = $this->db->query("SELECT r.review_id, r.author, r.rating, r.text, p.product_id, pd.name, p.price, p.image, r.date_added FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalReviewsByProductId($product_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}
}