<?php
class ControllerCatalogAttribute extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/attribute');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/attribute');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_attribute->addAttribute($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_profile_id'])) {
                $url .= '&filter_profile_id=' . $this->request->get['filter_profile_id'];
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

			$this->response->redirect($this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/attribute');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_attribute->editAttribute($this->request->get['attribute_group_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_profile_id'])) {
                $url .= '&filter_profile_id=' . $this->request->get['filter_profile_id'];
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

			$this->response->redirect($this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/attribute');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $attribute_group_id) {
				$this->model_catalog_attribute->deleteAttribute($attribute_group_id);
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

			$this->response->redirect($this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

    public function copy() {
        $this->load->language('catalog/attribute');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/attribute');

        if (isset($this->request->post['selected']) && $this->validateCopy()) {
            foreach ($this->request->post['selected'] as $product_id) {
                $this->model_catalog_attribute->copyAttribute($product_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_profile_id'])) {
                $url .= '&filter_profile_id=' . $this->request->get['filter_profile_id'];
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

            $this->response->redirect($this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

	protected function getList() {
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_profile_id'])) {
            $filter_profile_id = $this->request->get['filter_profile_id'];
        } else {
            $filter_profile_id = null;
        }
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'fgd.name';
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

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_profile_id'])) {
            $url .= '&filter_profile_id=' . $this->request->get['filter_profile_id'];
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
			'href' => $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('catalog/attribute/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/attribute/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['copy'] = $this->url->link('catalog/attribute/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['attributes'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
            'filter_name' => $filter_name,
            'filter_profile_id' => $filter_profile_id,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$attribute_total = $this->model_catalog_attribute->getTotalAttributeGroups($filter_data);

		$results = $this->model_catalog_attribute->getAttributeGroups($filter_data);

		foreach ($results as $result) {
			$data['attributes'][] = array(
				'attribute_group_id' => $result['attribute_group_id'],
				'name'            => $result['name'],
				'sort_order'      => $result['sort_order'],
				'edit'            => $this->url->link('catalog/attribute/edit', 'token=' . $this->session->data['token'] . '&attribute_group_id=' . $result['attribute_group_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_group'] = $this->language->get('column_group');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_profile'] = $this->language->get('entry_profile');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_copy'] = $this->language->get('button_copy');

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

		$data['sort_name'] = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . '&sort=fgd.name' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . '&sort=fg.sort_order' . $url, 'SSL');

		$url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_profile_id'])) {
            $url .= '&filter_profile_id=' . $this->request->get['filter_profile_id'];
        }

        if (isset($this->request->get['filter_profile_id'])) {
            $url .= '&filter_profile_id=' . $this->request->get['filter_profile_id'];
        }

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $attribute_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($attribute_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($attribute_total - $this->config->get('config_limit_admin'))) ? $attribute_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $attribute_total, ceil($attribute_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_profile_id'] = $filter_profile_id;
        $this->load->model('catalog/attribute_profile');
        $data['attribute_profiles'] = $this->model_catalog_attribute_profile->getAttributeProfiles();

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/attribute_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['attribute_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_none'] = $this->language->get('text_none');
		$data['entry_group'] = $this->language->get('entry_group');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_profile'] = $this->language->get('entry_profile');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_attribute_add'] = $this->language->get('button_attribute_add');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['group'])) {
			$data['error_group'] = $this->error['group'];
		} else {
			$data['error_group'] = array();
		}

		if (isset($this->error['attribute'])) {
			$data['error_attribute'] = $this->error['attribute'];
		} else {
			$data['error_attribute'] = array();
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

        if (isset($this->request->get['filter_profile_id'])) {
            $url .= '&filter_profile_id=' . $this->request->get['filter_profile_id'];
        }

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['attribute_group_id'])) {
			$data['action'] = $this->url->link('catalog/attribute/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/attribute/edit', 'token=' . $this->session->data['token'] . '&attribute_group_id=' . $this->request->get['attribute_group_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['attribute_group_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$attribute_group_info = $this->model_catalog_attribute->getAttributeGroup($this->request->get['attribute_group_id']);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['attribute_group_description'])) {
			$data['attribute_group_description'] = $this->request->post['attribute_group_description'];
		} elseif (isset($this->request->get['attribute_group_id'])) {
			$data['attribute_group_description'] = $this->model_catalog_attribute->getAttributeGroupDescriptions($this->request->get['attribute_group_id']);
		} else {
			$data['attribute_group_description'] = array();
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($attribute_group_info)) {
			$data['sort_order'] = $attribute_group_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

        if (isset($this->request->post['attribute_profile_id'])) {
            $data['attribute_profile_id'] = $this->request->post['attribute_profile_id'];
        } elseif (!empty($attribute_group_info)) {
            $data['attribute_profile_id'] = $attribute_group_info['attribute_profile_id'];
        } else {
            $data['attribute_profile_id'] = '';
        }

        if($data['attribute_profile_id']) {
            $this->load->model('catalog/attribute_profile');
            $result = $this->model_catalog_attribute_profile->getAttributeProfile($data['attribute_profile_id']);
            $data['attribute_profile'] =  $result['name'];
        } else {
            $data['attribute_profile'] = '';
        }
		if (isset($this->request->post['attribute'])) {
			$data['attributes'] = $this->request->post['attribute'];
		} elseif (isset($this->request->get['attribute_group_id'])) {
			$data['attributes'] = $this->model_catalog_attribute->getAttributeDescriptions($this->request->get['attribute_group_id']);
		} else {
			$data['attributes'] = array();
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/attribute_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/attribute')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['attribute_group_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 64)) {
				$this->error['group'][$language_id] = $this->language->get('error_group');
			}
		}

		if (isset($this->request->post['attribute'])) {
			foreach ($this->request->post['attribute'] as $attribute_id => $attribute) {
				foreach ($attribute['attribute_description'] as $language_id => $attribute_description) {
					if ((utf8_strlen($attribute_description['name']) < 1) || (utf8_strlen($attribute_description['name']) > 64)) {
						$this->error['attribute'][$attribute_id][$language_id] = $this->language->get('error_name');
					}
				}
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/attribute')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

    protected function validateCopy() {
        if (!$this->user->hasPermission('modify', 'catalog/attribute')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['attribute_name'])) {
			$this->load->model('catalog/attribute');

			$attribute_data = array(
				'attribute_name' => $this->request->get['attribute_name'],
				'start'       => 0,
				'limit'       => 5
			);

			$attributes = $this->model_catalog_attribute->getAttributes($attribute_data);

			foreach ($attributes as $attribute) {
				$json[] = array(
					'attribute_id' => $attribute['attribute_id'],
					'name'      => strip_tags(html_entity_decode($attribute['group'] . ' &gt; ' . $attribute['name'], ENT_QUOTES, 'UTF-8'))
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