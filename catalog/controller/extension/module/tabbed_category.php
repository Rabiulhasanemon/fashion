<?php
class ControllerExtensionModuleTabbedCategory extends Controller {
    public function index($setting) {
        // Ensure $setting is an array
        if (!is_array($setting)) {
            $setting = array();
        }
        
        // Default status to enabled if not set (backward compatibility)
        if (!isset($setting['status'])) {
            $setting['status'] = 1;
        }
        
        // Check if module is enabled
        if (isset($setting['status']) && !$setting['status']) {
            return '';
        }
        
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

        // Define standard image sizes for all modules
        $image_width = 500;
        $image_height = 500;

        // Unique id per module instance for DOM scoping
        static $module_instance = 0;
        $data['module_uid'] = 'tcp-' . ($module_instance++);

        // Set default values if not provided
        if (!isset($setting['limit']) || !$setting['limit']) { 
            $setting['limit'] = 8; 
        }
        if (!isset($setting['width']) || !$setting['width']) { 
            $setting['width'] = $image_width; 
        }
        if (!isset($setting['height']) || !$setting['height']) { 
            $setting['height'] = $image_height; 
        }
        
        $data['tabs'] = array();

        $tabs = isset($setting['tabs']) ? $setting['tabs'] : array();
        
        // Handle legacy product array format (convert to tabs if needed)
        if (empty($tabs) && !empty($setting['product']) && is_array($setting['product'])) {
            // If old format with product array, create a default tab
            $tabs = array(array(
                'title' => isset($setting['name']) && !empty($setting['name']) ? $setting['name'] : 'Featured Products',
                'products' => $setting['product']
            ));
        }
        
        // If no tabs configured, return empty
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

                $image = $product_info['image'] ? $this->model_tool_image->resize($product_info['image'], $image_width, $image_height) : $this->model_tool_image->resize('placeholder.png', $image_width, $image_height);
                
                // Note: The featured_image seems to be a different type. I will leave it as is for now.
                $featured_image = $product_info['featured_image'] ? $this->model_tool_image->resize($product_info['featured_image'], $this->config->get('config_featured_image_width'), $this->config->get('config_featured_image_height')) : $this->model_tool_image->resize('placeholder.png', $this->config->get('config_featured_image_width'), $this->config->get('config_featured_image_height'));

                $disablePurchase = ($product_info['quantity'] <= 0 && $product_info['stock_status'] != 'In Stock');
                $price = (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) ? $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'))) : false;
                $regular_price = ($product_info['regular_price'] > 0 && (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price'))) ? $this->currency->format($this->tax->calculate($product_info['regular_price'], $product_info['tax_class_id'], $this->config->get('config_tax'))) : false;
                if ((float)$product_info['special']) { 
                    $mark = 'Save: '.$this->currency->format($this->tax->calculate($product_info['price'] - $product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax'))); 
                    $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                } else { 
                    $mark=false; 
                    $special=false; 
                }
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
                    'href'=>$this->url->link('product/product', 'product_id=' . $product_info['product_id']),
                    'discount' => $discount_percentage,
                    'category_name' => $category_name,
                    'manufacturer'  => $product_info['manufacturer']
                );
            }

            $title = isset($tab['title']) ? $tab['title'] : '';
            if (empty($title) && !empty($tab['category_id'])) { 
                $cat = $this->model_catalog_category->getCategory((int)$tab['category_id']); 
                if ($cat) $title = $cat['name']; 
            }
            $data['tabs'][] = array('title'=>$title, 'products'=>$products_out);
        }

        // Filter out completely empty tabs (no title and no products)
        $valid_tabs = array();
        foreach ($data['tabs'] as $tab) {
            // Include tab if it has products OR has a title (show tabs even if temporarily empty)
            if (!empty($tab['products']) || !empty($tab['title'])) {
                $valid_tabs[] = $tab;
            }
        }
        
        // If no valid tabs at all, return empty
        if (empty($valid_tabs)) {
            return '';
        }
        
        $data['tabs'] = $valid_tabs;
        
        // Try theme-specific template first
        $theme = $this->config->get('config_template');
        $template_paths = array(
            $theme . '/template/module/tabbed_category.tpl',
            $theme . '/template/extension/module/tabbed_category.tpl',
            'default/template/module/tabbed_category.tpl'
        );
        
        foreach ($template_paths as $path) {
            $full_path = DIR_TEMPLATE . $path;
            if (file_exists($full_path)) {
                return $this->load->view($path, $data);
            }
        }
        
        // Last resort - return empty if no template found
        return '';
    }
}
