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

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			try {
				// Ensure all required fields are set before calling addCustomer
				if (!isset($this->request->post['email']) || empty($this->request->post['email'])) {
					$this->error['warning'] = $this->language->get('error_email');
				} else {
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
					
					if ($customer_id && $customer_id > 0) {
						// Clear any previous login attempts for unregistered accounts.
						if (method_exists($this->model_account_customer, 'deleteLoginAttempts')) {
							$this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
						}
						
						// Login the customer
						if ($this->customer->login($this->request->post['email'], null, true)) {
							unset($this->session->data['guest']);

							// Add to activity log
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
							
							// Redirect after successful registration
							try {
								if (isset($this->request->post['redirect']) && !empty($this->request->post['redirect']) && $this->customer->isLogged()) {
									$redirect_url = is_array($this->request->post['redirect']) ? '' : str_replace('&amp;', '&', $this->request->post['redirect']);
									if ($redirect_url && filter_var($redirect_url, FILTER_VALIDATE_URL)) {
										$this->response->redirect($redirect_url);
										return;
									}
								}
								// Default redirect to account page
								$account_url = $this->url->link('account/account', '', 'SSL');
								if ($account_url) {
									$this->response->redirect($account_url);
									return;
								} else {
									// Fallback to home if account URL fails
									$this->response->redirect($this->url->link('common/home'));
									return;
								}
							} catch (Exception $redirect_error) {
								error_log('Register Redirect Error: ' . $redirect_error->getMessage());
								// Fallback: redirect to home
								$this->response->redirect($this->url->link('common/home'));
								return;
							}
						} else {
							// Login failed, redirect to login page with success message
							$success_msg = $this->language->get('text_success');
							if (!$success_msg) {
								$success_msg = 'Your account has been created successfully. Please login.';
							}
							$this->session->data['success'] = $success_msg;
							$this->response->redirect($this->url->link('account/login', '', 'SSL'));
							return;
						}
					} else {
						error_log('Registration Failed - Customer ID returned: ' . ($customer_id ? $customer_id : 'FALSE/0'));
						$this->error['warning'] = $this->language->get('error_register');
						if (!$this->error['warning']) {
							$this->error['warning'] = 'Unable to create account. Please check all required fields and try again.';
						}
					}
				}
			} catch (Exception $e) {
				// Log error for debugging with full details
				$error_details = 'Registration Error: ' . $e->getMessage() . ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine() . ' | Trace: ' . $e->getTraceAsString();
				error_log($error_details);
				
				// Show more specific error message if possible
				$error_message = $e->getMessage();
				$error_lower = strtolower($error_message);
				
				// Check for duplicate entry errors - be very specific
				if (strpos($error_lower, 'duplicate entry') !== false) {
					// Check for email duplicate
					if (strpos($error_lower, 'email') !== false || strpos($error_lower, 'uk_email') !== false || strpos($error_lower, 'idx_email') !== false) {
						$this->error['warning'] = $this->language->get('error_exists') ? $this->language->get('error_exists') : 'This email address is already registered.';
					} 
					// Check for telephone duplicate
					elseif (strpos($error_lower, 'telephone') !== false || strpos($error_lower, 'phone') !== false || strpos($error_lower, 'uk_telephone') !== false) {
						$this->error['warning'] = $this->language->get('error_exists_telephone') ? $this->language->get('error_exists_telephone') : 'This phone number is already registered.';
					}
					// Check for primary key duplicate (this shouldn't happen normally)
					elseif (strpos($error_lower, 'primary') !== false || strpos($error_lower, 'customer_id') !== false) {
						error_log('Registration: Primary key duplicate error - this is unusual');
						$this->error['warning'] = 'A system error occurred. Please try again or contact support.';
					}
					// Generic duplicate
					else {
						// Log the full error for debugging
						error_log('Registration: Unknown duplicate error - ' . $error_message);
						$this->error['warning'] = 'This information may already be registered. Please check your email and phone number, or try again.';
					}
				} 
				// Check for SQL/database errors
				elseif (strpos($error_lower, 'sql') !== false || strpos($error_lower, 'database') !== false || strpos($error_lower, 'mysqli') !== false) {
					error_log('Registration: Database error - ' . $error_message);
					$this->error['warning'] = 'A database error occurred. Please try again or contact support.';
				} 
				// Other errors - show the actual error message if it's safe
				else {
					// Only show error if it's not too technical
					if (strlen($error_message) < 200 && !preg_match('/stack trace|fatal error|parse error/i', $error_message)) {
						$this->error['warning'] = 'Registration error: ' . htmlspecialchars($error_message);
					} else {
						$this->error['warning'] = 'An error occurred during registration. Please try again or contact support.';
					}
				}
				// Don't redirect on error - let the form display the error
			} catch (Error $e) {
				// Catch PHP 7+ fatal errors
				$error_details = 'Registration Fatal Error: ' . $e->getMessage() . ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine() . ' | Trace: ' . $e->getTraceAsString();
				error_log($error_details);
				
				$error_message = strtolower($e->getMessage());
				if (strpos($error_message, 'duplicate entry') !== false) {
					if (strpos($error_message, 'email') !== false) {
						$this->error['warning'] = $this->language->get('error_exists') ? $this->language->get('error_exists') : 'This email address is already registered.';
					} elseif (strpos($error_message, 'telephone') !== false || strpos($error_message, 'phone') !== false) {
						$this->error['warning'] = $this->language->get('error_exists_telephone') ? $this->language->get('error_exists_telephone') : 'This phone number is already registered.';
					} else {
						$this->error['warning'] = 'A system error occurred. Please try again or contact support.';
					}
				} else {
					$this->error['warning'] = 'An error occurred during registration. Please try again.';
				}
				// Don't redirect on error - let the form display the error
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

		// Check for duplicate email - only if email format is valid
		if (isset($this->request->post['email']) && !empty($this->request->post['email']) && !isset($this->error['email'])) {
			try {
				$email_total = $this->model_account_customer->getTotalCustomersByEmail($this->request->post['email']);
				if ($email_total && $email_total > 0) {
					$this->error['warning'] = $this->language->get('error_exists');
					if (!$this->error['warning']) {
						$this->error['warning'] = 'This email address is already registered.';
					}
					$this->error['email'] = $this->language->get('error_exists');
				}
			} catch (Exception $e) {
				error_log('Email duplicate check error: ' . $e->getMessage());
				// Don't block registration if check fails, but log it
			}
		}

        // Validate telephone format
        if (!isset($this->request->post['telephone']) || (utf8_strlen($this->request->post['telephone']) < 11) || !preg_match('/^(016|017|018|015|019|011|013)[0-9]{8}$/i', $this->request->post['telephone'])) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        // Check for duplicate telephone - only if format is valid
        if (isset($this->request->post['telephone']) && !empty($this->request->post['telephone']) && !isset($this->error['telephone'])) {
            try {
                $telephone_total = $this->model_account_customer->getTotalCustomersByTelephone($this->request->post['telephone']);
                if ($telephone_total && $telephone_total > 0) {
                    $this->error['warning'] = $this->language->get('error_exists_telephone');
                    if (!$this->error['warning']) {
                        $this->error['warning'] = 'This phone number is already registered.';
                    }
                    $this->error['telephone'] = $this->language->get('error_exists_telephone');
                }
            } catch (Exception $e) {
                error_log('Telephone duplicate check error: ' . $e->getMessage());
                // Don't block registration if check fails, but log it
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