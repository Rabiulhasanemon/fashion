<?php
class ModelDesignAds extends Model {

	public function getAdsByPositionId($position_id) {

        $sql = "SELECT * FROM " . DB_PREFIX . "ads a LEFT JOIN " . DB_PREFIX. "ads_position ap on a.ads_position_id = ap.ads_position_id WHERE status = '1' and device_type = '0' and ap.ads_position_id = '" . $this->db->escape($position_id) . "' ORDER BY RAND() LIMIT 1";
        $query = $this->db->query($sql);
        if($query->row) {
            return  array(
                'ads_position_id' => $query->row['ads_position_id'],
                'image' => $this->config->get('config_ssl') . '/image/' . $query->row['image'],
//                'width' => $query->row['width'],
//                'height' => $query->row['height'],
                'title' => $query->row['title'],
                'url' => $query->row['url'],
            );
        }
        return null;
	}

}