<?php
class ControllerModuleBlogCategory extends Controller {
    public function index($setting) {

        $data['name'] = $setting['name'];
        $data['blurb'] = $setting['blurb'];
        $data['class'] = $setting['class'];

        $this->load->model('blog/category');
        $this->load->model('tool/image');

        $data['home'] = $this->url->link('common/home');
        $data['categories'] = array();

        if (!$setting['limit']) {
            $setting['limit'] = 5;
        }

        if (!empty($setting['category'])) {
            $categories = array_slice($setting['category'], 0, (int)$setting['limit']);

            foreach ($categories as $category_id) {
                $category_info = $this->model_blog_category->getCategory($category_id);

                if ($category_info) {
                    if ($category_info['image']) {
                        $thumb = $this->model_tool_image->resize($category_info['image'], $setting['width'], $setting['height']);
                    } else {
                        $thumb = $this->model_tool_image->resize('default/category.png', $setting['width'], $setting['height']);
                    }

                    $data['categories'][] = array(
                        'category_id'  => $category_info['category_id'],
                        'image'       => $thumb,
                        'name'        => $category_info['name'],
                        'blurb'        => $category_info['blurb'],
                        'note'        => $category_info['note'],
                        'description' => $category_info['description'],
                        'href'        => $this->url->link('blog/category', 'blog_category_id=' . $category_id)
                    );
                }
            }
        }

// 		if ($data['categories']) {
// 			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category.tpl')) {
// 				return $this->load->view($this->config->get('config_template') . '/template/module/category.tpl', $data);
// 			} else {
// 				return $this->load->view('default/template/module/category.tpl', $data);
// 			}
// 		}
        if ($data['categories']) {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category_' . $setting['view'] . '.tpl')) {
                return $this->load->view($this->config->get('config_template') . '/template/module/category_' . $setting['view'] . '.tpl', $data);
            } else if (file_exists(DIR_TEMPLATE . 'default/template/module/category_' . $setting['view'] . '.tpl')) {
                return $this->load->view('default/template/module/category_' . $setting['view'] . '.tpl', $data);
            } else {
                return $this->load->view('default/template/module/category.tpl', $data);
            }
        }
    }
}