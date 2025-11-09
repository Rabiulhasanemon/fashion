<?php
class ControllerModuleFilter extends Controller {
	public function index() {
		if (isset($this->request->get['category_id'])) {
            $category_id = (int) $this->request->get['category_id'];
		} else {
			$category_id = 0;
		}



		if(isset($this->request->get['filter_profile_id'])) {
		    $filter_profile_id  = $this->request->get['filter_profile_id'];
        } else {
            $filter_profile_id = null;
        }

        if(isset($this->request->get['manufacturer_id'])) {
		    $manufacturer_id  = $this->request->get['manufacturer_id'];
        } else {
            $manufacturer_id = null;
        }

        $this->document->addScript('catalog/view/javascript/prod/listing.min.5.js');


        $this->load->language('module/filter');

        $price_range = $this->model_catalog_product->getPriceRange(array(
            'filter_category_id' => $category_id,
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
		    $data['filter_groups'] = $this->filter($filter_profile_id);
		} else {
            $data['filter_groups'] = '';
        }
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/filter.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/filter.tpl', $data);
        } else {
            return $this->load->view('default/template/module/filter.tpl', $data);
        }
	}

	public function filter($filter_profile_id) {
	    $cache_key = "filter_profile_" . $filter_profile_id;
        $cache = $this->cacheManger->getCache("html", $cache_key);
        if($cache) return $cache;

        $data['filter_groups'] = array();
        $this->load->model('catalog/category');
        $filter_groups = $this->model_catalog_category->getCategoryFilters($filter_profile_id);

        if(count($filter_groups) == 0) return null;

        foreach ($filter_groups as $filter_group) {
            $childen_data = array();

            foreach ($filter_group['filter'] as $filter) {
                $childen_data[] = array(
                    'filter_id' => $filter['filter_id'],
                    'name'      => $filter['name']
                );
            }

            $data['filter_groups'][] = array(
                'filter_group_id' => $filter_group['filter_group_id'],
                'name'            => $filter_group['name'],
                'filter'          => $childen_data
            );
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/_filter.tpl')) {
            $cache = $this->load->view($this->config->get('config_template') . '/template/module/_filter.tpl', $data);
        } else {
            $cache = $this->load->view('default/template/module/_filter.tpl', $data);
        }

        $this->cacheManger->setCache("html", $cache_key, $cache);
        return $cache;
    }
}