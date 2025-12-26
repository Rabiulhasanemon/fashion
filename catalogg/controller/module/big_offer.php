<?php
class ControllerModuleBigOffer extends Controller {
    public function index($setting) {
        $this->load->language('module/big_offer');
        
        // Get Big Offer settings
        $status = $this->config->get('big_offer_status');
        $title = $this->config->get('big_offer_title');
        $description = $this->config->get('big_offer_description');
        $image = $this->config->get('big_offer_image');
        $start = $this->config->get('big_offer_start');
        $end = $this->config->get('big_offer_end');
        $button_text = $this->config->get('big_offer_button_text');
        $button_icon = $this->config->get('big_offer_button_icon');
        $products = $this->config->get('big_offer_product');
        $limit = $this->config->get('big_offer_limit');
        
        // Check if module is enabled
        if (!$status) {
            return;
        }
        
        // Check if offer is active
        $is_running = false;
        $is_ended = false;
        $is_starting = false;

        // Determine offer window with robust parsing
        $start_ts = null;
        $end_ts = null;
        if (!empty($start)) {
            $start_ts = strtotime($start);
            if (!$start_ts) { $start_ts = strtotime(str_replace('/', '-', $start)); }
        }
        if (!empty($end)) {
            $end_ts = strtotime($end);
            if (!$end_ts) { $end_ts = strtotime(str_replace('/', '-', $end)); }
        }
        if ($start_ts && $end_ts) {
            $now = time();
            if ($now < $start_ts) {
                $is_starting = true;
            } elseif ($now > $end_ts) {
                $is_ended = true;
            } else {
                $is_running = true;
            }
        } else {
            // If dates are not set, consider it running to show content
            $is_running = true;
        }
        
        $data = array();
        $data['title'] = $title;
        $data['description'] = $description;
        $data['image'] = $image;
        $data['button_text'] = $button_text;
        $data['button_icon'] = $button_icon;
        $data['is_running'] = $is_running;
        $data['is_ended'] = $is_ended;
        $data['is_starting'] = $is_starting;
        $data['start'] = $start_ts ? date('c', $start_ts) : '';
        $data['end'] = $end_ts ? date('c', $end_ts) : '';
        
        // Load optional banner
        $banner_id = (int)$this->config->get('big_offer_banner_id');
        $data['banner_images'] = array();
        if ($banner_id) {
            $this->load->model('design/banner');
            $this->load->model('tool/image');
            foreach ($this->model_design_banner->getBanner($banner_id) as $banner_image) {
                $data['banner_images'][] = array(
                    'title' => $banner_image['title'],
                    'link'  => $banner_image['link'],
                    'image' => $this->model_tool_image->resize($banner_image['image'], 1200, 400)
                );
            }
        }

        // Load products if offer is running
        $data['products'] = array();
        // Remove debug in production
        if ($is_running && is_array($products) && !empty($products)) {
            $this->load->model('catalog/product');
            $this->load->model('tool/image');
            
            $product_limit = ($limit && $limit > 0) ? (int)$limit : 8;
            $selected_products = array_slice($products, 0, $product_limit);
            
            foreach ($selected_products as $product_id) {
                $product_info = $this->model_catalog_product->getProduct($product_id);
                if ($product_info) {
                    if ($product_info['image']) {
                        $image = $this->model_tool_image->resize($product_info['image'], 200, 200);
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', 200, 200);
                    }
                    
                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $price = false;
                    }
                    
                    if ((float)$product_info['special']) {
                        $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $special = false;
                    }
                    
                    $data['products'][] = array(
                        'product_id' => $product_info['product_id'],
                        'thumb' => $image,
                        'name' => $product_info['name'],
                        'price' => $price,
                        'special' => $special,
                        'href' => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                    );
                }
            }
        }
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/big_offer.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/big_offer.tpl', $data);
        } else {
            return $this->load->view('default/template/module/big_offer.tpl', $data);
        }
    }
}
