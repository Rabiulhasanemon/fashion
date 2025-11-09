<?php
class ModelBlogComment extends Model {

	public function addComment($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "blog_comment SET author = '" . $this->db->escape($data['author']) . "', article_id = '" . (int)$data['DB_PREFIX'] . "', ".
			"text = '" . $this->db->escape(strip_tags($data['text'])) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
		$comment_id = $this->db->getLastId();

		$this->cache->delete('article');

		return $comment_id;
	}

	public function editComment($comment_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "blog_comment SET author = '" . $this->db->escape($data['author']) . "', article_id = '" . (int)$data['article_id'] . "', ".
			"text = '" . $this->db->escape(strip_tags($data['text'])) . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE comment_id = '" . (int)$comment_id . "'");

		$this->cache->delete('article');
	}

	public function deleteComment($comment_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "blog_comment WHERE comment_id = '" . (int)$comment_id . "'");

		$this->cache->delete('article');
	}

	public function getComment($comment_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT pd.name FROM " . DB_PREFIX . "blog_article_description pd WHERE pd.article_id = r.article_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS article FROM " . DB_PREFIX . "blog_comment r WHERE r.comment_id = '" . (int)$comment_id . "'");

		return $query->row;
	}

	public function getComments($data = array()) {
		$sql = "SELECT r.comment_id, pd.name, r.author, r.status, r.date_added FROM " . DB_PREFIX . "blog_comment r LEFT JOIN " . DB_PREFIX . "blog_article_description pd ON (r.article_id = pd.article_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_article'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_article']) . "%'";
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
			'pd.name',
			'r.author',
			'r.status',
			'r.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY r.date_added";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
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

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalComments($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_comment r LEFT JOIN " . DB_PREFIX . "blog_article_description pd ON (r.article_id = pd.article_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_article'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_article']) . "%'";
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

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalCommentsAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_comment WHERE status = '0'");

		return $query->row['total'];
	}
}