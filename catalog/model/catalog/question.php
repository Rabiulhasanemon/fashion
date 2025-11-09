<?php
class ModelCatalogQuestion extends Model {
	public function addQuestion($product_id, $data) {
		$this->event->trigger('pre.question.add', $data);
		$this->db->query("INSERT INTO " . DB_PREFIX . "question SET author = '" . $this->db->escape($data['name']) . "', email = '" . $this->db->escape($data['email']) . "', customer_id = '" . (int)$this->customer->getId() . "', product_id = '" . (int)$product_id . "', text = '" . $this->db->escape($data['text']) . "', date_added = NOW()");
		$question_id = $this->db->getLastId();
		$this->event->trigger('post.question.add', $question_id);
	}

	public function getQuestionsByProductId($product_id, $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		$query = $this->db->query("SELECT q.question_id, q.author, q.answer, q.text, p.product_id, pd.name, p.price, p.image, q.date_added FROM " . DB_PREFIX . "question q LEFT JOIN " . DB_PREFIX . "product p ON (q.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.status = '1' AND q.status = '1' AND q.show = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY q.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
		return $query->rows;
	}

	public function getTotalQuestionsByProductId($product_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "question q LEFT JOIN " . DB_PREFIX . "product p ON (q.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.status = '1' AND q.status = '1' AND q.show = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}
}