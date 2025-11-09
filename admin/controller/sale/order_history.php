<?php
class ControllerSaleOrderHistory extends Controller {
	private $error = array();


	public function index() {
		$this->load->language('sale/order_history');

		$this->document->setTitle($this->language->get('heading_title'));


		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $results = array();
		    $orders = array();
            if (isset($this->request->post['order_id'])) {
                $orders = $this->request->post['order_id'];
            }

            foreach ($orders as $order_id) {
                $this->request->post['order_id'] = $order_id;
                $results[] = $this->addHistory($this->request->post);
            }
            $this->getList($results);
            return;
		}

		$this->getForm();
	}

	protected function getList($results) {

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sale/customer_group', 'token=' . $this->session->data['token'] , 'SSL')
        );

        $data['continue'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] , 'SSL');

        $data['results'] = array();

        foreach ($results as $result) {
            $data['results'][] = array(
                'order_id' => $result['order_id'],
                'type' => $result['type'],
                'message' => $result['message'],
                'old_status' => $result['old_status'],
                'assignee' => $result['assignee'],
                'view'          => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], 'SSL'),
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_type'] = $this->language->get('column_type');
        $data['column_message'] = $this->language->get('column_message');
        $data['column_old_status'] = $this->language->get('column_old_status');
        $data['column_assignee'] = $this->language->get('column_assignee');
        $data['column_sirial'] = $this->language->get('column_sirial');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_view'] = $this->language->get('button_view');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('sale/order_history_update_list.tpl', $data));
    }

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] =  $this->language->get('text_add');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

        $data['entry_order_id'] = $this->language->get('entry_order_id');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_notify'] = $this->language->get('entry_notify');
        $data['entry_comment'] = $this->language->get('entry_comment');
        $data['entry_status'] = $this->language->get('entry_status');


		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/order_history', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

        $data['action'] = $this->url->link('sale/order_history', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['cancel'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/order_history_form.tpl', $data));
	}

	function addHistory($data) {
        $this->load->language('sale/order');
        $this->load->model('sale/order');

        unset($this->session->data['cookie']);
        $this->error = array();

        $order_id = isset($data['order_id']) ? (int) $data['order_id'] : 0;

        if($order_id) {
            $order_info = $this->model_sale_order->getOrder($order_id);
        } else {
            $order_info = null;
        }

        if ($order_info && $this->validate($order_info, $data)) {

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

                $result = curl_exec($curl);

                if (!$result) {
                    $this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
                } else {
                    $response = json_decode($result, true);

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
            curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/order/history&order_id=' . $data['order_id']);

            $data['user_id'] = $this->user->getId();

            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

            curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $this->session->data['cookie'] . ';');

            $result = curl_exec($curl);

            if (!$result) {
                $this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
            } else {
                $response = json_decode($result, true);

                curl_close($curl);

                if (isset($response['error'])) {
                    $this->error['warning'] = $response['error'];
                }
            }
        }

        $result = array('order_id' => $order_id);

        $result['assignee'] = isset($order_info['assignee']) ? $order_info['assignee'] : '';
        $result['old_status'] = isset($order_info['order_status']) ? $order_info['order_status'] : '';

        if (isset($response['error'])) {
            $result['type'] = 'error';
            $result['message'] = $response['error'];
        } elseif ($this->error) {
            $result['type'] = 'error';
            $result['message'] = $this->error['warning'];
        } elseif (isset($response['success'])) {
            $result['type'] = 'success';
            $result['message'] = $response['success'];
        } else {
            $result['type'] = 'error';
            $result['message'] = 'Unexpected Error';
        }

        return $result;

    }

    protected function validate($order_info, $data) {
        if (!$this->user->hasPermission('modify', 'sale/order')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $order_status_id = isset($data['order_status_id']) ? (int) $data['order_status_id'] : null;

        if($order_status_id && $this->model_sale_order->getTotalOrderHistories($data['order_id'], $order_status_id)) {
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