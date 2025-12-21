<?php

class ControllerCheckoutOnepagecheckout extends Controller
{
    public $error = array();

    public function index() {
        // Ensure no output is sent before redirect
        if (ob_get_level()) {
            ob_clean();
        }
        
        $this->load->language('checkout/checkout');

        if(!$this->customer->isLogged() && !$this->config->get('config_checkout_guest')) {
            $this->session->data['redirect'] = $this->url->link('checkout/onepagecheckout', '', 'SSL');
            $this->response->redirect($this->url->link('account/login', '', 'SSL'));

        }
        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && $this->config->get('config_stock_checkout'))) {
            $this->response->redirect($this->url->link('checkout/cart'));
        }
        $isEMICart = $this->cart->isEMI();
        if($isEMICart && $this->cart->getTotal() < 5000) {
            $this->session->data['error'] = sprintf($this->language->get("error_emi_total"), 5000);;
            $this->response->redirect($this->url->link("checkout/cart"));
        }

        // Debug logging
        error_log('=== ONEPAGECHECKOUT DEBUG ===');
        error_log('Request Method: ' . (isset($this->request->server['REQUEST_METHOD']) ? $this->request->server['REQUEST_METHOD'] : 'NOT SET'));
        if (isset($this->request->server['REQUEST_METHOD']) && $this->request->server['REQUEST_METHOD'] == 'POST') {
            error_log('POST request detected');
            error_log('POST Data Keys: ' . implode(', ', array_keys($this->request->post)));
            error_log('Validating form...');
            $validation_result = $this->validate_form();
            error_log('Validation result: ' . ($validation_result ? 'PASSED' : 'FAILED'));
        }
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_form()) {
            error_log('Form validation passed, proceeding with order creation...');
            
            // CRITICAL: Clear all output buffers before processing
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            $order_data = array();

            $order_data['totals'] = array();
            $total = 0;
            $taxes = $this->cart->getTaxes();

            $this->load->language('checkout/checkout');

            $order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
            $order_data['store_id'] = $this->config->get('config_store_id');
            $order_data['store_name'] = $this->config->get('config_name');
            $order_data['emi'] = $isEMICart;
            if($isEMICart) {
                $order_data['emi_tenure'] = $this->request->post['emi_tenure'];
            } else {
                $order_data['emi_tenure'] = 0;
            }
            $this->session->data['emi_tenure'] = $order_data['emi_tenure'];

            if ($order_data['store_id']) {
                $order_data['store_url'] = $this->config->get('config_url');
            } else {
                $order_data['store_url'] = HTTP_SERVER;
            }

            $this->load->model('extension/extension');

            $sort_order = array();

            $results = $this->model_extension_extension->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('total/' . $result['code']);

                    $this->{'model_total_' . $result['code']}->getTotal($order_data['totals'], $total, $taxes);
                }
            }

            $sort_order = array();

            foreach ($order_data['totals'] as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $order_data['totals']);

            $this->load->model('localisation/country');
            $this->load->model('localisation/zone');
            $this->load->model('localisation/region');

            $default_country = $this->model_localisation_country->getCountry($this->config->get("config_country_id"));
            $this->request->post['country_id'] = $default_country["country_id"];
            $zone = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
            $region = $this->model_localisation_region->getRegion($this->request->post['region_id']);

            if ($this->customer->isLogged()) {
                $this->load->model('account/customer');
                $this->load->model('account/address');

                if($this->customer->isIncomplete()) {
                    $data = $this->request->post;
                    $data['default'] = true;
                    $this->model_account_customer->editCustomer($data);
                    $this->model_account_address->addAddress($data);
                }

                $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
                $order_data['customer_id'] = $this->customer->getId();
                $order_data['customer_group_id'] = $customer_info['customer_group_id'];
                $order_data['firstname'] = $customer_info['firstname'];
                $order_data['lastname'] = $customer_info['lastname'];
                $order_data['fax'] = $customer_info['fax'];
                $order_data['custom_field'] = unserialize($customer_info['custom_field']);
            } else {
                $order_data['customer_id'] = 0;
                $order_data['customer_group_id'] = $this->config->get('config_customer_group_id');
                $order_data['firstname'] = $this->request->post['firstname'];
                $order_data['lastname'] = isset($this->request->post['lastname']) ? $this->request->post['lastname'] : "";
                $order_data['fax'] = '';
                $order_data['custom_field'] = '';
            }

            $order_data['email'] = $this->request->post['email'];
            $order_data['telephone'] = $this->request->post['telephone'];


            $this->session->data['payment_address']['country_id'] = $default_country['country_id'];
            $order_data['country_id'] = $default_country['country_id'];
            $order_data['country_name'] = $default_country['name'];

            $order_data['payment_firstname'] = $this->request->post['firstname'];
            $order_data['payment_lastname'] = isset($this->request->post['lastname']) ? $this->request->post['lastname'] : "";
            $order_data['payment_company'] = '';
            $order_data['payment_address_1'] = $this->request->post['address_1'];
            $order_data['payment_address_2'] = '';
            $order_data['payment_city'] = isset($this->request->post['city']) ? $this->request->post['city'] : "";
            $order_data['payment_postcode'] = '';
            $order_data['payment_zone'] = isset($zone['name']) ? $zone['name'] : '';
            $order_data['payment_zone_id'] = isset($zone['zone_id']) ? $zone['zone_id'] : 0;
            $order_data['payment_region'] = isset($region['name']) ? $region['name'] : '';
            $order_data['payment_region_id'] = isset($region['region_id']) ? $region['region_id'] : 0;
            $order_data['payment_country'] = $default_country['name'];
            $order_data['payment_country_id'] = $default_country['country_id'];
            $order_data['payment_address_format'] = '';
            $order_data['payment_custom_field'] = (isset($this->request->post['custom_field']) ? $this->request->post['custom_field'] : array());

            $payment_method = $this->session->data['payment_methods'][$this->request->post['payment_method']];

            if (isset($payment_method['title'])) {
                $order_data['payment_method'] = $payment_method['title'];
            } else {
                $order_data['payment_method'] = '';
            }

            if (isset($payment_method['code'])) {
                $order_data['payment_code'] = $payment_method['code'];
            } else {
                $order_data['payment_code'] = '';
            }
            $this->session->data['payment_method'] = $payment_method;

            if ($this->cart->hasShipping()) {
                $order_data['shipping_firstname'] = $this->request->post['firstname'];
                $order_data['shipping_lastname'] = isset($this->request->post['lastname']) ? $this->request->post['lastname'] : "";
                $order_data['shipping_company'] = '';
                $order_data['shipping_address_1'] = $this->request->post['address_1'];
                $order_data['shipping_address_2'] = '';
                $order_data['shipping_city'] = isset($this->request->post['city']) ? $this->request->post['city'] : "";
                $order_data['shipping_postcode'] = '';
            $order_data['shipping_zone'] = isset($zone['name']) ? $zone['name'] : '';
            $order_data['shipping_zone_id'] = isset($zone['zone_id']) ? $zone['zone_id'] : 0;
            $order_data['shipping_region'] = isset($region['name']) ? $region['name'] : '';
            $order_data['shipping_region_id'] = isset($region['region_id']) ? $region['region_id'] : 0;
                $order_data['shipping_country'] = $default_country['name'];
                $order_data['shipping_country_id'] = $default_country['country_id'];
                $order_data['shipping_address_format'] = '';
                $order_data['shipping_custom_field'] = (isset($this->request->post['custom_field']) ? $this->request->post['custom_field'] : array());

                if (isset($this->request->post['shipping_method'])) {
                    $shipping_method = $this->request->post['shipping_method'];
                    $order_data['shipping_code'] = $shipping_method;
                    $order_data['shipping_method'] = $this->request->post[str_replace(".", "_", $shipping_method) . "_title"];
                }
            } else {
                $order_data['shipping_firstname'] = '';
                $order_data['shipping_lastname'] = '';
                $order_data['shipping_company'] = '';
                $order_data['shipping_address_1'] = '';
                $order_data['shipping_address_2'] = '';
                $order_data['shipping_city'] = '';
                $order_data['shipping_postcode'] = '';
                $order_data['shipping_zone'] = '';
                $order_data['shipping_zone_id'] = '';
                $order_data['shipping_region'] = '';
                $order_data['shipping_region_id'] = '';
                $order_data['shipping_country'] = '';
                $order_data['shipping_country_id'] = '';
                $order_data['shipping_address_format'] = '';
                $order_data['shipping_custom_field'] = array();
                $order_data['shipping_method'] = '';
                $order_data['shipping_code'] = '';
            }

            $order_data['products'] = array();

            foreach ($this->cart->getProducts() as $product) {
                $option_data = array();

                foreach ($product['option'] as $option) {
                    $option_data[] = array(
                        'product_option_id'       => $option['product_option_id'],
                        'product_option_value_id' => $option['product_option_value_id'],
                        'option_id'               => $option['option_id'],
                        'option_value_id'         => $option['option_value_id'],
                        'name'                    => $option['name'],
                        'value'                   => $option['value'],
                        'type'                    => $option['type']
                    );
                }

                $order_data['products'][] = array(
                    'product_id' => $product['product_id'],
                    'name'       => $product['name'],
                    'model'      => $product['model'],
                    'option'     => $option_data,
                    'download'   => $product['download'],
                    'quantity'   => $product['quantity'],
                    'subtract'   => $product['subtract'],
                    'price'      => $product['price'],
                    'total'      => $product['total'],
                    'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
                    'reward'     => $product['reward']
                );
            }

            // Gift Voucher
            $order_data['vouchers'] = array();

            if (!empty($this->session->data['vouchers'])) {
                foreach ($this->session->data['vouchers'] as $voucher) {
                    $order_data['vouchers'][] = array(
                        'description'      => $voucher['description'],
                        'code'             => substr(md5(mt_rand()), 0, 10),
                        'to_name'          => $voucher['to_name'],
                        'to_email'         => $voucher['to_email'],
                        'from_name'        => $voucher['from_name'],
                        'from_email'       => $voucher['from_email'],
                        'voucher_theme_id' => $voucher['voucher_theme_id'],
                        'message'          => $voucher['message'],
                        'amount'           => $voucher['amount']
                    );
                }
            }

            $order_data['comment'] = $this->request->post['comment'];
            $order_data['total'] = $total;

            if($isEMICart && $total < 5000) {
                $this->session->data['error'] = sprintf($this->language->get("error_emi_total"), 5000);
                $this->response->redirect($this->url->link("checkout/cart"));
            }

            if (isset($this->request->cookie['tracking'])) {
                $order_data['tracking'] = $this->request->cookie['tracking'];

                $subtotal = $this->cart->getSubTotal();

                // Affiliate
                $this->load->model('affiliate/affiliate');

                $affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);

                if ($affiliate_info) {
                    $order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
                    $order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
                } else {
                    $order_data['affiliate_id'] = 0;
                    $order_data['commission'] = 0;
                }

                // Marketing
                $this->load->model('checkout/marketing');

                $marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

                if ($marketing_info) {
                    $order_data['marketing_id'] = $marketing_info['marketing_id'];
                } else {
                    $order_data['marketing_id'] = 0;
                }
            } else {
                $order_data['affiliate_id'] = 0;
                $order_data['commission'] = 0;
                $order_data['marketing_id'] = 0;
                $order_data['tracking'] = '';
            }

            $order_data['language_id'] = $this->config->get('config_language_id');
            $order_data['currency_id'] = $this->currency->getId();
            $order_data['currency_code'] = $this->currency->getCode();
            $order_data['currency_value'] = $this->currency->getValue($this->currency->getCode());
            $order_data['ip'] = $this->request->server['REMOTE_ADDR'];

            if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
                $order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
            } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
                $order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
            } else {
                $order_data['forwarded_ip'] = '';
            }

            if (isset($this->request->server['HTTP_USER_AGENT'])) {
                $order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
            } else {
                $order_data['user_agent'] = '';
            }

            if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
                $order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
            } else {
                $order_data['accept_language'] = '';
            }

            $this->load->model('checkout/order');

            // Debug logging
            error_log('=== ONEPAGECHECKOUT ORDER CREATION START ===');
            error_log('Order Data Keys: ' . implode(', ', array_keys($order_data)));
            error_log('Payment Method: ' . print_r($payment_method, true));

            try {
                error_log('Calling addOrder...');
                $order_id = $this->model_checkout_order->addOrder($order_data);
                error_log('addOrder returned: ' . ($order_id ? $order_id : 'FALSE/0'));
                
                if (!$order_id || $order_id <= 0) {
                    error_log('ERROR: addOrder returned invalid order ID: ' . $order_id);
                    $this->session->data['error'] = 'Failed to create order. Please try again.';
                    $this->response->redirect($this->url->link('checkout/onepagecheckout', '', 'SSL'));
                    return;
                }
                
                $this->session->data['order_id'] = $order_id;
                error_log('Order ID saved to session: ' . $order_id);
                
                // Add order history
                error_log('Adding order history...');
                $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('config_order_status_id'), '', 0, 0);
                error_log('Order history added');
                
                // Clear cart after successful order creation
                error_log('Clearing cart...');
                $this->cart->clear();
                error_log('Cart cleared');
                
                // Set payment method in session for payment processing
                if (!isset($this->session->data['payment_method']) && isset($payment_method)) {
                    $this->session->data['payment_method'] = $payment_method;
                    error_log('Payment method set in session: ' . print_r($payment_method, true));
                }
                
                // Get payment code for redirect
                $payment_code = '';
                if (isset($payment_method) && is_array($payment_method) && isset($payment_method['code']) && !empty($payment_method['code'])) {
                    $payment_code = $payment_method['code'];
                    error_log('Payment code from payment_method variable: ' . $payment_code);
                } elseif (isset($this->session->data['payment_method']['code']) && !empty($this->session->data['payment_method']['code'])) {
                    $payment_code = $this->session->data['payment_method']['code'];
                    error_log('Payment code from session: ' . $payment_code);
                } elseif (isset($order_data['payment_code']) && !empty($order_data['payment_code'])) {
                    $payment_code = $order_data['payment_code'];
                    error_log('Payment code from order_data: ' . $payment_code);
                } else {
                    error_log('WARNING: No payment code found!');
                }
                
                // Clean all output buffers before redirect - CRITICAL
                while (ob_get_level()) {
                    ob_end_clean();
                }
                
                // Disable error display to prevent any output
                @ini_set('display_errors', 0);
                @error_reporting(0);
                
                // Ensure headers haven't been sent
                if (headers_sent($file, $line)) {
                    error_log('WARNING: Headers already sent in ' . $file . ' on line ' . $line);
                }
                
                // Build success URL first
                $success_url = $this->url->link("checkout/success", '', 'SSL');
                // Fix URL if missing slash
                $success_url = str_replace('://ruplexa1.master.com.bdindex.php', '://ruplexa1.master.com.bd/index.php', $success_url);
                
                // For COD (Cash on Delivery), go directly to success page
                if (strtolower($payment_code) == 'cod') {
                    error_log('Payment method is COD, redirecting to success page...');
                    error_log('Success URL: ' . $success_url);
                    
                    // Ensure no output before redirect
                    if (ob_get_level()) {
                        ob_end_clean();
                    }
                    
                    // Redirect immediately
                    if ($success_url && !empty($success_url)) {
                        error_log('Redirecting to success page: ' . $success_url);
                        header('Location: ' . $success_url, true, 302);
                        exit;
                    } else {
                        error_log('ERROR: Success URL is empty, using relative redirect');
                        header('Location: index.php?route=checkout/success', true, 302);
                        exit;
                    }
                }
                
                // For other payment methods, try to redirect to payment confirm first
                if (!empty($payment_code) && strtolower($payment_code) != 'cod') {
                    error_log('Payment code found: ' . $payment_code . ', checking if payment confirm exists...');
                    try {
                        $payment_confirm_url = $this->url->link('payment/' . $payment_code . '/confirm', '', 'SSL');
                        // Fix URL if missing slash
                        $payment_confirm_url = str_replace('://ruplexa1.master.com.bdindex.php', '://ruplexa1.master.com.bd/index.php', $payment_confirm_url);
                        error_log('Payment confirm URL: ' . $payment_confirm_url);
                        
                        // Check if payment confirm controller exists
                        $payment_confirm_file = DIR_APPLICATION . 'controller/payment/' . $payment_code . '.php';
                        if (file_exists($payment_confirm_file)) {
                            // Check if confirm method exists
                            require_once($payment_confirm_file);
                            $payment_class = 'ControllerPayment' . str_replace('_', '', ucwords($payment_code, '_'));
                            if (class_exists($payment_class)) {
                                $payment_obj = new $payment_class($this->registry);
                                if (method_exists($payment_obj, 'confirm')) {
                                    error_log('Payment confirm method exists, redirecting...');
                                    if (ob_get_level()) {
                                        ob_end_clean();
                                    }
                                    header('Location: ' . $payment_confirm_url, true, 302);
                                    exit;
                                }
                            }
                        }
                        error_log('Payment confirm not available, will use success page fallback');
                    } catch (Exception $e) {
                        error_log('Payment confirm check error: ' . $e->getMessage());
                    }
                }
                
                // Fallback - redirect to success page for all cases
                error_log('Using success page redirect (fallback or default)...');
                error_log('Success URL: ' . $success_url);
                
                // Ensure no output before redirect
                if (ob_get_level()) {
                    ob_end_clean();
                }
                
                if ($success_url && !empty($success_url)) {
                    error_log('Redirecting to success page: ' . $success_url);
                    header('Location: ' . $success_url, true, 302);
                    exit;
                } else {
                    error_log('ERROR: Success URL is empty, using relative redirect');
                    header('Location: index.php?route=checkout/success', true, 302);
                    exit;
                }
                
            } catch (Exception $e) {
                error_log('=== ONEPAGECHECKOUT EXCEPTION ===');
                error_log('Error Message: ' . $e->getMessage());
                error_log('Error File: ' . $e->getFile());
                error_log('Error Line: ' . $e->getLine());
                error_log('Error Trace: ' . $e->getTraceAsString());
                $this->session->data['error'] = 'An error occurred while processing your order. Please try again.';
                $this->response->redirect($this->url->link('checkout/onepagecheckout', '', 'SSL'));
                return;
            }
            
            error_log('=== ONEPAGECHECKOUT ORDER CREATION END ===');
        }

        $this->getForm();
    }

    private function applyGatewayCoupon($coupon) {
        if(isset($this->session->data['coupon']) && !isset($this->session->data['gateway_coupon'])) {
            return;
        }

        if(!$coupon && isset($this->session->data['gateway_coupon'])) {
            unset($this->session->data['gateway_coupon']);
            unset($this->session->data['coupon']);
            return;
        }

        if(!$coupon) {
            return;
        }

        $this->load->model('checkout/coupon');
        $coupon_info = $this->model_checkout_coupon->getCoupon($coupon);

        if($coupon_info) {
            $this->session->data['coupon'] = $coupon;
            $this->session->data['gateway_coupon'] = $coupon;
        }

    }

    public function reload() {
        $this->load->language('checkout/checkout');

        if(isset($this->request->get['zone_id'])) {
            $this->session->data['payment_address']['zone_id'] = $this->request->get['zone_id'];
            $this->session->data['shipping_address']['zone_id'] = $this->request->get['zone_id'];
        }

        if(isset($this->request->get['region_id'])) {
            $this->session->data['payment_address']['region_id'] = $this->request->get['region_id'];
            $this->session->data['shipping_address']['region_id'] = $this->request->get['region_id'];
        }

        if(isset($this->request->get['emi_tenure'])) {
            $this->session->data['emi_tenure'] = $this->request->get['emi_tenure'];
        }

        if(isset($this->request->get["shipping_method_code"])) {
            $shipping = explode('.', $this->request->get['shipping_method_code']);
            if (isset($shipping[1]) && isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
            }
        }

        if(isset($this->request->get["payment_method_code"])) {
            $this->session->data['payment_method']['code'] = $this->request->get["payment_method_code"];
            $this->applyGatewayCoupon($this->config->get($this->request->get["payment_method_code"] . "_coupon"));
        }

        $this->getForm();
    }

    public function getForm() {
        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');
        if ($this->config->get('config_checkout_id')) {
            $this->load->model('catalog/information');
            $checkout_terms = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));
            $return_terms = $this->model_catalog_information->getInformation($this->config->get('config_return_id'));
            $account_terms = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

            if ($checkout_terms && $return_terms) {
                $data['text_agree'] = sprintf(
                    $this->language->get('text_agree'),
                    $this->url->link('information/information', 'information_id=' . $this->config->get('config_checkout_id'), 'SSL'),
                    $checkout_terms['title'],
                    $this->url->link('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
                    $account_terms['title'],
                    $this->url->link('information/information', 'information_id=' . $this->config->get('config_return_id'), 'SSL'),
                    $return_terms['title'], $return_terms['title']
                );
            } else {
                $data['text_agree'] = '';
            }
        } else {
            $data['text_agree'] = '';
        }

        $isEMICart = $this->cart->isEMI();
        $points = $this->customer->getRewardPoints();
        $data['reward_heading'] = sprintf($this->language->get('reward_heading'), $points);

        $data['text_none'] = $this->language->get('text_none');
        $data['text_customer'] = $this->language->get('text_customer');
        $data['text_total'] = $this->language->get('text_total');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_shipping_method'] = $this->language->get('text_shipping_method');
        $data['text_payment_method'] = $this->language->get('text_payment_method');

        $data['entry_address'] = $this->language->get('entry_address');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_comment'] = $this->language->get('entry_comment');
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['entry_region'] = $this->language->get('entry_zone');
        $data['entry_coupon'] = $this->language->get('entry_coupon');
        $data['entry_voucher'] = $this->language->get('entry_voucher');
        $data['entry_reward'] = $this->language->get('entry_reward');
        $data['entry_emi_tenure'] = $this->language->get('entry_emi_tenure');
        $data['entry_reward'] = sprintf($this->language->get('entry_reward'), $points);

        $data['column_name'] = $this->language->get('column_name');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_quantity'] = $this->language->get('column_quantity');

        $data['button_confirm'] = $this->language->get('button_confirm');
        $data['button_apply'] = $this->language->get('button_apply');
        $data['button_coupon'] = $this->language->get('button_coupon');
        $data['button_voucher'] = $this->language->get('button_voucher');
        $data['button_reward'] = $this->language->get('button_reward');

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

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_cart'),
            'href' => $this->url->link('checkout/cart')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('checkout/checkout', '', true)
        );

        if ($this->customer->isLogged()) {
            $this->load->model('account/customer');
            $this->load->model('account/address');
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            $address_info = $customer_info['address_id'] ? $this->model_account_address->getAddress($customer_info['address_id']) : array();
        } else {
            $customer_info = array();
            $address_info = array();
        }
        $data['logged'] = $this->customer->isLogged();
        $data['action'] = $this->url->link('checkout/onepagecheckout', '', 'SSL');

        if (isset($this->session->data['account'])) {
            $data['account'] = $this->session->data['account'];
        } else {
            $data['account'] = '';
        }

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } elseif (isset($this->session->data['payment_address']['firstname'])) {
            $data['firstname'] = $this->session->data['payment_address']['firstname'];
        } elseif(isset($customer_info['firstname'])){
            $data['firstname'] = $customer_info['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        } elseif (isset($this->session->data['payment_address']['lastname'])) {
            $data['lastname'] = $this->session->data['payment_address']['lastname'];
        } elseif(isset($customer_info['firstname'])){
            $data['lastname'] = $customer_info['lastname'];
        } else {
            $data['lastname'] = '';
        }

        if (isset($this->request->post['address_1'])) {
            $data['address_1'] = $this->request->post['address_1'];
        } elseif (isset($this->session->data['payment_address']['firstname'])) {
            $data['address_1'] = $this->session->data['payment_address']['address_1'];
        } elseif(isset($address_info['address_1'])){
            $data['address_1'] = $address_info['address_1'];
        } else {
            $data['address_1'] = '';
        }

        if (isset($this->request->post['city'])) {
            $data['city'] = $this->request->post['city'];
        } elseif (isset($this->session->data['payment_address']['city'])) {
            $data['city'] = $this->session->data['payment_address']['city'];
        } elseif(isset($address_info['city'])){
            $data['city'] = $address_info['city'];
        } else {
            $data['city'] = '';
        }

        if (isset($this->request->post['telephone'])) {
            $data['telephone'] = $this->request->post['telephone'];
        } elseif (isset($this->session->data['payment_address']['telephone'])) {
            $data['telephone'] = $this->session->data['payment_address']['telephone'];
        } elseif(isset($customer_info['telephone'])){
            $data['telephone'] = $customer_info['telephone'];
        } else {
            $data['telephone'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } elseif (isset($this->session->data['payment_address']['email'])) {
            $data['email'] = $this->session->data['payment_address']['email'];
        } elseif(isset($customer_info['email'])){
            $data['email'] = $customer_info['email'];
        } else {
            $data['email'] = '';
        }


        if (isset($this->request->post['comment'])) {
            $data['comment'] = $this->request->post['comment'];
        } elseif (isset($this->session->data['payment_address']['comment'])) {
            $data['comment'] = $this->session->data['payment_address']['comment'];
        } else {
            $data['comment'] = '';
        }

        if(isset($this->session->data['agree'])) {
            $data['agree'] = $this->session->data['agree'];
        } else {
            $data['agree'] = 0;
        }

        if (isset($this->request->post['emi_tenure'])) {
            $data['emi_tenure'] = $this->request->post['emi_tenure'];
        } elseif(isset($this->session->data['emi_tenure'])) {
            $data['emi_tenure'] = $this->session->data['emi_tenure'];
        } else {
            $data['emi_tenure'] = 3;
        }

        $this->session->data['emi_tenure'] = $data["emi_tenure"];

        if (isset($this->request->post['payment_method'])) {
            $data['payment_method_code'] = $this->request->post['payment_method'];
        } elseif (isset($this->session->data['payment_method']['code'])) {
            $data['payment_method_code'] = $this->session->data['payment_method']['code'];
        } else {
            $data['payment_method_code'] = '';
        }

        $this->load->model('localisation/country');
        $this->load->model('localisation/zone');
        $this->load->model('localisation/region');

        $default_country = $this->model_localisation_country->getCountry($this->config->get("config_country_id"));
        $data['zones'] = $this->model_localisation_zone->getZonesByCountryId($default_country["country_id"]);;

        if (isset($this->request->post['zone_id'])) {
            $data['zone_id'] = $this->request->post['zone_id'];
        } elseif (isset($this->session->data['payment_address']['zone_id'])) {
            $data['zone_id'] = $this->session->data['payment_address']['zone_id'];
        } else {
            $data['zone_id'] = $this->config->get("config_zone_id");
        }

        $data['regions'] = $this->model_localisation_region->getRegionsByZoneId( $data['zone_id']);

        if (isset($this->request->post['region_id'])) {
            $data['region_id'] = $this->request->post['region_id'];
        } elseif (isset($this->session->data['payment_address']['region_id'])) {
            $data['region_id'] = $this->session->data['payment_address']['region_id'];
        } else {
            $data['region_id'] = $this->config->get("config_region_id");
        }

        $this->session->data['shipping_address']['region_id'] = $data['region_id'];
        $this->session->data['shipping_address']['zone_id'] = $data['zone_id'];
        $this->session->data['shipping_address']['country_id'] = $default_country["country_id"];

        // Shipping Methods
        $shipping_method_data = array();

        $this->load->model('extension/extension');
        $this->load->model('extension/shipping');

        $results = $this->model_extension_extension->getExtensions('shipping');

        foreach ($results as $result) {
            $shippings = $this->model_extension_shipping->getShippingsByCode($result['code']);
            if ($shippings || $this->config->get($result['code'] . '_status')) {
                $this->load->model('shipping/' . $result['code']);

                if($shippings) {
                    $quote = $this->{'model_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address'], $shippings);
                } else {
                    $quote = $this->{'model_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);
                }

                if ($quote) {
                    $shipping_method_data[$result['code']] = array(
                        'title' => $quote['title'],
                        'quote' => $quote['quote'],
                        'sort_order' => $quote['sort_order'],
                        'error' => $quote['error']
                    );
                }
            }
        }

        $sort_order = array();

        foreach ($shipping_method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $shipping_method_data);
        $data['shipping_methods']  = $shipping_method_data;
        $this->session->data['shipping_methods'] = $shipping_method_data;

        if(isset($this->session->data['shipping_method'])) {
            $shipping = explode('.', $this->session->data['shipping_method']['code']);
            if (!isset($shipping[1]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                unset($this->session->data['shipping_method']);
            }
        }
        if(!isset($this->session->data['shipping_method']) && count($shipping_method_data) > 0) {
            $this->session->data['shipping_method'] = array_values(array_values($shipping_method_data)[0]['quote'])[0];
        }

        if (isset($this->request->post['shipping_method'])) {
            $data['shipping_method_code'] = $this->request->post['shipping_method'];
        } elseif (isset($this->session->data['shipping_method']['code'])) {
            $data['shipping_method_code'] = $this->session->data['shipping_method']['code'];
        } else {
            $data['shipping_method_code'] = null;
        }

        $data['products'] = array();

        foreach ($this->cart->getProducts() as $product) {
            $option_data = array();

            foreach ($product['option'] as $option) {
                if ($option['type'] != 'file') {
                    $value = $option['value'];
                } else {
                    $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                    if ($upload_info) {
                        $value = $upload_info['name'];
                    } else {
                        $value = '';
                    }
                }

                $option_data[] = array(
                    'name'  => $option['name'],
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                );
            }

            $data['products'][] = array(
                'key'        => $product['key'],
                'product_id' => $product['product_id'],
                'name'       => $product['name'],
                'model'      => $product['model'],
                'reward'    => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
                'option'     => $option_data,
                'quantity'   => $product['quantity'],
                'subtract'   => $product['subtract'],
                'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
                'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']),
                'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id']),
            );
        }

        // Totals
        $totals = array();
        $taxes = $this->cart->getTaxes();
        $total_amount = 0;

        $this->load->model('extension/extension');
        $sort_order = array();
        $results = $this->model_extension_extension->getExtensions('total');
        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
        }
        array_multisort($sort_order, SORT_ASC, $results);
        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('total/' . $result['code']);

                $this->{'model_total_' . $result['code']}->getTotal($totals, $total_amount, $taxes);
            }
        }

        $data['totals'] = array();
        foreach ($totals as $total) {
            $data['totals'][] = array(
                'title' => $total['title'],
                'text'  => $this->currency->format($total['value']),
            );
        }

        $data['cart_total'] = $this->currency->format($total_amount, $this->session->data['currency']);

        $this->session->data['payment_address']['country_id'] = $default_country['country_id'];
        $this->session->data['payment_address']['zone_id'] = $data["zone_id"];
        $this->session->data['payment_address']['region_id'] = $data["region_id"];

        $this->load->model('extension/extension');

        // Payment Methods
        $method_data = array();

        $this->load->model('extension/extension');

        $results = $this->model_extension_extension->getExtensions('payment');


        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status') && !$this->config->get($result['code'] . '_online')) {
                $this->load->model('payment/' . $result['code']);

                $method = $this->{'model_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total_amount, $isEMICart);

                if ($method) {
                    $method_data[$result['code']] = $method;
                }
            }
        }

        $sort_order = array();

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);

        $this->session->data['payment_methods'] = $method_data;
        $this->load->model('catalog/information');
        $data['payment_methods'] = $method_data;

        $data['is_emi_cart'] = $isEMICart;
        $data['text_tree_month_emi'] = sprintf($this->language->get('text_emi_month'), 3, $this->currency->format($total_amount / 3));
        $data['text_six_month_emi'] = sprintf($this->language->get('text_emi_month'), 6, $this->currency->format($total_amount / 6));
        $data['text_nine_month_emi'] = sprintf($this->language->get('text_emi_month'), 9, $this->currency->format($total_amount / 9));
        $data['text_twelve_month_emi'] = sprintf($this->language->get('text_emi_month'), 12, $this->currency->format($total_amount / 12));

        $data['coupon_status'] = $this->config->get('coupon_status');
        $data['voucher_status'] = $this->config->get('voucher_status');


        $data['store_telephone'] = $this->config->get("config_telephone");
        $data['store_email'] = $this->config->get("config_email");

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/onepagecheckout.tpl', $data));
    }

    public function validate_form()
    {
        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 42)) {

            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if (isset($this->request->post['lastname']) && (utf8_strlen(trim($this->request->post['lastname'])) > 42)) {

            $this->error['lastname'] = $this->language->get('error_lastname');

        }

        if (isset($this->request->post['email']) && $this->request->post['email'] && !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ((utf8_strlen($this->request->post['telephone']) < 11) || !preg_match('/^(016|017|018|015|019|014|013)[0-9]{8}$/i', $this->request->post['telephone'])) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        if ((utf8_strlen(trim($this->request->post['address_1'])) < 1) || (utf8_strlen(trim($this->request->post['address_1'])) > 250)) {
            $this->error['address_1'] = $this->language->get('error_address_1');
        }

        if (isset($this->request->post['city']) && (utf8_strlen(trim($this->request->post['city'])) > 32)) {
            $this->error['city'] = $this->language->get('error_city');
        }

        if($this->cart->isEMI()) {
            $emi_tenure = (int) $this->request->post['emi_tenure'];
            if($emi_tenure != 3 && $emi_tenure != 6 && $emi_tenure != 9 && $emi_tenure != 12) {
                $this->error['emi_tenure'] = $this->language->get('error_emi_tenure');
            }
        }

        if ($this->config->get('config_checkout_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

            if ($information_info && !isset($this->request->post['agree'])) {
                $this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
            }
        }
        return !$this->error;
    }

}
