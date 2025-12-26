<?php
class ControllerAccountSavePc extends Controller {
    private $error = array();

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->pc_builder = new PcBuilder($registry);
    }

    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/save_pc', '', 'SSL');
            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load->language("tool/pc_builder");
        $this->load->language("account/save_pc");
        $this->load->model("tool/pc_builder");

        $results = $this->model_tool_pc_builder->getComponents(array());
        $is_condition_failed = false;

        $data = array();

        $data['products'] = [];
        $this->load->model("catalog/product");

        foreach ($results as $result) {
            $component_product = $this->pc_builder->getProduct($result['component_id']);
            if($result['is_required'] && $component_product == null) {
                $is_condition_failed = true;
                $this->session->data['error'] = sprintf($this->language->get('error_please_choose'), $result['name']);
                break;
            }
            $product_info = $this->model_catalog_product->getProduct($component_product);
            if($component_product) {
                $data['products'][] = array(
                    'component_id' => $result['component_id'],
                    'product_id' => $component_product,
                    'name' => $product_info['name'],
                    'model' => $product_info['model'],
                    'price' => $product_info['price'],
                    'tax' => 0.0
                );
            }
        }

        if($is_condition_failed) {
            $this->response->redirect($this->url->link('tool/pc_builder'));
        }

        if($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
            $data["name"] = html_entity_decode($this->request->post["name"]);
            $data["description"] = html_entity_decode(isset($this->request->post["description"]) ? $this->request->post["description"] : "");
            $this->load->model("catalog/quote");
            $this->model_tool_pc_builder->savePc($data);
            $this->response->redirect($this->url->link('account/pc'));
        } else {

            $data['breadcrumbs'] = array();

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home')
            );

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_account'),
                'href' => $this->url->link('account/account', '', 'SSL')
            );

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_save_pc'),
                'href' => $this->url->link('account/save_pc', '', 'SSL')
            );

            $data['heading_title'] = $this->language->get('heading_title');

            if(isset($this->request->psot['name'])) {
                $data['name'] = $this->request->psot['name'];
            } else {
                $data["name"] = "";
            }

            if(isset($this->request->psot['description'])) {
                $data['description'] = $this->request->psot['description'];
            } else {
                $data["description"] = "";
            }

            if(isset($this->error['name'])) {
                $data['error_name'] = $this->error['name'];
            } else {
                $data['error_name'] = null;
            }

            if(isset($this->error['description'])) {
                $data['error_description'] = $this->error['description'];
            } else {
                $data['error_description'] = null;
            }

            $data["text_save_pc"] = $this->language->get("text_save_pc");

            $data["entry_name"] = $this->language->get('entry_name');
            $data["entry_description"] = $this->language->get('entry_description');

            $data["button_save"] = $this->language->get('button_save');
            $data["button_back"] = $this->language->get('button_back');

            $data["action"] = $this->url->link("account/save_pc", '', 'SSL');
            $data["back"] = $this->url->link("tool/pc_builder", '', 'SSL');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/save_pc.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/save_pc.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/account/save_pc.tpl', $data));
            }
        }

    }

    protected function validate() {
        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 60)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ((utf8_strlen($this->request->post['description']) > 60)) {
            $this->error['name'] = $this->language->get('error_description');
        }

        return !$this->error;
    }

}