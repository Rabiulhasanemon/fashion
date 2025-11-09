<?php
class ControllerApiAds extends Controller {
    public function index() {
        $json = array();
        if(isset($this->request->post["ads_position"])) {
            $ads_positions = $this->request->post["ads_position"];
            $ads_positions = is_array($ads_positions) ? $ads_positions : array($ads_positions);
        } else {
            $ads_positions = array();
        }

        if(isset($this->request->post["device_type"])) {
            $device_type =  $this->request->post["device_type"];
        } else {
            $device_type = null;
        }

        foreach ($ads_positions as $ads_position) {
            $sql = "SELECT * FROM " . DB_PREFIX . "ads WHERE status = '1' and ads_position_id = '" . (int) $ads_position . "'";
            if($device_type) {
                $sql .= " AND (device_type = '0' OR device_type = '" . (int) $device_type. "')";
            } else {
                $sql .= " AND device_type = '0'";
            }
            $sql .= " ORDER BY RAND() LIMIT 1";
            $query = $this->db->query($sql);
            if($query->row) {
                $json[] = array(
                    'position' => $query->row['ads_position_id'],
                    'image' => $this->config->get('config_ssl') . '/image/' . $query->row['image'],
                    'title' => $query->row['title'],
                    'url' => $query->row['url'],
                );
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}