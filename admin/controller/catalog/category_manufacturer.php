<?php
class ControllerCatalogCategoryManufacturer extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category_manufacturer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category_manufacturer');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/category_manufacturer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category_manufacturer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_category_manufacturer->addCategoryManufacturer($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
            if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
            }

            if (isset($this->request->get['filter_manufacturer_id'])) {
                $url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
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

			$this->response->redirect($this->url->link('catalog/category_manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/category_manufacturer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category_manufacturer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_category_manufacturer->editCategoryManufacturer($this->request->get['category_manufacturer_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
            if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
            }

            if (isset($this->request->get['filter_manufacturer_id'])) {
                $url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
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

			$this->response->redirect($this->url->link('catalog/category_manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/category_manufacturer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category_manufacturer');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $category_manufacturer_id) {
				$this->model_catalog_category_manufacturer->deleteCategoryManufacturer($category_manufacturer_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
            if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
            }

            if (isset($this->request->get['filter_manufacturer_id'])) {
                $url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
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

			$this->response->redirect($this->url->link('catalog/category_manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
        if (isset($this->request->get['filter_category_id'])) {
            $filter_category_id = $this->request->get['filter_category_id'];
        } else {
            $filter_category_id = null;
        }

        if (isset($this->request->get['filter_manufacturer_id'])) {
            $filter_manufacturer_id = $this->request->get['filter_manufacturer_id'];
        } else {
            $filter_manufacturer_id = null;
        }

        
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
        
        if (isset($this->request->get['filter_category_id'])) {
            $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
        }
        
        if (isset($this->request->get['filter_manufacturer_id'])) {
            $url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
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
			'href' => $this->url->link('catalog/category_manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('catalog/category_manufacturer/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/category_manufacturer/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['category_manufacturers'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'filter_category_id'  => $filter_category_id,
			'filter_manufacturer_id'  => $filter_manufacturer_id,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$category_manufacturer_total = $this->model_catalog_category_manufacturer->getTotalCategoryManufacturers();

		$results = $this->model_catalog_category_manufacturer->getCategoryManufacturers($filter_data);

		foreach ($results as $result) {
			$data['category_manufacturers'][] = array(
				'category_manufacturer_id' => $result['category_manufacturer_id'],
				'category'            => $result['category'],
				'manufacturer'      => $result['manufacturer'],
				'edit'            => $this->url->link('catalog/category_manufacturer/edit', 'token=' . $this->session->data['token'] . '&category_manufacturer_id=' . $result['category_manufacturer_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_none'] = $this->language->get('text_none');

		$data['column_category'] = $this->language->get('column_category');
		$data['column_manufacturer'] = $this->language->get('column_manufacturer');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');

		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_manufacturer'] = $this->language->get('entry_manufacturer');

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
		
        if (isset($this->request->get['filter_category_id'])) {
            $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
        }

        if (isset($this->request->get['filter_manufacturer_id'])) {
            $url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
        }
        
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_category'] = $this->url->link('catalog/category_manufacturer', 'token=' . $this->session->data['token'] . '&sort=category' . $url, 'SSL');
		$data['sort_manufacturer'] = $this->url->link('catalog/category_manufacturer', 'token=' . $this->session->data['token'] . '&sort=manufacturer' . $url, 'SSL');

		$url = '';
		
        if (isset($this->request->get['filter_category_id'])) {
            $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
        }

        if (isset($this->request->get['filter_manufacturer_id'])) {
            $url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
        }
        
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $category_manufacturer_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/category_manufacturer', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($category_manufacturer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($category_manufacturer_total - $this->config->get('config_limit_admin'))) ? $category_manufacturer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $category_manufacturer_total, ceil($category_manufacturer_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

        $data['filter_category_id'] = $filter_category_id;
        if($filter_category_id) {
            $this->load->model("catalog/category");
            $filter_category_info = $this->model_catalog_category->getCategory($filter_category_id);
            $data["filter_category_name"] = $filter_category_info['name'];
        } else {
            $data["filter_category_name"] = "";
        }

        $data['filter_manufacturer_id'] = $filter_manufacturer_id;
        if($filter_manufacturer_id) {
            $this->load->model("catalog/manufacturer");
            $filter_manufacturer_info = $this->model_catalog_manufacturer->getmanufacturer($filter_manufacturer_id);
            $data["filter_manufacturer_name"] = $filter_manufacturer_info['name'];
        } else {
            $data["filter_manufacturer_name"] = "";
        }
        
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/category_manufacturer_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['category_manufacturer_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_none'] = $this->language->get('text_none');

        $data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_meta_title'] = $this->language->get('entry_meta_title');
        $data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_filter_profile'] = $this->language->get('entry_filter_profile');
        $data['entry_top'] = $this->language->get('entry_top');

        $data['help_top'] = $this->language->get('help_top');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_data'] = $this->language->get('tab_data');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

        if (isset($this->error['category'])) {
            $data['error_category'] = $this->error['category'];
        } else {
            $data['error_category'] = '';
        }
        
        if (isset($this->error['manufacturer'])) {
			$data['error_manufacturer'] = $this->error['manufacturer'];
		} else {
			$data['error_manufacturer'] = '';
		}

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = array();
        }

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = array();
        }

		$url = '';
        if (isset($this->request->get['filter_category_id'])) {
            $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
        }

        if (isset($this->request->get['filter_manufacturer_id'])) {
            $url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
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
			'href' => $this->url->link('catalog/category_manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['category_manufacturer_id'])) {
			$data['action'] = $this->url->link('catalog/category_manufacturer/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/category_manufacturer/edit', 'token=' . $this->session->data['token'] . '&category_manufacturer_id=' . $this->request->get['category_manufacturer_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/category_manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['category_manufacturer_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$category_manufacturer_info = $this->model_catalog_category_manufacturer->getCategoryManufacturer($this->request->get['category_manufacturer_id']);
		}

		$data['token'] = $this->session->data['token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['category_manufacturer_description'])) {
            $data['category_manufacturer_description'] = $this->request->post['category_manufacturer_description'];
        } elseif (isset($this->request->get['category_manufacturer_id'])) {
            $data['category_manufacturer_description'] = $this->model_catalog_category_manufacturer->getCategoryManufacturerDescriptions($this->request->get['category_manufacturer_id']);
        } else {
            $data['category_manufacturer_description'] = array();
        }

		if (isset($this->request->post['category_name'])) {
			$data['category_name'] = $this->request->post['category_name'];
		} elseif (!empty($category_manufacturer_info)) {
			$data['category_name'] = $category_manufacturer_info['category'];
		} else {
			$data['category_name'] = '';
		}

        if (isset($this->request->post['category_id'])) {
            $data['category_id'] = $this->request->post['category_id'];
        } elseif (!empty($category_manufacturer_info)) {
            $data['category_id'] = $category_manufacturer_info['category_id'];
        } else {
            $data['category_id'] = '';
        }

        if (isset($this->request->post['manufacturer_name'])) {
            $data['manufacturer_name'] = $this->request->post['manufacturer_name'];
        } elseif (!empty($category_manufacturer_info)) {
            $data['manufacturer_name'] = $category_manufacturer_info['manufacturer'];
        } else {
            $data['manufacturer_name'] = '';
        }

        if (isset($this->request->post['manufacturer_id'])) {
            $data['manufacturer_id'] = $this->request->post['manufacturer_id'];
        } elseif (!empty($category_manufacturer_info)) {
            $data['manufacturer_id'] = $category_manufacturer_info['manufacturer_id'];
        } else {
            $data['manufacturer_id'] = '';
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($category_manufacturer_info)) {
            $data['sort_order'] = $category_manufacturer_info['sort_order'];
        } else {
            $data['sort_order'] = 0;
        }

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($category_manufacturer_info)) {
			$data['image'] = $category_manufacturer_info['image'];
		} else {
			$data['image'] = '';
		}

        if (isset($this->request->post['filter_profile_id'])) {
            $data['filter_profile_id'] = $this->request->post['filter_profile_id'];
        } elseif (!empty($category_manufacturer_info)) {
            $data['filter_profile_id'] = $category_manufacturer_info['filter_profile_id'];
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

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($category_manufacturer_info) && is_file(DIR_IMAGE . $category_manufacturer_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($category_manufacturer_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['top'])) {
            $data['top'] = $this->request->post['top'];
        } elseif (!empty($category_manufacturer_info)) {
            $data['top'] = $category_manufacturer_info['top'];
        } else {
            $data['top'] = 0;
        }


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/category_manufacturer_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/category_manufacturer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!((int) $this->request->post['category_id'])) {
            $this->error['category'] = $this->language->get('error_category');
        }

        if (!((int)$this->request->post['manufacturer_id'])) {
            $this->error['manufacturer'] = $this->language->get('error_manufacturer');
        }

        if (!$this->error) {
            $sql = "select * from " . DB_PREFIX . "category_manufacturer where category_id = '".(int) $this->request->post['category_id'] . "' AND manufacturer_id = '".(int) $this->request->post['manufacturer_id'] . "'";
            if(isset($this->request->get['category_manufacturer_id'])) {
                $sql .= " AND category_manufacturer_id != ' " . (int) $this->request->get['category_manufacturer_id'] . "'";
            }
            if($this->db->query($sql)->row) {
                $this->error['warning'] = $this->language->get('error_category_manufacturer');
            }
        }

        foreach ($this->request->post['category_manufacturer_description'] as $language_id => $value) {
            if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
                $this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
            }

            if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 32)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }
		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/category_manufacturer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/category_manufacturer');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_catalog_category_manufacturer->getCategoryManufacturers($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'category_manufacturer_id' => $result['category_manufacturer_id'],
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
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