<?php
class ControllerOperatorQuote extends Controller {
	private $error = array();

	public function __construct($registry)
    {
        parent::__construct($registry);
        $this->operator = new Operator($registry);
        $this->pc_builder = new PcBuilder($registry);
    }

    public function index() {
		if (!$this->operator->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('operator/quote', '', 'SSL');

			$this->response->redirect($this->url->link('operator/login', '', 'SSL'));
		}

		if(isset($this->request->get["filter_status"])) {
		    $filter_status = trim($this->request->get["filter_status"]);
        } else {
            $filter_status = "open";
        }

		$this->load->language('operator/quote');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_operator'),
			'href' => $this->url->link('operator/operator', '', 'SSL')
		);

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if($filter_status) {
		    $url .= "&filter_status=" . $filter_status;
        }

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('operator/quote', $url, 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_empty'] = $this->language->get('text_empty');

		$data['column_quote_id'] = $this->language->get('column_quote_id');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_quote_by'] = $this->language->get('column_quote_by');
		$data['column_telephone'] = $this->language->get('column_telephone');
		$data['column_email'] = $this->language->get('column_email');
		$data['column_products'] = $this->language->get('column_products');
		$data['column_total'] = $this->language->get('column_total');

		$data['button_quote'] = $this->language->get('button_quote');
		$data['button_duplicate'] = $this->language->get('button_duplicate');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['quotes'] = array();

		$this->load->model('catalog/quote');

		$filter_data = array('filter_status' => $filter_status);

		$quote_total = $this->model_catalog_quote->getTotalQuotes($filter_data);

		$results = $this->model_catalog_quote->getQuotes($filter_data, ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$product_total = $this->model_catalog_quote->getTotalQuoteProductsByQuoteId($result['quote_id']);

			$data['quotes'][] = array(
				'quote_id'   => $result['quote_id'],
				'name'       => $result['firstname'] . ' ' . $result['lastname'],
				'telephone'   => $result['telephone'],
				'operator_name'   => $result['operator_name'],
				'email'   => $result['email'],
				'products'   => ($product_total),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'href'       => $this->url->link('operator/quote/quote', $url.'&quote_id=' . $result['quote_id'], 'SSL'),
                'duplicate'  => $this->url->link('operator/quote/duplicate', $url.'&quote_id=' . $result['quote_id'], 'SSL')
			);
		}

		$url = "";
		if($filter_status) {
		    $url .= "filter_status=" . $filter_status;
        }
		$pagination = new Pagination();
		$pagination->total = $quote_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('operator/quote', $url . '&page={page}', 'SSL');

		$data['filters'] = array();
        $data['filters'][] = array(
            'text' => $this->language->get('text_all'),
            'href' => $this->url->link('operator/quote', 'filter_status=all', 'SSL'),
            'value' => 'all'
        );

        $data['filters'][] = array(
            'text' => $this->language->get('text_open'),
            'href' => $this->url->link('operator/quote', 'filter_status=open', 'SSL'),
            'value' => 'open'
        );

        $data['filters'][] = array(
            'text' => $this->language->get('text_sent'),
            'href' => $this->url->link('operator/quote', 'filter_status=sent', 'SSL'),
            'value' => 'sent'
        );
        $data['filters'][] = array(
            'text' => $this->language->get('text_duplicate'),
            'href' => $this->url->link('operator/quote', 'filter_status=duplicate', 'SSL'),
            'value' => 'duplicate'
        );

        $data['filter_status'] = $filter_status;

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($quote_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($quote_total - 10)) ? $quote_total : ((($page - 1) * 10) + 10), $quote_total, ceil($quote_total / 10));

		$data['continue'] = $this->url->link('operator/operator', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/operator/quote_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/operator/quote_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/operator/quote_list.tpl', $data));
		}
	}

	public function add() {
        if (!$this->operator->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('operator/quote/add', 'quote_id=' . $quote_id, 'SSL');

            $this->response->redirect($this->url->link('operator/login', '', 'SSL'));
        }

        $this->load->language("tool/pc_builder");
        $this->load->language('operator/quote');
        $this->load->model("tool/pc_builder");

        $results = $this->model_tool_pc_builder->getComponents(array());
        $is_condition_failed = false;

        $data = array();

        $quote_data = array();

        $quote_data['products'] = [];
        $this->load->model("catalog/product");

        foreach ($results as $result) {
            $component_product = $this->pc_builder->getProduct($result['component_id']);
            if ($result['is_required'] && $component_product == null) {
                $is_condition_failed = true;
                $this->session->data['error'] = sprintf($this->language->get('error_please_choose'), $result['name']);
                break;
            }
            if ($component_product) {
                $product_info = $this->model_catalog_product->getProduct($component_product);
                $quote_data['products'][] = array(
                    'product_id' => $component_product,
                    'name' => $product_info['name'],
                    'model' => $product_info['model'],
                    'quantity' => 1,
                    'price' => $product_info['price'],
                    'tax' => 0.0
                );
            }
        }

        if ($is_condition_failed) {
            $this->response->redirect($this->url->link('tool/pc_builder'));
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST'  && $this->validate()) {
            $this->load->model("catalog/quote");
            $quote_data['fax'] = '';
            $quote_data['operator_id'] = $this->operator->getId();
            $quote_data['customer_id'] = 0;
            $quote_data['customer_group_id'] = 0;
            $quote_id = $this->model_catalog_quote->save(array_merge($quote_data, $this->request->post));
            $this->response->redirect($this->url->link('operator/quote/quote', 'quote_id='. $quote_id, 'SSL'));
        }


        $this->document->setTitle($this->language->get('text_add_quote'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_operator'),
            'href' => $this->url->link('operator/operator', '', 'SSL')
        );

        $url = '';

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_quote'),
            'href' => $this->url->link('operator/quote', $url, 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_add_quote'),
            'href' => $this->url->link('operator/quote/add', $url, 'SSL')
        );

        $data['heading_title'] = $this->language->get('text_add_quote');


        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_designation'] = $this->language->get('entry_designation');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_back'] = $this->language->get('button_back');
        $data['button_upload'] = $this->language->get('button_upload');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['firstname'])) {
            $data['error_firstname'] = $this->error['firstname'];
        } else {
            $data['error_firstname'] = '';
        }

        if (isset($this->error['lastname'])) {
            $data['error_lastname'] = $this->error['lastname'];
        } else {
            $data['error_lastname'] = '';
        }

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        if (isset($this->error['telephone'])) {
            $data['error_telephone'] = $this->error['telephone'];
        } else {
            $data['error_telephone'] = '';
        }

        $data['action'] = $this->url->link('operator/quote/add', '', 'SSL');

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        } else {
            $data['lastname'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->request->post['telephone'])) {
            $data['telephone'] = $this->request->post['telephone'];
        } else {
            $data['telephone'] = '';
        }

        $data['back'] = $this->url->link('tool/pc_builder', '', 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/operator/quote_add.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/operator/quote_add.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/operator/quote_add.tpl', $data));
        }
    }

    protected function validate() {
        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if (utf8_strlen($this->request->post['email']) > 1 && !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }
        return !$this->error;
    }

	public function quote() {
		$this->load->language('operator/quote');

		if (isset($this->request->get['quote_id'])) {
			$quote_id = $this->request->get['quote_id'];
		} else {
			$quote_id = 0;
		}

		if (!$this->operator->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('operator/quote/info', 'quote_id=' . $quote_id, 'SSL');

			$this->response->redirect($this->url->link('operator/login', '', 'SSL'));
		}

		$this->load->model('catalog/quote');

		$quote_info = $this->model_catalog_quote->getQuote($quote_id);

		if ($quote_info) {
			$this->document->setTitle($this->language->get('text_quote'));

            $data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_operator'),
				'href' => $this->url->link('operator/operator', '', 'SSL')
			);

			$url = '';


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if(isset($this->request->get["filter_status"])) {
			    $url .= "filter_status=" . $this->request->get["filter_status"];
            }
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('operator/quote', $url, 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_quote'),
				'href' => $this->url->link('operator/quote/quote', 'quote_id=' . $this->request->get['quote_id'] . $url, 'SSL')
			);

			$data['heading_title'] = $this->language->get('text_quote');

			$data['text_customer_name'] = $this->language->get('text_customer_name');
			$data['text_discount'] = $this->language->get('text_discount');
			$data['text_total'] = $this->language->get('text_total');
			$data['text_subtotal'] = $this->language->get('text_subtotal');
			$data['text_customer_email'] = $this->language->get('text_customer_email');
			$data['text_customer_telephone'] = $this->language->get('text_customer_telephone');
			$data['text_quote_detail'] = $this->language->get('text_quote_detail');
			$data['text_quote_id'] = $this->language->get('text_quote_id');
			$data['text_date_added'] = $this->language->get('text_date_added');

			$data['tab_quote'] =  $this->language->get('tab_quote');
			$data['tab_history'] =  $this->language->get('tab_history');

			$data['column_name'] = $this->language->get('column_name');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_quote_price'] = $this->language->get('column_quote_price');
			$data['column_total'] = $this->language->get('column_total');
			$data['column_action'] = $this->language->get('column_action');
			$data['column_date_added'] = $this->language->get('column_date_added');
			$data['column_status'] = $this->language->get('column_status');
			$data['column_comment'] = $this->language->get('column_comment');

			$data["entry_comment"] = $this->language->get('entry_comment');

			$data['button_send_quote'] = $this->language->get('button_send_quote');
			$data['button_print'] = $this->language->get('button_print');

			if (isset($this->session->data['error'])) {
				$data['error_warning'] = $this->session->data['error'];

				unset($this->session->data['error']);
			} else {
				$data['error_warning'] = '';
			}

			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}

			$data['quote_id'] = $this->request->get['quote_id'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($quote_info['date_added']));


			$data['customer_name'] = $quote_info['firstname'] . " " .$quote_info['lastname'];
			$data['customer_email'] = $quote_info['email'];
			$data['customer_telephone'] = $quote_info['telephone'];
			$data['comment'] = $quote_info['comment'];

			$this->load->model('catalog/product');
			$this->load->model('tool/upload');

			// Products
			$data['products'] = array();
			$products = $this->model_catalog_quote->getQuoteProducts($this->request->get['quote_id']);
			$subtotal_amount = 0;
			$total_discount = 0;
			foreach ($products as $product) {
				$option_data = array();
                $total = $product['price'] * $product['quantity'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0);
				if($product['quote_price'] > 0) {
				    $discount = $total - $product['quote_price'];
                } else {
				    $discount = 0;
                }
                if($discount > 0) {
				    $total_discount += $discount;
                }
				$data['products'][] = array(
				    'quote_product_id' => $product["quote_product_id"],
					'name'     => $product['name'],
					'model'    => $product['model'],
					'option'   => $option_data,
					'price_raw' => $product['price'],
					'quote_price' => $product['quote_price'] > 0 ? $product['quote_price'] : "",
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0)),
					'total'    => $this->currency->format($total),
				);
				$subtotal_amount += $total;
			}

            $data['subtotal_raw'] = $subtotal_amount;
            $data['subtotal'] = $this->currency->format($subtotal_amount);
			$data['total_discount'] = $this->currency->format($total_discount);
            $data['total'] = $this->currency->format($subtotal_amount - $total_discount);
			$data['currency_right_symbol'] = $this->currency->getSymbolRight();
            $data["histories"] = array();
            $histories = $this->model_catalog_quote->getQuoteHistories($this->request->get['quote_id']);

            foreach ($histories as $history) {
                $data['histories'][] = array(
                    'date_added' => date($this->language->get('date_format_short'), strtotime($history['date_added'])),
                    'comment' => $history['comment']
                );
            }

			$data['action'] =  $this->url->link('operator/quote/requote', "quote_id=" . (int)$this->request->get['quote_id'], 'SSL');
			$data['print'] =  $this->url->link('operator/quote/print_quote', "quote_id=" . (int)$this->request->get['quote_id'], 'SSL');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/operator/quote_info.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/operator/quote_info.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/operator/quote_info.tpl', $data));
			}
		} else {
			$this->document->setTitle($this->language->get('text_quote'));

			$data['heading_title'] = $this->language->get('text_quote');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_operator'),
				'href' => $this->url->link('operator/operator', '', 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('operator/quote', '', 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_quote'),
				'href' => $this->url->link('operator/quote/info', 'quote_id=' . $quote_id, 'SSL')
			);

			$data['continue'] = $this->url->link('operator/quote', '', 'SSL');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}

	public function requote() {
        if (!$this->operator->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('operator/quote', '', 'SSL');
            $this->response->redirect($this->url->link('operator/login', '', 'SSL'));
        }

        if($this->request->get['quote_id']) {
            $quote_id = $this->request->get['quote_id'];
        } else {
            $quote_id = null;
        }
        if($quote_id && $this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->load->model("catalog/quote");

            $this->model_catalog_quote->requote(array(
                'quote_id' => $quote_id,
                'quote_prices' => $this->request->post['quote_price'],
                'operator_id' => $this->operator->getId(),
                'comment' => $this->request->post['comment'],
                'operator_name' => $this->operator->getFirstName() . " ". $this->operator->getLastName()
            ));

            $this->model_catalog_quote->mail($quote_id);
        }
        $this->response->redirect($this->url->link('operator/quote'));
	}


    public function print_quote() {
        if (!$this->operator->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('operator/quote', '', 'SSL');
            $this->response->redirect($this->url->link('operator/login', '', 'SSL'));
        }

        if($this->request->get['quote_id']) {
            $quote_id = $this->request->get['quote_id'];
        } else {
            $quote_id = null;
        }

        $this->load->model("catalog/quote");

        if($quote_id && $this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->model_catalog_quote->requote(array(
                'quote_id' => $quote_id,
                'quote_prices' => $this->request->post['quote_price'],
                'operator_id' => $this->operator->getId(),
                'comment' => $this->request->post['comment'],
                'operator_name' => $this->operator->getFirstName() . " ". $this->operator->getLastName()
            ));
        }

        $quote_info = $this->model_catalog_quote->getQuote($quote_id);
        $this->load->language('operator/quote');

        $data['text_quotation'] = $this->language->get('text_quotation');
        $data['text_customer_name'] = $this->language->get('text_customer_name');
        $data['text_discount'] = $this->language->get('text_discount');
        $data['text_total'] = $this->language->get('text_total');
        $data['text_subtotal'] = $this->language->get('text_subtotal');
        $data['text_customer_email'] = $this->language->get('text_customer_email');
        $data['text_customer_telephone'] = $this->language->get('text_customer_telephone');
        $data['text_quote_detail'] = $this->language->get('text_quote_detail');
        $data['text_quote_id'] = $this->language->get('text_quote_id');
        $data['text_quote_by'] = $this->language->get('text_quote_by');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_email'] = $this->language->get('text_email');
        $data['text_date_added'] = $this->language->get('text_date_added');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_quote_price'] = $this->language->get('column_quote_price');
        $data['column_total'] = $this->language->get('column_total');

        $data['quote_id'] = $this->request->get['quote_id'];
        $data['date_added'] = date($this->language->get('date_format_short'), strtotime($quote_info['date_added']));


        $data['customer_name'] = $quote_info['firstname'] . " " .$quote_info['lastname'];
        $data['customer_email'] = $quote_info['email'];
        $data['customer_telephone'] = $quote_info['telephone'];
        $data['comment'] = $quote_info['comment'];

        $data['operator_name'] = $this->operator->getFirstName() . " " . $this->operator->getLastName();
        $data['operator_email'] = $this->operator->getEmail();
        $data['operator_telephone'] = $this->operator->getTelephone();

        $this->load->model('catalog/product');
        $this->load->model('tool/upload');

        // Products
        $data['products'] = array();
        $products = $this->model_catalog_quote->getQuoteProducts($this->request->get['quote_id']);
        $subtotal_amount = 0;
        $total_discount = 0;
        foreach ($products as $product) {
            $option_data = array();
            $total = $product['price'] * $product['quantity'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0);
            if($product['quote_price'] > 0) {
                $discount = $total - $product['quote_price'];
            } else {
                $discount = 0;
            }
            if($discount > 0) {
                $total_discount += $discount;
            }
            $data['products'][] = array(
                'quote_product_id' => $product["quote_product_id"],
                'name'     => $product['name'],
                'model'    => $product['model'],
                'option'   => $option_data,
                'quote_price' => $product['quote_price'] > 0 ? $product['quote_price'] : "",
                'quantity' => $product['quantity'],
                'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0)),
                'total'    => $this->currency->format($total),
            );
            $subtotal_amount += $total;
        }

        $data['subtotal'] = $this->currency->format($subtotal_amount);
        $data['total_discount'] = $this->currency->format($total_discount);
        $data['total'] = $this->currency->format($subtotal_amount - $total_discount);
        $data['currency_right_symbol'] = $this->currency->getSymbolRight();
        $data["histories"] = array();

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/operator/quotation.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/operator/quotation.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/operator/quotation.tpl', $data));
        }
    }

	public function duplicate() {
        if (!$this->operator->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('operator/quote', '', 'SSL');
            $this->response->redirect($this->url->link('operator/login', '', 'SSL'));
        }

        if($this->request->get['quote_id']) {
            $quote_id = $this->request->get['quote_id'];
        } else {
            $quote_id = null;
        }

        $this->load->model("catalog/quote");
        $this->model_catalog_quote->changeStatus(array(
            'quote_id' => $quote_id,
            'operator_id' => $this->operator->getId(),
            'status' => 'duplicate'
        ));
        $this->response->redirect($this->url->link('operator/quote'));
    }
}