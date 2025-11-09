<?php
class ControllerModuleArticle extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/article');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_extension_module->addModule('article', $this->request->post);
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_date'] = $this->language->get('text_date');
        $data['text_custom'] = $this->language->get('text_custom');
		$data['text_latest'] = $this->language->get('text_latest');
		$data['text_featured'] = $this->language->get('text_featured');
		$data['text_popular'] = $this->language->get('text_popular');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_selected'] = $this->language->get('text_selected');
        $data['text_grid'] = $this->language->get('text_grid');
        $data['text_list'] = $this->language->get('text_list');
        $data['text_single_featured_list'] = $this->language->get('text_single_featured_list');
        $data['text_single_featured_grid'] = $this->language->get('text_single_featured_grid');
        $data['text_multi_featured_list'] = $this->language->get('text_multi_featured_list');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_blurb'] = $this->language->get('entry_blurb');
		$data['entry_class'] = $this->language->get('entry_class');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_ads_position'] = $this->language->get('entry_ads_position');
		$data['entry_article'] = $this->language->get('entry_article');
		$data['entry_limit'] = $this->language->get('entry_limit');
        $data['entry_sort'] = $this->language->get('entry_sort');
		$data['entry_filter'] = $this->language->get('entry_filter');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_featured_image'] = $this->language->get('entry_featured_image');
		$data['entry_featured_width'] = $this->language->get('entry_featured_width');
		$data['entry_featured_height'] = $this->language->get('entry_featured_height');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_status'] = $this->language->get('entry_status');
        $data['entry_view'] = $this->language->get('entry_view');

		$data['help_article'] = $this->language->get('help_article');

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
            $data['error_name'] = '';
        }

        if (isset($this->error['blurb'])) {
            $data['error_blurb'] = $this->error['blurb'];
        } else {
            $data['error_blurb'] = '';
        }

		if (isset($this->error['class'])) {
			$data['error_class'] = $this->error['class'];
		} else {
			$data['error_class'] = '';
		}

		if (isset($this->error['featured_width'])) {
			$data['error_featured_width'] = $this->error['featured_width'];
		} else {
			$data['error_featured_width'] = '';
		}

		if (isset($this->error['featured_height'])) {
			$data['error_featured_height'] = $this->error['featured_height'];
		} else {
			$data['error_featured_height'] = '';
		}

		if (isset($this->error['width'])) {
			$data['error_width'] = $this->error['width'];
		} else {
			$data['error_width'] = '';
		}

		if (isset($this->error['height'])) {
			$data['error_height'] = $this->error['height'];
		} else {
			$data['error_height'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/article', 'token=' . $this->session->data['token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/article', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('module/article', 'token=' . $this->session->data['token'], true);
		} else {
			$data['action'] = $this->url->link('module/article', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}

		$data['token'] = $this->session->data['token'];

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($module_info)) {
            $data['name'] = $module_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['blurb'])) {
            $data['blurb'] = $this->request->post['blurb'];
        } elseif (!empty($module_info)) {
            $data['blurb'] = $module_info['blurb'];
        } else {
            $data['blurb'] = '';
        }

		if (isset($this->request->post['class'])) {
			$data['class'] = $this->request->post['class'];
		} elseif (!empty($module_info)) {
			$data['class'] = $module_info['class'];
		} else {
			$data['class'] = '';
		}

        if (isset($this->request->post['category_id'])) {
            $data['category_id'] = $this->request->post['category_id'];
        } elseif (!empty($module_info)) {
            $data['category_id'] = $module_info['category_id'];
        } else {
            $data['category_id'] = 0;
        }

        if($data['category_id']) {
            $this->load->model('blog/category');
            $result = $this->model_blog_category->getCategory($data['category_id']);
            $data['category'] =  $result['name'];
        } else {
            $data['category'] = '';
        }

		if (isset($this->request->post['filter'])) {
			$data['filter'] = $this->request->post['filter'];
		} elseif (!empty($module_info)) {
			$data['filter'] = $module_info['filter'];
		} else {
			$data['filter'] = 'latest';
		}

		$this->load->model('blog/article');

		$data['articles'] = array();

		if (!empty($this->request->post['article'])) {
			$articles = $this->request->post['article'];
		} elseif (!empty($module_info['article'])) {
			$articles = $module_info['article'];
		} else {
			$articles = array();
		}

		foreach ($articles as $article_id) {
			$article_info = $this->model_blog_article->getArticle($article_id);

			if ($article_info) {
				$data['articles'][] = array(
					'article_id' => $article_info['article_id'],
					'name'       => $article_info['name']
				);
			}
		}

		if (isset($this->request->post['limit'])) {
			$data['limit'] = $this->request->post['limit'];
		} elseif (!empty($module_info)) {
			$data['limit'] = $module_info['limit'];
		} else {
			$data['limit'] = 5;
		}

		if (isset($this->request->post['featured_width'])) {
			$data['featured_width'] = $this->request->post['featured_width'];
		} elseif (!empty($module_info)) {
			$data['featured_width'] = $module_info['featured_width'];
		} else {
			$data['featured_width'] = '';
		}

		if (isset($this->request->post['featured_height'])) {
			$data['featured_height'] = $this->request->post['featured_height'];
		} elseif (!empty($module_info)) {
			$data['featured_height'] = $module_info['featured_height'];
		} else {
			$data['featured_height'] = '';
		}

		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($module_info)) {
			$data['width'] = $module_info['width'];
		} else {
			$data['width'] = '';
		}

		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($module_info)) {
			$data['height'] = $module_info['height'];
		} else {
			$data['height'] = '';
		}

		if (isset($this->request->post['show_title'])) {
			$data['show_title'] = $this->request->post['show_title'];
		} elseif (!empty($module_info)) {
			$data['show_title'] = $module_info['show_title'];
		} else {
			$data['show_title'] = '';
		}

        if (isset($this->request->post['view'])) {
            $data['view'] = $this->request->post['view'];
        } elseif (!empty($module_info)) {
            $data['view'] = $module_info['view'];
        } else {
            $data['view'] = '';
        }

        if (isset($this->request->post['sort'])) {
            $data['sort'] = $this->request->post['sort'];
        } elseif (!empty($module_info)) {
            $data['sort'] = $module_info['sort'];
        } else {
            $data['sort'] = 'date';
        }

        if (isset($this->request->post['ads_position_id'])) {
            $data['ads_position_id'] = $this->request->post['ads_position_id'];
        } elseif (!empty($module_info)) {
            $data['ads_position_id'] = $module_info['ads_position_id'];
        } else {
            $data['ads_position_id'] = '';
        }

        $this->load->model('design/ads_position');
        $data['ads_positions'] = $this->model_design_ads_position->getAdsPositions();

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/article.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/article')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ((utf8_strlen($this->request->post['blurb']) < 20) || (utf8_strlen($this->request->post['blurb']) > 500)) {
            $this->error['blurb'] = $this->language->get('error_blurb');
        }

        if (utf8_strlen($this->request->post['class']) > 64) {
			$this->error['class'] = $this->language->get('error_class');
		}

		if (!$this->request->post['featured_width']) {
			$this->error['featured_width'] = $this->language->get('error_featured_width');
		}

		if (!$this->request->post['featured_height']) {
			$this->error['featured_height'] = $this->language->get('error_featured_height');
		}

		if (!$this->request->post['width']) {
			$this->error['width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['height']) {
			$this->error['height'] = $this->language->get('error_height');
		}

		return !$this->error;
	}
}
