<?php
class ControllerModuleCollage extends Controller {
    public function index($setting) {
        static $module = 0;

        $this->load->model('design/banner');
        $this->load->model('tool/image');

        $group_index = array();

        $data['banners'] = array();
        $data['class'] = $setting['class'];
        $data['name'] = $setting['name'];
        $data['blurb'] = $setting['blurb'];
        $data['button_text'] = $setting['button_text'];
        $data['button_link'] = $setting['button_link'];
        $data['show_title'] = $setting['show_title'];
        $results = $this->model_design_banner->getBanner($setting['banner_id']);


        foreach ($results as $result) {
            if (!is_file(DIR_IMAGE . $result['image'])) {
                continue;
            }
            $banner = array(
                'title' => $result['title'],
                'blurb' => html_entity_decode($result['blurb'], ENT_QUOTES, 'UTF-8'),
                'link'  => $result['link'],
                'image_class'  => $result['image_class'],
                'image' => $this->config->get('config_ssl') . '/image/' . $result['image']
            );

            $group_class = trim($result['group_class']);
            if ($group_class) {
                if(!isset($group_index[$group_class])) {
                    $group_index[$group_class] = array(
                        'group_class' => $group_class,
                        'banners' => array()
                    );
                    $data['banners'][] = &$group_index[$group_class];
                }
                $group_index[$group_class]['banners'][] = $banner;
            } else {
                $data['banners'][] = $banner;
            }
        }

        $data['module'] = $module++;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/collage.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/collage.tpl', $data);
        } else {
            return $this->load->view('default/template/module/collage.tpl', $data);
        }
    }
}