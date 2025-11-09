<?php

class ControllerApiOrder extends Controller {
    private $error = array();

    public function index() {
        $this->load->model('sale/order');

        if (isset($this->request->post['filter_order_id'])) {
            $filter_order_id = $this->request->post['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

        if (isset($this->request->post['filter_assignee_id'])) {
            $filter_assignee_id = $this->request->post['filter_assignee_id'];
        } else {
            $filter_assignee_id = null;
        }

        if (isset($this->request->post['filter_agent_id'])) {
            $filter_agent_id = $this->request->post['filter_agent_id'];
        } else {
            $filter_agent_id = null;
        }

        if (isset($this->request->post['filter_shipping_zone_id'])) {
            $filter_shipping_zone_id = $this->request->post['filter_shipping_zone_id'];
        } else {
            $filter_shipping_zone_id = null;
        }

        if (isset($this->request->post['filter_customer'])) {
            $filter_customer = $this->request->post['filter_customer'];
        } else {
            $filter_customer = null;
        }

        if (isset($this->request->post['filter_order_status_id'])) {
            $filter_order_status = $this->request->post['filter_order_status_id'];
        } else {
            $filter_order_status = null;
        }

        if($filter_order_status === "p") {
            $filter_order_status = $this->config->get('config_processing_status');
        } elseif ($filter_order_status === "c") {
            $filter_order_status = $this->config->get('config_complete_status');
        }

        if (isset($this->request->post['filter_total'])) {
            $filter_total = $this->request->post['filter_total'];
        } else {
            $filter_total = null;
        }

        if (isset($this->request->post['filter_date_added'])) {
            $filter_date_added = $this->request->post['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->post['filter_date_modified'])) {
            $filter_date_modified = $this->request->post['filter_date_modified'];
        } else {
            $filter_date_modified = null;
        }

        if (isset($this->request->post['filter_affiliate_id'])) {
            $filter_affiliate_id = $this->request->post['filter_affiliate_id'];
        } else {
            $filter_affiliate_id = null;
        }

        if (isset($this->request->post['filter_user_agent'])) {
            $filter_user_agent = $this->request->post['filter_user_agent'];
        } else {
            $filter_user_agent = null;
        }

        if (isset($this->request->post['filter_shipping_method'])) {
            $filter_shipping_method = $this->request->post['filter_shipping_method'];
        } else {
            $filter_shipping_method = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'o.order_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->post['limit'])) {
            $limit = $this->request->post['limit'];
        } else {
            $limit = $this->config->get('config_limit_admin');
        }

        $data['orders'] = array();

        $filter_data = array(
            'filter_order_id'      => $filter_order_id,
            'filter_assignee_id'   => $filter_assignee_id,
            'filter_agent_id'      => $filter_agent_id,
            'filter_shipping_zone_id'=> $filter_shipping_zone_id,
            'filter_customer'	   => $filter_customer,
            'filter_order_status'  => $filter_order_status,
            'filter_total'         => $filter_total,
            'filter_date_added'    => $filter_date_added,
            'filter_date_modified' => $filter_date_modified,
            'filter_affiliate_id'  => $filter_affiliate_id,
            'filter_shipping_method' => $filter_shipping_method,
            'filter_user_agent'    => $filter_user_agent,
            'sort'                 => $sort,
            'order'                => $order,
            'start'                => ($page - 1) * $limit,
            'limit'                => $limit
        );


        $results = $this->model_sale_order->getOrders($filter_data);

        foreach ($results as $result) {
            $order_data = array(
                'order_id' => $result['order_id'],
                'customer' => $result['customer'],
                'assignee' => $result['assignee'],
                'assignee_id' => $result['assignee_id'],
                'agent' => $result['agent'],
                'telephone' => $result['telephone'],
                'status' => $result['status'],
                'order_status_id' => $result['order_status_id'],
                'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
                'date_modified' => date($this->language->get('datetime_format'), strtotime($result['date_modified'])),
                'shipping_method' => $result['shipping_method'],
                'shipping_code' => $result['shipping_code'],
                'shipping_address_1' => $result['shipping_address_1'],
                'shipping_city' => $result['shipping_city'],
                'shipping_zone' => $result['shipping_zone'],
            );

            $order_data['order_products'] = array();
            $products = $this->model_sale_order->getOrderProducts($result['order_id']);
            foreach ($products as $product) {
                $order_data['order_products'][] = array(
                    'order_product_id' => $product['order_product_id'],
                    'product_id' => $product['product_id'],
                    'name' => $product['name'],
                    'sku' => $product['sku'],
                    'option' => $this->model_sale_order->getOrderOptions($result['order_id'], $product['order_product_id']),
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'total' => $product['total'],
                    'reward' => $product['reward']
                );
            }

            $order_data['assign_histories'] = array();
            $histories = $this->model_sale_order->getOrderAssignHistories($result['order_id'], 0, 1000);

            foreach ($histories as $history) {
                $order_data['assign_histories'][] = array(
                    'assignee' => $history['assignee'],
                    'agent' => $history['agent'],
                    'assign_by' => $history['assign_by'],
                    'comment'    => nl2br($history['comment']),
                    'date_added' => date($this->language->get('datetime_format'), strtotime($history['date_added']))
                );
            }

            $data['orders'][] = $order_data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function add_history() {
        $this->load->language('sale/order');
        $this->load->model('sale/order');

        unset($this->session->data['cookie']);
        $order_id = isset($this->request->post['order_id']) ? (int) $this->request->post['order_id'] : 0;

        if($order_id) {
            $order_info = $this->model_sale_order->getOrder($order_id);
        } else {
            $order_info = null;
        }

        if ($order_info && $this->validate($order_info)) {

            // API
            $this->load->model('user/api');

            $api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

            if ($api_info) {
                $curl = curl_init();

                // Set SSL if required
                if (substr(HTTPS_CATALOG, 0, 5) == 'https') {
                    curl_setopt($curl, CURLOPT_PORT, 443);
                }

                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/login');
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($api_info));

                $json = curl_exec($curl);

                if (!$json) {
                    $this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
                } else {
                    $response = json_decode($json, true);

                    if (isset($response['cookie'])) {
                        $this->session->data['cookie'] = $response['cookie'];
                    }

                    curl_close($curl);
                }
            }
        }

        if (isset($this->session->data['cookie'])) {
            $curl = curl_init();

            // Set SSL if required
            if (substr(HTTPS_CATALOG, 0, 5) == 'https') {
                curl_setopt($curl, CURLOPT_PORT, 443);
            }

            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLINFO_HEADER_OUT, true);
            curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/order/history&order_id=' . $this->request->get['order_id']);

            $data = $this->request->post;
            $data['user_id'] = $this->user->getId();

            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

            curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $this->session->data['cookie'] . ';');

            $json = curl_exec($curl);

            if (!$json) {
                $this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
            } else {
                $response = json_decode($json, true);

                curl_close($curl);

                if (isset($response['error'])) {
                    $this->error['warning'] = $response['error'];
                }
            }
        }

        $json = array();

        $json['assignee'] = isset($order_info['assignee']) ? $order_info['assignee'] : '';
        $json['old_status'] = isset($order_info['order_status']) ? $order_info['order_status'] : '';

        if (isset($response['error'])) {
            $json['type'] = 'error';
            $json['message'] = $response['error'];
        } elseif ($this->error) {
            $json['type'] = 'error';
            $json['message'] = $this->error['warning'];
        } elseif (isset($response['success'])) {
            $json['type'] = 'success';
            $json['message'] = $response['success'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }

    protected function validate($order_info) {
        if (!$this->user->hasPermission('modify', 'sale/order')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $order_status_id = isset($this->request->post['order_status_id']) ? (int) $this->request->post['order_status_id'] : null;

        if($order_status_id && $this->model_sale_order->getTotalOrderHistories($this->request->get['order_id'], $order_status_id)) {
            $this->error['warning'] = $this->language->get('error_duplicate_history');
        }

        if( $order_status_id
            && in_array($order_info['order_status_id'], $this->config->get('config_complete_status'))
            && !in_array($order_status_id, $this->config->get('config_complete_status'))
        ) {
            $this->error['warning'] = $this->language->get('error_complete_order');
        }
        return !$this->error;
    }
}