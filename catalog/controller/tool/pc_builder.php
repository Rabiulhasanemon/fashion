<?php

class ControllerToolPcBuilder extends Controller
{

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->pc_builder = new PcBuilder($registry);
    }

    public function index() {
        $data = array();

        $this->load->language("tool/pc_builder");

        $this->load->model("tool/pc_builder");
        $this->load->model("tool/image");

        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error'] = null;
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = null;
        }

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_pc_builder'),
            'href' => $this->url->link('tool/pc_builder')
        );

        $this->document->setTitle($this->language->get("heading_title"));
        $this->document->setDescription($this->language->get("meta_description"));

        $data["heading_title"] = $this->language->get("heading_title");

        $data['cart'] = $this->url->link('tool/pc_builder/add_to_cart');
        $data['quote'] = $this->url->link('tool/pc_builder/quote');
        $data['print'] = $this->url->link('tool/pc_builder/print_pc');
        $data['screenshot'] = $this->url->link('tool/pc_builder/base64_to_image');
        $data['save'] = $this->url->link('account/save_pc');
        if(isset($this->session->data['operator_id'])) {
            $data['send_quote'] = $this->url->link('operator/quote/add');
        } else {
            $data['send_quote'] = null;
        }

        $data["text_total"] = $this->language->get("text_total");
        $data["button_choose"] = $this->language->get("button_choose");
        $data["button_remove"] = $this->language->get("button_remove");
        $data["button_cart"] = $this->language->get("button_cart");
        $data["button_send_quote"] = $this->language->get("button_send_quote");
        $data["button_quote"] = $this->language->get("button_quote");
        $data["button_save"] = $this->language->get("button_save");
        $data["button_print"] = $this->language->get("button_print");
        $data["button_screenshot"] = $this->language->get("button_screenshot");

        $data["column_component"] = $this->language->get("column_component");
        $data["column_product_name"] = $this->language->get("column_product_name");
        $data["column_image"] = $this->language->get("column_image");
        $data["column_price"] = $this->language->get("column_price");
        $data["column_action"] = $this->language->get("column_action");

        $data["button_choose"] = $this->language->get("button_choose");
        $data["button_remove"] = $this->language->get("button_remove");

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $this->config->get('config_ssl') . '/image/' . $this->config->get('config_logo');
        } else {
            $data['logo'] = '';
        }
        $data['store'] = $this->config->get('config_name');
        $data['web'] = $this->config->get('config_ssl') . '/pc-builder';

        $data['components'] = array();
        $results = $this->model_tool_pc_builder->getComponents(array());
        if ($results['thumb']) {
            $thumb = $this->model_tool_image->resize($result['thumb'], 60, 60);
        } else {
            $thumb = '';
        }
        $this->load->model("catalog/product");
        $total = 0.0;
        foreach ($results as $result) {
            $product_id = $this->pc_builder->getProduct($result['component_id']);
            $product_info = $product_id ? $this->model_catalog_product->getProduct($product_id) : null;
            $product_price = $product_info ? $product_info['price'] : null;
            $product_special = $product_info && $product_info['special'] ? $product_info['special'] : null;
            $image = null;
            if ($product_info && $product_info['image']) {
                $image = $this->model_tool_image->resize($product_info['image'], 80, 80);
            }
            $image = $image ?: $this->model_tool_image->resize('placeholder.png', 80, 80);
            $data['components'][] = array(
                'component_id' => $result['component_id'],
                'name' => $result['name'],
                'thumb'   => $this->config->get('config_ssl') . '/image/' . $result['thumb'],
                'product_id' => $product_id,
                'product_name' => $product_info ? $product_info['name'] : "",
                'product_model' => $product_info ? $product_info['model'] : "",
                'product_image' => $image,
                'product_price' => $product_price ? $this->currency->format($product_price) : "",
                'product_special' => $product_special ? $this->currency->format($product_special) : "",
                'sort_order' => $result['sort_order'],
                'href' =>  $product_info ? $this->url->link('product/product', 'product_id=' . $product_info['product_id']) : "#",
                'choose' => $this->url->link('tool/pc_builder/choose', 'component_id=' . $result['component_id']),
                'remove' => $this->url->link('tool/pc_builder/remove', 'component_id=' . $result['component_id'])
            );
            if ($product_special > 0) {
                $total += $product_special;
            } elseif ($product_price) {
                $total += $product_price;
            }
        }

        $data['total'] = $this->currency->format($total);

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $this->config->get('config_ssl') . '/image/' . $this->config->get('config_logo');
        } else {
            $data['logo'] = '';
        }
        $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/pc-builder.css');
        $this->document->addScript('https://html2canvas.hertzen.com/dist/html2canvas.min.js');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/tool/pc_builder.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/tool/pc_builder.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/pc_builder.tpl', $data));
        }
    }

    public function choose() {
        $this->load->language("tool/pc_builder");

        $this->load->model("tool/pc_builder");
        $this->load->model("tool/image");
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        if (isset($this->request->get['filter_category'])) {
            $filter_category = $this->request->get['filter_category'];
        } else {
            $filter_category = '';
        }

        if (isset($this->request->get['filter'])) {
            $filter = $this->request->get['filter'];
        } else {
            $filter = '';
        }

        if (isset($this->request->get['filter_price'])) {
            $filter_price = $this->request->get['filter_price'];
        } else {
            $filter_price = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.price';
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

        $component_id = isset($this->request->get['component_id']) ? $this->request->get['component_id'] : null;

        if ($component_id != '' && $component_id != null) {
            $component_info = $this->model_tool_pc_builder->getComponent($component_id);
        } else {
            $component_info = null;
        }

        if (!$component_info) {
            $this->response->redirect($this->url->link('tool/pc_builder'));
        }

        $depends_on = (int)$component_info['depends_on'];
        $depends_on_product = $depends_on ? $this->pc_builder->getProduct($depends_on) : null;
        if ($depends_on && !$depends_on_product) {
            $depends_on_info = $this->model_tool_pc_builder->getComponent($depends_on);
            $this->session->data["error"] = sprintf($this->language->get('error_1'), $depends_on_info['name']);
            $this->response->redirect($this->url->link('tool/pc_builder', "component_id=" . $component_id));
        }

        $url = 'component_id=' . $component_id;

        $data = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_pc_builder'),
            'href' => $this->url->link('tool/pc_builder')
        );

        $data['breadcrumbs'][] = array(
            'text' => sprintf($this->language->get("text_choose_component"), $component_info['name']),
            'href' => $this->url->link('tool/pc_builder/choose', $url)
        );

        $data['back'] = $this->url->link('tool/pc_builder');

        $this->document->setTitle($this->language->get("heading_title"));
        $this->document->setDescription(sprintf($this->language->get("meta_description_choose_component"), $component_info['name']));

        $data["heading_title"] = sprintf($this->language->get("text_choose_component"), $component_info['name']);

        $data["column_product_name"] = $this->language->get("column_product_name");
        $data["column_image"] = $this->language->get("column_image");
        $data["column_model"] = $this->language->get("column_model");
        $data["column_price"] = $this->language->get("column_price");
        $data["column_action"] = $this->language->get("column_action");

        $data["button_add"] = $this->language->get("button_add");

        $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/pc-builder.css');

        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['filter_name'] = $filter_name;
        $data['filter_category'] = $filter_category;
        $data['component_id'] = $component_id;

        $filter_data = array(
            'component_id' => $component_id,
            'filter_name' => $filter_name,
            'filter_filter'      => $filter,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $limit,
            'limit' => $limit
        );

        if($filter_price) {
            $filter_price = explode("-", $filter_price);
            $filter_data['filter_price_from'] = (float) $filter_price[0];
            $filter_data['filter_price_to'] = isset($filter_price[1]) ? (float) $filter_price[1] : null;
        }

        if ($depends_on_product) {
            $filter_data['depends_on_product'] = $depends_on_product;
        }

        $component_categories = $this->model_tool_pc_builder->getComponentCategories($component_id);

        $filter_data['categories'] = array();

        foreach (explode(",", $filter_category) as $category_id) {
            $category_id = (int) $category_id;
            if ($category_id && array_search($category_id, $component_categories) !== false) {
                $filter_data['categories'][] = $category_id;
            }
        }
        if(!$filter_data['categories']) {
            $filter_data['categories'] = $component_categories;
        }

        $results = $this->model_tool_pc_builder->getProductsForComponent($filter_data);
        $product_total = $this->model_tool_pc_builder->getTotalProductsForComponent($filter_data);

        $data['products'] = array();

        foreach ($results as $result) {
            $image = null;
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], 200, 200);
            }
            $image = $image ?: $this->model_tool_image->resize('placeholder.png', 200, 200);


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

            $data['products'][] = array(
                'product_id' => $result['product_id'],
                'thumb' => $image,
                'short_description' => $result['short_description'],
                'name' => $result['name'],
                'model' => $result['model'],
                'price' => $price,
                'disablePurchase' => $disablePurchase,
                'special' => $special,
                'tax' => $tax,
                'rating' => $result['rating'],
                'href' => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                'add' => $this->url->link('tool/pc_builder/add', 'product_id=' . $result['product_id'] . "&" . $url)
            );
        }

        $url = '&component_id=' . $component_id;

        $data['categories'] = array();

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . $this->request->get['filter_category'];
        }

        if (isset($this->request->get['filter'])) {
            $url .= '&filter=' . $this->request->get['filter'];
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        $data['sorts'] = array();

        $data['sorts'][] = array(
            'text' => $this->language->get('text_default'),
            'value' => 'p.sort_order-ASC',
            'href' => $this->url->link('tool/pc_builder/choose', 'sort=p.sort_order&order=ASC' . $url)
        );

        $data['sorts'][] = array(
            'text' => $this->language->get('text_name_asc'),
            'value' => 'pd.name-ASC',
            'href' => $this->url->link('tool/pc_builder/choose', '&sort=pd.name&order=ASC' . $url)
        );

        $data['sorts'][] = array(
            'text' => $this->language->get('text_name_desc'),
            'value' => 'pd.name-DESC',
            'href' => $this->url->link('tool/pc_builder/choose', '&sort=pd.name&order=DESC' . $url)
        );

        $data['sorts'][] = array(
            'text' => $this->language->get('text_price_asc'),
            'value' => 'p.price-ASC',
            'href' => $this->url->link('tool/pc_builder/choose', '&sort=p.price&order=ASC' . $url)
        );

        $data['sorts'][] = array(
            'text' => $this->language->get('text_price_desc'),
            'value' => 'p.price-DESC',
            'href' => $this->url->link('tool/pc_builder/choose', '&sort=p.price&order=DESC' . $url)
        );

        if ($this->config->get('config_review_status')) {
            $data['sorts'][] = array(
                'text' => $this->language->get('text_rating_desc'),
                'value' => 'rating-DESC',
                'href' => $this->url->link('tool/pc_builder/choose', '&sort=rating&order=DESC' . $url)
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_rating_asc'),
                'value' => 'rating-ASC',
                'href' => $this->url->link('tool/pc_builder/choose', '&sort=rating&order=ASC' . $url)
            );
        }

        $data['sorts'][] = array(
            'text' => $this->language->get('text_model_asc'),
            'value' => 'p.model-ASC',
            'href' => $this->url->link('tool/pc_builder/choose', '&sort=p.model&order=ASC' . $url)
        );

        $data['sorts'][] = array(
            'text' => $this->language->get('text_model_desc'),
            'value' => 'p.model-DESC',
            'href' => $this->url->link('tool/pc_builder/choose', '&sort=p.model&order=DESC' . $url)
        );

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('tool/pc_builder/choose', $url . '&page={page}');

        $data['pagination'] = $pagination->render();
        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

        $data['filters'] = $this->filter($component_info, $component_categories);
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/tool/pb_choose_product.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/tool/pb_choose_product.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/pb_choose_product.tpl', $data));
        }
    }

    public function filter($component_info, $categories) {
        $data = array();

        if(count($categories) > 1) {
            $results = $this->model_tool_pc_builder->getCategories(array(
                'categories' => $categories
            ));

            foreach ($results as $result) {
                $data['categories'][] = array(
                    'name' => $result['name'],
                    'category_id' => $result['category_id'],
                );
            }
        }

        if(isset($component_info['filter_profile_id'])) {
            $filter_profile_id  = $component_info['filter_profile_id'];
        } else {
            $filter_profile_id = null;
        }

        if(isset($this->request->get['manufacturer_id'])) {
            $manufacturer_id  = $this->request->get['manufacturer_id'];
        } else {
            $manufacturer_id = null;
        }

//        $this->document->addScript('catalog/view/javascript/lib/noUi/nouislider.min.js');
//        $this->document->addScript('catalog/view/javascript/cms/listing.js');

        $this->document->addScript('catalog/view/javascript/prod/listing.min.4.js');

        $this->load->language('module/filter');

        $price_range = $this->model_catalog_product->getPriceRange(array(
            'filter_categories' => $categories,
            'filter_manufacturer_id' => $manufacturer_id
        ));
        $data['min_price'] = (int) $price_range['min_price'];
        $data['max_price'] = (int) $price_range['max_price'];

        if(isset($this->request->get['filter_price'])) {
            $filter_price = explode("-", $this->request->get['filter_price']);
            $data['price_from'] =  (float) $filter_price[0];
            $data['price_to'] = isset($filter_price[1]) ? (float) $filter_price[1] : null;
        }

        if(empty($data['price_from'])) {
            $data['price_from'] = $data['min_price'];
        }
        if(empty($data['price_to'])) {
            $data['price_to'] = $data['max_price'];
        }

        if ($filter_profile_id) {
            $data['filter_groups'] = $this->load->controller('module/filter/filter', $component_info["filter_profile_id"]);
        } else {
            $data['filter_groups'] = '';
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/filter.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/filter.tpl', $data);
        } else {
            return $this->load->view('default/template/module/filter.tpl', $data);
        }
    }

    public function add()
    {
        $this->load->language("tool/pc_builder");

        $this->load->model("tool/pc_builder");

        $product_id = isset($this->request->get['product_id']) ? $this->request->get['product_id'] : null;

        $component_id = isset($this->request->get['component_id']) ? $this->request->get['component_id'] : null;

        if ($component_id != '' && $component_id != null) {
            $component_info = $this->model_tool_pc_builder->getComponent($component_id);
        } else {
            $component_info = null;
        }

        if (!$component_info || !$product_id) {
            $this->response->redirect($this->url->link('tool/pc_builder'));
        }

        $depends_on = (int)$component_info['depends_on'];
        $depends_on_product = $depends_on ? $this->pc_builder->getProduct($depends_on) : null;
        if ($depends_on && !$depends_on_product) {
            $depends_on_info = $this->model_tool_pc_builder->getComponent($depends_on);
            $this->session->data["error"] = sprintf($this->language->get('error_1'), $depends_on_info['name']);
            $this->response->redirect($this->url->link('tool/pc_builder', "component_id=" . $component_id));
        }

        $this->pc_builder->setProduct($component_id, $product_id);

        $dependents = $this->model_tool_pc_builder->getDependentComponents($component_id);

        foreach ($dependents as $dependent) {
            $dependent_product_id = $this->pc_builder->getProduct($dependent["component_id"]);
            if ($dependent_product_id) {
                $dependent_product_categories = $this->model_tool_pc_builder->getComponentCategories($dependent["component_id"]);
                if (!$this->model_tool_pc_builder->isValidComponentProduct($dependent_product_id, $component_id, $dependent_product_categories)) {
                    $this->pc_builder->clearProduct($dependent["component_id"]);
                }
            }
        }
        $this->response->redirect($this->url->link('tool/pc_builder'));
    }

    public function remove()
    {
        $this->load->language("tool/pc_builder");

        $this->load->model("tool/pc_builder");


        $component_id = isset($this->request->get['component_id']) ? $this->request->get['component_id'] : null;

        if ($component_id != '' && $component_id != null) {
            $component_info = $this->model_tool_pc_builder->getComponent($component_id);
        } else {
            $component_info = null;
        }

        if (!$component_info) {
            $this->response->redirect($this->url->link('tool/pc_builder'));
        }

        $this->pc_builder->clearProduct($component_id);

        $dependents = $this->model_tool_pc_builder->getDependentComponents($component_id);

        foreach ($dependents as $dependent) {
            $dependent_product_id = $this->pc_builder->getProduct($dependent["component_id"]);
            if ($dependent_product_id) {
                $this->pc_builder->clearProduct($dependent["component_id"]);
            }
        }
        $this->response->redirect($this->url->link('tool/pc_builder'));
    }

    public function add_to_cart()
    {
        if(isset($this->request->get["pc_id"])) {
            $pc_id = $this->request->get["pc_id"];
        } else {
            $pc_id = null;
        }
        $this->load->language("tool/pc_builder");
        $this->load->model("tool/pc_builder");
        $this->load->model("catalog/product");

        $results = $this->model_tool_pc_builder->getComponents(array());
        $is_condition_failed = false;
        $products = array();
        foreach ($results as $result) {
            if($pc_id) {
                $component_product = $this->model_tool_pc_builder->getPcProduct($pc_id, $result['component_id']);
                $component_product_id = $component_product ? $component_product['product_id'] : null;
            } else {
                $component_product_id = $this->pc_builder->getProduct($result['component_id']);
            }
            if($component_product_id) {
                $product_info = $this->model_catalog_product->getProduct($component_product_id);
            } else {
                $product_info = null;
            }
            if ($result['is_required'] && ($product_info == null || $product_info['quantity'] <= 0)) {
                if ($pc_id) {
                    $this->session->data['error'] = sprintf($this->language->get('error_product_not_available'));
                } else {
                    $this->session->data['error'] = sprintf($this->language->get('error_please_choose'), $result['name']);
                }
                $is_condition_failed = true;
                break;
            }
            if($product_info['quantity'] > 0) {
                $products[] = $component_product_id;
            }
        }

        if ($is_condition_failed) {
            $this->response->redirect($this->url->link('tool/pc_builder'));
        }

        foreach ($products as $component_product_id) {
            $this->cart->add($component_product_id);
        }
        $this->response->redirect($this->url->link('checkout/cart'));
    }

    public function copy() {
        $this->load->language("tool/pc_builder");
        $this->load->model("tool/pc_builder");
        $this->load->model("catalog/product");

        if(isset($this->request->get["pc_id"])) {
            $pc_id = $this->request->get["pc_id"];
        } else {
            $pc_id = 0;
        }


        $results = $this->model_tool_pc_builder->getComponents(array('status' => 1));
        foreach ($results as $result) {
            $this->pc_builder->clearProduct($result['component_id'], false);
            $component_products = $this->model_tool_pc_builder->getPcProductsByComponent($pc_id, $result['component_id']);

            foreach ($component_products as $component_product) {
                $product_info = $this->model_catalog_product->getProduct($component_product['product_id']);
                if($product_info && $product_info['quantity'] > 0) {
                    $this->pc_builder->setProduct($result['component_id'], $component_product['product_id'], $result['multiple'], false);
                }
            }
        }
        $this->response->redirect($this->url->link('tool/pc_builder'));

    }
    public function quote() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('tool/pc_builder/quote', '', 'SSL');
            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load->language("tool/pc_builder");
        $this->load->model("tool/pc_builder");

        $results = $this->model_tool_pc_builder->getComponents(array());
        $is_condition_failed = false;

        $data = array();

        $data['products'] = [];
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
                $data['products'][] = array(
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

        $data['operator_id'] = 0;
        $data['customer_id'] = $this->customer->getId();
        $data['customer_group_id'] = $this->customer->getGroupId();
        $data['firstname'] = $this->customer->getFirstName();
        $data['lastname'] = $this->customer->getLastName();
        $data['email'] = $this->customer->getEmail();
        $data['telephone'] = $this->customer->getTelephone();
        $data['fax'] = $this->customer->getFax();

        $this->load->model("catalog/quote");
        $this->model_catalog_quote->save($data);
        $this->session->data["success"] = sprintf($this->language->get('success_quote'), $this->customer->getEmail());;
        $this->response->redirect($this->url->link('tool/pc_builder'));
    }

    public function info() {

        $this->load->model("tool/pc_builder");
        $this->load->language("tool/pc_builder");

        if (isset($this->request->get["pc_id"])) {
            $pc_id = $this->request->get["pc_id"];
        } else {
            $pc_id = null;
        }

        if ($pc_id) {
            $pc_info = $this->model_tool_pc_builder->getPc($pc_id);
        } else {
            $pc_info = null;
        }
        if(!$pc_info) {
            $this->response->redirect($this->url->link('tool/pc_builder'));
        }

        $data = array();

        $this->load->model("tool/image");

        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error'] = null;
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = null;
        }

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_pc_builder'),
            'href' => $this->url->link('tool/pc_builder')
        );

        $data['breadcrumbs'][] = array(
            'text' => $pc_info['name'],
            'href' => $this->url->link('tool/pc_builder/info', "pc_id=" . $pc_id)
        );

        $this->document->setTitle(sprintf($this->language->get("heading_title_info"), $pc_info['name']));

        $data["heading_title"] = $this->language->get("heading_title");

        $data['cart'] = $this->url->link('tool/pc_builder/add_to_cart', 'pc_id=' . $pc_id);
        $data['copy'] = $this->url->link('tool/pc_builder/copy', 'pc_id=' . $pc_id);
        $data['screenshot'] = $this->url->link('tool/pc_builder/base64_to_image');
        $data['build'] = $this->url->link('tool/pc_builder');

        $data["text_total"] = $this->language->get("text_total");

        $data["button_cart"] = $this->language->get("button_cart");
        $data["button_build"] = $this->language->get("button_build");
        $data["button_copy"] = $this->language->get("button_copy");

        $data["column_component"] = $this->language->get("column_component");
        $data["column_product_name"] = $this->language->get("column_product_name");
        $data["column_image"] = $this->language->get("column_image");
        $data["column_price"] = $this->language->get("column_price");
        $data["column_action"] = $this->language->get("column_action");
        $data["column_availability"] = $this->language->get("column_availability");

        $data["button_choose"] = $this->language->get("button_choose");
        $data["button_remove"] = $this->language->get("button_remove");

        $data['components'] = array();
        $results = $this->model_tool_pc_builder->getComponents(array());
        $this->load->model("catalog/product");
        $total = 0.0;
        foreach ($results as $result) {
            $pc_product = $this->model_tool_pc_builder->getPcProduct($pc_id, $result['component_id']);

            $product_info = $pc_product ? $this->model_catalog_product->getProduct($pc_product['product_id']) : null;
            $product_price = $product_info ? $product_info['price'] : ($pc_product ? $pc_product['price'] : "");
            $product_special = $product_info && $product_info['special'] ? $product_info['special'] : null;
            $image = null;
            if ($product_info && $product_info['image']) {
                $image = $this->model_tool_image->resize($product_info['image'], 80, 80);
            }
            $image = $image ?: $this->model_tool_image->resize('placeholder.png', 80, 80);
            $data['components'][] = array(
                'component_id' => $result['component_id'],
                'name' => $result['name'],
                'product_id' => $product_info ? $product_info['product_id'] : ($pc_product ? $pc_product['product_id'] : ""),
                'stock_status' => $product_info ? $product_info['stock_status'] : ($pc_product ? "Not Available" : ""),
                'product_name' => $product_info ? $product_info['name'] : ($pc_product ? $pc_product['name'] : ""),
                'product_model' => $product_info ? $product_info['model'] : ($pc_product ? $pc_product['model'] : ""),
                'product_image' => $image,
                'product_price' => $product_price ? $this->currency->format($product_price) : "",
                'product_special' => $product_special ? $this->currency->format($product_special) : "",
                'sort_order' => $result['sort_order'],
                'href' =>  $product_info ? $this->url->link('product/product', 'product_id=' . $product_info['product_id']) : "#"
            );
            if ($product_special) {
                $total += $product_special;
            } elseif ($product_price) {
                $total += $product_price;
            }
        }

        $data['total'] = $this->currency->format($total);

        $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/pc-builder.css');
        $this->document->addScript('https://html2canvas.hertzen.com/dist/html2canvas.min.js');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/tool/pc_info.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/tool/pc_info.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/pc_info.tpl', $data));
        }
    }

    public function print_pc() {
        $this->load->model("tool/pc_builder");
        $this->load->language("tool/pc_builder");

        if (isset($this->request->get["pc_id"])) {
            $pc_id = $this->request->get["pc_id"];
        } else {
            $pc_id = null;
        }

        if ($pc_id) {
            $pc_info = $this->model_tool_pc_builder->getPc($pc_id);
        } else {
            $pc_info = null;
        }

        $data = array();

        $this->load->model("tool/image");

        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error'] = null;
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = null;
        }

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_pc_builder'),
            'href' => $this->url->link('tool/pc_builder')
        );

        $data['breadcrumbs'][] = array(
            'text' => $pc_info['name'],
            'href' => $this->url->link('tool/pc_builder/info', "pc_id=" . $pc_id)
        );

        $this->document->setTitle($this->language->get("heading_title"));

        $data["heading_title"] = $this->language->get("heading_title_print");

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $this->config->get('config_ssl') . '/image/' . $this->config->get('config_logo');
        } else {
            $data['logo'] = '';
        }

        $data["text_total"] = $this->language->get("text_total");

        $data["column_component"] = $this->language->get("column_component");
        $data["column_product_name"] = $this->language->get("column_product_name");
        $data["column_image"] = $this->language->get("column_image");
        $data["column_price"] = $this->language->get("column_price");
        $data["column_action"] = $this->language->get("column_action");
        $data["column_availability"] = $this->language->get("column_availability");

        $data['components'] = array();
        $results = $this->model_tool_pc_builder->getComponents(array());
        $this->load->model("catalog/product");
        $total = 0.0;
        foreach ($results as $result) {
            if($pc_info) {
                $pc_product = $this->model_tool_pc_builder->getPcProduct($pc_id, $result['component_id']);
                $product_id = $pc_product ? $pc_product['product_id'] : "";
            } else {
                $pc_product = null;
                $product_id = $this->pc_builder->getProduct($result['component_id']);
            }

            $product_info = $product_id ? $this->model_catalog_product->getProduct($product_id) : null;
            $product_price = $product_info ? $product_info['price'] : ($pc_product ? $pc_product['price'] : "");
            $product_special = $product_info && $product_info['special'] ? $product_info['special'] : null;
            $image = null;
            if ($product_info && $product_info['image']) {
                $image = $this->model_tool_image->resize($product_info['image'], 80, 80);
            }
            $image = $image ?: $this->model_tool_image->resize('placeholder.png', 80, 80);
            $data['components'][] = array(
                'component_id' => $result['component_id'],
                'name' => $result['name'],
                'product_id' => $product_info ? $product_info['product_id'] : ($pc_product ? $pc_product['product_id'] : ""),
                'stock_status' => $product_info ? $product_info['stock_status'] : ($pc_product ? "Not Available" : ""),
                'product_name' => $product_info ? $product_info['name'] : ($pc_product ? $pc_product['name'] : ""),
                'product_model' => $product_info ? $product_info['model'] : ($pc_product ? $pc_product['model'] : ""),
                'product_image' => $image,
                'product_price' => $product_price ? $this->currency->format($product_price) : "",
                'product_special' => $product_special ? $this->currency->format($product_special) : "",
                'sort_order' => $result['sort_order'],
                'href' =>  $product_info ? $this->url->link('product/product', 'product_id=' . $product_info['product_id']) : "#"
            );
            if ($product_special) {
                $total += $product_special;
            } elseif ($product_price) {
                $total += $product_price;
            }
        }

        $data['total'] = $this->currency->format($total);

        $data['store'] = $this->config->get('config_name');
        $data['address'] = $this->config->get('config_address');
        $data['email'] = $this->config->get('config_email');
        $data['telephone'] = $this->config->get('config_telephone');
        $data['web'] = $this->config->get('config_ssl') . '/pc-builder';
        $data['home'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/tool/pc_print.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/tool/pc_print.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/pc_print.tpl', $data));
        }
    }

    public function base64_to_image() {
        $base64_string = $this->request->post['image'];
        $data = explode( ',', $base64_string );
        header('Content-Type: image/png');
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="PC Builder - ' . time(). '.png"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        echo base64_decode( $data[ 1 ] );
    }
}