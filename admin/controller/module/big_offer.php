<?php
class ControllerModuleBigOffer extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('module/big_offer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        $this->load->model('catalog/url_alias');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $post = $this->request->post;

            // Normalize dates
            $post['big_offer_start'] = isset($post['big_offer_start']) ? $post['big_offer_start'] : '';
            $post['big_offer_end'] = isset($post['big_offer_end']) ? $post['big_offer_end'] : '';

            // Handle product array
            if (isset($post['big_offer_product']) && is_array($post['big_offer_product'])) {
                $post['big_offer_product'] = array_filter($post['big_offer_product']); // Remove empty values
            } else {
                $post['big_offer_product'] = array();
            }

            // Handle limit
            $post['big_offer_limit'] = isset($post['big_offer_limit']) ? (int)$post['big_offer_limit'] : 8;
            // Handle banner id
            $post['big_offer_banner_id'] = isset($post['big_offer_banner_id']) ? (int)$post['big_offer_banner_id'] : 0;

            // Generate slug from title
            $generatedSlug = $this->generateSlug(isset($post['big_offer_title']) ? $post['big_offer_title'] : 'offer');
            $oldSlug = $this->config->get('big_offer_slug');

            $post['big_offer_slug'] = $generatedSlug;

            // Persist settings
            $this->model_setting_setting->editSetting('big_offer', $post);

            // Ensure layout exists for route common/big_offer
            $this->ensureLayout();

            // Maintain url_alias: map route=common/big_offer -> keyword = slug
            if ($oldSlug && $oldSlug !== $generatedSlug) {
                $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE `query` = 'route=common/big_offer'");
                $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE `keyword` = '" . $this->db->escape($oldSlug) . "'");
            }
            // Always upsert mapping
            $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE `query` = 'route=common/big_offer'");
            $this->model_catalog_url_alias->add('route=common/big_offer', $generatedSlug);

            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_start'] = $this->language->get('entry_start');
        $data['entry_end'] = $this->language->get('entry_end');
        $data['entry_button_text'] = $this->language->get('entry_button_text');
        $data['entry_button_icon'] = $this->language->get('entry_button_icon');
        $data['entry_slug'] = $this->language->get('entry_slug');
        $data['entry_product'] = $this->language->get('entry_product');
        $data['entry_limit'] = $this->language->get('entry_limit');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/big_offer', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['action'] = $this->url->link('module/big_offer', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        // Load current values or POST
        $fields = array(
            'big_offer_status', 'big_offer_title', 'big_offer_description', 'big_offer_image',
            'big_offer_start', 'big_offer_end', 'big_offer_button_text', 'big_offer_button_icon', 'big_offer_slug',
            'big_offer_product', 'big_offer_limit', 'big_offer_banner_id'
        );
        foreach ($fields as $f) {
            if (isset($this->request->post[$f])) {
                $data[$f] = $this->request->post[$f];
            } else {
                $data[$f] = $this->config->get($f);
            }
        }

        // Banners list for selector
        $this->load->model('design/banner');
        $data['banners'] = $this->model_design_banner->getBanners();

        // Build selected products list for UI
        $this->load->model('catalog/product');
        $data['products'] = array();
        $selected = array();
        if (!empty($data['big_offer_product']) && is_array($data['big_offer_product'])) {
            $selected = $data['big_offer_product'];
        }
        foreach ($selected as $product_id) {
            $product_info = $this->model_catalog_product->getProduct($product_id);
            if ($product_info) {
                $data['products'][] = array('product_id' => $product_info['product_id'], 'name' => $product_info['name']);
            }
        }

        // Image preview handling
        $this->load->model('tool/image');
        if (!empty($data['big_offer_image']) && is_file(DIR_IMAGE . $data['big_offer_image'])) {
            $data['thumb'] = $this->model_tool_image->resize($data['big_offer_image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }
        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        $data['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/big_offer.tpl', $data));
    }

    private function ensureLayout() {
        // Ensure layout 'Big Offer' exists with route 'common/big_offer'
        $layout = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout WHERE name = 'Big Offer'");
        if (!$layout->num_rows) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "layout SET name = 'Big Offer'");
            $layout_id = $this->db->getLastId();
            $this->db->query("INSERT INTO " . DB_PREFIX . "layout_route SET layout_id = '" . (int)$layout_id . "', store_id = '0', `route` = 'common/big_offer'");
        } else {
            $layout_id = (int)$layout->row['layout_id'];
            $exists = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE layout_id = '" . $layout_id . "' AND `route` = 'common/big_offer'");
            if (!$exists->num_rows) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "layout_route SET layout_id = '" . $layout_id . "', store_id = '0', `route` = 'common/big_offer'");
            }
        }
    }

    private function generateSlug($text) {
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        $text = strtolower($text);
        $text = preg_replace('~[^-a-z0-9]+~', '', $text);
        if (empty($text)) { $text = 'offer'; }
        return $text;
    }

    public function install() {
        // Default settings
        $this->load->model('setting/setting');
        $this->load->model('extension/extension');
        
        // Register the extension
        $this->model_extension_extension->install('module', 'big_offer');
        
        $defaults = array(
            'big_offer_status' => 0,
            'big_offer_title' => 'Big Offer',
            'big_offer_description' => '',
            'big_offer_image' => '',
            'big_offer_start' => '',
            'big_offer_end' => '',
            'big_offer_button_text' => 'Big Offer',
            'big_offer_button_icon' => 'local_offer',
            'big_offer_slug' => 'big-offer',
            'big_offer_product' => array(),
            'big_offer_limit' => 8
        );
        $this->model_setting_setting->editSetting('big_offer', $defaults);
        $this->ensureLayout();
    }

    public function uninstall() {
        $this->load->model('setting/setting');
        $this->load->model('extension/extension');
        
        $this->model_setting_setting->deleteSetting('big_offer');
        $this->model_extension_extension->uninstall('module', 'big_offer');
        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE `query` = 'route=common/big_offer'");
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'module/big_offer')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        return !$this->error;
    }
}


