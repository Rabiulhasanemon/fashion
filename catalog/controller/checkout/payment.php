<?php
class ControllerCheckoutPayment extends Controller {
    public $error = array();

    public function index() {
        $this->load->language('checkout/payment');
        $this->load->model("checkout/order");
        $this->load->model("checkout/payment");

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_form()) {
            $this->cart->clear();
            $this->session->data["order_id"] = $this->request->post['order_id'];
            $this->session->data['payment_method']['code'] = $this->request->post["payment_method"];
            $this->response->redirect($this->url->link('payment/' . $this->request->post["payment_method"] . "/confirm"));
        }
        $this->getForm();
    }

    public function getForm() {
        $this->document->setTitle($this->language->get('heading_title'));

        $data["heading_title"] = $this->language->get('heading_title');
        $data["entry_order_id"] = $this->language->get('entry_order_id');
        $data["entry_name"] = $this->language->get('entry_name');
        $data["entry_telephone"] = $this->language->get('entry_telephone');
        $data["entry_due"] = $this->language->get('entry_due');
        $data["entry_payment_method"] = $this->language->get('entry_payment_method');

        $data["button_pay"] = $this->language->get('button_pay');

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } elseif (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['order_id'])) {
            $data['error_order_id'] = $this->error['order_id'];
        } else {
            $data['error_order_id'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('checkout/payment', '', true)
        );


        $order_info = isset($this->request->get["order_id"]) ? $this->model_checkout_order->getOrder($this->request->get["order_id"]) : null;

        if(!$order_info && isset($this->request->get["orderid"]) ) {
            $order_info = $this->model_checkout_order->getOrder($this->request->get["orderid"]);
        }

        if($order_info) {
            $amount_paid = $this->model_checkout_payment->getAmountPaid($order_info['order_id']);
            $due = $order_info['total'] - $amount_paid;
            $isEMICart = $order_info['emi'];
            $data["name"] = $order_info['firstname'] . ' ' . $order_info['lastname'];
            $data["telephone"] = $order_info['telephone'];
            $data["due"] = $due;
            $data["totals"] = $order_info['totals'];
            $data["order_id"] = $order_info["order_id"];
        } else {
            $this->response->redirect("/");
        }

        // Payment Methods
        $method_data = array();

        $this->load->model('extension/extension');

        $results = $this->model_extension_extension->getExtensions('payment');

        $address = array(
            "zone_id" => $order_info['payment_zone_id'],
            "country_id" => $order_info['payment_country_id']
        );

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status') && $result['code'] !== 'cod') {
                $this->load->model('payment/' . $result['code']);

                $method = $this->{'model_payment_' . $result['code']}->getMethod($address, $due, $isEMICart);

                if (!$method) continue;

                if(isset($method["methods"])) {
                    foreach ( $method["methods"] as $value) {
                        $method_data[$value['code']] = $value;
                    }
                } else {
                    $method_data[$result['code']] = $method;
                }
            }
        }

        $sort_order = array();

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);

        $data["payment_methods"] = $method_data;

        $data["action"] = $this->url->link('checkout/payment', 'order_id=' . ($order_info? $order_info["order_id"] : "") , 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/payment.tpl', $data));

    }

    private function validate_form() {
        $this->load->model("checkout/order");
        $order_info = isset($this->request->post['order_id']) ? $this->model_checkout_order->getOrder($this->request->post["order_id"]) : null ;

        if (!$order_info) {
            $this->error['order_id'] = $this->language->get('error_order_id');
        } elseif ($order_info["total"] <= $this->model_checkout_payment->getAmountPaid($order_info['order_id'])) {
            $this->error['warning'] = $this->language->get('error_due');
        } elseif (!in_array($order_info['order_status_id'], $this->config->get('config_processing_status'))) {
            $this->error['warning'] = $this->language->get('error_occurred');
        }

        return !$this->error;

    }
}