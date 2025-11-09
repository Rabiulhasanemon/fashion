<?php
class ControllerModuleTabbedCategory extends Controller {
	private $error = array();

	public function index() {
        $this->load->language('module/tabbed_category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (!isset($this->request->get['module_id'])) {
                // Use 'tabbed_category' as code, OpenCart will look for extension/module/tabbed_category controller
                $this->model_extension_module->addModule('tabbed_category', $this->request->post);
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
        $data['text_list'] = $this->language->get('text_list');
        $data['text_tabs'] = $this->language->get('text_tabs');
        $data['text_add_tab'] = $this->language->get('text_add_tab');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_name'] = $this->language->get('entry_name');
        $data['entry_blurb'] = $this->language->get('entry_blurb');
        $data['entry_layout'] = $this->language->get('entry_layout');
        $data['entry_class'] = $this->language->get('entry_class');
        $data['entry_tabs'] = $this->language->get('entry_tabs');
        $data['entry_tab_title'] = $this->language->get('entry_tab_title');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_product'] = $this->language->get('entry_product');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_status'] = $this->language->get('entry_status');
        $data['entry_view'] = $this->language->get('entry_view');
        $data['entry_show_price'] = $this->language->get('entry_show_price');
        $data['entry_show_rating'] = $this->language->get('entry_show_rating');

		$data['help_product'] = $this->language->get('help_product');

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

        if (isset($this->error['url'])) {
            $data['error_url'] = $this->error['url'];
        } else {
            $data['error_url'] = '';
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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/tabbed_category', 'token=' . $this->session->data['token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/tabbed_category', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}

        if (!isset($this->request->get['module_id'])) {
            $data['action'] = $this->url->link('module/tabbed_category', 'token=' . $this->session->data['token'], 'SSL');
		} else {
            $data['action'] = $this->url->link('module/tabbed_category', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

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
		} elseif (!empty($module_info) && isset($module_info['blurb'])) {
			$data['blurb'] = $module_info['blurb'];
		} else {
			$data['blurb'] = '';
		}

        // layout type
        if (isset($this->request->post['layout'])) {
            $data['layout'] = $this->request->post['layout'];
        } elseif (!empty($module_info)) {
            $data['layout'] = isset($module_info['layout']) ? $module_info['layout'] : 'grid';
        } else {
            $data['layout'] = 'grid';
        }

		if (isset($this->request->post['class'])) {
			$data['class'] = $this->request->post['class'];
		} elseif (!empty($module_info)) {
			$data['class'] = $module_info['class'];
		} else {
			$data['class'] = '';
		}

        $this->load->model('catalog/category');

        $this->load->model('catalog/product');

        // Tabs array structure: [ ['title'=>'Featured','category_id'=>X,'products'=>[ids]] ]
        if (isset($this->request->post['tabs'])) {
            $data['tabs'] = $this->request->post['tabs'];
        } elseif (!empty($module_info) && isset($module_info['tabs'])) {
            $data['tabs'] = $module_info['tabs'];
        } else {
            $data['tabs'] = array();
        }

        // hydrate names for category and products for display in admin UI
        $data['tab_items'] = array();
        foreach ($data['tabs'] as $idx => $tab) {
            $tab_entry = array('title' => isset($tab['title']) ? $tab['title'] : '','category'=>array(),'products'=>array());
            if (!empty($tab['category_id'])) {
                $cat = $this->model_catalog_category->getCategory($tab['category_id']);
                if ($cat) {
                    $tab_entry['category'] = array('category_id'=>$cat['category_id'],'name'=>$cat['name']);
                }
            }
            if (!empty($tab['products']) && is_array($tab['products'])) {
                foreach ($tab['products'] as $pid) {
                    $p = $this->model_catalog_product->getProduct($pid);
                    if ($p) {
                        $tab_entry['products'][] = array('product_id'=>$p['product_id'],'name'=>$p['name']);
                    }
                }
            }
            $data['tab_items'][$idx] = $tab_entry;
        }

		if (isset($this->request->post['limit'])) {
			$data['limit'] = $this->request->post['limit'];
		} elseif (!empty($module_info)) {
			$data['limit'] = $module_info['limit'];
		} else {
			$data['limit'] = 5;
		}

		if (isset($this->request->post['product'])) {
			$product_ids = $this->request->post['product'];
		} elseif (!empty($module_info) && isset($module_info['product'])) {
			$product_ids = $module_info['product'];
		} else {
			$product_ids = array();
		}
		
		// Convert product IDs to product arrays with names
		$data['products'] = array();
		if (!empty($product_ids) && is_array($product_ids)) {
			foreach ($product_ids as $product_id) {
				// Handle case where product_id might be in an array
				$pid = is_array($product_id) ? (isset($product_id['product_id']) ? $product_id['product_id'] : (isset($product_id[0]) ? $product_id[0] : 0)) : $product_id;
				$pid = (int)$pid;
				
				if ($pid > 0) {
					$product_info = $this->model_catalog_product->getProduct($pid);
					if ($product_info && isset($product_info['name'])) {
						$data['products'][] = array(
							'product_id' => $pid,
							'name' => $product_info['name']
						);
					}
				}
			}
		}

		if (isset($this->request->post['date_end'])) {
			$data['date_end'] = $this->request->post['date_end'];
		} elseif (!empty($module_info) && isset($module_info['date_end'])) {
			$data['date_end'] = $module_info['date_end'];
		} else {
			$data['date_end'] = '';
		}

		if (isset($this->request->post['url'])) {
			$data['url'] = $this->request->post['url'];
		} elseif (!empty($module_info) && isset($module_info['url'])) {
			$data['url'] = $module_info['url'];
		} else {
			$data['url'] = '';
		}

		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($module_info)) {
			$data['width'] = $module_info['width'];
		} else {
			$data['width'] = 200;
		}

		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($module_info)) {
			$data['height'] = $module_info['height'];
		} else {
			$data['height'] = 200;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

        if (isset($this->request->post['view'])) {
            $data['view'] = $this->request->post['view'];
        } elseif (!empty($module_info)) {
            $data['view'] = $module_info['view'];
        } else {
            $data['view'] = '';
        }

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/tabbed_category.tpl', $data));
	}

    // Ensure permissions exist when clicking Install from Extensions list
    public function install() {
        $this->load->model('user/user_group');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'module/tabbed_category');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'module/tabbed_category');
    }

    public function uninstall() {
        // Nothing required; settings and instances are managed by core uninstall
    }

	protected function validate() {
        if (!$this->user->hasPermission('modify', 'module/tabbed_category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

        if (!empty($this->request->post['blurb']) && (utf8_strlen($this->request->post['blurb']) > 500)) {
            $this->error['blurb'] = $this->language->get('error_blurb');
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