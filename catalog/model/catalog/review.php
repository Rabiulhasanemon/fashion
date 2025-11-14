<?php
use ReflectionClass;

class ModelCatalogReview extends Model {
	public function addReview($product_id, $data) {
		// Validate product_id
		if (empty($product_id) || !is_numeric($product_id)) {
			error_log('Review addReview: Invalid product_id - ' . $product_id);
			throw new Exception('Invalid product ID');
		}
		
		// Validate required data
		if (empty($data) || !is_array($data)) {
			error_log('Review addReview: Invalid or empty data array');
			throw new Exception('Invalid review data');
		}
		
		$this->event->trigger('pre.review.add', $data);

		// Get author name - use provided name or customer name if logged in
		$author = '';
		if (isset($data['name']) && !empty(trim($data['name']))) {
			$author = trim($data['name']);
		} elseif (isset($this->customer) && method_exists($this->customer, 'isLogged') && $this->customer->isLogged()) {
			$first_name = $this->customer->getFirstName();
			$last_name = $this->customer->getLastName();
			$author = trim($first_name . ' ' . $last_name);
			if (empty($author)) {
				$author = $this->customer->getEmail();
			}
		} else {
			$author = isset($data['name']) && !empty(trim($data['name'])) ? trim($data['name']) : 'Guest';
		}
		
		if (empty($author)) {
			error_log('Review addReview: Empty author name, using Guest');
			$author = 'Guest'; // Fallback to Guest
		}

		// Get customer ID - 0 if not logged in
		$customer_id = 0;
		if (isset($this->customer) && method_exists($this->customer, 'isLogged') && $this->customer->isLogged()) {
			$customer_id = (int)$this->customer->getId();
		}

		// Get review text
		$text = isset($data['text']) ? trim($data['text']) : '';
		if (empty($text)) {
			error_log('Review addReview: Empty review text');
			throw new Exception('Review text is required');
		}
		
		// Get rating
		$rating = isset($data['rating']) ? (int)$data['rating'] : 0;
		if ($rating < 1 || $rating > 5) {
			error_log('Review addReview: Invalid rating - ' . $rating);
			throw new Exception('Rating must be between 1 and 5');
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
		// Use output buffering to catch any trigger_error output
		ob_start();
		
		$result = $this->db->query($sql);
		
		$output = ob_get_clean();
		
		// Check if query failed
		if ($result === false) {
			$error_msg = 'Database query failed';
			$error_no = 0;
			
			// Try to get database error if available (for MySQLi)
			if (is_object($this->db)) {
				try {
					$reflection = new ReflectionClass($this->db);
					if ($reflection->hasProperty('link')) {
						$link_property = $reflection->getProperty('link');
						$link_property->setAccessible(true);
						$link = $link_property->getValue($this->db);
						
						if ($link instanceof \mysqli && $link->error) {
							$error_msg .= ': ' . $link->error;
							$error_no = $link->errno;
						}
					}
				} catch (Exception $reflection_error) {
					// Reflection failed, continue without it
					error_log('Reflection error: ' . $reflection_error->getMessage());
				}
			}
			
			// Log captured output if any
			if (!empty($output)) {
				error_log('Review INSERT captured output: ' . $output);
			}
			
			// If it's a duplicate key error (1062), try to fix AUTO_INCREMENT and retry
			if ($error_no == 1062 || strpos(strtolower($error_msg), 'duplicate') !== false || strpos(strtolower($error_msg), 'primary') !== false) {
				error_log('=== DUPLICATE KEY ERROR DETECTED - FIXING AUTO_INCREMENT ===');
				
				// Fix AUTO_INCREMENT only when we get a duplicate key error
				// Step 1: Delete any review with review_id = 0 (shouldn't exist, but just in case)
				$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE review_id = 0");
				
				// Step 2: Get current max review_id
				$max_check = $this->db->query("SELECT MAX(review_id) as max_id FROM " . DB_PREFIX . "review");
				$max_id = 0;
				if ($max_check && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
					$max_id = (int)$max_check->row['max_id'];
				}
				$next_id = max($max_id + 1, 1);
				
				// Step 3: Set AUTO_INCREMENT to next available value
				$this->db->query("ALTER TABLE " . DB_PREFIX . "review AUTO_INCREMENT = " . $next_id);
				
				error_log('AUTO_INCREMENT fixed to: ' . $next_id);
				
				// Retry the insert
				ob_start();
				$result = $this->db->query($sql);
				$output = ob_get_clean();
				
				if ($result === false) {
					// Still failed after fix, log and throw
					error_log('Review INSERT still failed after AUTO_INCREMENT fix');
					error_log('=== REVIEW INSERT SQL ERROR ===');
					error_log('Error: ' . $error_msg);
					error_log('SQL: ' . $sql);
					error_log('Author: ' . $author);
					error_log('Customer ID: ' . $customer_id);
					error_log('Product ID: ' . $product_id);
					error_log('Rating: ' . $rating);
					error_log('Status: ' . $status);
					error_log('Text length: ' . strlen($text));
					error_log('Text preview: ' . substr($text, 0, 100));
					error_log('===============================');
					
					throw new Exception($error_msg);
				} else {
					error_log('Review INSERT succeeded after AUTO_INCREMENT fix');
				}
			} else {
				// Other error, log and throw
				error_log('=== REVIEW INSERT SQL ERROR ===');
				error_log('Error: ' . $error_msg);
				error_log('SQL: ' . $sql);
				error_log('Author: ' . $author);
				error_log('Customer ID: ' . $customer_id);
				error_log('Product ID: ' . $product_id);
				error_log('Rating: ' . $rating);
				error_log('Status: ' . $status);
				error_log('Text length: ' . strlen($text));
				error_log('Text preview: ' . substr($text, 0, 100));
				error_log('===============================');
				
				throw new Exception($error_msg);
			}
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