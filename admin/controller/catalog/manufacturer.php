<?php
class ControllerCatalogManufacturer extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/manufacturer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/manufacturer');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/manufacturer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/manufacturer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if ($this->validateForm()) {
				try {
					$manufacturer_id = $this->model_catalog_manufacturer->addManufacturer($this->request->post);

					if ($manufacturer_id > 0) {
						$this->session->data['success'] = $this->language->get('text_success');

						// Add to activity log (non-blocking - if it fails, manufacturer is still saved)
						try {
							$this->load->model('user/user');
							if (isset($this->user) && method_exists($this->user, 'getId')) {
								$activity_data = array(
									'%user_id' => $this->user->getId(),
									'%manufacturer_id' => $manufacturer_id,
									'%name' => $this->user->getFirstName() . ' ' . $this->user->getLastName()
								);
								$this->model_user_user->addActivity($this->user->getId(), 'add_manufacturer', $activity_data, $manufacturer_id);
							}
						} catch (Exception $e) {
							// Activity log failed, but manufacturer was saved - continue with redirect
							error_log('Activity log error: ' . $e->getMessage());
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

						// Redirect back to manufacturer list on success
						$this->response->redirect($this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
						return;
					} else {
						throw new Exception('Failed to add manufacturer - manufacturer_id was not returned');
					}
				} catch (Exception $e) {
					// Manufacturer add failed
					$this->error['warning'] = 'Error adding manufacturer: ' . $e->getMessage();
					error_log('Manufacturer add error: ' . $e->getMessage());
				}
			} else {
				// Validation failed - show form with errors
				$this->error['warning'] = $this->language->get('error_warning');
			}
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/manufacturer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/manufacturer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			// Get manufacturer_id from GET or POST (form might submit it as hidden field)
			$manufacturer_id = 0;
			if (isset($this->request->get['manufacturer_id']) && !empty($this->request->get['manufacturer_id'])) {
				$manufacturer_id = (int)$this->request->get['manufacturer_id'];
			} elseif (isset($this->request->post['manufacturer_id']) && !empty($this->request->post['manufacturer_id'])) {
				$manufacturer_id = (int)$this->request->post['manufacturer_id'];
				// Set it in GET so getForm() can access it
				$this->request->get['manufacturer_id'] = $manufacturer_id;
			}

			// Validate manufacturer_id
			if ($manufacturer_id <= 0) {
				$this->error['warning'] = 'Invalid manufacturer ID. Cannot update manufacturer.';
			} else {
				// Verify manufacturer exists
				$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
				if (!$manufacturer_info) {
					$this->error['warning'] = 'Manufacturer not found. Cannot update manufacturer.';
				} elseif ($this->validateForm()) {
					// Validation passed, proceed with update
					try {
						$this->model_catalog_manufacturer->editManufacturer($manufacturer_id, $this->request->post);

						$this->session->data['success'] = $this->language->get('text_success');

						// Add to activity log (non-blocking - if it fails, manufacturer is still saved)
						try {
							$this->load->model('user/user');
							if (isset($this->user) && method_exists($this->user, 'getId')) {
								$activity_data = array(
									'%user_id' => $this->user->getId(),
									'%manufacturer_id' => $manufacturer_id,
									'%name' => $this->user->getFirstName() . ' ' . $this->user->getLastName()
								);
								$this->model_user_user->addActivity($this->user->getId(), 'edit_manufacturer', $activity_data, $manufacturer_id);
							}
						} catch (Exception $e) {
							// Activity log failed, but manufacturer was saved - continue with redirect
							error_log('Activity log error: ' . $e->getMessage());
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

						// Redirect back to manufacturer list on success
						$this->response->redirect($this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
						return;
					} catch (Exception $e) {
						// Manufacturer update failed
						$this->error['warning'] = 'Error updating manufacturer: ' . $e->getMessage();
						error_log('Manufacturer update error: ' . $e->getMessage());
					}
				} else {
					// Validation failed - show form with errors
					$this->error['warning'] = $this->language->get('error_warning');
				}
			}
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/manufacturer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/manufacturer');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $manufacturer_id) {
				$this->model_catalog_manufacturer->deleteManufacturer($manufacturer_id);
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

			$this->response->redirect($this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
			'href' => $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('catalog/manufacturer/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/manufacturer/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['manufacturers'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$manufacturer_total = $this->model_catalog_manufacturer->getTotalManufacturers();

		$results = $this->model_catalog_manufacturer->getManufacturers($filter_data);

		foreach ($results as $result) {
			$data['manufacturers'][] = array(
				'manufacturer_id' => $result['manufacturer_id'],
				'name'            => $result['name'],
				'sort_order'      => $result['sort_order'],
				'edit'            => $this->url->link('catalog/manufacturer/edit', 'token=' . $this->session->data['token'] . '&manufacturer_id=' . $result['manufacturer_id'] . $url, 'SSL')
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

		$data['sort_name'] = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $manufacturer_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($manufacturer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($manufacturer_total - $this->config->get('config_limit_admin'))) ? $manufacturer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $manufacturer_total, ceil($manufacturer_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/manufacturer_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['manufacturer_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_percent'] = $this->language->get('text_percent');
		$data['text_amount'] = $this->language->get('text_amount');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_meta_title'] = $this->language->get('entry_meta_title');
        $data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
        $data['entry_keyword'] = $this->language->get('entry_keyword');
        $data['entry_store'] = $this->language->get('entry_store');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_thumb'] = $this->language->get('entry_thumb');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_layout'] = $this->language->get('entry_layout');

		$data['help_keyword'] = $this->language->get('help_keyword');

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
			$data['error_name'] = '';
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
			'href' => $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		// Get manufacturer_id from GET or POST for form
		$manufacturer_id_for_form = 0;
		if (isset($this->request->get['manufacturer_id']) && !empty($this->request->get['manufacturer_id'])) {
			$manufacturer_id_for_form = (int)$this->request->get['manufacturer_id'];
		} elseif (isset($this->request->post['manufacturer_id']) && !empty($this->request->post['manufacturer_id'])) {
			$manufacturer_id_for_form = (int)$this->request->post['manufacturer_id'];
		}
		$data['manufacturer_id'] = $manufacturer_id_for_form;

		if ($manufacturer_id_for_form <= 0) {
			$data['action'] = $this->url->link('catalog/manufacturer/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/manufacturer/edit', 'token=' . $this->session->data['token'] . '&manufacturer_id=' . $manufacturer_id_for_form . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$manufacturer_info = array();
		if ($manufacturer_id_for_form > 0 && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id_for_form);
			if (!$manufacturer_info) {
				$manufacturer_info = array();
			}
		} elseif ($manufacturer_id_for_form > 0 && ($this->request->server['REQUEST_METHOD'] == 'POST')) {
			// On POST, still load manufacturer info for form display
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id_for_form);
			if (!$manufacturer_info) {
				$manufacturer_info = array();
			}
		}

		$data['token'] = $this->session->data['token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['manufacturer_description'])) {
            $data['manufacturer_description'] = $this->request->post['manufacturer_description'];
        } elseif (isset($this->request->get['manufacturer_id'])) {
            $data['manufacturer_description'] = $this->model_catalog_manufacturer->getManufacturerDescriptions($this->request->get['manufacturer_id']);
        } else {
            $data['manufacturer_description'] = array();
        }

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($manufacturer_info) && isset($manufacturer_info['name'])) {
			$data['name'] = $manufacturer_info['name'];
		} else {
			$data['name'] = '';
		}

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['manufacturer_store'])) {
			$data['manufacturer_store'] = $this->request->post['manufacturer_store'];
		} elseif (isset($this->request->get['manufacturer_id'])) {
			$data['manufacturer_store'] = $this->model_catalog_manufacturer->getManufacturerStores($this->request->get['manufacturer_id']);
		} else {
			$data['manufacturer_store'] = array(0);
		}

		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($manufacturer_info) && isset($manufacturer_info['keyword'])) {
			$data['keyword'] = $manufacturer_info['keyword'];
		} else {
			$data['keyword'] = '';
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($manufacturer_info) && isset($manufacturer_info['image'])) {
			$data['image'] = $manufacturer_info['image'];
		} else {
			$data['image'] = '';
		}

		if (isset($this->request->post['thumb'])) {
			$data['thumb'] = $this->request->post['thumb'];
		} elseif (!empty($manufacturer_info) && isset($manufacturer_info['thumb'])) {
			$data['thumb'] = $manufacturer_info['thumb'];
		} else {
			$data['thumb'] = '';
		}

        if (isset($this->request->post['manufacturer_layout'])) {
            $data['manufacturer_layout'] = $this->request->post['manufacturer_layout'];
        } elseif (isset($this->request->get['manufacturer_id'])) {
            $data['manufacturer_layout'] = $this->model_catalog_manufacturer->getManufacturerLayouts($this->request->get['manufacturer_id']);
        } else {
            $data['manufacturer_layout'] = array();
        }

        $this->load->model('design/layout');

        $data['layouts'] = $this->model_design_layout->getLayouts();

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['image_thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($manufacturer_info) && isset($manufacturer_info['image']) && $manufacturer_info['image'] && is_file(DIR_IMAGE . $manufacturer_info['image'])) {
			$data['image_thumb'] = $this->model_tool_image->resize($manufacturer_info['image'], 100, 100);
		} else {
			$data['image_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['thumb']) && is_file(DIR_IMAGE . $this->request->post['thumb'])) {
			$data['thumb_thumb'] = $this->model_tool_image->resize($this->request->post['thumb'], 100, 100);
		} elseif (!empty($manufacturer_info) && isset($manufacturer_info['thumb']) && $manufacturer_info['thumb'] && is_file(DIR_IMAGE . $manufacturer_info['thumb'])) {
			$data['thumb_thumb'] = $this->model_tool_image->resize($manufacturer_info['thumb'], 100, 100);
		} else {
			$data['thumb_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($manufacturer_info) && isset($manufacturer_info['sort_order'])) {
			$data['sort_order'] = $manufacturer_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/manufacturer_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		// Check if name exists in POST, if not try to get from manufacturer_description
		$manufacturer_name = '';
		if (isset($this->request->post['name']) && !empty(trim($this->request->post['name']))) {
			$manufacturer_name = trim($this->request->post['name']);
		} elseif (isset($this->request->post['manufacturer_description']) && is_array($this->request->post['manufacturer_description'])) {
			// Try to get name from first language's meta_title
			foreach ($this->request->post['manufacturer_description'] as $lang_data) {
				if (isset($lang_data['meta_title']) && !empty(trim($lang_data['meta_title']))) {
					$manufacturer_name = trim($lang_data['meta_title']);
					break;
				}
			}
		}

		if (empty($manufacturer_name)) {
			$this->error['name'] = $this->language->get('error_name');
		} elseif ((utf8_strlen($manufacturer_name) < 2) || (utf8_strlen($manufacturer_name) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		} else {
			// Ensure name is set in POST data for model
			$this->request->post['name'] = $manufacturer_name;
		}

        if (isset($this->request->post['manufacturer_description']) && is_array($this->request->post['manufacturer_description'])) {
            foreach ($this->request->post['manufacturer_description'] as $language_id => $value) {
                if (isset($value['meta_title']) && !empty($value['meta_title']) && ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255))) {
                    $this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
                }
            }
        }
		
		if (isset($this->request->post['keyword']) && !empty(trim($this->request->post['keyword']))) {
			$this->load->model('catalog/url_alias');

			// Get manufacturer_id from GET or POST for keyword validation
			$manufacturer_id_for_validation = 0;
			if (isset($this->request->get['manufacturer_id']) && !empty($this->request->get['manufacturer_id'])) {
				$manufacturer_id_for_validation = (int)$this->request->get['manufacturer_id'];
			} elseif (isset($this->request->post['manufacturer_id']) && !empty($this->request->post['manufacturer_id'])) {
				$manufacturer_id_for_validation = (int)$this->request->post['manufacturer_id'];
			}

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && $manufacturer_id_for_validation > 0 && $url_alias_info['query'] != 'manufacturer_id=' . $manufacturer_id_for_validation) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && $manufacturer_id_for_validation <= 0) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/product');

		foreach ($this->request->post['selected'] as $manufacturer_id) {
			$product_total = $this->model_catalog_product->getTotalProductsByManufacturerId($manufacturer_id);

			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/manufacturer');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_catalog_manufacturer->getManufacturers($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'manufacturer_id' => $result['manufacturer_id'],
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