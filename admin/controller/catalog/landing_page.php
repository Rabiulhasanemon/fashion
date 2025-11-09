<?php
class ControllerCatalogLandingPage extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/landing_page');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/landing_page');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/landing_page');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/landing_page');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $landing_page_id = $this->model_catalog_landing_page->addLandingPage($this->request->post);

            // Add to activity log
            $this->load->model('user/user');

            $activity_data = array(
                '%user_id' => $this->user->getId(),
                '%landing_page_id' => $landing_page_id,
                '%name'        => $this->user->getFirstName() . ' ' . $this->user->getLastName()
            );

            $this->model_user_user->addActivity($this->user->getId(), 'add_landing_page', $activity_data, $landing_page_id);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

            if (isset($this->request->get['filter_title'])) {
                $url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
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

			$this->response->redirect($this->url->link('catalog/landing_page', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/landing_page');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/landing_page');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_landing_page->editLandingPage($this->request->get['landing_page_id'], $this->request->post);

            // Add to activity log
            $this->load->model('user/user');

            $activity_data = array(
                '%user_id' => $this->user->getId(),
                '%landing_page_id' => $this->request->get['landing_page_id'],
                '%name'        => $this->user->getFirstName() . ' ' . $this->user->getLastName()
            );

            $this->model_user_user->addActivity($this->user->getId(), 'edit_landing_page', $activity_data, $this->request->get['landing_page_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

            if (isset($this->request->get['filter_title'])) {
                $url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
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

			$this->response->redirect($this->url->link('catalog/landing_page', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/landing_page');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/landing_page');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $landing_page_id) {
				$this->model_catalog_landing_page->deleteLandingPage($landing_page_id);

                // Add to activity log
                $this->load->model('user/user');
                $activity_data = array(
                    '%user_id' => $this->user->getId(),
                    '%landing_page_id' => $landing_page_id,
                    '%name'        => $this->user->getFirstName() . ' ' . $this->user->getLastName()
                );

                $this->model_user_user->addActivity($this->user->getId(), 'delete_landing_page', $activity_data, $landing_page_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

            if (isset($this->request->get['filter_title'])) {
                $url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
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

			$this->response->redirect($this->url->link('catalog/landing_page', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {

        if (isset($this->request->get['filter_title'])) {
            $filter_title = $this->request->get['filter_title'];
        } else {
            $filter_title = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'id.title';
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

        if (isset($this->request->get['filter_title'])) {
            $url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
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
			'href' => $this->url->link('catalog/landing_page', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('catalog/landing_page/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/landing_page/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['landing_pages'] = array();

		$filter_data = array(
            'filter_title' => $filter_title,
            'filter_status' => $filter_status,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$landing_page_total = $this->model_catalog_landing_page->getTotalLandingPages();

		$results = $this->model_catalog_landing_page->getLandingPages($filter_data);

		foreach ($results as $result) {
			$data['landing_pages'][] = array(
				'landing_page_id' => $result['landing_page_id'],
				'title'          => $result['title'],
				'edit'           => $this->url->link('catalog/landing_page/edit', 'token=' . $this->session->data['token'] . '&landing_page_id=' . $result['landing_page_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['column_title'] = $this->language->get('column_title');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_filter'] = $this->language->get('button_filter');
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

		$data['sort_title'] = $this->url->link('catalog/landing_page', 'token=' . $this->session->data['token'] . '&sort=id.title' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

        $data['token'] = $this->session->data['token'];

		$pagination = new Pagination();
		$pagination->total = $landing_page_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/landing_page', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($landing_page_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($landing_page_total - $this->config->get('config_limit_admin'))) ? $landing_page_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $landing_page_total, ceil($landing_page_total / $this->config->get('config_limit_admin')));

        $data['filter_title'] = $filter_title;
        $data['filter_status'] = $filter_status;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/landing_page_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['landing_page_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_content_top'] = $this->language->get('text_content_top');
        $data['text_content_bottom'] = $this->language->get('text_content_bottom');
        $data['text_column_left'] = $this->language->get('text_column_left');
        $data['text_column_right'] = $this->language->get('text_column_right');

		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_summary'] = $this->language->get('entry_summary');
		$data['entry_class'] = $this->language->get('entry_class');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
        $data['entry_module'] = $this->language->get('entry_module');
        $data['entry_position'] = $this->language->get('entry_position');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_feature_image'] = $this->language->get('entry_feature_image');
		$data['entry_video_text'] = $this->language->get('entry_video_text');
		$data['entry_video_url'] = $this->language->get('entry_video_url');
		$data['entry_question_text'] = $this->language->get('entry_question_text');
		$data['entry_question'] = $this->language->get('entry_question');
		$data['entry_answer'] = $this->language->get('entry_answer');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['help_keyword'] = $this->language->get('help_keyword');
		$data['help_product'] = $this->language->get('help_product');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_module_add'] = $this->language->get('button_module_add');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_image_add'] = $this->language->get('button_image_add');
        $data['button_faq_add'] = $this->language->get('button_faq_add');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_module'] = $this->language->get('tab_module');
		$data['tab_image'] = $this->language->get('tab_image');
		$data['tab_faq'] = $this->language->get('tab_faq');

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

		if (isset($this->error['description'])) {
			$data['error_description'] = $this->error['description'];
		} else {
			$data['error_description'] = array();
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
			'href' => $this->url->link('catalog/landing_page', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['landing_page_id'])) {
			$data['action'] = $this->url->link('catalog/landing_page/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/landing_page/edit', 'token=' . $this->session->data['token'] . '&landing_page_id=' . $this->request->get['landing_page_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/landing_page', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['landing_page_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$landing_page_info = $this->model_catalog_landing_page->getLandingPage($this->request->get['landing_page_id']);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['landing_page_description'])) {
			$data['landing_page_description'] = $this->request->post['landing_page_description'];
		} elseif (isset($this->request->get['landing_page_id'])) {
			$data['landing_page_description'] = $this->model_catalog_landing_page->getLandingPageDescriptions($this->request->get['landing_page_id']);
		} else {
			$data['landing_page_description'] = array();
		}

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['landing_page_store'])) {
			$data['landing_page_store'] = $this->request->post['landing_page_store'];
		} elseif (isset($this->request->get['landing_page_id'])) {
			$data['landing_page_store'] = $this->model_catalog_landing_page->getLandingPageStores($this->request->get['landing_page_id']);
		} else {
			$data['landing_page_store'] = array(0);
		}

		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($landing_page_info)) {
			$data['keyword'] = $landing_page_info['keyword'];
		} else {
			$data['keyword'] = '';
		}

        if (isset($this->request->post['class'])) {
            $data['class'] = $this->request->post['class'];
        } elseif (!empty($landing_page_info)) {
            $data['class'] = $landing_page_info['class'];
        } else {
            $data['class'] = '';
        }

		if (isset($this->request->post['question_text'])) {
			$data['question_text'] = $this->request->post['question_text'];
		} elseif (!empty($landing_page_info)) {
			$data['question_text'] = $landing_page_info['question_text'];
		} else {
			$data['question_text'] = '';
		}

        if (isset($this->request->post['video_text'])) {
			$data['video_text'] = $this->request->post['video_text'];
		} elseif (!empty($landing_page_info)) {
			$data['video_text'] = $landing_page_info['video_text'];
		} else {
			$data['video_text'] = '';
		}

        if (isset($this->request->post['video_url'])) {
			$data['video_url'] = $this->request->post['video_url'];
		} elseif (!empty($landing_page_info)) {
			$data['video_url'] = $landing_page_info['video_url'];
		} else {
			$data['video_url'] = '';
		}

        if (isset($this->request->post['landing_page_product'])) {
            $landing_page_products = $this->request->post['landing_page_product'];
        } elseif (isset($this->request->get['landing_page_id'])) {
            $landing_page_products = $this->model_catalog_landing_page->getLandingPageProduct($this->request->get['landing_page_id']);
        } else {
            $landing_page_products = array();
        }

        $data['landing_page_products'] = array();

        foreach ($landing_page_products as $landing_page_product_id) {
            $this->load->model('catalog/product');
            $landing_product_info = $this->model_catalog_product->getProduct($landing_page_product_id);

            if ($landing_product_info) {
                $data['landing_page_products'][] = array(
                    'landing_page_id' => $landing_product_info['product_id'],
                    'name'       => $landing_product_info['name']
                );
            }
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($landing_page_info)) {
            $data['image'] = $landing_page_info['image'];
        } else {
            $data['image'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['image_preview'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($landing_page_info) && is_file(DIR_IMAGE . $landing_page_info['image'])) {
            $data['image_preview'] = $this->model_tool_image->resize($landing_page_info['image'], 100, 100);
        } else {
            $data['image_preview'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (isset($this->request->post['featured_image'])) {
            $data['featured_image'] = $this->request->post['featured_image'];
        } elseif (!empty($landing_page_info)) {
            $data['featured_image'] = $landing_page_info['featured_image'];
        } else {
            $data['featured_image'] = '';
        }

        if (isset($this->request->post['featured_image']) && is_file(DIR_IMAGE . $this->request->post['featured_image'])) {
            $data['featured_image_preview'] = $this->model_tool_image->resize($this->request->post['featured_image'], 100, 100);
        } elseif (!empty($landing_page_info) && is_file(DIR_IMAGE . $landing_page_info['featured_image'])) {
            $data['featured_image_preview'] = $this->model_tool_image->resize($landing_page_info['featured_image'], 100, 100);
        } else {
            $data['featured_image_preview'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($landing_page_info)) {
			$data['status'] = $landing_page_info['status'];
		} else {
			$data['status'] = true;
		}

        if (isset($this->request->post['landing_page_module'])) {
            $data['landing_page_modules'] = $this->request->post['landing_page_module'];
        } elseif (isset($this->request->get['landing_page_id'])) {
            $data['landing_page_modules'] = $this->model_catalog_landing_page->getLandingPageModules($this->request->get['landing_page_id']);
        } else {
            $data['landing_page_modules'] = array();
        }

        $this->load->model('extension/extension');

        $this->load->model('extension/module');

        $data['extensions'] = array();

        // Get a list of installed modules
        $extensions = $this->model_extension_extension->getInstalled('module');

        // Add all the modules which have multiple settings for each module
        foreach ($extensions as $code) {
            $this->load->language('module/' . $code);

            $module_data = array();

            $modules = $this->model_extension_module->getModulesByCode($code);

            foreach ($modules as $module) {
                $module_data[] = array(
                    'name' => $this->language->get('heading_title') . ' &gt; ' . $module['name'],
                    'code' => $code . '.' .  $module['module_id']
                );
            }

            if ($this->config->has($code . '_status') || $module_data) {
                $data['extensions'][] = array(
                    'name'   => $this->language->get('heading_title'),
                    'code'   => $code,
                    'module' => $module_data
                );
            }
        }

        // Images
        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['landing_page_image'])) {
            $landing_page_images = $this->request->post['landing_page_image'];
        } elseif (isset($this->request->get['landing_page_id'])) {
            $landing_page_images = $this->model_catalog_landing_page->getLandingPageProductImages($this->request->get['landing_page_id']);
        } else {
            $landing_page_images = array();
        }

        $data['landing_page_images'] = array();

        foreach ($landing_page_images as $landing_page_image) {
            if (is_file(DIR_IMAGE . $landing_page_image['image'])) {
                $image = $landing_page_image['image'];
                $thumb = $landing_page_image['image'];
            } else {
                $image = '';
                $thumb = 'no_image.png';
            }

            $data['landing_page_images'][] = array(
                'image'      => $image,
                'thumb'      => $this->model_tool_image->resize($thumb, 100, 100),
                'sort_order' => $landing_page_image['sort_order']
            );
        }


        $this->load->model('catalog/landing_page');
        if (isset($this->request->post['landing_page_faq'])) {
            $landing_page_faqs = $this->request->post['landing_page_faq'];
        } elseif (isset($this->request->get['landing_page_id'])) {
            $landing_page_faqs = $this->model_catalog_landing_page->getLandingPageFaqs($this->request->get['landing_page_id']);
        } else {
            $landing_page_faqs = array();
        }

        $data['landing_page_faqs'] = array();

        foreach ($landing_page_faqs as $landing_page_faq) {
            $data['landing_page_faqs'][] = array(
                'landing_page_faq_description' => $landing_page_faq['landing_page_faq_description'],
                'sort_order'               => $landing_page_faq['sort_order']
            );
        }


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/landing_page_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/landing_page')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['landing_page_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 64)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}

			if (utf8_strlen($value['description']) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}

			if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}
		}

		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['landing_page_id']) && $url_alias_info['query'] != 'landing_page_id=' . $this->request->get['landing_page_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['landing_page_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/landing_page')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}