<?php
class ControllerCatalogCategory extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			// Debug: Log what we're receiving
			error_log('Category Add - POST data received');
			error_log('Raw $_POST keys: ' . implode(', ', array_keys($_POST)));
			error_log('$this->request->post keys: ' . implode(', ', array_keys($this->request->post)));
			error_log('category_module in $_POST: ' . (isset($_POST['category_module']) ? 'YES' : 'NO'));
			error_log('category_module in $this->request->post: ' . (isset($this->request->post['category_module']) ? 'YES' : 'NO'));
			if (isset($this->request->post['category_module'])) {
				error_log('category_module data: ' . print_r($this->request->post['category_module'], true));
			} elseif (isset($_POST['category_module'])) {
				error_log('category_module in raw $_POST: ' . print_r($_POST['category_module'], true));
			}
			
            $category_id = $this->model_catalog_category->addCategory($this->request->post);

            // Add to activity log
            $this->load->model('user/user');

            $activity_data = array(
                '%user_id' => $this->user->getId(),
                '%category_id' => $category_id,
                '%name'        => $this->user->getFirstName() . ' ' . $this->user->getLastName()
            );

            $this->model_user_user->addActivity($this->user->getId(), 'add_category', $activity_data, $category_id);

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

			$this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			// Debug: Log what we're receiving
			$log_file = DIR_LOGS . 'category_module_debug.log';
			$log_msg = date('Y-m-d H:i:s') . " - Category Edit - POST data received for category_id: " . $this->request->get['category_id'] . "\n";
			$log_msg .= "Raw \$_POST keys: " . implode(', ', array_keys($_POST)) . "\n";
			$log_msg .= "\$this->request->post keys: " . implode(', ', array_keys($this->request->post)) . "\n";
			$log_msg .= "category_module in \$_POST: " . (isset($_POST['category_module']) ? 'YES' : 'NO') . "\n";
			$log_msg .= "category_module in \$this->request->post: " . (isset($this->request->post['category_module']) ? 'YES' : 'NO') . "\n";
			if (isset($this->request->post['category_module'])) {
				$log_msg .= "category_module data: " . print_r($this->request->post['category_module'], true) . "\n";
			} elseif (isset($_POST['category_module'])) {
				$log_msg .= "category_module in raw \$_POST: " . print_r($_POST['category_module'], true) . "\n";
			}
			$log_msg .= "---\n";
			file_put_contents($log_file, $log_msg, FILE_APPEND);
			
			// Also use error_log
			error_log('Category Edit - POST data received for category_id: ' . $this->request->get['category_id']);
			error_log('Raw $_POST keys: ' . implode(', ', array_keys($_POST)));
			error_log('$this->request->post keys: ' . implode(', ', array_keys($this->request->post)));
			error_log('category_module in $_POST: ' . (isset($_POST['category_module']) ? 'YES' : 'NO'));
			error_log('category_module in $this->request->post: ' . (isset($this->request->post['category_module']) ? 'YES' : 'NO'));
			if (isset($this->request->post['category_module'])) {
				error_log('category_module data: ' . print_r($this->request->post['category_module'], true));
			} elseif (isset($_POST['category_module'])) {
				error_log('category_module in raw $_POST: ' . print_r($_POST['category_module'], true));
			}
			
			$this->model_catalog_category->editCategory($this->request->get['category_id'], $this->request->post);

            // Add to activity log
            $this->load->model('user/user');

            $activity_data = array(
                '%user_id' => $this->user->getId(),
                '%category_id' => $this->request->get['category_id'],
                '%name'        => $this->user->getFirstName() . ' ' . $this->user->getLastName()
            );

            $this->model_user_user->addActivity($this->user->getId(), 'edit_category', $activity_data, $this->request->get['category_id']);

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

			$this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $category_id) {
				$this->model_catalog_category->deleteCategory($category_id);

                $this->load->model('user/user');
                // Add to activity log
                $activity_data = array(
                    '%user_id' => $this->user->getId(),
                    '%category_id' => $category_id,
                    '%name'        => $this->user->getFirstName() . ' ' . $this->user->getLastName()
                );

                $this->model_user_user->addActivity($this->user->getId(), 'delete_category', $activity_data, $category_id);
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

			$this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function repair() {
		$this->load->language('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category');

		if ($this->validateRepair()) {
			$this->model_catalog_category->repairCategories();

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL'));
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
			'href' => $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('catalog/category/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['import'] = $this->url->link('catalog/category/import', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/category/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['repair'] = $this->url->link('catalog/category/repair', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['categories'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$category_total = $this->model_catalog_category->getTotalCategories();

		$results = $this->model_catalog_category->getCategories($filter_data);

		foreach ($results as $result) {
			$data['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name'],
				'sort_order'  => $result['sort_order'],
				'edit'        => $this->url->link('catalog/category/edit', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'] . $url, 'SSL'),
				'delete'      => $this->url->link('catalog/category/delete', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'] . $url, 'SSL')
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
		$data['button_import'] = $this->language->get('button_import');

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

		$data['sort_name'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $category_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($category_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($category_total - $this->config->get('config_limit_admin'))) ? $category_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $category_total, ceil($category_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/category_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['category_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_blurb'] = $this->language->get('entry_blurb');
        $data['entry_intro'] = $this->language->get('entry_intro');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_parent'] = $this->language->get('entry_parent');
		$data['entry_filter_profile'] = $this->language->get('entry_filter_profile');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_thumb'] = $this->language->get('entry_thumb');
		$data['entry_icon'] = $this->language->get('entry_icon');
		$data['entry_top'] = $this->language->get('entry_top');
		$data['entry_column'] = $this->language->get('entry_column');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_view'] = $this->language->get('entry_view');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_layout'] = $this->language->get('entry_layout');

		$data['help_keyword'] = $this->language->get('help_keyword');
		$data['help_top'] = $this->language->get('help_top');
		$data['help_column'] = $this->language->get('help_column');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_design'] = $this->language->get('tab_design');

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

		if (isset($this->error['blurb'])) {
			$data['error_blurb'] = $this->error['blurb'];
		} else {
			$data['error_blurb'] = array();
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
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
			'href' => $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['category_id'])) {
			$data['action'] = $this->url->link('catalog/category/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/category/edit', 'token=' . $this->session->data['token'] . '&category_id=' . $this->request->get['category_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['category_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$category_info = $this->model_catalog_category->getCategory($this->request->get['category_id']);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['category_description'])) {
			$data['category_description'] = $this->request->post['category_description'];
		} elseif (isset($this->request->get['category_id'])) {
			$data['category_description'] = $this->model_catalog_category->getCategoryDescriptions($this->request->get['category_id']);
		} else {
			$data['category_description'] = array();
		}

		if (isset($this->request->post['path'])) {
			$data['path'] = $this->request->post['path'];
		} elseif (!empty($category_info)) {
			$data['path'] = $category_info['path'];
		} else {
			$data['path'] = '';
		}

		if (isset($this->request->post['parent_id'])) {
			$data['parent_id'] = $this->request->post['parent_id'];
		} elseif (!empty($category_info)) {
			$data['parent_id'] = $category_info['parent_id'];
		} else {
			$data['parent_id'] = 0;
		}

        if (isset($this->request->post['filter_profile_id'])) {
            $data['filter_profile_id'] = $this->request->post['filter_profile_id'];
        } elseif (!empty($category_info)) {
            $data['filter_profile_id'] = $category_info['filter_profile_id'];
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

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['category_store'])) {
			$data['category_store'] = $this->request->post['category_store'];
		} elseif (isset($this->request->get['category_id'])) {
			$data['category_store'] = $this->model_catalog_category->getCategoryStores($this->request->get['category_id']);
		} else {
			$data['category_store'] = array(0);
		}

		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($category_info)) {
			$data['keyword'] = $category_info['keyword'];
		} else {
			$data['keyword'] = '';
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($category_info)) {
			$data['image'] = $category_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['image_preview'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($category_info) && is_file(DIR_IMAGE . $category_info['image'])) {
			$data['image_preview'] = $this->model_tool_image->resize($category_info['image'], 100, 100);
		} else {
			$data['image_preview'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

        if (isset($this->request->post['thumb'])) {
            $data['thumb'] = $this->request->post['thumb'];
        } elseif (!empty($category_info)) {
            $data['thumb'] = $category_info['thumb'];
        } else {
            $data['thumb'] = '';
        }

        if (isset($this->request->post['thumb']) && is_file(DIR_IMAGE . $this->request->post['thumb'])) {
            $data['thumb_preview'] = $this->model_tool_image->resize($this->request->post['thumb'], 100, 100);
        } elseif (!empty($category_info) && is_file(DIR_IMAGE . $category_info['thumb'])) {
            $data['thumb_preview'] = $this->model_tool_image->resize($category_info['thumb'], 100, 100);
        } else {
            $data['thumb_preview'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (isset($this->request->post['icon'])) {
            $data['icon'] = $this->request->post['icon'];
        } elseif (!empty($category_info)) {
            $data['icon'] = $category_info['icon'];
        } else {
            $data['icon'] = '';
        }

        if (isset($this->request->post['icon']) && is_file(DIR_IMAGE . $this->request->post['icon'])) {
            $data['icon_preview'] = $this->model_tool_image->url($this->request->post['icon']);
        } elseif (!empty($category_info) && is_file(DIR_IMAGE . $category_info['icon'])) {
            $data['icon_preview'] = $this->model_tool_image->url($category_info['icon']);
        } else {
            $data['icon_preview'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }
        
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['top'])) {
			$data['top'] = $this->request->post['top'];
		} elseif (!empty($category_info)) {
			$data['top'] = $category_info['top'];
		} else {
			$data['top'] = 0;
		}

		if (isset($this->request->post['column'])) {
			$data['column'] = $this->request->post['column'];
		} elseif (!empty($category_info)) {
			$data['column'] = $category_info['column'];
		} else {
			$data['column'] = 1;
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($category_info)) {
			$data['sort_order'] = $category_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($category_info)) {
			$data['status'] = $category_info['status'];
		} else {
			$data['status'] = true;
		}

		if (isset($this->request->post['category_layout'])) {
			$data['category_layout'] = $this->request->post['category_layout'];
		} elseif (isset($this->request->get['category_id'])) {
			$data['category_layout'] = $this->model_catalog_category->getCategoryLayouts($this->request->get['category_id']);
		} else {
			$data['category_layout'] = array();
		}

        if (isset($this->request->post['view'])) {
            $data['view'] = $this->request->post['view'];
        } elseif (!empty($category_info)) {
            $data['view'] = $category_info['view'];
        } else {
            $data['view'] = '';
        }

		// Load available modules for category modules tab
		$this->load->model('extension/extension');
		$this->load->model('extension/module');
		
		$data['available_modules'] = array();
		$extensions = $this->model_extension_extension->getInstalled('module');
		
		foreach ($extensions as $code) {
			$this->load->language('module/' . $code);
			$modules = $this->model_extension_module->getModulesByCode($code);
			
			foreach ($modules as $module) {
				$data['available_modules'][] = array(
					'module_id' => $module['module_id'],
					'code' => $code,
					'name' => $this->language->get('heading_title') . ' - ' . $module['name']
				);
			}
			
			// Also add the module code itself if no instances exist
			if (empty($modules)) {
				$data['available_modules'][] = array(
					'module_id' => 0,
					'code' => $code,
					'name' => $this->language->get('heading_title')
				);
			}
		}
		
		// Load category modules if editing
		if (isset($this->request->get['category_id'])) {
			$data['category_modules'] = $this->model_catalog_category->getCategoryModules($this->request->get['category_id']);
		} elseif (isset($this->request->post['category_module'])) {
			$data['category_modules'] = $this->request->post['category_module'];
		} else {
			$data['category_modules'] = array();
		}

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/category_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['category_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}

            if (utf8_strlen($value['blurb']) > 255) {
                $this->error['blurb'][$language_id] = $this->language->get('error_blurb');
            }

			if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}
		}

		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['category_id']) && $url_alias_info['query'] != 'category_id=' . $this->request->get['category_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['category_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($this->error && !isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_warning');
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateRepair() {
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function import() {
		$this->load->language('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateImport()) {
			// Process CSV file
			$file = $this->request->files['import_file']['tmp_name'];
			
			if (($handle = fopen($file, "r")) !== FALSE) {
				// Get header row
				$headers = fgetcsv($handle, 1000, ",");
				
				// Map header names to indices
				$header_map = array();
				foreach ($headers as $index => $header) {
					$header_map[trim(strtolower($header))] = $index;
				}

				$success_count = 0;
				$error_count = 0;
				$errors = array();
				$row_num = 1;
				
				// Store imported categories for parent lookup: name => category_id
				$imported_categories = array();
				
				// Track used keywords to avoid duplicates during import session
				$used_keywords = array();

				// Get languages
				$this->load->model('localisation/language');
				$languages = $this->model_localisation_language->getLanguages();
				
				// Get default language ID
				$default_language_id = $this->config->get('config_language_id');
				
				// First pass: Import all parent categories
				rewind($handle);
				fgetcsv($handle, 1000, ","); // Skip header
				
				$first_pass_rows = array();
				while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
					$first_pass_rows[] = $data;
				}
				
				// Separate parent and child categories
				$parent_categories = array();
				$child_categories = array();
				
				foreach ($first_pass_rows as $idx => $row_data) {
					$getCol = function($name, $default = '') use ($row_data, $header_map) {
						$key = strtolower(trim($name));
						if (isset($header_map[$key]) && isset($row_data[$header_map[$key]])) {
							return trim($row_data[$header_map[$key]]);
						}
						return $default;
					};
					
					$parent = $getCol('parent');
					if (empty($parent)) {
						$parent_categories[] = $row_data;
					} else {
						$child_categories[] = array('parent' => $parent, 'data' => $row_data);
					}
				}
				
				// Import parent categories first
				foreach ($parent_categories as $row_data) {
					$row_num++;
					
					try {
						$category_data = array();
						
						// Get column helper function
						$getCol = function($name, $default = '') use ($row_data, $header_map) {
							$key = strtolower(trim($name));
							if (isset($header_map[$key]) && isset($row_data[$header_map[$key]])) {
								return trim($row_data[$header_map[$key]]);
							}
							return $default;
						};

						// Build category data
						$category_data['category_description'] = array();
						
						// Handle multilingual fields
						foreach ($languages as $lang_code => $lang_info) {
							$lang_id = $lang_info['language_id'];
							
							// Name - required
							$name = $getCol('name_' . $lang_code);
							if (empty($name)) {
								$name = $getCol('name'); // Fallback to generic name
							}
							
							if (empty($name)) {
								throw new Exception("Name is required for language: " . $lang_info['name']);
							}
							
							$category_data['category_description'][$lang_id] = array(
								'name' => $name,
								'blurb' => $getCol('blurb_' . $lang_code, $getCol('blurb')),
								'intro' => $getCol('intro_' . $lang_code, $getCol('intro')),
								'description' => $getCol('description_' . $lang_code, $getCol('description')),
								'meta_title' => $getCol('meta_title_' . $lang_code, $getCol('meta_title', $name)),
								'meta_description' => $getCol('meta_description_' . $lang_code, $getCol('meta_description')),
								'meta_keyword' => $getCol('meta_keyword_' . $lang_code, $getCol('meta_keyword'))
							);
						}

						// Parent category - for parent categories, this is empty
						$category_data['parent_id'] = 0;

						// Images - handle file paths or URLs
						$image_path = $getCol('image');
						if (!empty($image_path)) {
							$category_data['image'] = $this->processImage($image_path);
						}
						
						$thumb_path = $getCol('thumb');
						if (!empty($thumb_path)) {
							$category_data['thumb'] = $this->processImage($thumb_path);
						}
						
						$icon_path = $getCol('icon');
						if (!empty($icon_path)) {
							$category_data['icon'] = $this->processImage($icon_path);
						}

						// Other fields
						$category_data['status'] = strtolower($getCol('status', 'enabled')) == 'disabled' ? 0 : 1;
						
						// Top menu - always set to 1 (always checked/ticked)
						$category_data['top'] = 1;
						
						$category_data['column'] = (int)$getCol('column', 1);
						$category_data['sort_order'] = (int)$getCol('sort_order', 0);
						$category_data['view'] = $getCol('view', '');
						$category_data['filter_profile_id'] = (int)$getCol('filter_profile_id', 0);
						
						// SEO Keyword - always auto-generate from category name
						// Get category name first
						$name = '';
						foreach ($languages as $lang_code => $lang_info) {
							$name = $getCol('name_' . $lang_code);
							if (!empty($name)) {
								break;
							}
						}
						if (empty($name)) {
							$name = $getCol('name');
						}
						
						// Always generate keyword from category name
						if (!empty($name)) {
							// Convert name to URL-friendly keyword
							$keyword = strtolower(trim($name));
							$keyword = preg_replace('/[^a-z0-9]+/', '-', $keyword);
							$keyword = preg_replace('/^-+|-+$/', '', $keyword);
							
							// Ensure keyword is not empty
							if (empty($keyword)) {
								$keyword = 'category-' . time() . '-' . rand(1000, 9999);
							}
							
							// Check if keyword already exists in database and in current import session
							$this->load->model('catalog/url_alias');
							$counter = 1;
							$original_keyword = $keyword;
							
							// Check both database and current import session for duplicates
							while (isset($used_keywords[$keyword]) || $this->model_catalog_url_alias->getUrlAlias($keyword)) {
								$keyword = $original_keyword . '-' . $counter;
								$counter++;
							}
							
							// Mark keyword as used
							$used_keywords[$keyword] = true;
							$category_data['keyword'] = $keyword;
						} else {
							// Last resort: generate random keyword
							$keyword = 'category-' . time() . '-' . rand(1000, 9999);
							$category_data['keyword'] = $keyword;
						}

						// Stores - default to store 0
						$stores = $getCol('stores', '0');
						$category_data['category_store'] = array();
						foreach (explode(',', $stores) as $store_id) {
							$store_id = trim($store_id);
							if (is_numeric($store_id)) {
								$category_data['category_store'][] = (int)$store_id;
							}
						}
						if (empty($category_data['category_store'])) {
							$category_data['category_store'] = array(0);
						}

						// Validate form data
						if ($this->validateFormData($category_data)) {
							$category_id = $this->model_catalog_category->addCategory($category_data);
							
							// Store category name for later parent lookup
							foreach ($languages as $lang_code => $lang_info) {
								$name = $getCol('name_' . $lang_code);
								if (empty($name)) {
									$name = $getCol('name');
								}
								if (!empty($name)) {
									$imported_categories[strtolower(trim($name))] = $category_id;
									break; // Use first language name
								}
							}
							
							$success_count++;
						} else {
							throw new Exception("Validation failed: " . implode(", ", $this->error));
						}
						
					} catch (Exception $e) {
						$error_count++;
						$errors[] = "Row $row_num: " . $e->getMessage();
					}
				}
				
				// Second pass: Import child categories
				foreach ($child_categories as $child_info) {
					$row_num++;
					$parent_name = $child_info['parent'];
					$row_data = $child_info['data'];
					
					try {
						$category_data = array();
						
						// Get column helper function
						$getCol = function($name, $default = '') use ($row_data, $header_map) {
							$key = strtolower(trim($name));
							if (isset($header_map[$key]) && isset($row_data[$header_map[$key]])) {
								return trim($row_data[$header_map[$key]]);
							}
							return $default;
						};

						// Build category data
						$category_data['category_description'] = array();
						
						// Handle multilingual fields
						foreach ($languages as $lang_code => $lang_info) {
							$lang_id = $lang_info['language_id'];
							
							// Name - required
							$name = $getCol('name_' . $lang_code);
							if (empty($name)) {
								$name = $getCol('name'); // Fallback to generic name
							}
							
							if (empty($name)) {
								throw new Exception("Name is required for language: " . $lang_info['name']);
							}
							
							$category_data['category_description'][$lang_id] = array(
								'name' => $name,
								'blurb' => $getCol('blurb_' . $lang_code, $getCol('blurb')),
								'intro' => $getCol('intro_' . $lang_code, $getCol('intro')),
								'description' => $getCol('description_' . $lang_code, $getCol('description')),
								'meta_title' => $getCol('meta_title_' . $lang_code, $getCol('meta_title', $name)),
								'meta_description' => $getCol('meta_description_' . $lang_code, $getCol('meta_description')),
								'meta_keyword' => $getCol('meta_keyword_' . $lang_code, $getCol('meta_keyword'))
							);
						}

						// Find parent category ID
						$category_data['parent_id'] = 0;
						
						if (!empty($parent_name)) {
							// If it's numeric, use as ID
							if (is_numeric($parent_name)) {
								$category_data['parent_id'] = (int)$parent_name;
							} else {
								// Look in imported categories first
								$parent_key = strtolower(trim($parent_name));
								if (isset($imported_categories[$parent_key])) {
									$category_data['parent_id'] = $imported_categories[$parent_key];
								} else {
									// Try to find in existing categories
									$filter_data = array(
										'filter_name' => $parent_name,
										'limit' => 100
									);
									$existing_parents = $this->model_catalog_category->getCategories($filter_data);
									
									foreach ($existing_parents as $cat) {
										$cat_name = $cat['name'];
										if (strpos($cat_name, '&gt;') !== false) {
											$parts = explode('&gt;', $cat_name);
											$cat_name = trim(end($parts));
										}
										
										if (strcasecmp(trim($cat_name), trim($parent_name)) == 0) {
											$category_data['parent_id'] = $cat['category_id'];
											break;
										}
									}
								}
							}
						}

						// Images - handle file paths or URLs
						$image_path = $getCol('image');
						if (!empty($image_path)) {
							$category_data['image'] = $this->processImage($image_path);
						}
						
						$thumb_path = $getCol('thumb');
						if (!empty($thumb_path)) {
							$category_data['thumb'] = $this->processImage($thumb_path);
						}
						
						$icon_path = $getCol('icon');
						if (!empty($icon_path)) {
							$category_data['icon'] = $this->processImage($icon_path);
						}

						// Other fields
						$category_data['status'] = strtolower($getCol('status', 'enabled')) == 'disabled' ? 0 : 1;
						
						// Top menu - always set to 1 (always checked/ticked)
						$category_data['top'] = 1;
						
						$category_data['column'] = (int)$getCol('column', 1);
						$category_data['sort_order'] = (int)$getCol('sort_order', 0);
						$category_data['view'] = $getCol('view', '');
						$category_data['filter_profile_id'] = (int)$getCol('filter_profile_id', 0);
						
						// SEO Keyword - always auto-generate from category name
						// Get category name first
						$name = '';
						foreach ($languages as $lang_code => $lang_info) {
							$name = $getCol('name_' . $lang_code);
							if (!empty($name)) {
								break;
							}
						}
						if (empty($name)) {
							$name = $getCol('name');
						}
						
						// Always generate keyword from category name
						if (!empty($name)) {
							// Convert name to URL-friendly keyword
							$keyword = strtolower(trim($name));
							$keyword = preg_replace('/[^a-z0-9]+/', '-', $keyword);
							$keyword = preg_replace('/^-+|-+$/', '', $keyword);
							
							// Ensure keyword is not empty
							if (empty($keyword)) {
								$keyword = 'category-' . time() . '-' . rand(1000, 9999);
							}
							
							// Check if keyword already exists in database and in current import session
							$this->load->model('catalog/url_alias');
							$counter = 1;
							$original_keyword = $keyword;
							
							// Check both database and current import session for duplicates
							while (isset($used_keywords[$keyword]) || $this->model_catalog_url_alias->getUrlAlias($keyword)) {
								$keyword = $original_keyword . '-' . $counter;
								$counter++;
							}
							
							// Mark keyword as used
							$used_keywords[$keyword] = true;
							$category_data['keyword'] = $keyword;
						} else {
							// Last resort: generate random keyword
							$keyword = 'category-' . time() . '-' . rand(1000, 9999);
							$category_data['keyword'] = $keyword;
						}

						// Stores - default to store 0
						$stores = $getCol('stores', '0');
						$category_data['category_store'] = array();
						foreach (explode(',', $stores) as $store_id) {
							$store_id = trim($store_id);
							if (is_numeric($store_id)) {
								$category_data['category_store'][] = (int)$store_id;
							}
						}
						if (empty($category_data['category_store'])) {
							$category_data['category_store'] = array(0);
						}

						// Validate form data
						if ($this->validateFormData($category_data)) {
							$category_id = $this->model_catalog_category->addCategory($category_data);
							$success_count++;
						} else {
							throw new Exception("Validation failed: " . implode(", ", $this->error));
						}
						
					} catch (Exception $e) {
						$error_count++;
						$errors[] = "Row $row_num: " . $e->getMessage();
					}
				}

				fclose($handle);

				if ($error_count > 0) {
					$this->session->data['error_warning'] = sprintf($this->language->get('text_import_partial'), $success_count, $error_count) . "<br>" . implode("<br>", array_slice($errors, 0, 10));
					if (count($errors) > 10) {
						$this->session->data['error_warning'] .= "<br>... and " . (count($errors) - 10) . " more errors";
					}
				} else {
					$this->session->data['success'] = sprintf($this->language->get('text_import_success'), $success_count);
				}
			} else {
				$this->error['warning'] = $this->language->get('error_file_read');
			}

			$url = '';
			$redirect_url = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->response->redirect($redirect_url);
		}

		$this->getImportForm();
	}

	protected function processImage($image_path) {
		// Remove leading/trailing quotes and whitespace
		$image_path = trim($image_path, "\"' \t\n\r\0\x0B");
		
		// If it's a URL, download it
		if (filter_var($image_path, FILTER_VALIDATE_URL)) {
			return $this->downloadImageFromUrl($image_path);
		}
		
		// If it's an absolute path, use it directly (but sanitize)
		if (strpos($image_path, DIR_IMAGE) === 0) {
			$relative_path = str_replace(DIR_IMAGE, '', $image_path);
			if (is_file(DIR_IMAGE . $relative_path)) {
				return $relative_path;
			}
		}
		
		// If it's a relative path from catalog directory
		if (strpos($image_path, 'catalog/') === 0) {
			if (is_file(DIR_IMAGE . $image_path)) {
				return $image_path;
			}
		}
		
		// Try as direct path in image directory
		if (is_file(DIR_IMAGE . $image_path)) {
			return $image_path;
		}
		
		// Return empty if not found
		return '';
	}

	protected function downloadImageFromUrl($url) {
		// Get file info
		$path_info = pathinfo(parse_url($url, PHP_URL_PATH));
		$extension = isset($path_info['extension']) ? strtolower($path_info['extension']) : 'jpg';
		
		// Validate extension
		$allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp');
		if (!in_array($extension, $allowed_extensions)) {
			$extension = 'jpg';
		}
		
		$filename = basename($path_info['filename']);
		if (empty($filename)) {
			$filename = 'image_' . time();
		}
		$filename = $filename . '.' . $extension;
		
		// Sanitize filename
		$filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
		
		// Create catalog directory if it doesn't exist
		$target_dir = DIR_IMAGE . 'catalog/import/' . date('Y/m/d') . '/';
		if (!is_dir($target_dir)) {
			mkdir($target_dir, 0755, true);
		}
		
		$target_file = $target_dir . $filename;
		
		// Download the file using curl if available, otherwise use file_get_contents
		$success = false;
		
		if (function_exists('curl_init')) {
			$ch = curl_init($url);
			$fp = fopen($target_file, 'wb');
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_exec($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			fclose($fp);
			
			$success = ($http_code == 200 && is_file($target_file));
		} else {
			// Fallback to file_get_contents
			$context = stream_context_create(array(
				'http' => array(
					'timeout' => 30,
					'follow_location' => true
				)
			));
			
			$image_data = @file_get_contents($url, false, $context);
			if ($image_data !== false) {
				$success = (file_put_contents($target_file, $image_data) !== false);
			}
		}
		
		if ($success && is_file($target_file)) {
			return 'catalog/import/' . date('Y/m/d') . '/' . $filename;
		}
		
		return '';
	}

	protected function validateFormData($data) {
		$this->error = array();
		
		if (isset($data['category_description'])) {
			foreach ($data['category_description'] as $language_id => $value) {
				if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 255)) {
					$this->error['name'][$language_id] = $this->language->get('error_name');
				}

				if (isset($value['blurb']) && utf8_strlen($value['blurb']) > 255) {
					$this->error['blurb'][$language_id] = $this->language->get('error_blurb');
				}

				if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
					$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
				}
			}
		}

		// Note: We don't validate keyword uniqueness here during import
		// The keyword is made unique in the import function before setting category_data['keyword']

		return !$this->error;
	}

	protected function getImportForm() {
		$data['heading_title'] = $this->language->get('heading_title_import');

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
			'href' => $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_import'),
			'href' => $this->url->link('catalog/category/import', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['action'] = $this->url->link('catalog/category/import', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['cancel'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['text_import'] = $this->language->get('text_import');
		$data['text_import_info'] = $this->language->get('text_import_info');
		$data['text_import_info_line1'] = $this->language->get('text_import_info_line1');
		$data['text_import_info_line2'] = $this->language->get('text_import_info_line2');
		$data['text_import_info_line3'] = $this->language->get('text_import_info_line3');
		$data['text_import_info_line4'] = $this->language->get('text_import_info_line4');
		$data['button_import'] = $this->language->get('button_import');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_download_sample'] = $this->language->get('button_download_sample');
		$data['download_sample'] = $this->url->link('catalog/category/downloadSample', 'token=' . $this->session->data['token'], 'SSL');
		$data['entry_file'] = $this->language->get('entry_file');
		$data['help_file'] = $this->language->get('help_file');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/category_import.tpl', $data));
	}

	protected function validateImport() {
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
			return false;
		}

		if (!isset($this->request->files['import_file']) || !is_uploaded_file($this->request->files['import_file']['tmp_name'])) {
			$this->error['warning'] = $this->language->get('error_file_upload');
			return false;
		}

		$file_extension = strtolower(pathinfo($this->request->files['import_file']['name'], PATHINFO_EXTENSION));
		if ($file_extension != 'csv') {
			$this->error['warning'] = $this->language->get('error_file_type');
			return false;
		}

		return !$this->error;
	}

	public function downloadSample() {
		$this->load->language('catalog/category');

		// Get languages
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();

		// Build CSV header
		$headers = array();
		$headers[] = 'parent';
		foreach ($languages as $lang_code => $lang_info) {
			$headers[] = 'name_' . $lang_code;
			$headers[] = 'blurb_' . $lang_code;
			$headers[] = 'intro_' . $lang_code;
			$headers[] = 'description_' . $lang_code;
			$headers[] = 'meta_title_' . $lang_code;
			$headers[] = 'meta_description_' . $lang_code;
			$headers[] = 'meta_keyword_' . $lang_code;
		}
		$headers[] = 'image';
		$headers[] = 'thumb';
		$headers[] = 'icon';
		$headers[] = 'status';
		$headers[] = 'top';
		$headers[] = 'column';
		$headers[] = 'sort_order';
		$headers[] = 'view';
		$headers[] = 'filter_profile_id';
		$headers[] = 'keyword';
		$headers[] = 'stores';

		// Create sample row
		$sample_row = array();
		$sample_row[] = ''; // parent
		foreach ($languages as $lang_code => $lang_info) {
			$sample_row[] = 'Sample Category Name';
			$sample_row[] = 'Short blurb';
			$sample_row[] = 'Intro text';
			$sample_row[] = 'Full description';
			$sample_row[] = 'Meta Title';
			$sample_row[] = 'Meta Description';
			$sample_row[] = 'keyword1, keyword2';
		}
		$sample_row[] = 'catalog/sample/image.jpg'; // image - can be path or URL
		$sample_row[] = 'catalog/sample/thumb.jpg'; // thumb
		$sample_row[] = 'catalog/sample/icon.png'; // icon
		$sample_row[] = 'enabled'; // status
		$sample_row[] = 'no'; // top
		$sample_row[] = '1'; // column
		$sample_row[] = '0'; // sort_order
		$sample_row[] = ''; // view
		$sample_row[] = '0'; // filter_profile_id
		$sample_row[] = 'sample-category'; // keyword
		$sample_row[] = '0'; // stores

		// Escape CSV values
		$escapeCsv = function($value) {
			$value = str_replace('"', '""', $value);
			if (strpos($value, ',') !== false || strpos($value, '"') !== false || strpos($value, "\n") !== false) {
				$value = '"' . $value . '"';
			}
			return $value;
		};

		$csv_content = '';
		$csv_content .= implode(',', array_map($escapeCsv, $headers)) . "\n";
		$csv_content .= implode(',', array_map($escapeCsv, $sample_row)) . "\n";

		header('Content-Description: File Transfer');
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename="category_import_sample.csv"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . strlen($csv_content));
		
		// Add BOM for UTF-8 to help Excel
		echo "\xEF\xBB\xBF";
		echo $csv_content;
		exit();
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/category');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 20
			);

			$results = $this->model_catalog_category->getCategories($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'category_id' => $result['category_id'],
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
