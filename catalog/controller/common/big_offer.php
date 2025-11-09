<?php
class ControllerCommonBigOffer extends Controller {
    public function index() {
        $this->load->language('module/big_offer');
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        $data['breadcrumbs'] = array();
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('common/big_offer')
        );
        
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
        $banner_id = $this->config->get('big_offer_banner_id');
        
        if (!$status) {
            $this->response->redirect($this->url->link('common/home'));
            return;
        }
        
        $is_running = false;
        $is_ended = false;
        $is_starting = false;
        $end_date_iso = '';
        
        if ($start && $end) {
            $start_ts = strtotime(str_replace('/', '-', $start));
            $end_ts = strtotime(str_replace('/', '-', $end));
            $now = time();
            
            if ($now >= $start_ts && $now <= $end_ts) {
                $is_running = true;
                $end_date_iso = date('c', $end_ts);
            } elseif ($now > $end_ts) {
                $is_ended = true;
            } else {
                $is_starting = true;
                $end_date_iso = date('c', $start_ts);
            }
        } else {
            $is_running = true;
        }
        
        $data['title'] = html_entity_decode($title, ENT_QUOTES, 'UTF-8');
        $data['description'] = html_entity_decode($description, ENT_QUOTES, 'UTF-8');
        $data['image'] = $image;
        $data['button_text'] = $button_text;
        $data['button_icon'] = $button_icon;
        $data['is_running'] = $is_running;
        $data['is_ended'] = $is_ended;
        $data['is_starting'] = $is_starting;
        $data['end_date_iso'] = $end_date_iso;
        $data['start'] = $start;
        $data['end'] = $end;
        
        // Load products
        $data['products'] = array();
        if (is_array($products) && !empty($products)) {
            $this->load->model('catalog/product');
            $this->load->model('tool/image');
            
            $product_limit = ($limit && $limit > 0) ? (int)$limit : 12;
            $selected_products = array_slice($products, 0, $product_limit);
            
            foreach ($selected_products as $product_id) {
                $product_info = $this->model_catalog_product->getProduct($product_id);
                if ($product_info) {
                    if ($product_info['image']) {
                        $image = $this->model_tool_image->resize($product_info['image'], 300, 300);
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', 300, 300);
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
        
        // Load banner images
        $data['banner_images'] = array();
        if ($banner_id) {
            $this->load->model('design/banner');
            $this->load->model('tool/image');
            $results = $this->model_design_banner->getBanner($banner_id);
            
            foreach ($results as $result) {
                if (is_file(DIR_IMAGE . $result['image'])) {
                    $data['banner_images'][] = array(
                        'title' => $result['title'],
                        'link'  => $result['link'],
                        'image' => $this->model_tool_image->resize($result['image'], 1200, 400)
                    );
                }
            }
        }
        
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/big_offer.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/big_offer.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/big_offer.tpl', $data));
        }
    }
}