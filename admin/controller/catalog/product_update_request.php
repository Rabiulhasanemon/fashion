<?php
class ControllerCatalogProductUpdateRequest extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/product_update_request');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_product'])) {
			$filter_product = $this->request->get['filter_product'];
		} else {
			$filter_product = null;
		}

		if (isset($this->request->get['filter_operator'])) {
			$filter_operator = $this->request->get['filter_operator'];
		} else {
            $filter_operator = null;
		}


		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'r.date_added';
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
		}



		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

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
			'href' => $this->url->link('catalog/product_update_request', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

        $data['delete'] = $this->url->link('catalog/product_update_request/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['approve'] = $this->url->link('catalog/product_update_request/approve', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['product_update_requests'] = array();

		$filter_data = array(
			'filter_product'    => $filter_product,
			'filter_date_added' => $filter_date_added,
			'filter_operator' => $filter_operator,
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);

		$stock_status_index = array();
		$this->load->model("catalog/product");
		$results = $this->model_catalog_product->getProductStockStatuses();
        foreach ($results as $result) {
            $stock_status_index[$result['stock_status_id']] = $result['name'];
        }
		$this->load->model("catalog/product_update_request");
		$product_update_request_total = $this->model_catalog_product_update_request->getTotalProductUpdateRequests($filter_data);
		$results = $this->model_catalog_product_update_request->getProductUpdateRequests($filter_data);
        $data['product_update_requests'] = array();
		foreach ($results as $result) {
		    $current_status = $result['status'] == false ? "Disabled" : ($result['quantity'] > 0 ? "In Stock" : $stock_status_index[$result['stock_status_id']]);
		    $new_status = $result['new_status'] !== null &&  $result['new_status'] == false  ? "Disabled" : ($result['new_stock_status_id']  ? $stock_status_index[$result['new_stock_status_id']] : "");
			$data['product_update_requests'][] = array(
				'product_update_request_id'  => $result['product_update_request_id'],
				'name'       => $result['name'],
				'operator'     => $result['operator_name'],
				'price'     => number_format($result['price']) ,
				'regular_price'     => number_format($result['regular_price']) ,
				'new_price'     => $result['new_price'] ? number_format($result['new_price']) : "",
				'new_regular_price'     => $result['new_regular_price'] ? number_format($result['new_regular_price']) : "",
				'status'     => $current_status,
				'new_status'     => $new_status,
				'sort_order'       => $result['sort_order'],
				'new_sort_order'       => $result['new_sort_order'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
				'approve'       => $this->url->link('catalog/product_update_request/approve', 'token=' . $this->session->data['token'] . '&product_update_request_id=' . $result['product_update_request_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_product'] = $this->language->get('column_product');
		$data['column_operator'] = $this->language->get('column_operator');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_new_status'] = $this->language->get('column_new_status');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_new_price'] = $this->language->get('column_new_price');
		$data['column_regular_price'] = $this->language->get('column_regular_price');
		$data['column_new_regular_price'] = $this->language->get('column_new_regular_price');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_new_sort_order'] = $this->language->get('column_new_sort_order');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_operator'] = $this->language->get('entry_operator');
		$data['entry_date_added'] = $this->language->get('entry_date_added');

		$data['button_approve'] = $this->language->get('button_approve');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_product'] = $this->url->link('catalog/product_update_request', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$data['sort_operator'] = $this->url->link('catalog/product_update_request', 'token=' . $this->session->data['token'] . '&sort=o.operator_name' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('catalog/product_update_request', 'token=' . $this->session->data['token'] . '&sort=r.date_added' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_operator=' . urlencode(html_entity_decode($this->request->get['filter_operator'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_update_request_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/product_update_request', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_update_request_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_update_request_total - $this->config->get('config_limit_admin'))) ? $product_update_request_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_update_request_total, ceil($product_update_request_total / $this->config->get('config_limit_admin')));

		$data['filter_product'] = $filter_product;
		$data['filter_operator'] = $filter_operator;
		$data['filter_date_added'] = $filter_date_added;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/product_update_request_list.tpl', $data));
	}

    public function delete() {
        $this->load->language('catalog/product_update_request');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product_update_request');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $product_update_request_id) {
                $this->model_catalog_product_update_request->deleteProductUpdateRequest($product_update_request_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_product'])) {
                $url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_operator'])) {
                $url .= '&filter_operator=' . urlencode(html_entity_decode($this->request->get['filter_operator'], ENT_QUOTES, 'UTF-8'));
            }


            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('catalog/product_update_request', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function approve() {
        $this->load->language('catalog/product_update_request');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product_update_request');

        $product_update_requests = array();

        if (isset($this->request->post['selected'])) {
            $product_update_requests = $this->request->post['selected'];
        } elseif (isset($this->request->get['product_update_request_id'])) {
            $product_update_requests[] = $this->request->get['product_update_request_id'];
        }

        if ($product_update_requests && $this->validateApprove()) {
            $this->model_catalog_product_update_request->approveRequests($product_update_requests);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_product'])) {
                $url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_operator'])) {
                $url .= '&filter_operator=' . urlencode(html_entity_decode($this->request->get['filter_operator'], ENT_QUOTES, 'UTF-8'));
            }


            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('catalog/product_update_request', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function validateApprove()
    {
        if (!$this->user->hasPermission('modify', 'catalog/product_update_request')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }


    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'catalog/product_update_request')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

}