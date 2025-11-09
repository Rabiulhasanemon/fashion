<?php
class ControllerPaymentAamarpay extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('payment/aamarpay');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('aamarpay', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all_zones'] = $this->language->get('text_all_zones');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_live'] = $this->language->get('text_live');
        $data['text_test'] = $this->language->get('text_test');
        $data['text_fail'] = $this->language->get('text_fail');

        $data['enter_store_id'] = $this->language->get('enter_store_id');
        $data['entry_store_password'] = $this->language->get('entry_store_password');
        $data['entry_mode'] = $this->language->get('entry_mode');
        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_order_risk_status'] = $this->language->get('entry_order_risk_status');
        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $data['entry_emi'] = $this->language->get('entry_emi');
        $data['entry_online'] = $this->language->get('entry_online');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['aamarpay_merchant'])) {
            $data['error_aamarpay_merchant'] = $this->error['aamarpay_merchant'];
        } else {
            $data['error_aamarpay_merchant'] = '';
        }

        if (isset($this->error['aamarpay_password'])) {
            $data['error_aamarpay_password'] = $this->error['aamarpay_password'];
        } else {
            $data['error_aamarpay_password'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/aamarpay', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['action'] = $this->url->link('payment/aamarpay', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['aamarpay_merchant'])) {
            $data['aamarpay_merchant'] = $this->request->post['aamarpay_merchant'];
        } else {
            $data['aamarpay_merchant'] = $this->config->get('aamarpay_merchant');
        }

        if (isset($this->request->post['aamarpay_password'])) {
            $data['aamarpay_password'] = $this->request->post['aamarpay_password'];
        } else {
            $data['aamarpay_password'] = $this->config->get('aamarpay_password');
        }

        if (isset($this->request->post['aamarpay_mode'])) {
            $data['aamarpay_mode'] = $this->request->post['aamarpay_mode'];
        } else {
            $data['aamarpay_mode'] = $this->config->get('aamarpay_mode');
        }

        if (isset($this->request->post['aamarpay_total'])) {
            $data['aamarpay_total'] = $this->request->post['aamarpay_total'];
        } else {
            $data['aamarpay_total'] = $this->config->get('aamarpay_total');
        }

        if (isset($this->request->post['aamarpay_order_status_id'])) {
            $data['aamarpay_order_status_id'] = $this->request->post['aamarpay_order_status_id'];
        } else {
            $data['aamarpay_order_status_id'] = $this->config->get('aamarpay_order_status_id');
        }

        if (isset($this->request->post['aamarpay_order_risk_id'])) {
            $data['aamarpay_order_risk_id'] = $this->request->post['aamarpay_order_risk_id'];
        } else {
            $data['aamarpay_order_risk_id'] = $this->config->get('aamarpay_order_risk_id');
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['aamarpay_geo_zone_id'])) {
            $data['aamarpay_geo_zone_id'] = $this->request->post['aamarpay_geo_zone_id'];
        } else {
            $data['aamarpay_geo_zone_id'] = $this->config->get('aamarpay_geo_zone_id');
        }

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['aamarpay_emi'])) {
            $data['aamarpay_emi'] = $this->request->post['aamarpay_emi'];
        } else {
            $data['aamarpay_emi'] = $this->config->get('aamarpay_emi');
        }

        if (isset($this->request->post['aamarpay_online'])) {
            $data['aamarpay_online'] = $this->request->post['aamarpay_online'];
        } else {
            $data['aamarpay_online'] = $this->config->get('aamarpay_online');
        }

        if (isset($this->request->post['aamarpay_status'])) {
            $data['aamarpay_status'] = $this->request->post['aamarpay_status'];
        } else {
            $data['aamarpay_status'] = $this->config->get('aamarpay_status');
        }

        if (isset($this->request->post['aamarpay_sort_order'])) {
            $data['aamarpay_sort_order'] = $this->request->post['aamarpay_sort_order'];
        } else {
            $data['aamarpay_sort_order'] = $this->config->get('aamarpay_sort_order');
        }
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/aamarpay.tpl', $data));

    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'payment/aamarpay')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['aamarpay_merchant']) {
            $this->error['aamarpay_merchant'] = $this->language->get('error_merchant');
        }

        if (!$this->request->post['aamarpay_password']) {
            $this->error['aamarpay_password'] = $this->language->get('error_password');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
