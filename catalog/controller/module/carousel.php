<?php
class ControllerModuleCarousel extends Controller {
    public function index($setting) {
        static $module = 0;

        $this->load->model('design/banner');
        $this->load->model('tool/image');
        $data['name'] = $setting['name'];
        $data['blurb'] = $setting['blurb'];
        $data['button_text']  = $setting['button_text'];
        $data['button_link']  = $setting['button_link'];

        $this->document->addStyle('catalog/view/css/owl-carousel/owl.carousel.css');
        $this->document->addScript('catalog/view/javascript/lib/jquery/owl-carousel/owl.carousel.js');

        $data['banners'] = array();

        $results = $this->model_design_banner->getBanner($setting['banner_id']);

        foreach ($results as $result) {
            if (is_file(DIR_IMAGE . $result['image'])) {
                $data['banners'][] = array(
                    'title' => $result['title'],
                    'link'  => $result['link'],
                    'blurb'  => $result['blurb'],
                    'image_class'  => $result['image_class'],
                    'image' => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height'])
                );
            }
        }

        $data['module'] = $module++;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/carousel_' . $setting['view'] . '.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/carousel_' . $setting['view'] . '.tpl', $data);
        } else if (file_exists(DIR_TEMPLATE . 'default/template/module/carousel_' . $setting['view'] . '.tpl')) {
            return $this->load->view('default/template/module/carousel_' . $setting['view'] . '.tpl', $data);
        } else if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/carousel.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/carousel.tpl', $data);
        } else {
            return $this->load->view('default/template/module/carousel.tpl', $data);
        }
    }
}