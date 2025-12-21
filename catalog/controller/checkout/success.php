<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {
		// Debug logging
		error_log('=== CHECKOUT SUCCESS PAGE CALLED ===');
		error_log('Order ID in session: ' . (isset($this->session->data['order_id']) ? $this->session->data['order_id'] : 'NOT SET'));
		
		$this->load->language('checkout/success');
        $data = array();
        
        // Ensure order_id is preserved - don't unset it until after we've loaded the order data
        $order_id = isset($this->session->data['order_id']) ? $this->session->data['order_id'] : 0;
        error_log('Order ID to process: ' . $order_id);
        
		if ($order_id && $order_id > 0) {
			$this->cart->clear();
            $this->load->model("checkout/order");
            
            error_log('Loading order info for ID: ' . $order_id);
            $order_info = $this->model_checkout_order->getOrder($order_id);
            
            if (!$order_info || empty($order_info)) {
                error_log('ERROR: Order not found for ID: ' . $order_id);
                // Still show success page even if order not found
                $order_info = array();
            } else {
                error_log('Order found: ' . print_r($order_info, true));
            }
			$this->load->model('account/activity');
            if ($this->customer->isLogged()) {
				$activity_data = array(
					'customer_id' => $this->customer->getId(),
					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
					'order_id'    => $this->session->data['order_id']
				);

				$this->model_account_activity->addActivity('order_account', $activity_data);
			} else {

				$activity_data = array(
					'name'     => (isset($order_info['firstname']) ? $order_info['firstname'] : '') . ' ' . (isset($order_info['lastname']) ? $order_info['lastname'] : ''),
					'order_id' => $order_id
				);
				$this->model_account_activity->addActivity('order_guest', $activity_data);
			}
            $data['order'] = $order_info;
            
            if ($order_id && $order_id > 0) {
                $data['order_products'] = $this->model_checkout_order->getOrderProducts($order_id);
                error_log('Loaded ' . count($data['order_products']) . ' order products');
                
                // Ensure totals are properly formatted (getOrder already includes totals, but format them)
                if (isset($order_info['totals']) && is_array($order_info['totals'])) {
                    error_log('Order totals already in order_info: ' . count($order_info['totals']));
                    $data['order']['totals'] = $order_info['totals'];
                } else {
                    // Fallback: get totals from order_total table
                    $order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
                    $data['order']['totals'] = array();
                    foreach ($order_total_query->rows as $total) {
                        $data['order']['totals'][] = array(
                            'title' => $total['title'],
                            'text'  => $this->currency->format($total['value'], isset($order_info['currency_code']) ? $order_info['currency_code'] : 'BDT', isset($order_info['currency_value']) ? $order_info['currency_value'] : 1),
                        );
                    }
                    error_log('Loaded ' . count($data['order']['totals']) . ' order totals from database');
                }
            } else {
                $data['order_products'] = array();
                $data['order']['totals'] = array();
            }

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);
			// Keep order_id until after page is displayed
		} else {
            error_log('WARNING: No order_id in session - showing success page without order details');
            $data['order'] = array();
            $data['order_products'] = array();
        }
        
        // DON'T unset order_id yet - keep it until page is fully rendered
        // This ensures the order data is available even if there are redirects

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		if ($this->customer->isLogged()) {
			$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}

		$data['button_continue'] = $this->language->get('button_continue');

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		// Ensure class variable is set for template
		if (!isset($data['class'])) {
			$data['class'] = '';
		}
		
		error_log('Success page data prepared. Order: ' . (isset($data['order']) && !empty($data['order']) ? 'YES' : 'NO'));
		error_log('About to render success template...');

		try {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/success.tpl')) {
				error_log('Rendering template: ' . DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/success.tpl');
				$output = $this->load->view($this->config->get('config_template') . '/template/checkout/success.tpl', $data);
				$this->response->setOutput($output);
			} else {
				error_log('Rendering default template: default/template/checkout/success.tpl');
				$output = $this->load->view('default/template/checkout/success.tpl', $data);
				$this->response->setOutput($output);
			}
			error_log('=== SUCCESS PAGE RENDERED ===');
			
			// Now safe to unset order_id after page is rendered
			if (isset($this->session->data['order_id'])) {
				unset($this->session->data['order_id']);
			}
		} catch (Exception $e) {
			error_log('ERROR rendering success page: ' . $e->getMessage());
			error_log('Error trace: ' . $e->getTraceAsString());
			// Show a simple success message even if template fails
			echo '<h1>Order Successful!</h1>';
			echo '<p>Your order has been placed successfully.</p>';
			if (isset($order_id) && $order_id > 0) {
				echo '<p>Order ID: ' . htmlspecialchars($order_id) . '</p>';
			}
			echo '<p><a href="' . $this->url->link('common/home') . '">Continue Shopping</a></p>';
		}
	}
}