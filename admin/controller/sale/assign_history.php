<?php
class ControllerSaleAssignHistory extends Controller {

    public function index() {
        $this->load->language('sale/order');
        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_assignee'] = $this->language->get('column_assignee');
        $data['column_assign_by'] = $this->language->get('column_assign_by');
        $data['column_comment'] = $this->language->get('column_comment');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['histories'] = array();

        $this->load->model('sale/order');

        $results = $this->model_sale_order->getOrderAssignHistories($this->request->get['order_id'], ($page - 1) * 10, 10);

        foreach ($results as $result) {
            $data['histories'][] = array(
                'assignee' => $result['assignee'],
                'assign_by' => $result['assign_by'],
                'comment'    => nl2br($result['comment']),
                'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
            );
        }

        $history_total = $this->model_sale_order->getTotalOrderAssignHistories($this->request->get['order_id']);

        $pagination = new Pagination();
        $pagination->total = $history_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('sale/assign_history', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

        $this->response->setOutput($this->load->view('sale/assign_history.tpl', $data));
    }

    public function add() {
        $json = array();
        $this->load->language('sale/order');
        if($this->validate()) {
            $this->load->model("sale/order");
            $this->model_sale_order->addOrderAssignHistory($this->request->get['order_id'], $this->request->post);

            if($this->request->post['order_status_id']) {
                $this->request->get['api'] = "api/order/history";
                $this->request->post["user_id"] = $this->user->getId();
                $this->request->post["comment"] = "";
                $this->request->post["notify"] = isset($this->request->post["notify"]) ? 1 : 0;
                $this->load->controller("sale/order/api");
            }

            $json['success'] = $this->language->get("text_success_add_assign_history");
        } else {
            $json['error'] = $this->error['warning'];
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'sale/assign_history')) {
            $this->error['warning'] = $this->language->gert('error_permission');
        }

        return !$this->error;
    }
}