<?php
class ControllerApiERP extends Controller {
    public function __construct($registry) {
        parent::__construct($registry);
        $this->erp = new ERP($registry);
    }

    public function handleOrderAdd($order_id) {
        if(!isset( $this->request->post['order_create_api_request'])) {
            $this->erp->addOrderToERP($order_id);
        }
    }

    public function addOrder() {
        $this->validateRequest();
        $this->request->post['order_create_api_request'] = true;
        $json = array();
        $params = $this->request->getJSONData();
        $invoice = $params['inv_no'];
        $telephone = $params["customer_telephone"];
        try {
            $this->load->model('localisation/country');
            $this->load->model('localisation/zone');
            $this->load->model('catalog/product');

            if(!$telephone || !$invoice || count($params["items"]) == 0) {
                throw new Exception("Invalid Data");
            }

            $order_id = $this->erp->getOrderIdByInvoice($invoice);
            if($order_id) {  throw new Exception("INV Already Exits"); }

            foreach ($params["items"] as $item) {
                $product_id = $this->model_catalog_product->getProductIdBySKU($item['CODE']);
                if(!$product_id) {
                    throw new Exception("Item " . $item['CODE'] . "not found");
                }
                $this->cart->add($product_id, $item['QUANTITY']);
            }



            $order_data = array();

            $order_data['totals'] = array();
            $total = 0;
            $taxes = $this->cart->getTaxes();

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

            $order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
            $order_data['store_id'] = $this->config->get('config_store_id');
            $order_data['store_name'] = $this->config->get('config_name');
            $order_data['emi'] = false;
            $order_data['emi_tenure'] = 0;

            if ($order_data['store_id']) {
                $order_data['store_url'] = $this->config->get('config_url');
            } else {
                $order_data['store_url'] = HTTP_SERVER;
            }

            $order_data['customer_id'] = 0;
            $order_data['customer_group_id'] = $this->config->get('config_customer_group_id');
            $order_data['firstname'] = $params['customer_name'];
            $order_data['lastname'] = "";
            $order_data['fax'] = '';
            $order_data['custom_field'] = '';



            $bd = $this->model_localisation_country->getCountryByCode("BD");
            $zone = $this->model_localisation_zone->getZone($this->config->get('config_zone_id'));

            $order_data['email'] = isset($params['customer_email']) ? $params['customer_email'] : '';
            $order_data['telephone'] = isset($params['customer_telephone']) ? $params['customer_telephone'] : '';
            $order_data['zone_name'] = $zone["name"];


            $order_data['country_id'] = $bd['country_id'];
            $order_data['country_name'] = $bd['name'];

            $order_data['payment_firstname'] = $order_data['firstname'];
            $order_data['payment_lastname'] = "";
            $order_data['payment_company'] = '';
            $order_data['payment_address_1'] = $params['customer_address1'];
            $order_data['payment_address_2'] = '';
            $order_data['payment_city'] =  "";
            $order_data['payment_postcode'] = '';
            $order_data['payment_zone'] = $zone['name'];
            $order_data['payment_zone_id'] = $zone['zone_id'];
            $order_data['payment_country'] = $bd['name'];
            $order_data['payment_country_id'] = $bd['country_id'];
            $order_data['payment_address_format'] = '';
            $order_data['payment_custom_field'] = '';

            $order_data['payment_method'] = '';
            $order_data['payment_code'] = '';


            $order_data['shipping_firstname'] = '';
            $order_data['shipping_lastname'] = '';
            $order_data['shipping_company'] = '';
            $order_data['shipping_address_1'] = '';
            $order_data['shipping_address_2'] = '';
            $order_data['shipping_city'] = '';
            $order_data['shipping_postcode'] = '';
            $order_data['shipping_zone'] = '';
            $order_data['shipping_zone_id'] = '';
            $order_data['shipping_country'] = '';
            $order_data['shipping_country_id'] = '';
            $order_data['shipping_address_format'] = '';
            $order_data['shipping_custom_field'] = array();
            $order_data['shipping_method'] = '';
            $order_data['shipping_code'] = '';

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

            $order_data['comment'] = '';
            $order_data['total'] = $total;

            $order_data['affiliate_id'] = 0;
            $order_data['commission'] = 0;
            $order_data['marketing_id'] = 0;
            $order_data['tracking'] = '';

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

            $order_id = $this->model_checkout_order->addOrder($order_data);

            $this->load->model('checkout/order');
            $this->model_checkout_order->addOrderHistory( $order_id, $this->config->get('config_order_status_id'), '', 0, 0);

            $this->erp->addERPOrder($order_id, $invoice);
            $json['status'] = 'success';

        } catch (Exception $exception) {
            $json['message'] = $exception->getMessage();
            $json["status"] = 'error';
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addProduct() {
        $this->validateRequest();
        $data = $this->request->getJSONData();
        $json = array();
        $error = $this->validateAddProduct($data);
        if($error) {
            $json = $error;
            $json["status"] = 'error';
        } else {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '', sku = '" . $this->db->escape($data['sku']) . "', mpn = '', short_note = '', quantity = 0, minimum = 1, subtract = 0, stock_status_id = 0, date_available = now(), manufacturer_id = 0, attribute_profile_id =0, status = 0, sort_order = 0, date_added = NOW(), date_modified = NOW()");
            $product_id = $this->db->getLastId();
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$this->config->get('config_language_id') . "', name = '" . $this->db->escape($data['name']) . "', description = '', short_description = '', tag = '', meta_title = '', meta_description = '', meta_keyword = ''");
            $json["status"] = "success";
            $json['product_id'] = $product_id;
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }

    public function updateProductQuantity() {
//        $this->validateRequest();
        $data = $this->request->getJSONData();
        $json = array();
        $error = $this->validateUpdateProductQuantity($data);
        if($error) {
            $json = $error;
            $json["status"] = 'error';
        } else {
            $this->load->model('catalog/product');
            $product_id = $this->model_catalog_product->getProductIdBySKU($data['sku']);
            $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '" . (int) $data["quantity"] . "', date_modified = NOW() WHERE product_id = '" . (int) $product_id . "'");
            $json["status"] = "success";
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function validateRequest() {
        $this->load->model("account/api");
        if (isset($this->request->server['PHP_AUTH_USER']) && isset($this->request->server['PHP_AUTH_PW'])) {
            $api_user = $this->model_account_api->login($this->request->server['PHP_AUTH_USER'], $this->request->server['PHP_AUTH_PW']);
        } else {
            $api_user = null;
        }
        if(!$api_user) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(array("status" => 'error', "message" => 'Permission Denied' )));
            $this->response->output();
            exit();
        }
    }

    private function validateAddProduct($data) {
        $error = array();

        if ((utf8_strlen($data['sku']) < 1) || (utf8_strlen($data['sku']) > 255)) {
            $this['error_sku'] = $this->language->get('error_sku');
        }

        if ((utf8_strlen($data['name']) < 1) || (utf8_strlen($data['name']) > 255)) {
            $error['error_name'] = $this->language->get('error_name');
        }

        $this->load->model('catalog/product');
        if(isset($data['sku']) && $this->model_catalog_product->getProductIdBySKU($data['sku'])) {
            $error['error_sku'] = $this->language->get('error_sku_exist');
        }
        return $error;
    }

    private function validateUpdateProductQuantity($data) {
        $error = array();


        if ((utf8_strlen($data['sku']) < 1) || (utf8_strlen($data['sku']) > 255)) {
            $error['error_sku'] = $this->language->get('error_sku');
        }

        if(!isset($data['quantity']) || !is_numeric($data['quantity'])) {
            $error['error_quantity'] = $this->language->get('error_quantity');
        }

        $this->load->model('catalog/product');
        if(isset($data['sku']) && !$this->model_catalog_product->getProductIdBySKU($data['sku'])) {
            $error['error_sku'] = $this->language->get('error_sku');
        }
        return $error;
    }
}