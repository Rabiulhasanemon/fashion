<?php
class ControllerApiAssignHistory extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('sale/order');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        } else {
            $limit = $this->config->get('config_limit_admin');
        }

        $data['histories'] = array();

        $this->load->model('sale/order');

        $results = $this->model_sale_order->getOrderAssignHistories($this->request->get['order_id'], ($page - 1) * $limit, $limit);

        foreach ($results as $result) {
            $data['histories'][] = array(
                'assignee' => $result['assignee'],
                'agent' => $result['agent'],
                'assign_by' => $result['assign_by'],
                'comment'    => nl2br($result['comment']),
                'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
            );
        }

        $history_total = $this->model_sale_order->getTotalOrderAssignHistories($this->request->get['order_id']);
        $data['total'] = $history_total;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function add() {
        $json = array();
        $this->load->language('sale/order');
        $this->load->model("sale/order");

        $order_id = isset($this->request->post['order_id']) ? (int) $this->request->post['order_id'] : 0;

        if($order_id) {
            $order_info = $this->model_sale_order->getOrder($order_id);
        } else {
            $order_info = null;
        }

        if($order_info && $this->validate($order_info)) {
            unset($this->session->data['cookie']);
            $order_status_id = isset($this->request->post['order_status_id']) ? $this->request->post['order_status_id'] : null;
            if ($order_status_id) {

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

                    $response = curl_exec($curl);

                    if (!$response) {
                        $this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
                    } else {
                        $response = json_decode($response, true);

                        if (isset($response['cookie'])) {
                            $this->session->data['cookie'] = $response['cookie'];
                        }

                        curl_close($curl);
                    }
                }
            }

            if (isset($this->session->data['cookie']) && $order_status_id) {
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
                curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/order/history&order_id=' . $order_id);

                $data = $this->request->post;
                $data['user_id'] = $this->user->getId();

                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

                curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $this->session->data['cookie'] . ';');

                $response = curl_exec($curl);

                if (!$response) {
                    $this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
                } else {
                    $response = json_decode($response, true);

                    curl_close($curl);

                    if (isset($response['error'])) {
                        $this->error['warning'] = $response['error'];
                    }
                }
            }

            if (!$this->error) {
                $data = array(
                    'assignee_id' => isset($this->request->post['assignee_id']) ? (int) $this->request->post['assignee_id'] : 0,
                    'agent_id' => isset($this->request->post['agent_id']) ? (int) $this->request->post['agent_id'] : 0,
                    'comment' => isset($this->request->post['comment']) ? $this->request->post['comment'] : ''
                );
                $this->model_sale_order->addOrderAssignHistory($order_id, $data);
                $json['type'] = "success";
                $json['message'] = $this->language->get("text_success_add_assign_history");
                $this->log->write(json_encode($json));
            }

        }

        $json['old_agent'] = isset($order_info['agent']) ? $order_info['agent'] : '';
        $json['old_assignee'] = isset($order_info['assignee']) ? $order_info['assignee'] : '';
        $json['old_status'] = isset($order_info['order_status']) ? $order_info['order_status'] : '';

        if($this->error) {
            $json['type'] = 'error';
            $json['message'] = $this->error['warning'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate($order_info) {
        if (!$this->user->hasPermission('modify', 'api/assign_history')) {
            $this->error['type'] = "error";
            $this->error['error'] = "permission";
            $this->error['message'] = $this->language->gert('error_permission');
        }

        $order_status_id = isset($this->request->post['order_status_id']) ? (int) $this->request->post['order_status_id'] : null;

        if($order_status_id && $this->model_sale_order->getTotalOrderHistories($this->request->get['order_id'], $this->request->post['order_status_id'])) {
            $this->error['warning'] = $this->language->get('error_duplicate_history');
        }

        if(
            $order_status_id
            && in_array($order_info['order_status_id'], $this->config->get('config_complete_status'))
            && !in_array($order_status_id, $this->config->get('config_complete_status'))
        ) {
            $this->error['warning'] = $this->language->get('error_complete_order');
        }

        if(empty($this->request->post["assignee_id"]) && empty($this->request->post["agent_id"])) {
            $this->error['warning'] = $this->language->get('error_assignee');
        }
        return !$this->error;
    }
}