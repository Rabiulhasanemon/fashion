<?php

class ControllerApiProduct extends Controller
{

    public function index() {
        $this->load->model("account/api");
        if (isset($this->request->server['PHP_AUTH_USER']) && isset($this->request->server['PHP_AUTH_PW'])) {
            $api_user = $this->model_account_api->login($this->request->server['PHP_AUTH_USER'], $this->request->server['PHP_AUTH_PW']);
        } else {
            $api_user = null;
        }

        $this->load->language('api/catalog');

        $json = array();

        if ($api_user) {
            $this->load->model('catalog/product');

            $this->load->model('tool/image');

            if (isset($this->request->get['sort'])) {
                $sort = $this->request->get['sort'];
            } else {
                $sort = 'p.sort_order';
            }

            if (isset($this->request->get['order'])) {
                $order = $this->request->get['order'];
            } else {
                $order = 'ASC';
            }

            if (isset($this->request->get['page'])) {
                $page = $this->request->get['page'];
            } else {
                $page = 1;
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = $this->config->get('config_product_limit');
            }

            if (isset($this->request->get['category_id'])) {
                $category_id = $this->request->get['category_id'];
            } else {
                $category_id = 0;
            }

            $filter_data = array(
                'filter_category_id' => $category_id,
                'sort' => $sort,
                'order' => $order,
                'start' => ($page - 1) * $limit,
                'limit' => $limit
            );

            $json['product_total'] = $this->model_catalog_product->getTotalProducts($filter_data);
            $results = $this->model_catalog_product->getProducts($filter_data);

            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                }

                $disablePurchase = false;
                if ($result['quantity'] <= 0 && $result['stock_status'] != "In Stock") {
                    $disablePurchase = true;
                    $price = $result['stock_status'];
                } else if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
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

                if ($this->config->get('config_review_status')) {
                    $rating = (int)$result['rating'];
                } else {
                    $rating = false;
                }

                $json['products'][] = array(
                    'product_id' => $result['product_id'],
                    'thumb' => $image,
                    'name' => $result['name'],
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                    'price' => 100.00,
                    'disablePurchase' => $disablePurchase,
                    'special' => $special,
                    'tax' => $tax,
                    'stock_status' => $result['stock_status'],
                    'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                    'rating' => $result['rating'],
                    'href' => $this->url->link('', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'])
                );
            }

        } else {
            $json['error']['warning'] = $this->language->get('error_permission');
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function info() {
        $this->load->model("account/api");
        $this->load->language('');
        if (isset($this->request->server['PHP_AUTH_USER']) && isset($this->request->server['PHP_AUTH_PW'])) {
            $api_user = $this->model_account_api->login($this->request->server['PHP_AUTH_USER'], $this->request->server['PHP_AUTH_PW']);
        } else {
            $api_user = null;
        }

        $this->load->language('api/catalog');

        $json = array();

        if ($api_user) {
            $this->load->model('catalog/product');

            if (isset($this->request->get['product_id'])) {
                $product_id = $this->request->get['product_id'];
            } else {
                $product_id = 0;
            }

            $product_info = $this->model_catalog_product->getProduct($product_id);

        } else {
            $product_info = null;
            $json['error']['warning'] = $this->language->get('error_permission');
        }
        if($product_info) {
            $json['product_id'] = (int)$this->request->get['product_id'];
            $json['manufacturer'] = $product_info['manufacturer'];
            $json['model'] = $product_info['model'];
            $json['reward'] = $product_info['reward'];
            $json['points'] = $product_info['points'];
            $json['product_image_url'] = (int)$this->request->get['product_id'];

            if ($product_info['quantity'] <= 0) {
                $json['stock'] = $product_info['stock_status'];
            } elseif ($this->config->get('config_stock_display')) {
                $json['stock'] = $product_info['quantity'];
            } else {
                $json['stock'] = $this->language->get('text_instock');
            }
            $json['stock_meta'] = str_replace(" ", "", $json['stock']);

            $this->load->model('tool/image');

            if ($product_info['image']) {
                $json['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
            } else {
                $json['popup'] = '';
            }

            if ($product_info['image']) {
                $product_thumb = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
                $json['thumb'] = $product_thumb;
                $this->document->addMeta('og:image', $product_thumb);
            } else {
                $json['thumb'] = '';
            }

            $json['images'] = array();

            $results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

            foreach ($results as $result) {
                $json['images'][] = array(
                    'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
                    'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
                );
            }
            $json["disablePurchase"] = false;
            if ($product_info['quantity'] <= 0 && $product_info['stock_status'] != "In Stock") {
                $json["disablePurchase"] = true;
                $json['price'] = $product_info['stock_status'];
            } else if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $json['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $json['price'] = false;
            }

            $json['price'] = 10000;
            if ((float)$product_info['special']) {
                $json['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $json['special'] = false;
            }

            if ($this->config->get('config_tax')) {
                $json['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
            } else {
                $json['tax'] = false;
            }

            $discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

            $json['discounts'] = array();

            foreach ($discounts as $discount) {
                $json['discounts'][] = array(
                    'quantity' => $discount['quantity'],
                    'price' => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
                );
            }

            $json['options'] = array();

            foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
                $product_option_value_data = array();

                foreach ($option['product_option_value'] as $option_value) {
                    if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
                        if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
                            $price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false));
                        } else {
                            $price = false;
                        }

                        $product_option_value_data[] = array(
                            'product_option_value_id' => $option_value['product_option_value_id'],
                            'option_value_id' => $option_value['option_value_id'],
                            'name' => $option_value['name'],
                            'image' => $this->model_tool_image->resize($option_value['image'], 50, 50),
                            'price' => $price,
                            'price_prefix' => $option_value['price_prefix']
                        );
                    }
                }

                $json['options'][] = array(
                    'product_option_id' => $option['product_option_id'],
                    'product_option_value' => $product_option_value_data,
                    'option_id' => $option['option_id'],
                    'name' => $option['name'],
                    'type' => $option['type'],
                    'value' => $option['value'],
                    'required' => $option['required']
                );
            }

            if ($product_info['minimum']) {
                $json['minimum'] = $product_info['minimum'];
            } else {
                $json['minimum'] = 1;
            }

            $json['review_status'] = $this->config->get('config_review_status');

            if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
                $json['review_guest'] = true;
            } else {
                $json['review_guest'] = false;
            }

            if ($this->customer->isLogged()) {
                $json['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
            } else {
                $json['customer_name'] = '';
            }

            $json['no_of_review'] = (int) $product_info['reviews'];
            $json['rating'] = (int)$product_info['rating'];
            $json['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
            $json['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);

            $json['related_products'] = array();

            $results = $this->model_catalog_product->getProductRelated($this->request->get['product_id'], (float)$product_info['price']);

            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
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

                if ($this->config->get('config_review_status')) {
                    $rating = (int)$result['rating'];
                } else {
                    $rating = false;
                }

                $json['related_products'][] = array(
                    'product_id' => $result['product_id'],
                    'thumb' => $image,
                    'name' => $result['name'],
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                    'price' => 1000,
                    'special' => $special,
                    'tax' => $tax,
                    'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                    'rating' => $rating,
                    'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                );
            }

            $json['tags'] = array();

            if ($product_info['tag']) {
                $tags = explode(',', $product_info['tag']);

                foreach ($tags as $tag) {
                    $json['tags'][] = array(
                        'tag' => trim($tag),
                        'href' => $this->url->link('product/search', 'tag=' . trim($tag))
                    );
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }
}