<?php
class ControllerCatalogOffer extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/offer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/offer');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/offer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/offer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $offer_id = $this->model_catalog_offer->addOffer($this->request->post);

            // Add to activity log
            $this->load->model('user/user');

            $activity_data = array(
                '%user_id' => $this->user->getId(),
                '%offer_id' => $offer_id,
                '%name'        => $this->user->getFirstName() . ' ' . $this->user->getLastName()
            );

            $this->model_user_user->addActivity($this->user->getId(), 'add_offer', $activity_data, $offer_id);

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

			$this->response->redirect($this->url->link('catalog/offer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/offer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/offer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_offer->editOffer($this->request->get['offer_id'], $this->request->post);

            // Add to activity log
            $this->load->model('user/user');

            $activity_data = array(
                '%user_id' => $this->user->getId(),
                '%offer_id' => $this->request->get['offer_id'],
                '%name'        => $this->user->getFirstName() . ' ' . $this->user->getLastName()
            );

            $this->model_user_user->addActivity($this->user->getId(), 'edit_offer', $activity_data, $this->request->get['offer_id']);

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

			$this->response->redirect($this->url->link('catalog/offer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/offer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/offer');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $offer_id) {
				$this->model_catalog_offer->deleteOffer($offer_id);

                // Add to activity log
                $this->load->model('user/user');
                $activity_data = array(
                    '%user_id' => $this->user->getId(),
                    '%offer_id' => $offer_id,
                    '%name'        => $this->user->getFirstName() . ' ' . $this->user->getLastName()
                );
                $this->model_user_user->addActivity($this->user->getId(), 'delete_offer', $activity_data, $offer_id);
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

			$this->response->redirect($this->url->link('catalog/offer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function repair() {
		$this->load->language('catalog/offer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/offer');

		if ($this->validateRepair()) {
			$this->model_catalog_offer->repairOffers();

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('catalog/offer', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'sort_order';
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
			'href' => $this->url->link('catalog/offer', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('catalog/offer/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/offer/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['offers'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$offer_total = $this->model_catalog_offer->getTotalOffers();

		$results = $this->model_catalog_offer->getOffers($filter_data);

		foreach ($results as $result) {
			$data['offers'][] = array(
				'offer_id' => $result['offer_id'],
				'title'        => $result['title'],
				'sort_order'  => $result['sort_order'],
				'edit'        => $this->url->link('catalog/offer/edit', 'token=' . $this->session->data['token'] . '&offer_id=' . $result['offer_id'] . $url, 'SSL'),
				'delete'      => $this->url->link('catalog/offer/delete', 'token=' . $this->session->data['token'] . '&offer_id=' . $result['offer_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_title'] = $this->language->get('column_title');
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

		$data['sort_title'] = $this->url->link('catalog/offer', 'token=' . $this->session->data['token'] . '&sort=title' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('catalog/offer', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $offer_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/offer', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($offer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($offer_total - $this->config->get('config_limit_admin'))) ? $offer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $offer_total, ceil($offer_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/offer_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['offer_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_short_description'] = $this->language->get('entry_short_description');
		$data['entry_branch'] = $this->language->get('entry_branch');
		$data['entry_link'] = $this->language->get('entry_link');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_status'] = $this->language->get('entry_status');



		$data['button_link_add'] = $this->language->get('button_link_add');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_link'] = $this->language->get('tab_link');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = array();
		}

        if (isset($this->error['short_description'])) {
            $data['error_short_description'] = $this->error['short_description'];
        } else {
            $data['error_short_description'] = array();
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
			'href' => $this->url->link('catalog/offer', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['offer_id'])) {
			$data['action'] = $this->url->link('catalog/offer/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/offer/edit', 'token=' . $this->session->data['token'] . '&offer_id=' . $this->request->get['offer_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/offer', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['offer_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$offer_info = $this->model_catalog_offer->getOffer($this->request->get['offer_id']);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['offer_description'])) {
			$data['offer_description'] = $this->request->post['offer_description'];
		} elseif (isset($this->request->get['offer_id'])) {
			$data['offer_description'] = $this->model_catalog_offer->getOfferDescriptions($this->request->get['offer_id']);
		} else {
			$data['offer_description'] = array();
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($offer_info)) {
			$data['image'] = $offer_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($offer_info) && is_file(DIR_IMAGE . $offer_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($offer_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($offer_info)) {
			$data['sort_order'] = $offer_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

        if (isset($this->request->post['date_start'])) {
            $data['date_start'] = $this->request->post['date_start'];
        } elseif (!empty($offer_info)) {
            $data['date_start'] = $offer_info['date_start'];
        } else {
            $data['date_start'] = '';
        }

        if (isset($this->request->post['date_end'])) {
            $data['date_end'] = $this->request->post['date_end'];
        } elseif (!empty($offer_info)) {
            $data['date_end'] = $offer_info['date_end'];
        } else {
            $data['date_end'] = '';
        }

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($offer_info)) {
			$data['status'] = $offer_info['status'];
		} else {
			$data['status'] = true;
		}


        if (isset($this->request->post['offer_link'])) {
            $data['offer_links'] = $this->request->post['offer_link'];
        } elseif (isset($this->request->get['offer_id'])) {
            $data['offer_links'] = $this->model_catalog_offer->getOfferLinks($this->request->get['offer_id']);
        } else {
            $data['offer_links'] = array();
        }

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/offer_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/offer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['offer_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 2) || (utf8_strlen($value['title']) > 255)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}

            if ((utf8_strlen($value['short_description']) < 2) || (utf8_strlen($value['short_description']) > 255)) {
                $this->error['short_description'][$language_id] = $this->language->get('error_short_description');
            }
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/offer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateRepair() {
		if (!$this->user->hasPermission('modify', 'catalog/offer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_title'])) {
			$this->load->model('catalog/offer');

			$filter_data = array(
				'filter_title' => $this->request->get['filter_title'],
				'sort'        => 'sort_order',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 10
			);

			$results = $this->model_catalog_offer->getOffers($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'offer_id' => $result['offer_id'],
					'title'        => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'))
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