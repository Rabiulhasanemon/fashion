<?php
class ModelAccountCustomer extends Model {
	public function addCustomer($data) {
		try {
			$this->event->trigger('pre.customer.add', $data);

			if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
				$customer_group_id = $data['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}

			$this->load->model('account/customer_group');

			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
			
			if (!$customer_group_info) {
				error_log('addCustomer Error: Customer group not found for ID: ' . $customer_group_id);
				return false;
			}

			// Ensure required fields are present
			$missing = array();
			if (empty($data['firstname'])) $missing[] = 'firstname';
			if (empty($data['email'])) $missing[] = 'email';
			if (empty($data['telephone'])) $missing[] = 'telephone';
			if (empty($data['password'])) $missing[] = 'password';
			
			if (!empty($missing)) {
				error_log('addCustomer Error: Missing required fields: ' . implode(', ', $missing));
				error_log('addCustomer Data received: ' . print_r($data, true));
				return false;
			}

			$ip = isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '0.0.0.0';
			$salt = substr(md5(uniqid(rand(), true)), 0, 9);
			$password_hash = sha1($salt . sha1($salt . sha1($data['password'])));

			// Check if customer already exists by email
			$existing_customer = $this->getCustomerByEmail($data['email']);
			if ($existing_customer) {
				error_log('addCustomer: Customer already exists with email: ' . $data['email'] . ' | ID: ' . $existing_customer['customer_id']);
				return (int)$existing_customer['customer_id'];
			}
			
			// Check if there's a record with customer_id = 0 (this can break AUTO_INCREMENT)
			$zero_check = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE customer_id = 0 LIMIT 1");
			if ($zero_check && $zero_check->num_rows > 0) {
				error_log('addCustomer WARNING: Record with customer_id = 0 exists! This may cause insert failures. Attempting to fix...');
				// Try to fix by updating the record to a valid ID
				$max_id_query = $this->db->query("SELECT MAX(customer_id) as max_id FROM " . DB_PREFIX . "customer");
				$new_id = 1;
				if ($max_id_query && $max_id_query->num_rows > 0 && isset($max_id_query->row['max_id'])) {
					$new_id = (int)$max_id_query->row['max_id'] + 1;
				}
				// Update the record with customer_id = 0 to a new ID
				$update_result = $this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_id = '" . (int)$new_id . "' WHERE customer_id = 0 LIMIT 1");
				if ($update_result !== false) {
					error_log('addCustomer: Successfully updated customer_id = 0 to customer_id = ' . $new_id);
				} else {
					error_log('addCustomer ERROR: Failed to update customer_id = 0. Registration may fail!');
				}
				
				// Also check and fix AUTO_INCREMENT if needed
				$status_check = $this->db->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . "customer'");
				if ($status_check && $status_check->num_rows > 0) {
					$auto_inc = isset($status_check->row['Auto_increment']) ? $status_check->row['Auto_increment'] : null;
					if (!$auto_inc || $auto_inc <= 0) {
						error_log('addCustomer: AUTO_INCREMENT is not set correctly. Attempting to fix...');
						$fix_ai_sql = "ALTER TABLE " . DB_PREFIX . "customer MODIFY customer_id INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = " . ($new_id + 1);
						$fix_result = $this->db->query($fix_ai_sql);
						if ($fix_result !== false) {
							error_log('addCustomer: Successfully fixed AUTO_INCREMENT');
						} else {
							error_log('addCustomer WARNING: Could not fix AUTO_INCREMENT automatically');
						}
					}
				}
			}
			
			// Build and execute customer insert query
			// Always auto-approve customers on registration to allow immediate login
			$approved = 1; // Force auto-approval for all new registrations
			$customer_sql = "INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape(isset($data['lastname']) ? $data['lastname'] : "") . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape(isset($data['fax']) ? $data['fax'] : "") . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt) . "', password = '" . $this->db->escape($password_hash) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ip = '" . $this->db->escape($ip) . "', status = '1', approved = '" . (int)$approved . "', date_added = NOW()";
			
			error_log('addCustomer: Attempting customer insert for email: ' . $data['email']);
			error_log('addCustomer: SQL Query: ' . substr($customer_sql, 0, 200) . '...');
			
			// Execute regular INSERT (not IGNORE) to ensure data is inserted
			error_log('addCustomer: Executing INSERT query...');
			$customer_query = $this->db->query($customer_sql);
			
			// Check for database errors
			if ($customer_query === false) {
				$db_error = '';
				if (method_exists($this->db, 'getError')) {
					$db_error = $this->db->getError();
				} elseif (isset($this->db->error)) {
					$db_error = $this->db->error;
				} elseif (isset($this->db->link->error)) {
					$db_error = $this->db->link->error;
				}
				error_log('addCustomer ERROR: INSERT query failed.');
				error_log('addCustomer ERROR: Database error: ' . $db_error);
				error_log('addCustomer ERROR: SQL Query: ' . substr($customer_sql, 0, 500));
				// Check if customer was inserted anyway (race condition)
				$check_query = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($data['email']) . "' LIMIT 1");
				if ($check_query && $check_query->num_rows > 0) {
					$customer_id = (int)$check_query->row['customer_id'];
					error_log('addCustomer: Customer found after failed INSERT (race condition). ID: ' . $customer_id);
				} else {
					error_log('addCustomer: INSERT failed and customer not found in database');
					return false;
				}
			} else {
				error_log('addCustomer: Query executed successfully');
				// CRITICAL: Always query the database directly to get the inserted ID
				// Don't rely solely on getLastId() as it may return 0 if AUTO_INCREMENT has issues
				$check_query = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($data['email']) . "' ORDER BY customer_id DESC LIMIT 1");
				
				if ($check_query && $check_query->num_rows > 0) {
					$customer_id = (int)$check_query->row['customer_id'];
					error_log('addCustomer: Found customer in database. ID: ' . $customer_id);
					
					// Verify getLastId() for debugging
					$getLastId_value = $this->db->getLastId();
					if ($getLastId_value != $customer_id) {
						error_log('addCustomer WARNING: getLastId() returned ' . $getLastId_value . ' but actual ID is ' . $customer_id);
					}
				} else {
					// Fallback to getLastId() if query fails
					$customer_id = $this->db->getLastId();
					error_log('addCustomer: getLastId returned: ' . ($customer_id ? $customer_id : 'FALSE/0'));
					
					if (!$customer_id || $customer_id <= 0) {
						error_log('addCustomer ERROR: Customer not found in database after insert and getLastId returned 0');
						return false;
					}
				}
			}
			
			// Final verification - ensure customer actually exists in database
			if ($customer_id && $customer_id > 0) {
				$verify_query = $this->db->query("SELECT customer_id, email, firstname FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "' LIMIT 1");
				if ($verify_query && $verify_query->num_rows > 0) {
					error_log('addCustomer: Customer verified in database. ID: ' . $customer_id . ' | Email: ' . $verify_query->row['email'] . ' | Name: ' . $verify_query->row['firstname']);
				} else {
					error_log('addCustomer ERROR: Customer ID ' . $customer_id . ' does not exist in database after insert!');
					// Try to find by email as last resort
					$email_find = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($data['email']) . "' LIMIT 1");
					if ($email_find && $email_find->num_rows > 0) {
						$customer_id = $email_find->row['customer_id'];
						error_log('addCustomer: Found customer by email. ID: ' . $customer_id);
					} else {
						error_log('addCustomer CRITICAL: Customer was not inserted into database!');
						return false;
					}
				}
			} else {
				error_log('addCustomer ERROR: Invalid customer ID: ' . $customer_id);
				return false;
			}
			
			error_log('addCustomer: Customer created successfully. ID: ' . $customer_id);

			// Build and execute address insert query
			$address_sql = "INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape(isset($data['lastname']) ? $data['lastname'] : "") . "', company = '" . $this->db->escape(isset($data['company']) ? $data['company'] : "") . "', address_1 = '" . $this->db->escape(isset($data['address_1']) ? $data['address_1'] : "") . "', address_2 = '" . $this->db->escape(isset($data['address_2']) ? $data['address_2'] : "") . "', city = '" . $this->db->escape(isset($data['city']) ? $data['city'] : "") . "', postcode = '" . (isset($data['postcode']) ? $this->db->escape($data['postcode']) : "") . "', country_id = '" . (int)(isset($data['country_id']) ? $data['country_id'] : $this->config->get('config_country_id')) . "', zone_id = '" . (int)(isset($data['zone_id']) ? $data['zone_id'] : 0) . "', region_id = '" . (int)(isset($data['region_id']) ? $data['region_id'] : 0) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['address']) ? serialize($data['custom_field']['address']) : '') . "'";
			
			error_log('addCustomer: Attempting address insert for customer ID: ' . $customer_id);
			$address_query = $this->db->query($address_sql);

			if (!$address_query) {
				error_log('addCustomer Warning: Address INSERT query failed, but continuing');
			}

			$address_id = $this->db->getLastId();
			
			if ($address_id && $address_id > 0) {
				$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
				error_log('addCustomer: Address created and linked. Address ID: ' . $address_id);
			} else {
				error_log('addCustomer Warning: Failed to create address, but customer created. Customer ID: ' . $customer_id);
				// Don't fail registration if address creation fails - customer is more important
			}

			$this->load->language('mail/customer');

			$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

		$message = sprintf($this->language->get('text_welcome'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')) . "\n\n";

		if (!$customer_group_info['approval']) {
			$message .= $this->language->get('text_login') . "\n";
		} else {
			$message .= $this->language->get('text_approval') . "\n";
		}

		$message .= $this->url->link('account/login', '', 'SSL') . "\n\n";
		$message .= $this->language->get('text_services') . "\n\n";
		$message .= $this->language->get('text_thanks') . "\n";
		$message .= html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

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

		// Send to main admin email if new account email is enabled
		if ($this->config->get('config_account_mail')) {
			$message  = $this->language->get('text_signup') . "\n\n";
			$message .= $this->language->get('text_website') . ' ' . html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8') . "\n";
			$message .= $this->language->get('text_firstname') . ' ' . $data['firstname'] . "\n";
			$message .= $this->language->get('text_lastname') . ' ' . $data['lastname'] . "\n";
			$message .= $this->language->get('text_customer_group') . ' ' . $customer_group_info['name'] . "\n";
			$message .= $this->language->get('text_email') . ' '  .  $data['email'] . "\n";
			$message .= $this->language->get('text_telephone') . ' ' . $data['telephone'] . "\n";

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			
			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode($this->language->get('text_new_customer'), ENT_QUOTES, 'UTF-8'));
			$mail->setText($message);
			$mail->send();

			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', $this->config->get('config_mail_alert'));

			foreach ($emails as $email) {
				if (utf8_strlen($email) > 0 && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
			}

			$this->event->trigger('post.customer.add', $customer_id);

			return $customer_id;
		} catch (Exception $e) {
			error_log('addCustomer Exception: ' . $e->getMessage() . ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine());
			// Try to return existing customer ID instead of throwing
			if (isset($data['email']) && !empty($data['email'])) {
				$check_query = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($data['email']) . "' LIMIT 1");
				if ($check_query && $check_query->num_rows > 0) {
					error_log('addCustomer: Returning existing customer ID: ' . $check_query->row['customer_id']);
					return (int)$check_query->row['customer_id'];
				}
			}
			// If no existing customer, return false but don't throw
			error_log('addCustomer: No existing customer found, returning false');
			return false;
		} catch (Error $e) {
			error_log('addCustomer Fatal Error: ' . $e->getMessage() . ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine());
			// Try to return existing customer ID instead of throwing
			if (isset($data['email']) && !empty($data['email'])) {
				$check_query = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($data['email']) . "' LIMIT 1");
				if ($check_query && $check_query->num_rows > 0) {
					error_log('addCustomer: Returning existing customer ID: ' . $check_query->row['customer_id']);
					return (int)$check_query->row['customer_id'];
				}
			}
			// Return false but don't throw
			return false;
		}
	}

	public function addCustomerByTelephone($telephone) {
        $customer_group_id = $this->config->get('config_customer_group_id');
        $this->load->model('account/customer_group');

        $customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
        $password = uniqid();
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') .  "', telephone = '" . $this->db->escape($telephone) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', incomplete = 1, date_added = NOW()");

        $customer_id = $this->db->getLastId();
        return $customer_id;
    }

	public function addCustomerByEmail($email, $name = '') {
        $customer_group_id = $this->config->get('config_customer_group_id');
        $this->load->model('account/customer_group');

        $customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
        $password = uniqid();
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') .  "', firstname = '" . $this->db->escape($name).  "', email = '" . $this->db->escape($email) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', incomplete = 1, date_added = NOW()");

        $customer_id = $this->db->getLastId();
        return $customer_id;
    }

	public function editCustomer($data) {
		$this->event->trigger('pre.customer.edit', $data);

		$customer_id = $this->customer->getId();

        $email = $data['email'];
        if($this->getCustomerByEmail($email)) {
            $email = null;
        }

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname'])
            . "', lastname = '" . $this->db->escape(isset($this->request->post['lastname']) ? $this->request->post['lastname'] : "")
            . ($email ? "', email = '" . $this->db->escape($email) : "")
            . "', fax = '" . $this->db->escape(isset($this->request->post['fax']) ? $this->request->post['fax'] : "")
            . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '')
            . "', incomplete = 0 WHERE customer_id = '" . (int)$customer_id . "'");

		$this->event->trigger('post.customer.edit', $customer_id);
	}

	public function editPassword($email, $password) {
		$this->event->trigger('pre.customer.edit.password');

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		$this->event->trigger('post.customer.edit.password');
	}

	public function editNewsletter($newsletter) {
		$this->event->trigger('pre.customer.edit.newsletter');

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");

		$this->event->trigger('post.customer.edit.newsletter');
	}

	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getCustomerByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

    public function getCustomerByTelephone($telephone) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE telephone = '" . $this->db->escape(trim($telephone)) . "'");

        return $query->row;
    }

	public function getCustomerByToken($token) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = ''");

		return $query->row;
	}

	public function getTotalCustomersByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}

	public function getTotalCustomersByTelephone($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(telephone) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}

	public function getIps($customer_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->rows;
	}

	public function isBanIp($ip) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ban_ip` WHERE ip = '" . $this->db->escape($ip) . "'");

		return $query->num_rows;
	}
	
	public function addLoginAttempt($email) {
		if (empty($email)) {
			return;
		}
		
		$ip = isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '0.0.0.0';
		$email_lower = utf8_strtolower((string)$email);
		
		// Use INSERT ... ON DUPLICATE KEY UPDATE to handle race conditions and duplicates
		try {
			// First, try to get existing record
			$query = $this->db->query("SELECT customer_login_id FROM " . DB_PREFIX . "customer_login WHERE email = '" . $this->db->escape($email_lower) . "' AND ip = '" . $this->db->escape($ip) . "' LIMIT 1");
			
			if ($query->num_rows > 0 && isset($query->row['customer_login_id'])) {
				// Update existing record
				$this->db->query("UPDATE " . DB_PREFIX . "customer_login SET total = (total + 1), date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE customer_login_id = '" . (int)$query->row['customer_login_id'] . "'");
			} else {
				// Try to insert new record - use INSERT IGNORE to prevent duplicate key errors
				$this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "customer_login SET email = '" . $this->db->escape($email_lower) . "', ip = '" . $this->db->escape($ip) . "', total = 1, date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
				
				// If insert failed due to duplicate, try to update instead
				$check_query = $this->db->query("SELECT customer_login_id FROM " . DB_PREFIX . "customer_login WHERE email = '" . $this->db->escape($email_lower) . "' AND ip = '" . $this->db->escape($ip) . "' LIMIT 1");
				if ($check_query->num_rows > 0 && isset($check_query->row['customer_login_id'])) {
					$this->db->query("UPDATE " . DB_PREFIX . "customer_login SET total = (total + 1), date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE customer_login_id = '" . (int)$check_query->row['customer_login_id'] . "'");
				}
			}
		} catch (Exception $e) {
			// Log error but don't break login process
			error_log('addLoginAttempt Error: ' . $e->getMessage() . ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine());
			// Silently fail - don't break the login process
		} catch (Error $e) {
			// Catch PHP 7+ fatal errors
			error_log('addLoginAttempt Fatal Error: ' . $e->getMessage() . ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine());
			// Silently fail - don't break the login process
		}
	}	
	
	public function getLoginAttempts($login_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($login_id)) . "'");

		return $query->row;
	}
	
	public function deleteLoginAttempts($login_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($login_id)) . "'");
	}
}
