<?php
class ControllerAccountPc extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/pc', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/pc');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/pc', $url, 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_empty'] = $this->language->get('text_empty');

		$data['column_pc_id'] = $this->language->get('column_pc_id');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_description'] = $this->language->get('column_description');
		$data['column_date_added'] = $this->language->get('column_date_added');

		$data['button_view'] = $this->language->get('button_view');
		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['pcs'] = array();

		$this->load->model('tool/pc_builder');

		$pc_total = $this->model_tool_pc_builder->getTotalPc();

		$results = $this->model_tool_pc_builder->getPcs(($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['pcs'][] = array(
				'pc_id'   => $result['pc_id'],
				'name'       => $result["name"],
				'description'     => $result['description'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
				'href'       => $this->url->link('tool/pc_builder/info', 'pc_id=' . $result['pc_id'], 'SSL'),
				'delete'       => $this->url->link('account/pc/delete', 'pc_id=' . $result['pc_id'], 'SSL'),
			);
		}

		$pagination = new Pagination();
		$pagination->total = $pc_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/pc', 'page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($pc_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($pc_total - 10)) ? $pc_total : ((($page - 1) * 10) + 10), $pc_total, ceil($pc_total / 10));

		$data['continue'] = $this->url->link('account/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/pc_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/pc_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/pc_list.tpl', $data));
		}
	}

    public function delete() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/pc', '', 'SSL');
            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }
        $this->load->model("tool/pc_builder");
        if($this->request->get['pc_id']) {
            $pc_info = $this->model_tool_pc_builder->getPc((int) $this->request->get['pc_id']);
        } else {
            $pc_info = null;
        }
        if($pc_info && $pc_info['customer_id'] == $this->customer->getId()) {
            $this->model_tool_pc_builder->deletePc((int) $this->request->get['pc_id']);
        }
        $this->response->redirect($this->url->link('account/pc', '', 'SSL'));

    }
}