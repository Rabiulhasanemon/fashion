<?php
class ControllerModuleBanner extends Controller {
	public function index($setting) {
		static $module = 0;

		$this->load->model('design/banner');
		$this->load->model('tool/image');

		$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.css');
		$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.transitions.css');
		$this->document->addScript('catalog/view/javascript/jquery/owl-carousel/carousel.min.js');

		$data['banners'] = array();
        $group_index = array();

		$results = $this->model_design_banner->getBanner($setting['banner_id']);

		// Limit to only 1 banner for full width display
		$results = array_slice($results, 0, 1);

		foreach ($results as $result) {
            if (!is_file(DIR_IMAGE . $result['image'])) {
                continue;
            }
            
            // Get original image dimensions automatically
            $image_path = DIR_IMAGE . $result['image'];
            $original_dimensions = @getimagesize($image_path);
            
            // Always use original image URL - NEVER resize to preserve quality and prevent blurriness
            // This ensures the uploaded image displays at its original quality
            // Construct the original image URL directly
            if ($this->request->server['HTTPS']) {
                $banner_image = $this->config->get('config_ssl') . '/image/' . $result['image'];
            } else {
                $banner_image = $this->config->get('config_url') . '/image/' . $result['image'];
            }
            
            if ($original_dimensions) {
                $banner_width = $original_dimensions[0];
                $banner_height = $original_dimensions[1];
            } else {
                $banner_width = null;
                $banner_height = null;
            }
            
            $banner = array(
                'title' => $result['title'],
                'link'  => $result['link'],
                'blurb'  => $result['blurb'],
                'image_class'  => $result['image_class'],
                'image' => $banner_image,
                'width' => $banner_width,
                'height' => $banner_height
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

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/banner.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/banner.tpl', $data);
		} else {
			return $this->load->view('default/template/module/banner.tpl', $data);
		}
	}
}