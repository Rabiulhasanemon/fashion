<?php
class ControllerCatalogFilter extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/filter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/filter');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/filter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/filter');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$result = $this->model_catalog_filter->addFilter($this->request->post);

			if ($result === false) {
				$this->error['warning'] = 'Error: Failed to add filter. Please check error logs.';
				error_log("Filter Add Failed - POST Data: " . print_r($this->request->post, true));
			} else {
				$this->session->data['success'] = $this->language->get('text_success');
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

			if (!isset($this->error['warning'])) {
				$this->response->redirect($this->url->link('catalog/filter', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/filter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/filter');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if (!isset($this->request->get['filter_group_id'])) {
				$this->error['warning'] = 'Error: Filter group ID is missing.';
			} else {
				$result = $this->model_catalog_filter->editFilter($this->request->get['filter_group_id'], $this->request->post);

				if ($result === false) {
					$this->error['warning'] = 'Error: Failed to edit filter. Please check error logs.';
					error_log("Filter Edit Failed - POST Data: " . print_r($this->request->post, true));
				} else {
					$this->session->data['success'] = $this->language->get('text_success');
				}
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

			if (!isset($this->error['warning'])) {
				$this->response->redirect($this->url->link('catalog/filter', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/filter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/filter');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $filter_group_id) {
				$this->model_catalog_filter->deleteFilter($filter_group_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

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

			$this->response->redirect($this->url->link('catalog/filter', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
			'href' => $this->url->link('catalog/filter', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('catalog/filter/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/filter/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['filters'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'filter_name' => $filter_name,
			'filter_profile_id' => $filter_profile_id,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$filter_total = $this->model_catalog_filter->getTotalFilterGroups($filter_data);

		$results = $this->model_catalog_filter->getFilterGroups($filter_data);

		foreach ($results as $result) {
			$data['filters'][] = array(
				'filter_group_id' => $result['filter_group_id'],
				'label'            => $result['label'],
				'name'            => $result['name'],
				'sort_order'      => $result['sort_order'],
				'edit'            => $this->url->link('catalog/filter/edit', 'token=' . $this->session->data['token'] . '&filter_group_id=' . $result['filter_group_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_group'] = $this->language->get('column_group');
		$data['column_label'] = $this->language->get('column_label');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_profile'] = $this->language->get('entry_profile');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
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
        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_profile_id'])) {
            $url .= '&filter_profile_id=' . $this->request->get['filter_profile_id'];
        }

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_label'] = $this->url->link('catalog/filter', 'token=' . $this->session->data['token'] . '&sort=fg.label' . $url, 'SSL');
		$data['sort_name'] = $this->url->link('catalog/filter', 'token=' . $this->session->data['token'] . '&sort=fgd.name' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('catalog/filter', 'token=' . $this->session->data['token'] . '&sort=fg.sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_profile_id'])) {
            $url .= '&filter_profile_id=' . $this->request->get['filter_profile_id'];
        }

		$pagination = new Pagination();
		$pagination->total = $filter_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/filter', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($filter_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($filter_total - $this->config->get('config_limit_admin'))) ? $filter_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $filter_total, ceil($filter_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_profile_id'] = $filter_profile_id;
		$this->load->model('catalog/filter_profile');
		$data['filter_profiles'] = $this->model_catalog_filter_profile->getFilterProfiles();
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/filter_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['filter_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_none'] = $this->language->get('text_none');
		$data['entry_group'] = $this->language->get('entry_group');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_label'] = $this->language->get('entry_label');
        $data['entry_profile'] = $this->language->get('entry_profile');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_filter_add'] = $this->language->get('button_filter_add');
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

        if (isset($this->error['label'])) {
            $data['error_label'] = $this->error['label'];
        } else {
            $data['error_label'] = '';
        }

		if (isset($this->error['filter'])) {
			$data['error_filter'] = $this->error['filter'];
		} else {
			$data['error_filter'] = array();
		}

		$url = '';

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
			'href' => $this->url->link('catalog/filter', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['filter_group_id'])) {
			$data['action'] = $this->url->link('catalog/filter/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/filter/edit', 'token=' . $this->session->data['token'] . '&filter_group_id=' . $this->request->get['filter_group_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/filter', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['filter_group_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$filter_group_info = $this->model_catalog_filter->getFilterGroup($this->request->get['filter_group_id']);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['filter_group_description'])) {
			$data['filter_group_description'] = $this->request->post['filter_group_description'];
		} elseif (isset($this->request->get['filter_group_id'])) {
			$data['filter_group_description'] = $this->model_catalog_filter->getFilterGroupDescriptions($this->request->get['filter_group_id']);
		} else {
			$data['filter_group_description'] = array();
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($filter_group_info)) {
			$data['sort_order'] = $filter_group_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		if (isset($this->request->post['label'])) {
			$data['label'] = $this->request->post['label'];
		} elseif (!empty($filter_group_info)) {
			$data['label'] = $filter_group_info['label'];
		} else {
			$data['label'] = '';
		}

        $this->load->model('catalog/filter_profile');

        if (isset($this->request->post['group_filter_profile'])) {
            $group_filter_profiles = $this->request->post['group_filter_profile'];
        } elseif (isset($this->request->get['filter_group_id'])) {
            $group_filter_profiles = $this->model_catalog_filter->getGroupFilterProfiles($this->request->get['filter_group_id']);
        } else {
            $group_filter_profiles = array();
        }

        $data['group_filter_profiles'] = array();

        foreach ($group_filter_profiles as $group_filter_profile_id) {
            $group_filter_profile_info = $this->model_catalog_filter_profile->getFilterProfile($group_filter_profile_id);

            if ($group_filter_profile_info) {
                $data['group_filter_profiles'][] = array(
                    'filter_profile_id' => $group_filter_profile_info['filter_profile_id'],
                    'name' => $group_filter_profile_info['name']
                );
            }
        }

		if (isset($this->request->post['filter'])) {
			$data['filters'] = $this->request->post['filter'];
		} elseif (isset($this->request->get['filter_group_id'])) {
			$data['filters'] = $this->model_catalog_filter->getFilterDescriptions($this->request->get['filter_group_id']);
		} else {
			$data['filters'] = array();
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/filter_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/filter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		// Check if label exists and is valid
		if (!isset($this->request->post['label']) || empty($this->request->post['label'])) {
			$this->error['label'] = $this->language->get('error_label');
		} elseif ((utf8_strlen($this->request->post['label']) < 3) || (utf8_strlen($this->request->post['label']) > 120)) {
			$this->error['label'] = $this->language->get('error_label');
		} elseif ($this->request->post['label'] && ($filter_profile = $this->model_catalog_filter->getFilterGroupByLabel($this->request->post['label'])) && (!isset($this->request->get['filter_group_id']) || $filter_profile['filter_group_id'] != $this->request->get['filter_group_id'])) {
			$this->error['label'] = $this->language->get('error_label');
		}

		// Check filter group description
		if (!isset($this->request->post['filter_group_description']) || !is_array($this->request->post['filter_group_description'])) {
			$this->error['warning'] = $this->language->get('error_group');
		} else {
			foreach ($this->request->post['filter_group_description'] as $language_id => $value) {
				if (!isset($value['name']) || (utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 100)) {
					$this->error['group'][$language_id] = $this->language->get('error_group');
				}
			}
		}

		// Validate filters if provided
		if (isset($this->request->post['filter']) && is_array($this->request->post['filter'])) {
			foreach ($this->request->post['filter'] as $filter_id => $filter) {
				if (isset($filter['filter_description']) && is_array($filter['filter_description'])) {
					foreach ($filter['filter_description'] as $language_id => $filter_description) {
						if (!isset($filter_description['name']) || (utf8_strlen($filter_description['name']) < 1) || (utf8_strlen($filter_description['name']) > 64)) {
							$this->error['filter'][$filter_id][$language_id] = $this->language->get('error_name');
						}
					}
				}
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/filter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/filter');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);

			$filters = $this->model_catalog_filter->getFilters($filter_data);

			foreach ($filters as $filter) {
				$json[] = array(
					'filter_id' => $filter['filter_id'],
					'name'      => strip_tags(html_entity_decode($filter['group'] . ' &gt; ' . $filter['name'], ENT_QUOTES, 'UTF-8'))
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