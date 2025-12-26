<?php
class ModelCatalogOffer extends Model {
	public function getOffer($offer_id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "offer o LEFT JOIN " . DB_PREFIX . "offer_description od ON (o.offer_id = od.offer_id) WHERE od.language_id = '" . (int)$this->config->get('config_language_id') . "' AND o.offer_id = '" . (int)$offer_id . "'";
        $query = $this->db->query($sql);
		return array(
		    'offer_id' => $query->row['offer_id'],
		    'title' => $query->row['title'],
		    'branch' => $query->row['branch'],
		    'short_description' => $query->row['short_description'],
		    'description' => $query->row['description'],
            'links' => $this->getOfferLinks($offer_id),
            'date_start' => $query->row['date_start'],
            'date_end' => $query->row['date_end'],
            'image' => $query->row['image']
        );
	}

	public function getOffers() {
		$sql = "SELECT offer_id FROM " . DB_PREFIX . "offer o WHERE o.status = 1 AND date_end >= CURRENT_DATE() ORDER BY sort_order";
		$query = $this->db->query($sql);
		$offers = array();
		foreach ($query->rows as $row) {
		    $offers[] = $this->getOffer($row['offer_id']);
        }
		return $offers;
	}


	public function getOfferLinks($offer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "offer_link WHERE offer_id = '" . (int)$offer_id . "'");
        return $query->rows;
	}



}
