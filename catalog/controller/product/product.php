<?php

class ControllerProductProduct extends Controller
{
    private $error = array();

    public function index() {
        $this->load->language('product/product');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        if (isset($this->request->get['product_id'])) {
            $product_id = (int)$this->request->get['product_id'];
        } else {
            $product_id = 0;
        }

        $this->load->model('catalog/product');

        $product_info = $this->model_catalog_product->getProduct($product_id);



        if ($product_info) {
            $this->load->model('catalog/category');
            if($product_info['parent_id']) {
                $path = $this->model_catalog_category->getCategoryPath($product_info['parent_id']);
            } else {
                $path = '';
            }

            if ($path) {
                $parts = explode('_', $path);
                $category_id = (int)array_pop($parts);

                foreach ($parts as $path_id) {
                    $category_info = $this->model_catalog_category->getCategory($path_id);
                    $data['category_name'] = $category_info['name'];
                    if ($category_info) {
                        $data['breadcrumbs'][] = array(
                            'text' => $category_info['name'],
                            'href' => $this->url->link('product/category', 'category_id=' . $path_id)
                        );
                    }
                }


                $category_info = $this->model_catalog_category->getCategory($category_id);

                if ($category_info) {
                    $data['breadcrumbs'][] = array(
                        'text' => $category_info['name'],
                        'href' => $this->url->link('product/category', 'category_id=' . $category_id)
                    );
                    $data['category_name'] = $category_info['name'];
                } else {
                    $data['category_name'] = '';
                }

                if($product_info['is_manufacturer_is_parent'] && $product_info['manufacturer_id']) {
                    $data['breadcrumbs'][] = array(
                        'text' => $product_info['manufacturer'],
                        'href' => $this->url->link('product/category', 'category_id=' . $category_id)
                    );
                }
            }

            $this->load->model('catalog/manufacturer');

            $data['breadcrumbs'][] = array(
                'text' => $product_info['name'],
                'href' => $this->url->link('product/product', '&product_id=' . $this->request->get['product_id'])
            );

            $product_url = $this->url->link('product/product', 'product_id=' . $this->request->get['product_id']);

            $this->document->setTitle($product_info['meta_title']);
            $this->document->setDescription($product_info['meta_description']);
            $this->document->setKeywords($product_info['meta_keyword']);
            if(count($this->request->get) > 3) {
                $this->document->addLink($product_url, 'canonical');
            }
//            $this->document->addScript('catalog/view/javascript/cms/operator.js');
            $this->document->addScript('catalog/view/javascript/cms/product.js?v=1');
//            $this->document->addScript('catalog/view/javascript/prod/product.min.10.js');

            $data['heading_title'] = $product_info['name'];
            $data['text_select'] = $this->language->get('text_select');
            $data['text_manufacturer'] = $this->language->get('text_manufacturer');
            $data['text_model'] = $this->language->get('text_model');
            $data['text_reward'] = $this->language->get('text_reward');
            $data['text_points'] = $this->language->get('text_points');
            $data['text_stock'] = $this->language->get('text_stock');
            $data['text_discount'] = $this->language->get('text_discount');
            $data['text_tax'] = $this->language->get('text_tax');
            $data['text_regular_price'] = $this->language->get('text_regular_price');
            $data['text_special_price'] = $this->language->get('text_special_price');
            $data['text_option'] = $this->language->get('text_option');
            $data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
            $data['text_write_review'] = $this->language->get('text_write_review');
            $data['text_ask_question'] = $this->language->get('text_ask_question');
            $data['text_question_help'] = $this->language->get('text_question_help');
            $data['text_review_help'] = $this->language->get('text_review_help');
            $data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
            $data['text_note'] = $this->language->get('text_note');
            $data['text_tags'] = $this->language->get('text_tags');
            $data['text_related'] = $this->language->get('text_related');
            $data['text_loading'] = $this->language->get('text_loading');
            $data['text_emi_offer'] = $this->language->get('text_emi_offer');

            $data['entry_qty'] = $this->language->get('entry_qty');
            $data['entry_name'] = $this->language->get('entry_name');
            $data['entry_email'] = $this->language->get('entry_email');
            $data['entry_question'] = $this->language->get('entry_question');
            $data['entry_review'] = $this->language->get('entry_review');
            $data['entry_rating'] = $this->language->get('entry_rating');
            $data['entry_good'] = $this->language->get('entry_good');
            $data['entry_bad'] = $this->language->get('entry_bad');

            $data['button_cart'] = $this->language->get('button_cart');
            $data['button_wishlist'] = $this->language->get('button_wishlist');
            $data['button_compare'] = $this->language->get('button_compare');
            $data['button_print'] = $this->language->get('button_print');
            $data['button_upload'] = $this->language->get('button_upload');
            $data['button_continue'] = $this->language->get('button_continue');

            $this->load->model('catalog/review');

            $data['tab_description'] = $this->language->get('tab_description');
            $data['tab_attribute'] = $this->language->get('tab_attribute');
            $data['tab_question'] = $this->language->get('tab_question');
            $data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);

            $data['product_id'] = (int)$this->request->get['product_id'];
            $data['manufacturer'] = $product_info['manufacturer'];
            $data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
            $data['sub_name'] = $product_info['sub_name'];
            $data['model'] = $product_info['model'];
            $data['sku'] = $product_info['sku'];
            $data['mpn'] = $product_info['mpn'];
            $data['reward'] = $product_info['reward'];
            $data['points'] = $product_info['points'];
            $data['short_description'] = $product_info['short_description'];
            $data['short_note'] = $product_info['short_note'];
            $data['product_image_url'] = (int)$this->request->get['product_id'];
            $data["print_url"] = $this->url->link('product/print', 'product_id=' . $product_info['product_id']);

            if (isset($this->session->data['success'])) {
                $data['success'] = $this->session->data['success'];
                unset($this->session->data['success']);
            } else {
                $data['success'] = '';
            }

            if ($product_info['quantity'] <= 0) {
                $data['stock'] = $product_info['stock_status'];
            } elseif ($this->config->get('config_stock_display')) {
                $data['stock'] = $product_info['quantity'];
            } else {
                $data['stock'] = $this->language->get('text_instock');
            }

            $data['stock_meta'] = str_replace(" ", "", $data['stock']);

            $this->load->model('tool/image');
            if ($product_info['image']) {
                $data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
            } else {
                $data['popup'] = '';
            }

            if ($product_info['image']) {
                $product_thumb = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
                $data['thumb'] = $product_thumb;
            } else {
                $data['thumb'] = '';
            }

            if ($product_info['image']) {
                $data['featured_image'] = $this->model_tool_image->resize($product_info['featured_image'], $this->config->get('config_featured_image_width'), $this->config->get('config_featured_image_height'));
            } else {
                $data['featured_image'] = '';
            }

            $data['images'] = array();
            $results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
            foreach ($results as $result) {
                $data['images'][] = array(
                    'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
                    'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
                );
            }

            $data["disablePurchase"] = false;
            $data['raw_price'] = $product_info['special'] ?: $product_info['price'];


            if ($product_info['quantity'] <= 0 && $product_info['stock_status'] != "In Stock") {
                $data["disablePurchase"] = true;
                $data["restock_request_btn"] = $product_info['restock_request_btn'];
            } else {
                $data["stock_request_btn"] = false;
                $data["disablePurchase"] = false;
            }

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $data['price'] = false;
            }

            if ((float)$product_info['special']) {
                $data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $data['special'] = false;
            }

            if ($product_info['emi']) {
                $data['text_available_emi'] = $this->language->get('text_available_emi');
                $data['emi_price'] = $this->currency->format(($product_info['regular_price'] > 0 ? $product_info['regular_price'] : $product_info['price']) / 6);
            } else {
                $data['text_available_emi'] = false;
            }

            if ($this->config->get('config_tax')) {
                $data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
            } else {
                $data['tax'] = false;
            }

            if($product_info['regular_price'] > 0) {
               $data['regular_price'] = $this->currency->format($product_info['regular_price']);
            } else {
                $data['regular_price'] = false;
            }

            $discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

            $data['discounts'] = array();

            foreach ($discounts as $discount) {
                $data['discounts'][] = array(
                    'quantity' => $discount['quantity'],
                    'price' => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
                );
            }

            $data['options'] = array();

            foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
                $product_option_value_data = array();

                foreach ($option['product_option_value'] as $option_value) {
                    $product_option_value_data[] = array(
                        'product_option_value_id' => $option_value['product_option_value_id'],
                        'option_value_id' => $option_value['option_value_id'],
                        'name' => $option_value['name'],
                        'color' => $option_value['color'],
                    );
                }

                $data['options'][] = array(
                    'product_option_id' => $option['product_option_id'],
                    'product_option_value' => $product_option_value_data,
                    'option_id' => $option['option_id'],
                    'name' => $option['name'],
                    'type' => $option['type']
                );
            }

            if ($product_info['minimum']) {
                $data['minimum'] = $product_info['minimum'];
            } else {
                $data['minimum'] = 1;
            }

            $data['review_status'] = $this->config->get('config_review_status');

            if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
                $data['review_guest'] = true;
            } else {
                $data['review_guest'] = false;
            }

            if ($this->customer->isLogged()) {
                $data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
            } else {
                $data['customer_name'] = '';
            }

            $data['no_of_review'] = (int) $product_info['reviews'];
            $data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
            $data['rating'] = (int)$product_info['rating'];
            $data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
            $data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);


            $this->model_catalog_product->updateViewed($this->request->get['product_id']);

            if ($this->config->get('config_google_captcha_status')) {
                $this->document->addScript('https://www.google.com/recaptcha/api.js');
                $data['site_key'] = $this->config->get('config_google_captcha_public');
            } else {
                $data['site_key'] = '';
            }

            $data['products'] = array();

            $results = $this->model_catalog_product->getProductRelated($this->request->get['product_id'], (float)$product_info['price']);

            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
                }
                if ($result['featured_image']) {
                    $featured_image = $this->model_tool_image->resize($result['featured_image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
                } else {
                    $featured_image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
                }

                $disablePurchase = false;
                if ($result['quantity'] <= 0 && $result['stock_status'] != "In Stock") {
                    $disablePurchase = true;
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                if ((float)$result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }

                if ($this->config->get('config_tax')) {
                    $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
                } else {
                    $tax = false;
                }

                $data['products'][] = array(
                    'product_id'  => $result['product_id'],
                    'thumb'       => $image,
                    'featured_image'       => $featured_image,
                    'name'        => $result['name'],
                    'sub_name'        => $result['sub_name'],
                    'short_description' => $result['short_description'],
                    'price'       => $price,
                    'disablePurchase' => $disablePurchase,
                    'stock_status' => $result['stock_status'],
                    'special'     => $special,
                    'tax'         => $tax,
                    'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
                    'rating'      => $result['rating'],
                    'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                );
            }

            $data['compatible_products'] = array();

            $compatible_results = $this->model_catalog_product->getProductCompatible($this->request->get['product_id'], (float)$product_info['price']);

            foreach ($compatible_results as $result) {
                // Skip if product data is invalid
                if (!$result || !is_array($result) || !isset($result['product_id'])) {
                    continue;
                }
                
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
                }
                if (isset($result['featured_image']) && $result['featured_image']) {
                    $featured_image = $this->model_tool_image->resize($result['featured_image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
                } else {
                    $featured_image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
                }

                $disablePurchase = false;
                if (isset($result['quantity']) && $result['quantity'] <= 0 && isset($result['stock_status']) && $result['stock_status'] != "In Stock") {
                    $disablePurchase = true;
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                if (isset($result['special']) && (float)$result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }

                if ($this->config->get('config_tax')) {
                    $tax = $this->currency->format((float)(isset($result['special']) && $result['special'] ? $result['special'] : $result['price']));
                } else {
                    $tax = false;
                }

                $data['compatible_products'][] = array(
                    'product_id'  => $result['product_id'],
                    'thumb'       => $image,
                    'featured_image'       => $featured_image,
                    'name'        => isset($result['name']) ? $result['name'] : '',
                    'sub_name'        => isset($result['sub_name']) ? $result['sub_name'] : '',
                    'short_description' => isset($result['short_description']) ? (is_array($result['short_description']) ? implode(' ', $result['short_description']) : $result['short_description']) : '',
                    'price'       => $price,
                    'disablePurchase' => $disablePurchase,
                    'stock_status' => isset($result['stock_status']) ? $result['stock_status'] : '',
                    'special'     => $special,
                    'tax'         => $tax,
                    'minimum'     => isset($result['minimum']) && $result['minimum'] > 0 ? $result['minimum'] : 1,
                    'rating'      => isset($result['rating']) ? $result['rating'] : 0,
                    'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                );
            }


            if(isset($this->session->data["recent"])) {
                $recent_products = $this->session->data["recent"];
            } else {
                $recent_products = [];
            }

            $index = array_search($product_id, $recent_products);

            if($index !== false) {
                array_splice($recent_products, $index, 1);
            }

            $data['recent_products'] = [];
            foreach ($recent_products as $recent_product) {
                $result = $this->model_catalog_product->getProduct($recent_product);
                if(!$result) continue;
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
                }

                $disablePurchase = false;
                if ($result['quantity'] <= 0 && $result['stock_status'] != "In Stock") {
                    $disablePurchase = true;
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                if ((float)$result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }

                $data['recent_products'][] = array(
                    'product_id'  => $result['product_id'],
                    'thumb'       => $image,
                    'name'        => $result['name'],
                    'short_description' => $result['short_description'],
                    'price'       => $price,
                    'disablePurchase' => $disablePurchase,
                    'stock_status' => $result['stock_status'],
                    'special'     => $special,
                    'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
                    'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                );
            }

            if(count($recent_products) >= 4) {
                array_shift($recent_products);
            }
            array_push($recent_products, $product_id);
            $this->session->data["recent"] = $recent_products;

            $data['tags'] = array();

            if ($product_info['tag']) {
                $tags = explode(',', $product_info['tag']);

                foreach ($tags as $tag) {
                    $data['tags'][] = array(
                        'tag' => trim($tag),
                        'href' => $this->url->link('product/search', 'tag=' . trim($tag))
                    );
                }
            }

            $data['write'] =  $this->url->link('account/review', 'product_id=' . $this->request->get['product_id']);
            $data['ask'] =  $this->url->link('account/question', 'product_id=' . $this->request->get['product_id']);

            $this->document->addMeta('fb:app_id', FB_APP_ID);
            $this->document->addMeta('og:title', $product_info['name']);
            $this->document->addMeta('og:type', "product");
            $this->document->addMeta('og:url', $product_url);
            $this->document->addMeta('og:description', $product_info['meta_description']);
            $this->document->addMeta('og:site_name', $this->config->get('config_name'));
            $this->document->addMeta('og:image', $data['thumb']);
            $this->document->addMeta('product:brand', $data['manufacturer']);
            $this->document->addMeta('product:availability', $data['stock']);
            $this->document->addMeta('product:condition', "new");
            $this->document->addMeta('product:price:amount', $product_info['price']);
            $this->document->addMeta('product:price:currency', "BDT");
            $this->document->addMeta('product:retailer_item_id', $data['product_id']);

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            if ($product_info['view'] && file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product_' . $product_info['view'] . '.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/product_' . $product_info['view'] . '.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/product.tpl', $data));
            }
        } else {
            $url = '';

            if (isset($this->request->get['path'])) {
                $url .= '&path=' . $this->request->get['path'];
            }

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['manufacturer_id'])) {
                $url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
            }

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . $this->request->get['search'];
            }

            if (isset($this->request->get['tag'])) {
                $url .= '&tag=' . $this->request->get['tag'];
            }

            if (isset($this->request->get['description'])) {
                $url .= '&description=' . $this->request->get['description'];
            }

            if (isset($this->request->get['category_id'])) {
                $url .= '&category_id=' . $this->request->get['category_id'];
            }

            if (isset($this->request->get['sub_category'])) {
                $url .= '&sub_category=' . $this->request->get['sub_category'];
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

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('product/product', $url . '&product_id=' . $product_id)
            );

            $this->document->setTitle($this->language->get('text_error'));

            $data['heading_title'] = $this->language->get('text_error');

            $data['text_error'] = $this->language->get('text_error');

            $data['button_continue'] = $this->language->get('button_continue');

            $data['continue'] = $this->url->link('common/home');

            $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

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

    public function review() {
        $this->load->language('product/product');

        $this->load->model('catalog/review');

        $data['text_no_reviews'] = $this->language->get('text_no_reviews');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['reviews'] = array();

        $review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);

        $results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);

        foreach ($results as $result) {
            $data['reviews'][] = array(
                'author' => $result['author'],
                'text' => nl2br($result['text']),
                'rating' => (int)$result['rating'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
            );
        }

        $pagination = new Pagination();
        $pagination->total = $review_total;
        $pagination->page = $page;
        $pagination->limit = 5;
        $pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/review.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/product/review.tpl', $data));
        }
    }

    public function write() {
        $this->load->language('product/product');

        $json = array();

        // Check if product_id is provided
        if (!isset($this->request->get['product_id']) || empty($this->request->get['product_id'])) {
            $json['error'] = 'Invalid product ID';
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }

        $product_id = (int)$this->request->get['product_id'];

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            // Validate name
            if (!isset($this->request->post['name']) || empty($this->request->post['name'])) {
                $json['error'] = $this->language->get('error_name');
            } elseif (function_exists('utf8_strlen')) {
                if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 100)) {
                    $json['error'] = $this->language->get('error_name');
                }
            } elseif (function_exists('mb_strlen')) {
                if ((mb_strlen($this->request->post['name'], 'UTF-8') < 3) || (mb_strlen($this->request->post['name'], 'UTF-8') > 100)) {
                    $json['error'] = $this->language->get('error_name');
                }
            } else {
                if ((strlen($this->request->post['name']) < 3) || (strlen($this->request->post['name']) > 100)) {
                    $json['error'] = $this->language->get('error_name');
                }
            }

            // Validate text
            if (!isset($this->request->post['text']) || empty($this->request->post['text'])) {
                $json['error'] = $this->language->get('error_text');
            } elseif (function_exists('utf8_strlen')) {
                if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
                    $json['error'] = $this->language->get('error_text');
                }
            } elseif (function_exists('mb_strlen')) {
                if ((mb_strlen($this->request->post['text'], 'UTF-8') < 25) || (mb_strlen($this->request->post['text'], 'UTF-8') > 1000)) {
                    $json['error'] = $this->language->get('error_text');
                }
            } else {
                if ((strlen($this->request->post['text']) < 25) || (strlen($this->request->post['text']) > 1000)) {
                    $json['error'] = $this->language->get('error_text');
                }
            }

            // Validate rating
            if (!isset($this->request->post['rating']) || empty($this->request->post['rating']) || $this->request->post['rating'] < 1 || $this->request->post['rating'] > 5) {
                $json['error'] = $this->language->get('error_rating');
            }

            // Validate Google reCAPTCHA if enabled
            if ($this->config->get('config_google_captcha_status') && empty($json['error'])) {
                if (isset($this->request->post['g-recaptcha-response'])) {
                    $recaptcha = @file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($this->config->get('config_google_captcha_secret')) . '&response=' . $this->request->post['g-recaptcha-response'] . '&remoteip=' . (isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : ''));

                    if ($recaptcha) {
                        $recaptcha = json_decode($recaptcha, true);

                        if (!$recaptcha || !isset($recaptcha['success']) || !$recaptcha['success']) {
                            $json['error'] = $this->language->get('error_captcha');
                        }
                    } else {
                        $json['error'] = $this->language->get('error_captcha');
                    }
                } else {
                    $json['error'] = $this->language->get('error_captcha');
                }
            }

            // If no errors, save the review
            if (!isset($json['error'])) {
                try {
                    $this->load->model('catalog/review');
                    
                    // Check if review status should be auto-approved
                    $review_status = 0; // Default to pending (0)
                    if ($this->config->get('config_review_status')) {
                        $review_status = 1; // Auto-approve if enabled
                    }
                    
                    // Add status to post data
                    $review_data = $this->request->post;
                    $review_data['status'] = $review_status;
                    
                    // Log review data for debugging (remove in production)
                    error_log('Review Data: ' . print_r($review_data, true));
                    error_log('Product ID: ' . $product_id);
                    
                    $this->model_catalog_review->addReview($product_id, $review_data);
                    
                    // Clear cache
                    $this->cache->delete('product');
                    
                    $json['success'] = $this->language->get('text_success');
                } catch (Exception $e) {
                    $error_message = $e->getMessage();
                    
                    // Log detailed error for debugging
                    error_log('=== REVIEW SUBMISSION ERROR ===');
                    error_log('Error Message: ' . $error_message);
                    error_log('Error File: ' . $e->getFile());
                    error_log('Error Line: ' . $e->getLine());
                    error_log('Error Trace: ' . $e->getTraceAsString());
                    error_log('POST Data: ' . print_r($this->request->post, true));
                    error_log('Product ID: ' . $product_id);
                    error_log('==============================');
                    
                    // Show more specific error message for debugging (can be removed in production)
                    if ($this->config->get('config_error_display')) {
                        $json['error'] = 'Error: ' . $error_message;
                        $json['debug'] = array(
                            'file' => $e->getFile(),
                            'line' => $e->getLine()
                        );
                    } else {
                        $json['error'] = 'An error occurred while saving your review. Please try again.';
                    }
                    
                    // Check if it's a database error
                    if (strpos(strtolower($error_message), 'sql') !== false || strpos(strtolower($error_message), 'database') !== false || strpos(strtolower($error_message), 'query') !== false) {
                        $json['error'] = 'Database error occurred. Please contact the administrator.';
                    }
                } catch (Error $e) {
                    $error_message = $e->getMessage();
                    
                    // Log detailed error for debugging
                    error_log('=== REVIEW SUBMISSION FATAL ERROR ===');
                    error_log('Error Message: ' . $error_message);
                    error_log('Error File: ' . $e->getFile());
                    error_log('Error Line: ' . $e->getLine());
                    error_log('Error Trace: ' . $e->getTraceAsString());
                    error_log('POST Data: ' . print_r($this->request->post, true));
                    error_log('Product ID: ' . $product_id);
                    error_log('===================================');
                    
                    $json['error'] = 'A fatal error occurred. Please contact the administrator.';
                }
            }
        } else {
            $json['error'] = 'Invalid request method';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function question() {
        $this->load->language('product/product');

        $this->load->model('catalog/question');

        $data['text_no_questions'] = $this->language->get('text_no_questions');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['questions'] = array();

        $question_total = $this->model_catalog_question->getTotalQuestionsByProductId($this->request->get['product_id']);

        $results = $this->model_catalog_question->getQuestionsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);

        foreach ($results as $result) {
            $data['questions'][] = array(
                'author' => $result['author'],
                'text' => nl2br($result['text']),
                'answer' => nl2br($result['answer']),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
            );
        }

        $pagination = new Pagination();
        $pagination->total = $question_total;
        $pagination->page = $page;
        $pagination->limit = 5;
        $pagination->url = $this->url->link('product/product/question', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($question_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($question_total - 5)) ? $question_total : ((($page - 1) * 5) + 5), $question_total, ceil($question_total / 5));

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/question.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/question.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/product/question.tpl', $data));
        }
    }

    public function ask() {
        $this->load->language('product/product');

        $json = array();

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
                $json['error'] = $this->language->get('error_name');
            }

            if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
                $json['error'] = $this->language->get('error_email');
            }

            if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 250)) {
                $json['error'] = $this->language->get('error_question');
            }

            if ($this->config->get('config_google_captcha_status') && empty($json['error'])) {
                if (isset($this->request->post['g-recaptcha-response'])) {
                    $recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($this->config->get('config_google_captcha_secret')) . '&response=' . $this->request->post['g-recaptcha-response'] . '&remoteip=' . $this->request->server['REMOTE_ADDR']);

                    $recaptcha = json_decode($recaptcha, true);

                    if (!$recaptcha['success']) {
                        $json['error'] = $this->language->get('error_captcha');
                    }
                } else {
                    $json['error'] = $this->language->get('error_captcha');
                }
            }

            if (!isset($json['error'])) {
                $this->load->model('catalog/question');

                $this->model_catalog_question->addQuestion($this->request->get['product_id'], $this->request->post);

                $json['success'] = $this->language->get('text_question_success');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function variation() {
        $this->load->language('product/product');
        $this->load->model('catalog/product');

        $data = array();

        if (isset($this->request->post['product_id'])) {
            $product_id = (int)$this->request->post['product_id'];
        } else {
            $product_id = 0;
        }

        $product_info = $this->model_catalog_product->getProduct($product_id);
        if (isset($this->request->post['option'])) {
            $option = array_filter($this->request->post['option']);
        } else {
            $option = array();
        }
        asort($option);
        $key = implode("-", array_values($option));
        $variation = $this->model_catalog_product->getProductVariation($product_id, $key);

        if ($product_info && $variation) {
            if($variation["price_prefix"] == "-") {
                $additional_price = $variation["price"] * -1;
            } else {
                $additional_price = $variation["price"];
            }

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $data['price'] = $this->currency->format($this->tax->calculate($product_info['price'] + $additional_price, $product_info['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $data['price'] = false;
            }

            if ((float)$product_info['special']) {
                $data['special'] = $this->currency->format($this->tax->calculate($product_info['special']  + $additional_price, $product_info['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $data['special'] = false;
            }

            if ($product_info['emi']) {
                $data['text_available_emi'] = $this->language->get('text_available_emi');
                $data['emi_price'] = $this->currency->format(($product_info['regular_price'] > 0 ? $product_info['regular_price']  + $additional_price : $product_info['price']  + $additional_price) / 12);
            } else {
                $data['text_available_emi'] = false;
            }

            if ($this->config->get('config_tax')) {
                $data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special']  + $additional_price: $product_info['price'] + $additional_price);
            } else {
                $data['tax'] = false;
            }

            if($product_info['regular_price'] > 0) {
                $data['regular_price'] = $this->currency->format($product_info['regular_price'] + $additional_price);
            } else {
                $data['regular_price'] = false;
            }


            $this->load->model('tool/image');
            if ($variation['image']) {
                $data['thumb'] = $this->model_tool_image->resize($variation['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
            } else {
                $data['thumb'] = null;
            }
        } else {
            $data['error'] =   $this->language->get('error_variation');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }
}
