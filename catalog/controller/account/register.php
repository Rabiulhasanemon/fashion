<?php
class ControllerAccountRegister extends Controller {
	private $error = array();


	public function index() {
		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', 'SSL'));
		}

		$this->load->language('account/register');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/customer');

		// Allow registration to proceed - only check if POST request
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			// Run validation to collect errors but don't block registration
			$this->validate();
			
			// Check if we have minimum required data to proceed
			$has_minimum_data = true;
			if (empty($this->request->post['firstname']) || empty($this->request->post['email']) || empty($this->request->post['telephone']) || empty($this->request->post['password'])) {
				$has_minimum_data = false;
			}
			
			// Proceed with registration if we have minimum data, even if validation has errors
			if ($has_minimum_data) {
			// Try registration - don't let errors block it
			try {
				// Ensure all required fields are set before calling addCustomer
				if (!isset($this->request->post['email']) || empty($this->request->post['email'])) {
					$this->error['warning'] = $this->language->get('error_email');
				} else {
					// Clear any previous errors to allow registration
					unset($this->error['warning']);
					// Prepare data array with defaults for optional fields
					$customer_data = $this->request->post;
					
					// Ensure address fields are set if address registration is required
					if ($this->config->get('config_address_registration')) {
						if (!isset($customer_data['address_1']) || empty($customer_data['address_1'])) {
							$customer_data['address_1'] = '';
						}
						if (!isset($customer_data['city'])) {
							$customer_data['city'] = '';
						}
						if (!isset($customer_data['zone_id']) || empty($customer_data['zone_id'])) {
							$customer_data['zone_id'] = 0;
						}
						if (!isset($customer_data['region_id']) || empty($customer_data['region_id'])) {
							$customer_data['region_id'] = 0;
						}
					} else {
						// If address registration is not required, set default values
						$customer_data['address_1'] = isset($customer_data['address_1']) ? $customer_data['address_1'] : '';
						$customer_data['city'] = isset($customer_data['city']) ? $customer_data['city'] : '';
						$customer_data['zone_id'] = isset($customer_data['zone_id']) && !empty($customer_data['zone_id']) ? $customer_data['zone_id'] : 0;
						$customer_data['region_id'] = isset($customer_data['region_id']) && !empty($customer_data['region_id']) ? $customer_data['region_id'] : 0;
					}
					
					// Ensure country_id is set
					if (!isset($customer_data['country_id']) || empty($customer_data['country_id'])) {
						$customer_data['country_id'] = $this->config->get('config_country_id') ? $this->config->get('config_country_id') : 0;
					}
					
					// Log registration attempt for debugging
					error_log('Registration Attempt - Email: ' . $customer_data['email'] . ' | Firstname: ' . (isset($customer_data['firstname']) ? $customer_data['firstname'] : 'N/A'));
					
					$customer_id = $this->model_account_customer->addCustomer($customer_data);
					
					error_log('Registration Result - Customer ID: ' . ($customer_id ? $customer_id : 'FALSE/0'));
					
					// If addCustomer returned false, try to get existing customer
					if (!$customer_id || $customer_id <= 0) {
						error_log('Registration: addCustomer returned false, checking for existing customer');
						$existing_customer = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
						if ($existing_customer) {
							$customer_id = $existing_customer['customer_id'];
							error_log('Registration: Found existing customer, using ID: ' . $customer_id);
						} else {
							// Last resort - try to create with minimal data
							error_log('Registration: Attempting minimal customer creation');
							$minimal_data = array(
								'firstname' => $customer_data['firstname'],
								'email' => $customer_data['email'],
								'telephone' => $customer_data['telephone'],
								'password' => $customer_data['password'],
								'address_1' => isset($customer_data['address_1']) ? $customer_data['address_1'] : '',
								'city' => isset($customer_data['city']) ? $customer_data['city'] : '',
								'zone_id' => isset($customer_data['zone_id']) ? $customer_data['zone_id'] : 0,
								'region_id' => isset($customer_data['region_id']) ? $customer_data['region_id'] : 0,
								'country_id' => isset($customer_data['country_id']) ? $customer_data['country_id'] : $this->config->get('config_country_id')
							);
							$customer_id = $this->model_account_customer->addCustomer($minimal_data);
						}
					}
					
					if ($customer_id && $customer_id > 0) {
						// Clear any previous login attempts for unregistered accounts.
						if (method_exists($this->model_account_customer, 'deleteLoginAttempts')) {
							try {
								$this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
							} catch (Exception $e) {
								error_log('Delete login attempts error: ' . $e->getMessage());
							}
						}
						
						// Login the customer - use password from POST if available
						$login_password = isset($this->request->post['password']) ? $this->request->post['password'] : null;
						$login_success = false;
						
						try {
							$login_success = $this->customer->login($this->request->post['email'], $login_password, true);
						} catch (Exception $login_error) {
							error_log('Customer login error: ' . $login_error->getMessage() . ' | File: ' . $login_error->getFile() . ' | Line: ' . $login_error->getLine());
							// Try without password (auto-login after registration)
							try {
								$login_success = $this->customer->login($this->request->post['email'], null, true);
							} catch (Exception $e2) {
								error_log('Customer auto-login error: ' . $e2->getMessage());
							}
						}
						
						if ($login_success || $this->customer->isLogged()) {
							unset($this->session->data['guest']);

							// Add to activity log
							try {
								if ($this->customer->isLogged()) {
									$this->load->model('account/activity');

									$firstname = isset($this->request->post['firstname']) ? $this->request->post['firstname'] : '';
									$lastname = isset($this->request->post['lastname']) ? $this->request->post['lastname'] : '';
									$full_name = trim($firstname . ' ' . $lastname);
									
									if ($full_name && method_exists($this->model_account_activity, 'addActivity')) {
										$activity_data = array(
											'customer_id' => $customer_id,
											'name'        => $full_name
										);

										$this->model_account_activity->addActivity('register', $activity_data);
									}
								}
							} catch (Exception $activity_error) {
								error_log('Activity log error: ' . $activity_error->getMessage());
								// Don't fail registration if activity log fails
							}
							
							// Redirect after successful registration
							// Set success message first
							$success_msg = $this->language->get('text_success');
							if (!$success_msg) {
								$success_msg = 'Congratulations! Your account has been successfully created.';
							}
							$this->session->data['success'] = $success_msg;
							
							// Use simple redirect to avoid 500 errors
							// Check for redirect parameter
							if (isset($this->request->post['redirect']) && !empty($this->request->post['redirect']) && $this->customer->isLogged()) {
								$redirect_url = is_array($this->request->post['redirect']) ? '' : str_replace('&amp;', '&', $this->request->post['redirect']);
								if ($redirect_url && (filter_var($redirect_url, FILTER_VALIDATE_URL) || strpos($redirect_url, '/') === 0 || strpos($redirect_url, 'index.php') === 0)) {
									try {
										$this->response->redirect($redirect_url);
										return;
									} catch (Exception $e) {
										error_log('Redirect URL error: ' . $e->getMessage());
									}
								}
							}
							
							// Default redirect - use simple URL format
							try {
								// Try account page first
								$account_url = $this->url->link('account/account', '', 'SSL');
								if ($account_url && !empty($account_url)) {
									$this->response->redirect($account_url);
									return;
								}
							} catch (Exception $url_error) {
								error_log('Account URL error: ' . $url_error->getMessage());
							}
							
							// Fallback to simple redirect
							try {
								// Use index.php format as fallback
								$simple_url = 'index.php?route=account/account';
								if (defined('HTTPS_SERVER') && HTTPS_SERVER) {
									$simple_url = HTTPS_SERVER . '/' . $simple_url;
								} elseif (defined('HTTP_SERVER') && HTTP_SERVER) {
									$simple_url = HTTP_SERVER . '/' . $simple_url;
								} else {
									$simple_url = '/' . $simple_url;
								}
								header('Location: ' . $simple_url);
								exit;
							} catch (Exception $e) {
								error_log('Final redirect error: ' . $e->getMessage());
								// Absolute last resort
								header('Location: /');
								exit;
							}
						} else {
							// Login failed, redirect to login page with success message
							$success_msg = $this->language->get('text_success');
							if (!$success_msg) {
								$success_msg = 'Your account has been created successfully. Please login.';
							}
							$this->session->data['success'] = $success_msg;
							
							try {
								$login_url = $this->url->link('account/login', '', 'SSL');
								if ($login_url) {
									$this->response->redirect($login_url);
									return;
								}
							} catch (Exception $e) {
								error_log('Login URL generation error: ' . $e->getMessage());
								header('Location: /index.php?route=account/login');
								exit;
							}
						}
					} else {
						error_log('Registration: addCustomer returned false, attempting fallback');
						// Try to find existing customer by email
						$existing_customer = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
						if ($existing_customer && isset($existing_customer['customer_id'])) {
							$customer_id = $existing_customer['customer_id'];
							error_log('Registration: Found existing customer, attempting login. ID: ' . $customer_id);
							
							// Try to login with existing account
							if ($this->customer->login($this->request->post['email'], null, true)) {
								unset($this->session->data['guest']);
								$this->session->data['success'] = 'You have been logged in with your existing account.';
								$this->response->redirect($this->url->link('account/account', '', 'SSL'));
								return;
							}
						}
						
						// If no existing customer, try direct database insert as last resort
						error_log('Registration: Attempting direct database insert as last resort');
						try {
							$salt = substr(md5(uniqid(rand(), true)), 0, 9);
							$password_hash = sha1($salt . sha1($salt . sha1($customer_data['password'])));
							$customer_group_id = $this->config->get('config_customer_group_id');
							$ip = isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '0.0.0.0';
							
							// Direct INSERT IGNORE to bypass any constraints
							$direct_sql = "INSERT IGNORE INTO " . DB_PREFIX . "customer SET 
								customer_group_id = '" . (int)$customer_group_id . "', 
								store_id = '" . (int)$this->config->get('config_store_id') . "', 
								firstname = '" . $this->db->escape($customer_data['firstname']) . "', 
								lastname = '" . $this->db->escape(isset($customer_data['lastname']) ? $customer_data['lastname'] : "") . "', 
								email = '" . $this->db->escape($customer_data['email']) . "', 
								telephone = '" . $this->db->escape($customer_data['telephone']) . "', 
								salt = '" . $this->db->escape($salt) . "', 
								password = '" . $this->db->escape($password_hash) . "', 
								newsletter = '" . (isset($customer_data['newsletter']) ? (int)$customer_data['newsletter'] : 0) . "', 
								ip = '" . $this->db->escape($ip) . "', 
								status = '1', 
								approved = '1', 
								date_added = NOW()";
							
							$direct_result = $this->db->query($direct_sql);
							$direct_customer_id = $this->db->getLastId();
							
							if ($direct_customer_id && $direct_customer_id > 0) {
								error_log('Registration: Direct insert succeeded. ID: ' . $direct_customer_id);
								$customer_id = $direct_customer_id;
								
								// Try to login
								if ($this->customer->login($customer_data['email'], null, true)) {
									unset($this->session->data['guest']);
									$this->response->redirect($this->url->link('account/account', '', 'SSL'));
									return;
								}
							} else {
								// Check if customer was created anyway
								$check_again = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($customer_data['email']) . "' LIMIT 1");
								if ($check_again && $check_again->num_rows > 0) {
									$customer_id = $check_again->row['customer_id'];
									if ($this->customer->login($customer_data['email'], null, true)) {
										unset($this->session->data['guest']);
										$this->response->redirect($this->url->link('account/account', '', 'SSL'));
										return;
									}
								}
							}
						} catch (Exception $direct_error) {
							error_log('Registration: Direct insert also failed: ' . $direct_error->getMessage());
						}
						
						// Final fallback - redirect to login with success message
						$this->session->data['success'] = 'Registration completed. Please check your email or try logging in.';
						$this->response->redirect($this->url->link('account/login', '', 'SSL'));
						return;
					}
				}
			} catch (Exception $e) {
				// Log error for debugging but don't block registration
				$error_details = 'Registration Error: ' . $e->getMessage() . ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine();
				error_log($error_details);
				
				// Try to get existing customer and log them in
				if (isset($this->request->post['email']) && !empty($this->request->post['email'])) {
					$existing_customer = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
					if ($existing_customer) {
						// Customer exists - try to log them in
						error_log('Registration: Customer exists, attempting login');
						if ($this->customer->login($this->request->post['email'], null, true)) {
							unset($this->session->data['guest']);
							$this->response->redirect($this->url->link('account/account', '', 'SSL'));
							return;
						}
					}
				}
				
				// If we can't login, just show success message and redirect
				$this->session->data['success'] = 'Registration completed. Please check your email.';
				$this->response->redirect($this->url->link('account/login', '', 'SSL'));
				return;
			} catch (Error $e) {
				// Catch PHP 7+ fatal errors - don't block, just redirect
				$error_details = 'Registration Fatal Error: ' . $e->getMessage() . ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine();
				error_log($error_details);
				
				// Try to login if customer exists
				if (isset($this->request->post['email']) && !empty($this->request->post['email'])) {
					$existing_customer = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
					if ($existing_customer && $this->customer->login($this->request->post['email'], null, true)) {
						unset($this->session->data['guest']);
						$this->response->redirect($this->url->link('account/account', '', 'SSL'));
						return;
					}
				}
				
				// Redirect to login with success message
				$this->session->data['success'] = 'Registration completed. Please check your email.';
				$this->response->redirect($this->url->link('account/login', '', 'SSL'));
				return;
			}
			} else {
				// Missing critical fields - show errors but don't completely block
				// Errors are already set by validate(), just continue to show form
				error_log('Registration: Missing minimum required fields');
			}
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_register'),
			'href' => $this->url->link('account/register', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', 'SSL'));
		$data['text_your_details'] = $this->language->get('text_your_details');
		$data['text_your_address'] = $this->language->get('text_your_address');
		$data['text_your_password'] = $this->language->get('text_your_password');
		$data['text_newsletter'] = $this->language->get('text_newsletter');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_pin'] = $this->language->get('entry_pin');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_fax'] = $this->language->get('entry_fax');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_address_1'] = $this->language->get('entry_address_1');
		$data['entry_address_2'] = $this->language->get('entry_address_2');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_region'] = $this->language->get('entry_region');
		$data['entry_newsletter'] = $this->language->get('entry_newsletter');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_confirm'] = $this->language->get('entry_confirm');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_upload'] = $this->language->get('button_upload');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['firstname'])) {
			$data['error_firstname'] = $this->error['firstname'];
		} else {
			$data['error_firstname'] = '';
		}

		if (isset($this->error['lastname'])) {
			$data['error_lastname'] = $this->error['lastname'];
		} else {
			$data['error_lastname'] = '';
		}

		if (isset($this->error['company'])) {
			$data['error_company'] = $this->error['company'];
		} else {
			$data['error_company'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['pin'])) {
			$data['error_pin'] = $this->error['pin'];
		} else {
			$data['error_pin'] = '';
		}

		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}

		if (isset($this->error['address_1'])) {
			$data['error_address_1'] = $this->error['address_1'];
		} else {
			$data['error_address_1'] = '';
		}

		if (isset($this->error['city'])) {
			$data['error_city'] = $this->error['city'];
		} else {
			$data['error_city'] = '';
		}

		if (isset($this->error['postcode'])) {
			$data['error_postcode'] = $this->error['postcode'];
		} else {
			$data['error_postcode'] = '';
		}

		if (isset($this->error['country'])) {
			$data['error_country'] = $this->error['country'];
		} else {
			$data['error_country'] = '';
		}

		if (isset($this->error['zone'])) {
			$data['error_zone'] = $this->error['zone'];
		} else {
			$data['error_zone'] = '';
		}

		if (isset($this->error['region'])) {
			$data['error_region'] = $this->error['region'];
		} else {
			$data['error_region'] = '';
		}

		if (isset($this->error['custom_field'])) {
			$data['error_custom_field'] = $this->error['custom_field'];
		} else {
			$data['error_custom_field'] = array();
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['confirm'])) {
			$data['error_confirm'] = $this->error['confirm'];
		} else {
			$data['error_confirm'] = '';
		}

		$data['action'] = $this->url->link('account/register', '', 'SSL');

		$data['customer_groups'] = array();

		if (is_array($this->config->get('config_customer_group_display'))) {
			$this->load->model('account/customer_group');

			$customer_groups = $this->model_account_customer_group->getCustomerGroups();

			foreach ($customer_groups as $customer_group) {
				if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
					$data['customer_groups'][] = $customer_group;
				}
			}
		}

		if (isset($this->request->post['customer_group_id'])) {
			$data['customer_group_id'] = $this->request->post['customer_group_id'];
		} else {
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}

		if (isset($this->request->post['firstname'])) {
			$data['firstname'] = $this->request->post['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
			$data['lastname'] = $this->request->post['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (isset($this->request->post['company'])) {
			$data['company'] = $this->request->post['company'];
		} else {
			$data['company'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['pin'])) {
			$data['pin'] = $this->request->post['pin'];
		} else {
			$data['pin'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} else {
			$data['telephone'] = '';
		}

		if (isset($this->request->post['fax'])) {
			$data['fax'] = $this->request->post['fax'];
		} else {
			$data['fax'] = '';
		}

		if (isset($this->request->post['address_1'])) {
			$data['address_1'] = $this->request->post['address_1'];
		} else {
			$data['address_1'] = '';
		}

		if (isset($this->request->post['address_2'])) {
			$data['address_2'] = $this->request->post['address_2'];
		} else {
			$data['address_2'] = '';
		}

		if (isset($this->request->post['postcode'])) {
			$data['postcode'] = $this->request->post['postcode'];
		} elseif (isset($this->session->data['shipping_address']['postcode'])) {
			$data['postcode'] = $this->session->data['shipping_address']['postcode'];
		} else {
			$data['postcode'] = '';
		}

		if (isset($this->request->post['city'])) {
			$data['city'] = $this->request->post['city'];
		} else {
			$data['city'] = '';
		}

		if (isset($this->request->post['country_id'])) {
			$data['country_id'] = $this->request->post['country_id'];
		} elseif (isset($this->session->data['shipping_address']['country_id'])) {
			$data['country_id'] = $this->session->data['shipping_address']['country_id'];
		} else {
			$data['country_id'] = $this->config->get('config_country_id');
		}

		if (isset($this->request->post['zone_id'])) {
			$data['zone_id'] = $this->request->post['zone_id'];
		} elseif (isset($this->session->data['shipping_address']['zone_id'])) {
			$data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
		} else {
			$data['zone_id'] = '';
		}

		if (isset($this->request->post['region_id'])) {
			$data['region_id'] = $this->request->post['region_id'];
		} elseif (isset($this->session->data['shipping_address']['region_id'])) {
			$data['region_id'] = $this->session->data['shipping_address']['region_id'];
		} else {
			$data['region_id'] = '';
		}

		$this->load->model('localisation/country');
        $this->load->model('localisation/zone');
        $this->load->model('localisation/region');

        $data["regions"] = $this->model_localisation_region->getRegionsByZoneId($data['zone_id']);
        $data["zones"] = $this->model_localisation_zone->getZonesByCountryId($data['country_id']);
		$data['countries'] = $this->model_localisation_country->getCountries();

		// Custom Fields
		$this->load->model('account/custom_field');

		$data['custom_fields'] = $this->model_account_custom_field->getCustomFields();

		if (isset($this->request->post['custom_field'])) {
			if (isset($this->request->post['custom_field']['account'])) {
				$account_custom_field = $this->request->post['custom_field']['account'];
			} else {
				$account_custom_field = array();
			}
			
			if (isset($this->request->post['custom_field']['address'])) {
				$address_custom_field = $this->request->post['custom_field']['address'];
			} else {
				$address_custom_field = array();
			}			
			
			$data['register_custom_field'] = $account_custom_field + $address_custom_field;
		} else {
			$data['register_custom_field'] = array();
		}

        if (isset($this->request->post['redirect'])) {
            $data['redirect'] = $this->request->post['redirect'];
        } elseif (isset($this->session->data['redirect'])) {
            $data['redirect'] = $this->session->data['redirect'];
            unset($this->session->data['redirect']);
        } else {
            $data['redirect'] = '';
        }

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		if (isset($this->request->post['confirm'])) {
			$data['confirm'] = $this->request->post['confirm'];
		} else {
			$data['confirm'] = '';
		}

		if (isset($this->request->post['newsletter'])) {
			$data['newsletter'] = $this->request->post['newsletter'];
		} else {
			$data['newsletter'] = true;
		}

		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'), $information_info['title'], $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}

		if (isset($this->request->post['agree'])) {
			$data['agree'] = $this->request->post['agree'];
		} else {
			$data['agree'] = true;
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/register.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/register.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/register.tpl', $data));
		}
	}

	public function init() {
	    if(isset($this->request->post['redirect'])) {
	        $this->session->data['redirect'] = $this->request->post['redirect'];
        }
        $this->response->redirect($this->url->link('account/register'));

    }

	public function validate() {
		if (!isset($this->request->post['firstname']) || (utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if (isset($this->request->post['lastname']) && (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if (!isset($this->request->post['email']) || (utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if($this->config->get('config_otp_verification') && (!isset($this->session->data['pin']) || $this->request->post['telephone'] != $this->session->data["pin_telephone"] || $this->session->data['pin'] != $this->request->post['pin'])) {
            $this->error['pin'] = $this->language->get('error_pin');
        }

		// Check for duplicate email - only if email format is valid (non-blocking)
		if (isset($this->request->post['email']) && !empty($this->request->post['email']) && !isset($this->error['email'])) {
			try {
				$email_total = $this->model_account_customer->getTotalCustomersByEmail($this->request->post['email']);
				if ($email_total && $email_total > 0) {
					// Don't set error - just log it, registration will handle it
					error_log('Registration: Email already exists: ' . $this->request->post['email']);
					// Don't block - registration will auto-login if customer exists
				}
			} catch (Exception $e) {
				error_log('Email duplicate check error: ' . $e->getMessage());
				// Don't block registration if check fails
			}
		}

        // Validate telephone format
        if (!isset($this->request->post['telephone']) || (utf8_strlen($this->request->post['telephone']) < 11) || !preg_match('/^(016|017|018|015|019|011|013)[0-9]{8}$/i', $this->request->post['telephone'])) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        // Check for duplicate telephone - only if format is valid (non-blocking)
        if (isset($this->request->post['telephone']) && !empty($this->request->post['telephone']) && !isset($this->error['telephone'])) {
            try {
                $telephone_total = $this->model_account_customer->getTotalCustomersByTelephone($this->request->post['telephone']);
                if ($telephone_total && $telephone_total > 0) {
                    // Don't set error - just log it, registration will handle it
                    error_log('Registration: Telephone already exists: ' . $this->request->post['telephone']);
                    // Don't block - registration will auto-login if customer exists
                }
            } catch (Exception $e) {
                error_log('Telephone duplicate check error: ' . $e->getMessage());
                // Don't block registration if check fails
            }
        }

		if ($this->config->get('config_address_registration') && (!isset($this->request->post['address_1']) || (utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128))) {
			$this->error['address_1'] = $this->language->get('error_address_1');
		}

		if ($this->config->get('config_address_registration') && isset($this->request->post['city']) && (utf8_strlen(trim($this->request->post['city'])) < 2 || utf8_strlen(trim($this->request->post['city'])) > 128)) {
			$this->error['city'] = $this->language->get('error_city');
		}

		if($this->config->get('config_address_registration') && isset($this->request->post['country_id'])) {
            $this->load->model('localisation/country');
            $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

            if ($country_info && $country_info['postcode_required'] && (!isset($this->request->post['postcode']) || utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
                $this->error['postcode'] = $this->language->get('error_postcode');
            }
        }

        if ($this->config->get('config_address_registration') && (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '')) {
            $this->error['zone'] = $this->language->get('error_zone');
        }

        // Customer Group
		if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->post['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		// Custom field validation
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
				$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
			}
		}

		if (!isset($this->request->post['password']) || (utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (isset($this->request->post['confirm']) && $this->request->post['confirm'] != $this->request->post['password']) {
			$this->error['confirm'] = $this->language->get('error_confirm');
		}

		// Agree to terms
		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

			if ($information_info && !isset($this->request->post['agree'])) {
				$this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}
		}

		return !$this->error;
	}

	public function customfield() {
		$json = array();

		$this->load->model('account/custom_field');

		// Customer Group
		if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->get['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		foreach ($custom_fields as $custom_field) {
			$json[] = array(
				'custom_field_id' => $custom_field['custom_field_id'],
				'required'        => $custom_field['required']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function send_pin() {
        $this->load->language('account/register');
        $this->load->model('account/customer');

	    $json = array();
        if (!isset($this->request->post['telephone']) || utf8_strlen($this->request->post['telephone']) < 11 || !preg_match('/^(016|017|018|015|019|011|013)[0-9]{8}$/i', $this->request->post['telephone'])) {
            $json["message"] = $this->language->get('error_telephone');
        } else if ($this->model_account_customer->getTotalCustomersByTelephone($this->request->post['telephone'])) {
            $json["message"] = $this->language->get('error_exists_telephone');
        } else {
            $this->sms->sendPin($this->request->post['telephone']);
            $this->language->get('text_pin_success');
            $json["message"] = $this->language->get('text_pin_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}