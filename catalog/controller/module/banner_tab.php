<?php
class ControllerModuleBannerTab extends Controller {
	public function index($setting) {
		$this->load->language('module/featured');

		$data['heading_title'] = $this->language->get('heading_title');
        $data['name'] = isset($setting['name']) ? $setting['name'] : '';
		$data['blurb'] = isset($setting['blurb']) ? $setting['blurb'] : '';
        $data['see_all'] = isset($setting['url']) ? $setting['url'] : null;
        $data['class'] = isset($setting['class']) ? $setting['class'] : '';

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('design/banner');

		$this->load->model('tool/image');

		$data['banners'] = array();

		if (!isset($setting['limit']) || !$setting['limit']) {
			$setting['limit'] = 3;
		}

        if (!empty($setting['banner'])) {
            // Limit to 3 banners for display
            $banners = array_slice($setting['banner'], 0, 3);

            foreach ($banners as $banner_id) {
                $banner_info = $this->model_design_banner->getBannerInfo($banner_id);

                if (!$banner_info) { continue; }

                $banner_images = $this->model_design_banner->getBanner($banner_id);
                $child_list = array();
                foreach($banner_images as $banner_image) {
                    // Use original image URL for high resolution - never resize to preserve quality
                    if ($this->request->server['HTTPS']) {
                        $banner_image_url = $this->config->get('config_ssl') . '/image/' . $banner_image['image'];
                    } else {
                        $banner_image_url = $this->config->get('config_url') . '/image/' . $banner_image['image'];
                    }
                    
                    $child_list[] = array(
                        'banner_image_id' => $banner_image['banner_image_id'],
                        'banner_id' => $banner_image['banner_id'],
                        'title' => $banner_image['title'],
                        'blurb' => $banner_image['blurb'],
                        'link' => $banner_image['link'],
                        'image' => $banner_image_url, // Use original image for high resolution
                        'group_class' => $banner_image['group_class'],
                        'image_class' => $banner_image['image_class'],
                    );
                }

                $data['banners'][] = array(
                    'banner_children' => $child_list,
                    'name' => $banner_info['name'],
                );
            }
        }

        // Always return output, even if banners array is empty (for debugging)
        if (isset($setting['view']) && $setting['view']) {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/banner_tab_' . $setting['view'] . '.tpl')) {
                return $this->load->view($this->config->get('config_template') . '/template/module/banner_tab_' . $setting['view'] . '.tpl', $data);
            }
        }
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/banner_tab.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/banner_tab.tpl', $data);
        } else {
            return $this->load->view('default/template/module/banner_tab.tpl', $data);
        }
	}
}