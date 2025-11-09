<?php
class ModelBlogArticle extends Model {

    public function addHistory($data) {
        $sql = "INSERT INTO " . DB_PREFIX. "blog_article_history SET "
        . "article_id = '" . (int) $data['article_id'] . "', "
        . "action = '" . $this->db->escape($data['action']) . "', "
        . "user_id = '" . (int) $this->user->getId() . "', "
        . "date_added = NOW()";


        $this->db->query($sql);

		return  $this->db->getLastId();
	}

	public function addArticle($data) {
        $parent_id = isset($data['parent_id']) ? $data['parent_id'] : 0;
        $status = (int)$data['status'];
        $sql = "INSERT INTO " . DB_PREFIX. "blog_article SET sort_order = '0', "
        . "parent_id = '" . (int) $parent_id . "', "
        . "upazila_id = '" . (int) $data['upazila_id'] . "', "
        . "user_created_id = '" . (int) $this->user->getId() . "', "
        . "status = '" . $status . "', "
        . "top = 1, "
        . "featured = '" . (int) ($data['on_lead'] == "featured") . "', "
        . "show_in_headline = '" . (int)$data['show_in_headline'] . "', "
        . "video_url = '" . $this->db->escape($data['video_url']) . "', "
        . "video_icon = '" . (int) $data['video_icon'] . "', "
        . "date_added = NOW()";

        if($status) {
            $sql .= ", date_published = NOW()";
        }

        $this->db->query($sql);

		$article_id = $this->db->getLastId();
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX. "blog_article SET image = '" . $this->db->escape($data['image']) . "' WHERE article_id = '" . (int)$article_id . "'");
		}

        if (isset($data['thumb'])) {
            $this->db->query("UPDATE " . DB_PREFIX. "blog_article SET thumb = '" . $this->db->escape($data['thumb']) . "' WHERE article_id = '" . (int)$article_id . "'");
        }

        $this->db->query("UPDATE " . DB_PREFIX. "blog_article SET sort_order = '" . (int)$article_id  . "', featured_order = '" . (int)$article_id  . "' WHERE article_id = '" . (int)$article_id . "'");

		foreach ($data['article_description'] as $language_id => $value) {
			$this->db->query(
				"INSERT INTO " . DB_PREFIX. "blog_article_description SET article_id = '" . (int)$article_id . "', ".
				"language_id = '" . (int)$language_id . "', ".
                "name = '" . $this->db->escape($value['name']) . "', ".
                "headline_for_details = '" . $this->db->escape($value['headline_for_details']) . "', ".
				"shoulder = '" . $this->db->escape($value['shoulder']) . "', ".
				"hanger = '" . $this->db->escape($value['hanger']) . "', ".
				"reporter = '" . $this->db->escape($value['reporter']) . "', ".
				"description = '" . $this->db->escape($value['description']) . "', ".
				"intro_text = '" . $this->db->escape($value['intro_text']) . "', ".
				"meta_title = '" . $this->db->escape($value['meta_title']) . "', ".
				"tags = '" . $this->db->escape($value['tags']) . "', ".
				"meta_description = '" . $this->db->escape($value['meta_description']) . "', ".
				"meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'"
			);
		}

		if (isset($data['article_store'])) {
			foreach ($data['article_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX. "article_to_store SET article_id = '" . (int)$article_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
        // Set which layout to use with this article
        if (isset($data['article_layout'])) {
            foreach ($data['article_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "article_to_layout SET article_id = '" . (int)$article_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }

		$categories = isset($data['article_category']) ? $data['article_category'] : array();

		if($parent_id && array_search($parent_id, $categories) === false) {
		    $categories[] = $parent_id;
        }

        foreach ($categories as $category_id) {
            $this->db->query("INSERT INTO " . DB_PREFIX. "article_to_category SET article_id = '" . (int)$article_id . "', category_id = '" . (int)$category_id . "', top = 1, sort_order = '" . (int)$article_id  . "'");
        }

        $event_streams = isset($data['article_event_stream']) ? $data['article_event_stream'] : array();
        foreach ($event_streams as $event_stream_id) {
            $this->db->query("INSERT INTO " . DB_PREFIX. "article_to_event_stream SET article_id = '" . (int)$article_id . "', event_stream_id = '" . (int)$event_stream_id . "'");
        }

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'article_id=" . (int)$article_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

        $this->load->model('setting/setting');
		if($data['on_lead'] == 'lead') {
		    $this->model_setting_setting->editSetting("article", array("article_lead" => $article_id));
        }
		$this->cache->delete('article');

		$this->addHistory(array('article_id' => $article_id, 'action' => 'add'));
		return $article_id;
	}

	public function editArticle($article_id, $data) {
        $parent_id = isset($data['parent_id']) ? $data['parent_id'] : 0;
        $status = (int)$data['status'];
        $article_info = $this->getArticle($article_id);
		$this->db->query("UPDATE " . DB_PREFIX. "blog_article SET parent_id = '" . (int)$parent_id . "', user_modified_id = '" . (int)$this->user->getId() . "', video_url = '" . $this->db->escape($data['video_url'])
            . "', status = '" . (int)$data['status'] . "', "
            . "upazila_id = '" . (int) $data['upazila_id'] . "', "
            . "video_icon = '" . (int) $data['video_icon'] . "', "
            . "featured = '" . (int) ($data['on_lead'] == "featured") . "', "
            . "show_in_headline = '" . (int)$data['show_in_headline'] . "', "
            . "date_modified = NOW() WHERE article_id = '" . (int)$article_id . "'"
        );


		if ($status && !$article_info['date_published']) {
			$this->db->query("UPDATE " . DB_PREFIX. "blog_article SET date_published = NOW() WHERE article_id = '" . (int)$article_id . "'");
		}

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX. "blog_article SET image = '" . $this->db->escape($data['image']) . "' WHERE article_id = '" . (int)$article_id . "'");
		}

        if (isset($data['thumb'])) {
            $this->db->query("UPDATE " . DB_PREFIX. "blog_article SET thumb = '" . $this->db->escape($data['thumb']) . "' WHERE article_id = '" . (int)$article_id . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX. "blog_article_description WHERE article_id = '" . (int)$article_id . "'");
		foreach ($data['article_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX. "blog_article_description SET article_id = '" . (int)$article_id . "', ".
				"language_id = '" . (int)$language_id . "', "
                . "name = '" . $this->db->escape($value['name']) . "', "
                . "headline_for_details = '" . $this->db->escape($value['headline_for_details']) . "', "
                . "shoulder = '" . $this->db->escape($value['shoulder']) . "', "
                . "hanger = '" . $this->db->escape($value['hanger']) . "', "
                . "reporter = '" . $this->db->escape($value['reporter']) . "', "
                . "description = '" . $this->db->escape($value['description']) . "', intro_text = '" . $this->db->escape($value['intro_text']) . "', "
                . "meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', "
                . "tags = '" . $this->db->escape($value['tags']) . "', "
                . "meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'"
			);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX. "article_to_store WHERE article_id = '" . (int)$article_id . "'");
		if (isset($data['article_store'])) {
			foreach ($data['article_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX. "article_to_store SET article_id = '" . (int)$article_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
        $this->db->query("DELETE FROM " . DB_PREFIX . "article_to_layout WHERE article_id = '" . (int)$article_id . "'");

        if (isset($data['article_layout'])) {
            foreach ($data['article_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "article_to_layout SET article_id = '" . (int)$article_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }

        $article_category_data = array();
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX. "article_to_category WHERE article_id = '" . (int)$article_id . "'");
        foreach ($query->rows as $result) {
            $article_category_data[$result['category_id']] = array(
                'top' => $result['top'],
                'sort_order' => $result['sort_order'],
            );
        }

		$this->db->query("DELETE FROM " . DB_PREFIX. "article_to_category WHERE article_id = '" . (int)$article_id . "'");
        $categories = isset($data['article_category']) ? $data['article_category'] : array();
        if($parent_id && array_search($parent_id, $categories) === false) {
            $categories[] = $parent_id;
        }

        foreach ($categories as $category_id) {
            $top = isset($article_category_data[$article_id]) ? $article_category_data[$article_id]['top'] : 1;
            $sort_order = isset($article_category_data[$article_id]) ? $article_category_data[$article_id]['sort_order'] : $article_id;
            $this->db->query("INSERT INTO " . DB_PREFIX. "article_to_category SET article_id = '" . (int)$article_id . "', category_id = '" . (int)$category_id . "', top = '" . (int) $top  . "', sort_order = '" . (int) $sort_order . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX. "article_to_event_stream WHERE article_id = '" . (int)$article_id . "'");
        $event_streams = isset($data['article_event_stream']) ? $data['article_event_stream'] : array();
        foreach ($event_streams as $event_stream_id) {
            $this->db->query("INSERT INTO " . DB_PREFIX. "article_to_event_stream SET article_id = '" . (int)$article_id . "', event_stream_id = '" . (int)$event_stream_id . "'");
        }

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'article_id=" . (int)$article_id . "'");
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'article_id=" . (int)$article_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

        if($data['on_lead'] == 'lead') {
            $this->model_setting_setting->editSetting("article", array("article_lead" => $article_id));
        }

        $this->addHistory(array('article_id' => $article_id, 'action' => 'update'));

		$this->cache->delete('article');
	}

//	public function editTopArticles($parent_id, $article_ids) {
//        $this->db->query("UPDATE " . DB_PREFIX. "blog_article SET sort_order =  article_id, top = 0 WHERE top = 1 and parent_id = '" . (int)$parent_id . "'");
//        $count = count($article_ids);
//        foreach ($article_ids as $i => $article_id) {
//            $this->db->query("UPDATE " . DB_PREFIX. "blog_article SET sort_order =  '" . (int) ($count - $i) . "', top = 1 WHERE article_id = '" . (int)$article_id . "'");
//        }
//    }

    public function editTopArticles($category_id, $article_ids) {
        $this->db->query("UPDATE " . DB_PREFIX. "article_to_category SET sort_order =  article_id, top = 0 WHERE top = 1 and category_id = '" . (int)$category_id . "'");

        $count = count($article_ids);
        foreach ($article_ids as $i => $article_id) {
            $this->db->query("UPDATE " . DB_PREFIX. "article_to_category SET sort_order =  '" . (int) ($count - $i) . "', top = 1 WHERE article_id = '" . (int)$article_id . "' AND category_id='" . (int) $category_id . "'");
        }
    }


    public function editFeaturedArticles($article_ids) {
        $this->db->query("UPDATE " . DB_PREFIX. "blog_article SET featured_order =  0, featured = 0 WHERE featured = 1");
        $count = count($article_ids);
        foreach ($article_ids as $i => $article_id) {
            $this->db->query("UPDATE " . DB_PREFIX. "blog_article SET featured_order =  '" . (int) ($count - $i) . "', featured = 1 WHERE article_id = '" . (int)$article_id . "'");
        }
    }

	public function copyArticle($article_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX. "blog_article a LEFT JOIN " . DB_PREFIX. "blog_article_description ad ON (a.article_id = ad.article_id) WHERE a.article_id = '" . (int)$article_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		if ($query->num_rows) {
			$data = $query->row;
			$data['keyword'] = '';
			$data['status'] = '0';
			$data['article_category'] = $this->getArticleCategories($article_id);
			$data['article_store'] = $this->getArticleStores($article_id);
			$data['article_description'] = $this->getArticleDescriptions($article_id);
			$this->addArticle($data);
		}
	}

	public function deleteArticle($article_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX. "blog_article WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX. "blog_article_description WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX. "article_to_event_stream WHERE article_id = '" . (int)$article_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "article_to_layout WHERE article_id = '" . (int)$article_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX. "article_to_category WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX. "blog_comment WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX. "url_alias WHERE query = 'article_id=" . (int)$article_id . "'");
		$this->cache->delete('article');
	}

	public function getArticle($article_id) {
		$query = $this->db->query("SELECT DISTINCT * , (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'article_id=" . (int)$article_id . "') AS keyword FROM " . DB_PREFIX. "blog_article a LEFT JOIN " .
			DB_PREFIX. "blog_article_description ad ON (a.article_id = ad.article_id) ".
			"WHERE a.article_id = '" . (int)$article_id . "' ".
			"AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

    public function getArticleDescriptions($article_id) {
        $article_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX. "blog_article_description WHERE article_id = '" . (int)$article_id . "'");

        foreach ($query->rows as $result) {
            $article_description_data[$result['language_id']] = array(
                'name'             => $result['name'],
                'headline_for_details'=> $result['headline_for_details'],
                'shoulder'         => $result['shoulder'],
                'hanger'           => $result['hanger'],
                'reporter'         => $result['reporter'],
                'intro_text'       => $result['intro_text'],
                'description'      => $result['description'],
                'meta_title'       => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword'     => $result['meta_keyword'],
                'tags'             => $result['tags'],
            );
        }

        return $article_description_data;
    }

    public function getArticles($data = array()) {
		$sql = "SELECT *, (SELECT CONCAT(u.firstname, ' ', u.lastname) FROM " . DB_PREFIX . "user u WHERE u.user_id = a.user_created_id) as user_created, (SELECT CONCAT(u.firstname, ' ', u.lastname) FROM " . DB_PREFIX . "user u WHERE u.user_id = a.user_modified_id) as user_modified FROM " . DB_PREFIX . "article_to_category a2c "
            . "LEFT JOIN ". DB_PREFIX. "blog_article a  ON (a2c.article_id = a.article_id) LEFT JOIN " . DB_PREFIX. "blog_article_description ad ON (a.article_id = ad.article_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_parent_id'])) {
			$sql .= " AND a.parent_id = '" . (int)$data['filter_parent_id']  . "%'";
		}

        if (!empty($data['filter_category_id'])) {
            $sql .= " AND a2c.category_id = '" . (int)$data['filter_category_id'] . "'";
        }

        if (!empty($data['filter_article_id'])) {
            $sql .= " AND a.article_id = '" . (int)$data['filter_article_id'] . "'";
        }

        if (!empty($data['filter_user_id'])) {
            $sql .= " AND a.user_created_id = '" . (int)$data['filter_user_id'] . "'";
        }

        if (!empty($data['filter_date_from'])) {
            $sql .= " AND DATE(a.date_added) >= DATE('" . $this->db->escape($data['filter_date_from']) . "')";
        }

        if (!empty($data['filter_date_to'])) {
            $sql .= " AND DATE(a.date_added) <= DATE('" . $this->db->escape($data['filter_date_to']) . "')";
        }

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND a.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_featured']) && !is_null($data['filter_featured'])) {
			$sql .= " AND a.featured = '" . (int)$data['filter_featured'] . "'";
		}

		if (isset($data['filter_featured']) && !is_null($data['filter_featured'])) {
			$sql .= " AND a.featured = '" . (int)$data['filter_featured'] . "'";
		}
		
		$sql .= " GROUP BY a.article_id";


        $sort_data = array(
            'ad.name',
            'a.status',
            'a.top DESC, a.sort_order',
            'a2c.top DESC, a2c.sort_order',
            'a.sort_order',
            'a2c.sort_order',
            'a.featured_order',
            'a.date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY date_added";
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

	public function getTotalArticles($data = array()) {
		$sql = "SELECT COUNT(DISTINCT a.article_id) AS total FROM " . DB_PREFIX . "article_to_category a2c LEFT JOIN " . DB_PREFIX. "blog_article a  ON (a2c.article_id = a.article_id) LEFT JOIN " . DB_PREFIX. "blog_article_description ad ON (a.article_id = ad.article_id)";

		$sql .= " WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

        if (!empty($data['filter_category_id'])) {
            $sql .= " AND a2c.category_id = '" . (int)$data['filter_category_id'] . "'";
        }

        if (!empty($data['filter_parent_id'])) {
            $sql .= " AND a.parent_id = '" . (int)$data['filter_parent_id']  . "%'";
        }

        if (!empty($data['filter_article_id'])) {
            $sql .= " AND a.article_id = '" . (int)$data['filter_article_id'] . "'";
        }

        if (!empty($data['filter_user_id'])) {
            $sql .= " AND a.user_created_id = '" . (int)$data['filter_user_id'] . "'";
        }

        if (!empty($data['filter_date_from'])) {
            $sql .= " AND DATE(a.date_added) >= DATE('" . $this->db->escape($data['filter_date_from']) . "')";
        }

        if (!empty($data['filter_date_to'])) {
            $sql .= " AND DATE(a.date_added) <= DATE('" . $this->db->escape($data['filter_date_to']) . "')";
        }

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND a.status = '" . (int)$data['filter_status'] . "'";
		}

        if (isset($data['filter_featured']) && !is_null($data['filter_featured'])) {
            $sql .= " AND a.featured = '" . (int)$data['filter_featured'] . "'";
        }

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getArticleStores($article_id) {
		$article_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX. "article_to_store WHERE article_id = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_store_data[] = $result['store_id'];
		}

		return $article_store_data;
	}

	public function getArticleCategories($article_id) {
		$article_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX. "article_to_category WHERE article_id = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_category_data[] = $result['category_id'];
		}

		return $article_category_data;
	}

	public function getArticleEventStreams($article_id) {
		$article_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX. "article_to_event_stream WHERE article_id = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_category_data[] = $result['event_stream_id'];
		}

		return $article_category_data;
	}

    public function getArticleHistories($data = array()) {
        $sql = "SELECT ah.*, ad.name, (SELECT CONCAT(u.firstname, ' ', u.lastname) FROM " . DB_PREFIX . "user u WHERE u.user_id = ah.user_id) as user "
             . "FROM " . DB_PREFIX . "blog_article_history ah "
             . "LEFT JOIN ". DB_PREFIX. "blog_article a  ON (ah.article_id = a.article_id) "
             . "LEFT JOIN " . DB_PREFIX. "blog_article_description ad ON (a.article_id = ad.article_id) "
             . "WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($data['filter_article_id'])) {
            $sql .= " AND a.article_id = '" . (int)$data['filter_article_id'] . "'";
        }

        $sort_data = array(
            'a.date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY date_added";
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

    public function getTotalArticleHistories($data = array()) {
        $sql = "SELECT COUNT(DISTINCT a.article_id) AS total FROM " . DB_PREFIX . "blog_article_history ah LEFT JOIN " . DB_PREFIX. "blog_article a  ON (ah.article_id = a.article_id) LEFT JOIN " . DB_PREFIX. "blog_article_description ad ON (a.article_id = ad.article_id)";

        $sql .= " WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($data['filter_article_id'])) {
            $sql .= " AND a.article_id = '" . (int)$data['filter_article_id'] . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function geArticleLayouts($article_id) {
        $article_layout_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_to_layout WHERE article_id = '" . (int)$article_id . "'");

        foreach ($query->rows as $result) {
            $article_layout_data[$result['store_id']] = $result['layout_id'];
        }

        return $article_layout_data;
    }

    public function getTotalArticlesByLayoutId($layout_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "article_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

        return $query->row['total'];
    }
}