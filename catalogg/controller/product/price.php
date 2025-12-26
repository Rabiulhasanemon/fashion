<?php
class ControllerProductPrice extends Controller {

    public function index() {
        $this->load->model('catalog/category');
        $this->load->model('catalog/product');
        $filter_data = array(
            'filter_category_id' => "",
            'start'              => 0,
            'limit'              => 1000
        );
        $categories = $this->model_catalog_category->getCategories(0);
        $results = array();
        foreach ($categories as $category) {
            if ($category['top']) {
                $children_level_1 = $this->model_catalog_category->getCategories($category['category_id']);
                foreach ($children_level_1 as $child) {
                    $children_level_2 = $this->model_catalog_category->getCategories($child['category_id']);
                    if(count($children_level_2) > 0) {
                        foreach ($children_level_2 as $child_2) {

                            $results[$category["name"]." -> ".$child["name"]." -> ". $child_2["name"]] = $this->model_catalog_product->getProducts(array(
                                'filter_category_id' => $child_2['category_id'],
                                'start'              => 0,
                                'limit'              => 1000
                            ));
                        }
                    } else {
                         $results[$category["name"]." -> ".$child["name"]] = $this->model_catalog_product->getProducts(array(
                            'filter_category_id' => $child['category_id'],
                            'start'              => 0,
                            'limit'              => 1000
                        ));
                        
                    }
                }

            }
        }

//        $this->response->setOutput($this->load->view('default/template/product/price.tpl', array('results' => $results)));
    }
}