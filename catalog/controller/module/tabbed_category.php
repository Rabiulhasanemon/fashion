<?php
class ControllerModuleTabbedCategory extends Controller {
    public function index($setting) {
        $this->load->language('module/tabbed_category');

        $data['heading_title'] = $this->language->get('heading_title');
        $data['name'] = isset($setting['name']) ? $setting['name'] : '';
        $data['blurb'] = isset($setting['blurb']) ? $setting['blurb'] : '';
        $data['class'] = isset($setting['class']) ? $setting['class'] : '';
        $data['layout'] = isset($setting['layout']) ? $setting['layout'] : 'grid';

        $data['text_tax'] = $this->language->get('text_tax');
        $data['button_cart'] = $this->language->get('button_cart');
        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');

        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('tool/image');

        // Unique id per module instance for DOM scoping
        static $module_instance = 0;
        $data['module_uid'] = 'tcp-' . ($module_instance++);

        // Set default values if not provided
        if (!isset($setting['limit']) || !$setting['limit']) { 
            $setting['limit'] = 8; 
        }
        if (!isset($setting['width']) || !$setting['width']) { 
            $setting['width'] = 200; 
        }
        if (!isset($setting['height']) || !$setting['height']) { 
            $setting['height'] = 200; 
        }
        
        $data['tabs'] = array();
        $data['see_all_url'] = $this->url->link('product/category');
        
        // Get date_end from settings for countdown timer
        $data['date_end'] = isset($setting['date_end']) ? $setting['date_end'] : '';

        $tabs = isset($setting['tabs']) ? $setting['tabs'] : array();
        
        // If no tabs configured, return empty (don't show module)
        if (empty($tabs)) {
            return '';
        }
        foreach ($tabs as $tab) {
            $products_out = array();
            $ids = array();

            if (!empty($tab['category_id'])) {
                $filter = array('filter_category_id' => (int)$tab['category_id'], 'start'=>0, 'limit'=>(int)$setting['limit']);
                $rows = $this->model_catalog_product->getProducts($filter);
                foreach ($rows as $r) { $ids[] = (int)$r['product_id']; }
            }
            if (!empty($tab['products']) && is_array($tab['products'])) {
                $ids = array_unique(array_merge($ids, $tab['products']));
            }

            $ids = array_slice($ids, 0, (int)$setting['limit']);

            foreach ($ids as $product_id) {
                $product_info = $this->model_catalog_product->getProduct($product_id);
                if (!$product_info) continue;

                $image = $product_info['image'] ? $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']) : $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
                $featured_image = $product_info['featured_image'] ? $this->model_tool_image->resize($product_info['featured_image'], $this->config->get('config_featured_image_width'), $this->config->get('config_featured_image_height')) : $this->model_tool_image->resize('placeholder.png', $this->config->get('config_featured_image_width'), $this->config->get('config_featured_image_height'));

                $disablePurchase = ($product_info['quantity'] <= 0 && $product_info['stock_status'] != 'In Stock');
                $price = (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) ? $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'))) : false;
                $regular_price = ($product_info['regular_price'] > 0 && (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price'))) ? $this->currency->format($this->tax->calculate($product_info['regular_price'], $product_info['tax_class_id'], $this->config->get('config_tax'))) : false;
                if ((float)$product_info['special']) { $mark = 'Save: '.$this->currency->format($this->tax->calculate($product_info['price'] - $product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax'))); $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));} else { $mark=false; $special=false; }
                $tax = $this->config->get('config_tax') ? $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']) : false;
                $rating = $this->config->get('config_review_status') ? $product_info['rating'] : false;

                $discount_percentage = false;
                if ((float)$product_info['special'] && (float)$product_info['price'] > 0) {
                    $discount_percentage = round((( (float)$product_info['price'] - (float)$product_info['special'] ) / (float)$product_info['price'] ) * 100);
                }

                $category_name = '';
                $category_query = $this->db->query("SELECT cd.name FROM " . DB_PREFIX . "product_to_category p2c LEFT JOIN " . DB_PREFIX . "category c ON (p2c.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE p2c.product_id = '" . (int)$product_info['product_id'] . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, LCASE(cd.name) ASC LIMIT 1");
                if ($category_query->num_rows) {
                    $category_name = $category_query->row['name'];
                }

                $products_out[] = array(
                    'product_id'=>$product_info['product_id'],
                    'thumb'=>$image,
                    'featured_image'=>$featured_image,
                    'name'=>$product_info['name'],
                    'disable'=>$disablePurchase,
                    'mark'=>$mark,
                    'price'=>$price,
                    'regular_price'=>$regular_price,
                    'special'=>$special,
                    'tax'=>$tax,
                    'rating'=>(int)$rating,
                    'points'=>isset($product_info['points']) ? (int)$product_info['points'] : 0,
                    'reward'=>isset($product_info['reward']) ? (int)$product_info['reward'] : 0,
                    'href'=>$this->url->link('product/product', 'product_id=' . $product_info['product_id']),
                    'discount' => $discount_percentage,
                    'category_name' => $category_name,
                    'manufacturer'  => $product_info['manufacturer']
                );
            }

            $title = isset($tab['title']) ? $tab['title'] : '';
            if (empty($title) && !empty($tab['category_id'])) { $cat = $this->model_catalog_category->getCategory((int)$tab['category_id']); if ($cat) $title = $cat['name']; }
            $data['tabs'][] = array('title'=>$title, 'products'=>$products_out);
        }

        return $this->load->view($this->config->get('config_template') . '/template/module/tabbed_category.tpl', $data);
    }
}