<?php
class ControllerSettingComponent extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('setting/component');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/component');

		$this->getList();
	}

	public function add() {
		$this->load->language('setting/component');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/component');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_setting_component->addComponent($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('setting/component', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('setting/component');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/component');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_setting_component->editComponent($this->request->get['component_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('setting/component', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('setting/component');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/component');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $component_id) {
				$this->model_setting_component->deleteComponent($component_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('setting/component', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
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
			'href' => $this->url->link('setting/component', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('setting/component/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('setting/component/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['repair'] = $this->url->link('setting/component/repair', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['components'] = array();

		$category_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$component_total = $this->model_setting_component->getTotalComponents();

		$results = $this->model_setting_component->getComponents($category_data);

		foreach ($results as $result) {
			$data['components'][] = array(
				'component_id' => $result['component_id'],
				'name'        => $result['name'],
				'sort_order'  => $result['sort_order'],
				'edit'        => $this->url->link('setting/component/edit', 'token=' . $this->session->data['token'] . '&component_id=' . $result['component_id'] . $url, 'SSL'),
				'delete'      => $this->url->link('setting/component/delete', 'token=' . $this->session->data['token'] . '&component_id=' . $result['component_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_rebuild'] = $this->language->get('button_rebuild');

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

		$data['sort_name'] = $this->url->link('setting/component', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('setting/component', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $component_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('setting/component', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($component_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($component_total - $this->config->get('config_limit_admin'))) ? $component_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $component_total, ceil($component_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/component_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['component_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_depends_on'] = $this->language->get('entry_depends_on');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_thumb'] = $this->language->get('entry_thumb');
		$data['entry_excluded_product'] = $this->language->get('entry_excluded_product');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_is_required'] = $this->language->get('entry_is_required');
        $data['entry_filter_profile'] = $this->language->get('entry_filter_profile');

		$data['help_category'] = $this->language->get('help_category');

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
			$data['error_name'] = null;
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
			'href' => $this->url->link('setting/component', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['component_id'])) {
			$data['action'] = $this->url->link('setting/component/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('setting/component/edit', 'token=' . $this->session->data['token'] . '&component_id=' . $this->request->get['component_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('setting/component', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['component_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$component_info = $this->model_setting_component->getComponent($this->request->get['component_id']);
		}

		$data['token'] = $this->session->data['token'];


        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($component_info)) {
            $data['name'] = $component_info['name'];
        } else {
            $data['name'] = '';
        }

		if (isset($this->request->post['depends_on'])) {
			$data['depends_on'] = $this->request->post['depends_on'];
		} elseif (!empty($component_info)) {
			$data['depends_on'] = $component_info['depends_on'];
		} else {
			$data['depends_on'] = '';
		}

		if($data['depends_on']) {
            $depends = $this->model_setting_component->getComponent($data['depends_on']);
            $data['depends_on_label'] = $depends['name'];
        } else {
            $data['depends_on_label'] = '';
        }

		$this->load->model('catalog/category');

		if (isset($this->request->post['component_category'])) {
			$categories = $this->request->post['component_category'];
		} elseif (isset($this->request->get['component_id'])) {
			$categories = $this->model_setting_component->getComponentCategories($this->request->get['component_id']);
		} else {
			$categories = array();
		}

		$data['component_categories'] = array();

        foreach ($categories as $category_id) {
            $category_info = $this->model_catalog_category->getCategory($category_id);

            if ($category_info) {
                $data['component_categories'][] = array(
                    'category_id' => $category_info['category_id'],
                    'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
                );
            }
        }

        $this->load->model('catalog/product');

        if (isset($this->request->post['component_excluded_product'])) {
            $excluded_products = $this->request->post['component_excluded_product'];
        } elseif (isset($this->request->get['component_id'])) {
            $excluded_products = $this->model_setting_component->getComponentExcludeProducts($this->request->get['component_id']);
        } else {
            $excluded_products = array();
        }

        $data['excluded_products'] = array();

        foreach ($excluded_products as $product_id) {
            $product_info = $this->model_catalog_product->getProduct($product_id);
            if ($product_info) {
                $data['excluded_products'][] = array(
                    'product_id' => $product_info['product_id'],
                    'name' => $product_info['name']
                );
            }
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['thumb'])) {
            $data['thumb'] = $this->request->post['thumb'];
        } elseif (!empty($component_info)) {
            $data['thumb'] = $component_info['thumb'];
        } else {
            $data['thumb'] = '';
        }

        if (isset($this->request->post['thumb']) && is_file(DIR_IMAGE . $this->request->post['thumb'])) {
            $data['thumb_preview'] = $this->model_tool_image->resize($this->request->post['thumb'], 100, 100);
        } elseif (!empty($component_info) && is_file(DIR_IMAGE . $component_info['thumb'])) {
            $data['thumb_preview'] = $this->model_tool_image->resize($component_info['thumb'], 100, 100);
        } else {
            $data['thumb_preview'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($component_info)) {
			$data['sort_order'] = $component_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		if (isset($this->request->post['is_required'])) {
			$data['is_required'] = $this->request->post['is_required'];
		} elseif (!empty($component_info)) {
			$data['is_required'] = $component_info['is_required'];
		} else {
			$data['is_required'] = true;
		}

        if (isset($this->request->post['filter_profile_id'])) {
            $data['filter_profile_id'] = $this->request->post['filter_profile_id'];
        } elseif (!empty($component_info)) {
            $data['filter_profile_id'] = $component_info['filter_profile_id'];
        } else {
            $data['filter_profile_id'] = '';
        }

        if($data['filter_profile_id']) {
            $this->load->model('catalog/filter_profile');
            $result = $this->model_catalog_filter_profile->getFilterProfile($data['filter_profile_id']);
            $data['filter_profile'] =  $result['name'];
        } else {
            $data['filter_profile'] = '';
        }

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/component_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'setting/component')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
        if ((utf8_strlen($this->request->post['name']) < 2) || (utf8_strlen($this->request->post['name']) > 255)) {
            $this->error['name'] = $this->language->get('error_name');
        }
		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'setting/component')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateRepair() {
		if (!$this->user->hasPermission('modify', 'setting/component')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('setting/component');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_setting_component->getComponents($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'component_id' => $result['component_id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}