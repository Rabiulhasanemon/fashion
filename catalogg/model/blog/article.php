<?php

class ModelBlogArticle extends Model
{
    public function updateViewed($article_id) {
        $this->db->query("UPDATE " . DB_PREFIX . "blog_article SET viewed = (viewed + 1) WHERE article_id = '" . (int)$article_id . "'");
    }
    
    public function getArticle($article_id)
    {
        $query = $this->db->query("SELECT DISTINCT a.article_id, a.parent_id, a.sort_order, a.status, a.thumb, a.video_url, a.video_icon, a.image, a.date_published, a.date_added, a.date_modified, ad.language_id, ad.name, ad.headline_for_details, ad.shoulder, ad.hanger, ad.reporter, ad.description, ad.intro_text, ad.meta_title, ad.meta_description, ad.meta_keyword , ad.tags FROM " . DB_PREFIX . "blog_article a LEFT JOIN " . DB_PREFIX . "blog_article_description ad ON (a.article_id = ad.article_id) WHERE a.article_id = '" . (int)$article_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a.status = '1' ");

        if ($query->num_rows) {
            return array(
                'article_id' => $query->row['article_id'],
                'parent_id' => $query->row['parent_id'],
                'name' => $query->row['name'],
                'headline_for_details' => trim($query->row['headline_for_details']),
                'shoulder' => $query->row['shoulder'],
                'hanger' => $query->row['hanger'],
                'reporter' => $query->row['reporter'],
                'thumb' => $query->row['thumb'],
                'image' => $query->row['image'],
                'description' => $query->row['description'],
                'meta_title' => $query->row['meta_title'],
                'meta_description' => $query->row['meta_description'],
                'meta_keyword' => $query->row['meta_keyword'],
                'tags' => trim($query->row['tags']),
                'intro_text' => $query->row['intro_text'],
                'sort_order' => $query->row['sort_order'],
                'video_url' => $query->row['video_url'],
                'video_icon' => $query->row['video_icon'],
                'status' => $query->row['status'],
                'date_added' => $query->row['date_added'],
                'date_published' => $query->row['date_published'],
                'date_modified' => $query->row['date_modified']
            );
        } else {
            return false;
        }
    }

    public function getArticles($data = array()) {
        $sql = "SELECT a.article_id ";
        if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $sql .= " FROM " . DB_PREFIX . "blog_category_path cp LEFT JOIN " . DB_PREFIX . "article_to_category a2c ON (cp.category_id = a2c.category_id) ";
            } else {
                $sql .= " FROM " . DB_PREFIX . "article_to_category a2c ";
            }

            $sql .= " LEFT JOIN " . DB_PREFIX  . "blog_article a  ON (a2c.article_id = a.article_id)";
        } elseif (!empty($data['filter_event_stream_id'])) {
            $sql .= " FROM " . DB_PREFIX . "article_to_event_stream a2es LEFT JOIN " . DB_PREFIX  . "blog_article a  ON (a2es.article_id = a.article_id)";
        } else {
            $sql .= " FROM " . DB_PREFIX . "blog_article a ";
        }

        $sql .= "LEFT JOIN " . DB_PREFIX . "blog_article_description ad ON (a.article_id = ad.article_id) " .
                "LEFT JOIN " . DB_PREFIX . "article_to_store a2s ON (a.article_id = a2s.article_id) " .
                "WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'" .
                " AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'" .
                "AND a.status = '1' ";

        if(isset($data['filter_excluded_article'])) {
            $sql .= "AND a.article_id <> '" . (int) $data['filter_excluded_article']  . "' " ;
        }

        if(isset($data['filter_headline'])) {
            $sql .= "AND a.show_in_headline = '1' ";
        }

        if(isset($data['filter_featured'])) {
            $sql .= "AND a.featured = '1' ";
        }
        if(isset($data['popular'])) {
            $sql .= " ORDER BY a.viewed ";
        }

        if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
            } else {
                $sql .= " AND a2c.category_id = '" . (int)$data['filter_category_id'] . "'";
            }
        } elseif (!empty($data['filter_event_stream_id'])) {
            $sql .= " AND a2es.event_stream_id = '" . (int)$data['filter_event_stream_id'] . "'";
        }

        if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
            $sql .= " AND (";

            if (!empty($data['filter_name'])) {
                $implode = array();

                $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

                foreach ($words as $word) {
                    $implode[] = "ad.name LIKE '%" . $this->db->escape($word) . "%'";
                }

                if ($implode) {
                    $sql .= " " . implode(" AND ", $implode) . "";
                }

                if (!empty($data['filter_description'])) {
                    $sql .= " OR ad.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
                }
            }

            if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
                $sql .= " OR ";
            }

            if (!empty($data['filter_tag'])) {
                $sql .= "ad.tags LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
            }

            $sql .= ")";
        }

        $sql .= " GROUP BY a.article_id";

        $sort_data = array(
            'ad.name',
            'a.status',
            'a.top DESC, a.sort_order',
            'a2c.top DESC, a2c.sort_order',
            'a.sort_order',
            'a.featured_order',
            'a.date_published',
            'a.date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY a.sort_order";
        }

        if (isset($data['order']) && ($data['order'] == 'ASC')) {
            $sql .= " ASC";
        } else {
            $sql .= " DESC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if (!isset($data['start']) || $data['start'] < 0) {
                $data['start'] = 0;
            }

            if (!isset($data['limit']) || $data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $article_data = array();

        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $article_data[$result['article_id']] = $this->getArticle($result['article_id']);
        }

        return $article_data;
    }

    public function getLatestArticles($limit) {
        $article_data = $this->cache->get('article.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$limit);

        if (!$article_data) {
            $query = $this->db->query("SELECT article_id FROM " . DB_PREFIX . "blog_article WHERE status = '1' ORDER BY date_modified DESC LIMIT " . (int)$limit);

            foreach ($query->rows as $result) {
                $article_data[$result['article_id']] = $this->getArticle($result['article_id']);
            }

            $this->cache->set('article.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$limit, $article_data);
        }

        return $article_data;
    }

    public function getTotalArticles($data = array()) {
        $sql = "SELECT COUNT(DISTINCT a.article_id) AS total";

        if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $sql .= " FROM " . DB_PREFIX . "blog_category_path cp LEFT JOIN " . DB_PREFIX . "article_to_category a2c ON (cp.category_id = a2c.category_id) ";
            } else {
                $sql .= " FROM " . DB_PREFIX . "article_to_category a2c ";
            }

            $sql .= " LEFT JOIN " . DB_PREFIX  . "blog_article a  ON (a2c.article_id = a.article_id)";
        } elseif (!empty($data['filter_event_stream_id'])) {
            $sql .= " FROM " . DB_PREFIX . "article_to_event_stream a2es LEFT JOIN " . DB_PREFIX  . "blog_article a  ON (a2es.article_id = a.article_id)";
        } else {
            $sql .= " FROM " . DB_PREFIX . "blog_article a ";
        }


        $sql .= " LEFT JOIN " . DB_PREFIX . "blog_article_description ad ON (a.article_id = ad.article_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a.status = '1' ";

        if(isset($data['filter_excluded_article'])) {
            $sql .= "AND a.article_id <> '" . (int) $data['filter_excluded_article']  . "' " ;
        }

        if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
            } else {
                $sql .= " AND a2c.category_id = '" . (int)$data['filter_category_id'] . "'";
            }
        } elseif (!empty($data['filter_event_stream_id'])) {
            $sql .= " AND a2es.event_stream_id = '" . (int)$data['filter_event_stream_id'] . "'";
        }


        if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
            $sql .= " AND (";

            if (!empty($data['filter_name'])) {
                $implode = array();

                $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

                foreach ($words as $word) {
                    $implode[] = "ad.name LIKE '%" . $this->db->escape($word) . "%'";
                }

                if ($implode) {
                    $sql .= " " . implode(" AND ", $implode) . "";
                }

                if (!empty($data['filter_description'])) {
                    $sql .= " OR ad.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
                }
            }

            if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
                $sql .= " OR ";
            }

            if (!empty($data['filter_tag'])) {
                $sql .= "ad.tags LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
            }

            $sql .= ")";
        }
        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getPopularArticles($data) {
        $article_data = array();
        $sql = "SELECT a.article_id FROM " . DB_PREFIX . "blog_article a  WHERE a.status = '1'";

        if(isset($data['limit'])) {
            $limit = (int) $data['limit'];
        } else {
            $limit = 5;
        }

        if (!empty($data['today'])) {
            $sql .= " AND date_added  >= (NOW() - INTERVAL 24 HOUR) ";
        }

        $sql .= " ORDER BY a.viewed DESC LIMIT " . $limit;


        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $article_data[$result['article_id']] = $this->getArticle($result['article_id']);
        }

        return $article_data;
    }

    public function getArticleEventStreams($article_id) {
        $event_streams = array();

        $sql = "SELECT * FROM " . DB_PREFIX. "article_to_event_stream a2es LEFT JOIN " . DB_PREFIX . "event_stream_description esd on a2es.event_stream_id = esd.event_stream_id WHERE "
            . "article_id = '" . (int)$article_id . "' "
            . " AND esd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $event_streams[] = array(
                'event_stream_id'  => $result['event_stream_id'],
                'name'             => $result['name'],
                'meta_title'       => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword'     => $result['meta_keyword'],
                'description'      => $result['description']
            );
        }

        return $event_streams;
    }

    public function getArticleLayoutId($article_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_to_layout WHERE article_id = '" . (int)$article_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

        if ($query->num_rows) {
            return $query->row['layout_id'];
        } else {
            return 0;
        }
    }
}
