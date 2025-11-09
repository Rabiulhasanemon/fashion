<?php
class ControllerModuleFeatured extends Controller {
    public function index($setting) {
        $this->load->language('module/featured');

        $data['heading_title'] = $this->language->get('heading_title');
        $data['name'] = $setting['name'];
        $data['blurb'] = $setting['blurb'];
        $data['see_all'] = isset($setting['url']) ? $setting['url'] : null;
        $data['class'] = $setting['class'];

        $data['text_tax'] = $this->language->get('text_tax');

        $data['button_cart'] = $this->language->get('button_cart');
        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        // Define standard image sizes for all modules
        $image_width = 500;
        $image_height = 500;

        $data['products'] = array();

        if($setting['date_end']) {
            $data['date_end'] =  date("F j, Y H:i:s",  strtotime($setting['date_end']));
        } else {
            $data['date_end'] = '';
        }

        if (!$setting['limit']) {
            $setting['limit'] = 4;
        }

        if (!empty($setting['product'])) {
            $products = array_slice($setting['product'], 0, (int)$setting['limit']);

            foreach ($products as $product_id) {
                $product_info = $this->model_catalog_product->getProduct($product_id);

                if ($product_info) {
                    if ($product_info['image']) {
                        $image = $this->model_tool_image->resize($product_info['image'], $image_width, $image_height);
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $image_width, $image_height);
                    }

                    if ($product_info['featured_image']) {
                        $featured_image = $this->model_tool_image->resize($product_info['featured_image'], $this->config->get('config_featured_image_width'), $this->config->get('config_featured_image_height'));
                    } else {
                        $featured_image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_featured_image_width'), $this->config->get('config_featured_image_height'));;
                    }

                    $disablePurchase = false;
                    if ($product_info['quantity'] <= 0 && $product_info['stock_status'] != "In Stock") {
                        $disablePurchase = true;
                    }

                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $price = false;
                    }

                    if ($product_info['regular_price'] > 0 && (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price'))) {
                        $regular_price = $this->currency->format($this->tax->calculate($product_info['regular_price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $regular_price = false;
                    }




                    if ((float)$product_info['special']) {
                        $mark = "Save: " . $this->currency->format($this->tax->calculate($product_info['price'] - $product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                        $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $mark = false;
                        $special = false;
                    }

                    if ($this->config->get('config_tax')) {
                        $tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
                    } else {
                        $tax = false;
                    }

                    if ($this->config->get('config_review_status')) {
                        $rating = $product_info['rating'];
                    } else {
                        $rating = false;
                    }

                    if($product_info['manufacturer_thumb']) {
                        $manufacturer_thumb = $this->config->get('config_ssl') . '/image/' . $product_info['manufacturer_thumb'];
                    } else {
                        $manufacturer_thumb = null;
                    }

                    // Compute discount percentage from price vs special
                    $discount_percentage = false;
                    if ((float)$product_info['special'] && (float)$product_info['price'] > 0) {
                        $discount_percentage = round((( (float)$product_info['price'] - (float)$product_info['special'] ) / (float)$product_info['price'] ) * 100);
                    }

                    // Fetch a primary category name for display
                    $category_name = '';
                    $category_query = $this->db->query("SELECT cd.name FROM " . DB_PREFIX . "product_to_category p2c LEFT JOIN " . DB_PREFIX . "category c ON (p2c.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE p2c.product_id = '" . (int)$product_info['product_id'] . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, LCASE(cd.name) ASC LIMIT 1");
                    if ($category_query->num_rows) {
                        $category_name = $category_query->row['name'];
                    }

                    $data['products'][] = array(
                        'product_id'  => $product_info['product_id'],
                        'thumb'       => $image,
                        'featured_image'   => $featured_image,
                        'manufacturer' => $product_info['manufacturer'],
                        'manufacturer_thumb' => $manufacturer_thumb,
                        'name'        => $product_info['name'],
                        'sub_name'        => $product_info['sub_name'],
                        'short_description' => $product_info['short_description'],
                        'price'       => $price,
                        'regular_price'       => $regular_price,
                        'disablePurchase'=> $disablePurchase,
                        'stock_status' => $product_info['stock_status'],
                        'restock_request_btn' => $product_info['restock_request_btn'],
                        'special'     => $special,
                        'marks'     => $mark,
                        'short_note'     => $product_info['short_note'],
                        'tax'         => $tax,
                        'minimum'     => $product_info['minimum'] > 0 ? $product_info['minimum'] : 1,
                        'rating'      => $rating,
                        'category_name' => $category_name,
                        'discount_percent' => $discount_percentage,
                        'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                    );
                }
            }
        }


        if ($data['products']) {
            if ($setting['view']) {
                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featured_' . $setting['view'] . '.tpl')) {
                    return $this->load->view($this->config->get('config_template') . '/template/module/featured_' . $setting['view'] . '.tpl', $data);
                }
            }
            else if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featured.tpl')) {
                return $this->load->view($this->config->get('config_template') . '/template/module/featured.tpl', $data);
            } else {
                return $this->load->view('default/template/module/featured.tpl', $data);
            }
        }
    }
}