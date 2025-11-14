<?php
class ModelCatalogReview extends Model {
	public function addReview($data) {
		$this->event->trigger('pre.admin.review.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "review SET author = '" . $this->db->escape($data['author']) . "', product_id = '" . (int)$data['product_id'] . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");

		$review_id = $this->db->getLastId();

		$this->cache->delete('product');

		$this->event->trigger('post.admin.review.add', $review_id);

		return $review_id;
	}

	public function editReview($review_id, $data) {
		$this->event->trigger('pre.admin.review.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "review SET author = '" . $this->db->escape($data['author']) . "', product_id = '" . (int)$data['product_id'] . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE review_id = '" . (int)$review_id . "'");

		$this->cache->delete('product');

		$this->event->trigger('post.admin.review.edit', $review_id);
	}

	public function deleteReview($review_id) {
		$this->event->trigger('pre.admin.review.delete', $review_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE review_id = '" . (int)$review_id . "'");

		$this->cache->delete('product');

		$this->event->trigger('post.admin.review.delete', $review_id);
	}

	public function getReview($review_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT pd.name FROM " . DB_PREFIX . "product_description pd WHERE pd.product_id = r.product_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS product FROM " . DB_PREFIX . "review r WHERE r.review_id = '" . (int)$review_id . "'");

		return $query->row;
	}

	public function getReviews($data = array()) {
		// Use subquery to get product name to avoid filtering out reviews without product descriptions
		$sql = "SELECT r.review_id, 
			(SELECT pd.name FROM " . DB_PREFIX . "product_description pd WHERE pd.product_id = r.product_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' LIMIT 1) AS name, 
			r.author, r.rating, r.status, r.date_added 
			FROM " . DB_PREFIX . "review r 
			WHERE 1=1";

		if (!empty($data['filter_product'])) {
			$sql .= " AND (SELECT pd.name FROM " . DB_PREFIX . "product_description pd WHERE pd.product_id = r.product_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' LIMIT 1) LIKE '" . $this->db->escape($data['filter_product']) . "%'";
		}

		if (!empty($data['filter_author'])) {
			$sql .= " AND r.author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND r.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		$sort_data = array(
			'name',
			'r.author',
			'r.rating',
			'r.status',
			'r.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			// Handle special case for product name sorting
			if ($data['sort'] == 'name' || $data['sort'] == 'pd.name') {
				$sql .= " ORDER BY (SELECT pd.name FROM " . DB_PREFIX . "product_description pd WHERE pd.product_id = r.product_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' LIMIT 1)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			// Default sort by date_added DESC (newest first)
			$sql .= " ORDER BY r.date_added DESC";
		}

		// Only add order direction if sort was explicitly set
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				// If already has DESC from default, don't add again
				if (strpos($sql, 'DESC') === false && strpos($sql, 'ASC') === false) {
					$sql .= " DESC";
				}
			} else if (isset($data['order']) && ($data['order'] == 'ASC')) {
				// Replace DESC with ASC if needed
				$sql = str_replace(' DESC', ' ASC', $sql);
			}
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		// Debug: Log the SQL query
		error_log('=== ADMIN REVIEW QUERY DEBUG ===');
		error_log('SQL: ' . $sql);
		error_log('Filter Data: ' . print_r($data, true));
		
		$query = $this->db->query($sql);
		
		// Debug: Log the results
		if ($query) {
			error_log('Query executed successfully');
			error_log('Number of rows returned: ' . (isset($query->num_rows) ? $query->num_rows : 'N/A'));
			error_log('Rows: ' . print_r($query->rows, true));
		} else {
			error_log('Query failed!');
		}
		error_log('================================');

		return $query->rows;
	}

	public function getTotalReviews($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r WHERE 1=1";

		if (!empty($data['filter_product'])) {
			$sql .= " AND (SELECT pd.name FROM " . DB_PREFIX . "product_description pd WHERE pd.product_id = r.product_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' LIMIT 1) LIKE '" . $this->db->escape($data['filter_product']) . "%'";
		}

		if (!empty($data['filter_author'])) {
			$sql .= " AND r.author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND r.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		// Debug: Log the count query
		error_log('=== ADMIN REVIEW COUNT QUERY DEBUG ===');
		error_log('Count SQL: ' . $sql);
		
		$query = $this->db->query($sql);
		
		$total = isset($query->row['total']) ? $query->row['total'] : 0;
		error_log('Total reviews found: ' . $total);
		error_log('======================================');

		return $total;
	}

	public function getTotalReviewsAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review WHERE status = '0'");

		return $query->row['total'];
	}
}