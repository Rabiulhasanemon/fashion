<?php
class ControllerApiCategory extends Controller {

    public function index() {
        $this->load->model("account/api");
        if(isset($this->request->server['PHP_AUTH_USER']) &&  isset($this->request->server['PHP_AUTH_PW'])) {
            $api_user = $this->model_account_api->login($this->request->server['PHP_AUTH_USER'], $this->request->server['PHP_AUTH_PW']);
        } else {
            $api_user = null;
        }

        $this->load->language('api/catalog');

        $json = array();

        if($api_user) {
            $this->load->model("catalog/category");
            if(isset($this->request->request['parent_id'])) {
                $parent_id = (int) $this->request->request['parent_id'];
            } else {
                $parent_id = null;
            }
            $sql = "SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE ";
            if($parent_id !== null) {
                $sql .=  "c.parent_id = '" . (int)$parent_id . "' AND ";
            }
            $sql .= "cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)";
            $query = $this->db->query($sql);
            $results = $query->rows;
            foreach ($results as $result) {
                $json[] = array(
                    'category_id' => $result['category_id'],
                    'name' => $result['name'],
                    'parent_id' => $result['parent_id'],
                    'top' => $result['top'],
                    'column' => $result['column'],
                    'meta_title' => $result['meta_title'],
                    'meta_description' => $result['meta_description']
                );
            }
        } else {
            $json['error']['warning'] = $this->language->get('error_permission');
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}