<?php
class ControllerDesignAds extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('design/ads');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/ads');

		$this->getList();
	}

	public function add() {
		$this->load->language('design/ads');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/ads');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_design_ads->addAds($this->request->post);

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

			$this->response->redirect($this->url->link('design/ads', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('design/ads');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/ads');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_design_ads->editAds($this->request->get['ads_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('design/ads', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('design/ads');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/ads');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $ads_id) {
				$this->model_design_ads->deleteAds($ads_id);
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

			$this->response->redirect($this->url->link('design/ads', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'title';
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
			'href' => $this->url->link('design/ads', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('design/ads/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('design/ads/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['ads_list'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$ads_total = $this->model_design_ads->getTotalAds();

		$results = $this->model_design_ads->getAdsList($filter_data);

		foreach ($results as $result) {
		    $device_type = $this->language->get("text_all");
		    if($result['device_type'] == 1) {
		        $device_type = $this->language->get("text_mobile");
            } else if ($result['device_type'] == 2) {
		        $device_type = $this->language->get("text_tablet");
            } else if($result['device_type'] == 3) {
		        $device_type = $this->language->get("text_desktop");
            }

			$data['ads_list'][] = array(
				'ads_id'     => $result['ads_id'],
				'title'   => $result['title'],
				'device_type' => $device_type,
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'       => $this->url->link('design/ads/edit', 'token=' . $this->session->data['token'] . '&ads_id=' . $result['ads_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_title'] = $this->language->get('column_title');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_device_type'] = $this->language->get('column_device_type');
		$data['column_date_added'] = $this->language->get('column_date_added');
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

		$data['sort_title'] = $this->url->link('design/ads', 'token=' . $this->session->data['token'] . '&sort=title' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('design/ads', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$data['sort_device_type'] = $this->url->link('design/ads', 'token=' . $this->session->data['token'] . '&sort=device_type' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('design/ads', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, 'SSL');
		$data['sort_date_modified'] = $this->url->link('design/ads', 'token=' . $this->session->data['token'] . '&sort=date_modified' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $ads_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('design/ads', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($ads_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($ads_total - $this->config->get('config_limit_admin'))) ? $ads_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $ads_total, ceil($ads_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/ads_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['ads_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_ads_position'] = $this->language->get('entry_ads_position');
		$data['entry_device_type'] = $this->language->get('entry_device_type');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_url'] = $this->language->get('entry_url');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_generate'] = $this->language->get('button_generate');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = '';
		}
        if (isset($this->error['image'])) {
            $data['error_image'] = $this->error['image'];
        } else {
            $data['error_image'] = '';
        }

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
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
			'href' => $this->url->link('design/ads', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['ads_id'])) {
			$data['action'] = $this->url->link('design/ads/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('design/ads/edit', 'token=' . $this->session->data['token'] . '&ads_id=' . $this->request->get['ads_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('design/ads', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['ads_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$ads_info = $this->model_design_ads->getAds($this->request->get['ads_id']);
		}

		if (isset($this->request->post['title'])) {
			$data['title'] = $this->request->post['title'];
		} elseif (!empty($ads_info)) {
			$data['title'] = $ads_info['title'];
		} else {
			$data['title'] = '';
		}

        if (isset($this->request->post['ads_position_id'])) {
            $data['ads_position_id'] = $this->request->post['ads_position_id'];
        } elseif (!empty($ads_info)) {
            $data['ads_position_id'] = $ads_info['ads_position_id'];
        } else {
            $data['ads_position_id'] = '';
        }

        $this->load->model('design/ads_position');
        $data['ads_positions'] = $this->model_design_ads_position->getAdsPositions();

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($ads_info)) {
            $data['image'] = $ads_info['image'];
        } else {
            $data['image'] = '';
        }

        if (isset($this->request->post['url'])) {
            $data['url'] = $this->request->post['url'];
        } elseif (!empty($ads_info)) {
            $data['url'] = $ads_info['url'];
        } else {
            $data['url'] = '';
        }

        if (isset($this->request->post['device_type'])) {
            $data['device_type'] = $this->request->post['device_type'];
        } elseif (!empty($ads_info)) {
            $data['device_type'] = $ads_info['device_type'];
        } else {
            $data['device_type'] = '';
        }

        $data['devices'] = array();
        $data['devices'][] = array('device_id' => 0, 'name' => $this->language->get("text_all"));
        $data['devices'][] = array('device_id' => 1, 'name' => $this->language->get("text_mobile"));
        $data['devices'][] = array('device_id' => 2, 'name' => $this->language->get("text_tablet"));
        $data['devices'][] = array('device_id' => 3, 'name' => $this->language->get("text_desktop"));

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($ads_info) && $ads_info['image'] && is_file(DIR_IMAGE . $ads_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($ads_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($ads_info)) {
			$data['status'] = $ads_info['status'];
		} else {
			$data['status'] = 0;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/ads_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'design/ads')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen(trim($this->request->post['title'])) < 3) || (utf8_strlen(trim($this->request->post['title'])) > 64)) {
			$this->error['title'] = $this->language->get('error_title');
		}

		if (utf8_strlen($this->request->post['image']) < 1) {
			$this->error['image'] = $this->language->get('error_image');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'design/ads')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}