<?php
class ControllerAccountQuestion extends Controller {
    private $error = array();


    public function index() {
        $this->load->language("account/question");
        $this->load->model("catalog/product");

        if(isset($this->request->get['product_id'])) {
            $product_id = $this->request->get['product_id'];
        } else {
            $product_id = '';
        }

        $product_info = $this->model_catalog_product->getProduct($product_id);
        if(!$product_info) {
            $this->response->redirect($this->url->link('common/home', '', 'SSL'));
        }

        $this->document->setTitle($this->language->get('heading_title'));


        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/question', 'product_id=' . $product_id, 'SSL');
            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        if($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
            $this->load->model('catalog/question');

            $this->model_catalog_question->addQuestion($this->request->get['product_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link("product/product", 'product_id=' . $product_id, 'SSL'));
        } else {

            $data['breadcrumbs'] = array();

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home')
            );

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_product'),
                'href' => $this->url->link("product/product", 'product_id=' . $product_id, 'SSL')
            );

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_question'),
                'href' => $this->url->link('account/question', '', 'SSL')
            );

            $data['heading_title'] = $this->language->get('heading_title');


            if(isset($this->request->post['email'])) {
                $data['email'] = $this->request->post['email'];
            } else {
                $data["email"] = $this->customer->getEmail();
            }

            if(isset($this->request->post['text'])) {
                $data['text'] = $this->request->post['text'];
            } else {
                $data["text"] = "";
            }

            if(isset($this->error['email'])) {
                $data['error_email'] = $this->error['email'];
            } else {
                $data['error_email'] = null;
            }
            
            if(isset($this->error['text'])) {
                $data['error_text'] = $this->error['text'];
            } else {
                $data['error_text'] = null;
            }

            $data['entry_email'] = $this->language->get('entry_email');
            $data['entry_question'] = $this->language->get('entry_question');

            $data["button_save"] = $this->language->get('button_save');
            $data["button_back"] = $this->language->get('button_back');

            $data['product_d'] = $product_id;
            $data['name'] = $product_info['name'];

            $data["action"] = $this->url->link("account/question", 'product_id=' . $product_id, 'SSL');
            $data["back"] = $this->url->link("product/product", 'product_id=' . $product_id, 'SSL');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/question.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/question.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/account/question.tpl', $data));
            }
        }

    }

    protected function validate() {
        if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
            $this->error['text'] = $this->language->get('error_text');
        }

        if ((utf8_strlen($this->request->post['email']) < 10) ||  (utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
            $this->error['email'] = $this->language->get('error_email');
        }

        return !$this->error;
    }

}