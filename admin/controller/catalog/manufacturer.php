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

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$log_file = DIR_LOGS . 'manufacturer_error.log';
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' ========== MANUFACTURER ADD REQUEST ==========' . PHP_EOL, FILE_APPEND);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - POST data keys: ' . implode(', ', array_keys($this->request->post)) . PHP_EOL, FILE_APPEND);
			
			try {
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Calling addManufacturer()...' . PHP_EOL, FILE_APPEND);
				$manufacturer_id = $this->model_catalog_manufacturer->addManufacturer($this->request->post);
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - addManufacturer() returned: ' . $manufacturer_id . PHP_EOL, FILE_APPEND);

				if ($manufacturer_id > 0) {
					$this->session->data['success'] = $this->language->get('text_success');
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Success! Redirecting...' . PHP_EOL, FILE_APPEND);

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
				} else {
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ERROR: manufacturer_id is 0 or negative' . PHP_EOL, FILE_APPEND);
					$this->error['warning'] = $this->language->get('error_insert_failed');
				}
			} catch (Exception $e) {
				// Log the error with full details
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - EXCEPTION: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - File: ' . $e->getFile() . ' Line: ' . $e->getLine() . PHP_EOL, FILE_APPEND);
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Stack trace: ' . PHP_EOL . $e->getTraceAsString() . PHP_EOL, FILE_APPEND);
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - POST data: ' . print_r($this->request->post, true) . PHP_EOL, FILE_APPEND);
				
				$this->error['warning'] = 'Error adding manufacturer: ' . $e->getMessage();
			} catch (Error $e) {
				// Catch PHP 7+ errors
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - PHP ERROR: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - File: ' . $e->getFile() . ' Line: ' . $e->getLine() . PHP_EOL, FILE_APPEND);
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Stack trace: ' . PHP_EOL . $e->getTraceAsString() . PHP_EOL, FILE_APPEND);
				
				$this->error['warning'] = 'PHP Error: ' . $e->getMessage();
			}
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/manufacturer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/manufacturer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			try {
				$this->model_catalog_manufacturer->editManufacturer($this->request->get['manufacturer_id'], $this->request->post);

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
			} catch (Exception $e) {
				// Log the error
				$log_file = DIR_LOGS . 'manufacturer_error.log';
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Error editing manufacturer ID ' . (isset($this->request->get['manufacturer_id']) ? $this->request->get['manufacturer_id'] : 'unknown') . ': ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - POST data: ' . print_r($this->request->post, true) . PHP_EOL, FILE_APPEND);
				
				$this->error['warning'] = 'Error editing manufacturer: ' . $e->getMessage();
			}
		}

		$this->getForm();
	}

	public function delete() {
		$log_file = DIR_LOGS . 'manufacturer_error.log';
		
		// Set up error handler to catch fatal errors
		register_shutdown_function(function() use ($log_file) {
			$error = error_get_last();
			if ($error !== NULL && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - FATAL ERROR: ' . $error['message'] . ' in ' . $error['file'] . ' on line ' . $error['line'] . PHP_EOL, FILE_APPEND);
			}
		});
		
		try {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' ========== DELETE CONTROLLER CALLED ==========' . PHP_EOL, FILE_APPEND);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - POST data: ' . print_r(isset($this->request->post) ? $this->request->post : 'NO POST DATA', true) . PHP_EOL, FILE_APPEND);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - GET data: ' . print_r(isset($this->request->get) ? $this->request->get : 'NO GET DATA', true) . PHP_EOL, FILE_APPEND);
			
			$this->load->language('catalog/manufacturer');
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Language loaded successfully' . PHP_EOL, FILE_APPEND);

			$this->document->setTitle($this->language->get('heading_title'));

			$this->load->model('catalog/manufacturer');
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Model loaded successfully' . PHP_EOL, FILE_APPEND);

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Validation passed, proceeding with deletion' . PHP_EOL, FILE_APPEND);
			$deleted_count = 0;
			$failed_count = 0;
			$errors = array();
			
			// Log deletion attempt
			$log_file = DIR_LOGS . 'manufacturer_error.log';
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' ========== DELETE REQUEST ==========' . PHP_EOL, FILE_APPEND);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Selected manufacturers: ' . implode(', ', $this->request->post['selected']) . PHP_EOL, FILE_APPEND);
			
			foreach ($this->request->post['selected'] as $manufacturer_id) {
				try {
					$this->model_catalog_manufacturer->deleteManufacturer($manufacturer_id);
					$deleted_count++;
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ✓ Successfully deleted manufacturer ID: ' . $manufacturer_id . PHP_EOL, FILE_APPEND);
				} catch (Exception $e) {
					$failed_count++;
					$errors[] = 'Manufacturer ID ' . $manufacturer_id . ': ' . $e->getMessage();
					
					// Log the error
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ✗ Error deleting manufacturer ID ' . $manufacturer_id . ': ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
				}
			}
			
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Deletion complete: ' . $deleted_count . ' succeeded, ' . $failed_count . ' failed' . PHP_EOL, FILE_APPEND);
			
			if ($failed_count > 0) {
				// Use language key if exists, otherwise use fallback
				$error_text = $this->language->get('text_error');
				if (empty($error_text) || $error_text == 'text_error') {
					$error_text = 'Warning: %s manufacturer(s) deleted successfully, but %s failed to delete.';
				}
				$this->error['warning'] = sprintf($error_text, $deleted_count, $failed_count);
				if (!empty($errors)) {
					$this->error['warning'] .= '<br />' . implode('<br />', $errors);
				}
			} else {
				// Set success message
				$success_msg = $this->language->get('text_success');
				if (empty($success_msg) || $success_msg == 'text_success') {
					$success_msg = 'Success: You have modified manufacturers!';
				}
				$this->session->data['success'] = $success_msg;
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Success message set: ' . $success_msg . PHP_EOL, FILE_APPEND);
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

			// Build redirect URL
			$redirect_url = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL');
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Redirecting to: ' . $redirect_url . PHP_EOL, FILE_APPEND);
			
			// Ensure no output before redirect
			if (ob_get_level()) {
				ob_end_clean();
			}
			
			try {
				$this->response->redirect($redirect_url);
				return; // Exit the function after redirect
			} catch (Exception $e) {
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ERROR during redirect: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
				// Fallback: show list with error message
				$this->error['warning'] = 'Redirect failed, but deletion completed. ' . $e->getMessage();
				$this->getList();
				return;
			}
		} else {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Validation failed or no selected items' . PHP_EOL, FILE_APPEND);
			if (!isset($this->request->post['selected'])) {
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - No selected items in POST' . PHP_EOL, FILE_APPEND);
			}
			if (isset($this->error) && !empty($this->error)) {
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Validation errors: ' . print_r($this->error, true) . PHP_EOL, FILE_APPEND);
			}
		}

		try {
			$this->getList();
		} catch (Exception $e) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - FATAL ERROR in getList(): ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Stack trace: ' . $e->getTraceAsString() . PHP_EOL, FILE_APPEND);
			// Show a basic error page
			if (isset($this->response)) {
				$this->response->addHeader('Content-Type: text/html; charset=utf-8');
				$this->response->setOutput('Error: ' . htmlspecialchars($e->getMessage()) . '<br />Check log file: ' . DIR_LOGS . 'manufacturer_error.log');
			}
		} catch (Error $e) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - FATAL PHP ERROR: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL, FILE_APPEND);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Stack trace: ' . $e->getTraceAsString() . PHP_EOL, FILE_APPEND);
			// Show a basic error page
			if (isset($this->response)) {
				$this->response->addHeader('Content-Type: text/html; charset=utf-8');
				$this->response->setOutput('Fatal Error: ' . htmlspecialchars($e->getMessage()) . '<br />File: ' . $e->getFile() . ' Line: ' . $e->getLine() . '<br />Check log file: ' . DIR_LOGS . 'manufacturer_error.log');
			}
		}
		} catch (Exception $e) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - EXCEPTION in delete(): ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Stack trace: ' . $e->getTraceAsString() . PHP_EOL, FILE_APPEND);
			// Try to show error
			if (isset($this->response)) {
				$this->response->addHeader('Content-Type: text/html; charset=utf-8');
				$this->response->setOutput('Error deleting manufacturer: ' . htmlspecialchars($e->getMessage()) . '<br />Check log file: ' . DIR_LOGS . 'manufacturer_error.log');
			}
		} catch (Error $e) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - FATAL PHP ERROR in delete(): ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL, FILE_APPEND);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Stack trace: ' . $e->getTraceAsString() . PHP_EOL, FILE_APPEND);
			// Try to show error
			if (isset($this->response)) {
				$this->response->addHeader('Content-Type: text/html; charset=utf-8');
				$this->response->setOutput('Fatal Error: ' . htmlspecialchars($e->getMessage()) . '<br />File: ' . $e->getFile() . ' Line: ' . $e->getLine() . '<br />Check log file: ' . DIR_LOGS . 'manufacturer_error.log');
			}
		}
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

		if (!isset($this->request->get['manufacturer_id'])) {
			$data['action'] = $this->url->link('catalog/manufacturer/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/manufacturer/edit', 'token=' . $this->session->data['token'] . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['manufacturer_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);
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
		} elseif (!empty($manufacturer_info)) {
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
		} elseif (!empty($manufacturer_info)) {
			$data['keyword'] = $manufacturer_info['keyword'];
		} else {
			$data['keyword'] = '';
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($manufacturer_info)) {
			$data['image'] = $manufacturer_info['image'];
		} else {
			$data['image'] = '';
		}

		if (isset($this->request->post['thumb'])) {
			$data['thumb'] = $this->request->post['thumb'];
		} elseif (!empty($manufacturer_info)) {
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
		} elseif (!empty($manufacturer_info) && is_file(DIR_IMAGE . $manufacturer_info['image'])) {
			$data['image_thumb'] = $this->model_tool_image->resize($manufacturer_info['image'], 100, 100);
		} else {
			$data['image_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['thumb']) && is_file(DIR_IMAGE . $this->request->post['thumb'])) {
			$data['thumb_thumb'] = $this->model_tool_image->resize($this->request->post['thumb'], 100, 100);
		} elseif (!empty($manufacturer_info) && is_file(DIR_IMAGE . $manufacturer_info['thumb'])) {
			$data['thumb_thumb'] = $this->model_tool_image->resize($manufacturer_info['thumb'], 100, 100);
		} else {
			$data['thumb_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($manufacturer_info)) {
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

		if ((utf8_strlen($this->request->post['name']) < 2) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

        if (isset($this->request->post['manufacturer_description']) && is_array($this->request->post['manufacturer_description'])) {
            foreach ($this->request->post['manufacturer_description'] as $language_id => $value) {
                if (isset($value['meta_title']) && (utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
                    $this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
                }
            }
        }
		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['manufacturer_id']) && $url_alias_info['query'] != 'manufacturer_id=' . $this->request->get['manufacturer_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['manufacturer_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
			$this->error['warning'] = $this->language->get('error_permission');
			return false;
		}

		// Check if products are linked, but allow deletion anyway (we'll unlink them)
		$this->load->model('catalog/product');
		$manufacturers_with_products = array();

		foreach ($this->request->post['selected'] as $manufacturer_id) {
			$product_total = $this->model_catalog_product->getTotalProductsByManufacturerId($manufacturer_id);
			if ($product_total > 0) {
				$manufacturers_with_products[] = array(
					'id' => $manufacturer_id,
					'count' => $product_total
				);
			}
		}

		// Log warning but don't prevent deletion
		if (!empty($manufacturers_with_products)) {
			$log_file = DIR_LOGS . 'manufacturer_error.log';
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - WARNING: Attempting to delete manufacturers with linked products:' . PHP_EOL, FILE_APPEND);
			foreach ($manufacturers_with_products as $mfg) {
				file_put_contents($log_file, date('Y-m-d H:i:s') . '   - Manufacturer ID ' . $mfg['id'] . ' has ' . $mfg['count'] . ' products (will be unlinked)' . PHP_EOL, FILE_APPEND);
			}
		}

		return true; // Always allow deletion (we'll handle unlinking products)
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
		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['image_thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($manufacturer_info) && is_file(DIR_IMAGE . $manufacturer_info['image'])) {
			$data['image_thumb'] = $this->model_tool_image->resize($manufacturer_info['image'], 100, 100);
		} else {
			$data['image_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['thumb']) && is_file(DIR_IMAGE . $this->request->post['thumb'])) {
			$data['thumb_thumb'] = $this->model_tool_image->resize($this->request->post['thumb'], 100, 100);
		} elseif (!empty($manufacturer_info) && is_file(DIR_IMAGE . $manufacturer_info['thumb'])) {
			$data['thumb_thumb'] = $this->model_tool_image->resize($manufacturer_info['thumb'], 100, 100);
		} else {
			$data['thumb_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($manufacturer_info)) {
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

		if ((utf8_strlen($this->request->post['name']) < 2) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

        if (isset($this->request->post['manufacturer_description']) && is_array($this->request->post['manufacturer_description'])) {
            foreach ($this->request->post['manufacturer_description'] as $language_id => $value) {
                if (isset($value['meta_title']) && (utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
                    $this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
                }
            }
        }
		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['manufacturer_id']) && $url_alias_info['query'] != 'manufacturer_id=' . $this->request->get['manufacturer_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['manufacturer_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
			$this->error['warning'] = $this->language->get('error_permission');
			return false;
		}

		// Check if products are linked, but allow deletion anyway (we'll unlink them)
		$this->load->model('catalog/product');
		$manufacturers_with_products = array();

		foreach ($this->request->post['selected'] as $manufacturer_id) {
			$product_total = $this->model_catalog_product->getTotalProductsByManufacturerId($manufacturer_id);
			if ($product_total > 0) {
				$manufacturers_with_products[] = array(
					'id' => $manufacturer_id,
					'count' => $product_total
				);
			}
		}

		// Log warning but don't prevent deletion
		if (!empty($manufacturers_with_products)) {
			$log_file = DIR_LOGS . 'manufacturer_error.log';
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - WARNING: Attempting to delete manufacturers with linked products:' . PHP_EOL, FILE_APPEND);
			foreach ($manufacturers_with_products as $mfg) {
				file_put_contents($log_file, date('Y-m-d H:i:s') . '   - Manufacturer ID ' . $mfg['id'] . ' has ' . $mfg['count'] . ' products (will be unlinked)' . PHP_EOL, FILE_APPEND);
			}
		}

		return true; // Always allow deletion (we'll handle unlinking products)
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