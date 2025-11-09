<?php
class ModelCatalogLandingPage extends Model {
    public function addLandingPage($data) {
        $this->event->trigger('pre.admin.landing_page.add', $data);

        $sql = "INSERT INTO " . DB_PREFIX . "landing_page SET  class = '" .  $this->db->escape($data['class'])  . "', question_text = '" .  $this->db->escape($data['question_text'])  . "',   video_text = '" .  $this->db->escape($data['video_text'])  . "', video_url = '" .  $this->db->escape($data['video_url'])  . "', status = '" . (int)$data['status'] . "'";


        $this->db->query($sql);

        $landing_page_id = $this->db->getLastId();

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "landing_page SET image = '" . $this->db->escape($data['image']) . "' WHERE landing_page_id = '" . (int)$landing_page_id . "'");
        }

        if (isset($data['featured_image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "landing_page SET featured_image = '" . $this->db->escape($data['featured_image']) . "' WHERE landing_page_id = '" . (int)$landing_page_id . "'");
        }

        foreach ($data['landing_page_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "landing_page_description SET landing_page_id = '" . (int)$landing_page_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', summary = '" . $this->db->escape($value['summary']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        if (isset($data['landing_page_store'])) {
            foreach ($data['landing_page_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "landing_page_to_store SET landing_page_id = '" . (int)$landing_page_id . "', store_id = '" . (int)$store_id . "'");
            }
        }


        if (isset($data['landing_page_module'])) {
            foreach ($data['landing_page_module'] as $landing_page_module) {
                $part = explode('.', $landing_page_module['code']);
                $this->db->query("INSERT INTO " . DB_PREFIX . "landing_page_module SET landing_page_id = '" . (int)$landing_page_id . "', module_id = '" . (int) $part[1] . "', code = '" . $this->db->escape($landing_page_module['code']) . "', position = '" . $this->db->escape($landing_page_module['position']) . "', sort_order = '" . (int)$landing_page_module['sort_order'] . "'");
            }
        }

        if (isset($data['landing_page_image'])) {
            foreach ($data['landing_page_image'] as $landing_page_image) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "landing_page_image SET landing_page_id = '" . (int)$landing_page_id . "', image = '" . $this->db->escape($landing_page_image['image']) . "', sort_order = '" . (int)$landing_page_image['sort_order'] . "'");
            }
        }

        if (isset($data['landing_page_product'])) {
            foreach ($data['landing_page_product'] as $landing_page_product_id) {
                $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_product WHERE landing_page_id = '" . (int)$landing_page_id . "'  AND landing_page_product_id = '" . (int)$landing_page_product_id . "'");
                $this->db->query("INSERT INTO " . DB_PREFIX . "landing_page_product SET landing_page_id = '" . (int)$landing_page_id . "', landing_page_product_id = '" . (int)$landing_page_product_id . "'");
                $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_product WHERE landing_page_id = '" . (int)$landing_page_product_id . "' AND landing_page_product_id = '" . (int)$landing_page_id . "'");
                $this->db->query("INSERT INTO " . DB_PREFIX . "landing_page_product SET landing_page_id = '" . (int)$landing_page_product_id . "', landing_page_product_id = '" . (int)$landing_page_id . "'");
            }
        }

        if (isset($data['landing_page_faq'])) {
            foreach ($data['landing_page_faq'] as $faq) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "landing_page_faq SET landing_page_id = '" . (int)$landing_page_id . "', sort_order = '" . (int)$faq['sort_order'] . "'");

                $faq_id = $this->db->getLastId();

                foreach ($faq['landing_page_faq_description'] as $language_id => $landing_page_faq_description) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "landing_page_faq_description SET faq_id = '" . (int)$faq_id . "', language_id = '" . (int)$language_id . "', landing_page_id = '" . (int)$landing_page_id . "',  question = '" . $this->db->escape($landing_page_faq_description['question']) . "',  answer = '" .  $this->db->escape($landing_page_faq_description['answer']) . "'");
                }
            }
        }


        if (isset($data['keyword'])) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'landing_page_id=" . (int)$landing_page_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }

        $this->cache->delete('landing_page');

        $this->event->trigger('post.admin.landing_page.add', $landing_page_id);

        return $landing_page_id;
    }

    public function editLandingPage($landing_page_id, $data) {
        $this->event->trigger('pre.admin.landing_page.edit', $data);

        $sql = "UPDATE " . DB_PREFIX . "landing_page SET class = '" .  $this->db->escape($data['class'])  . "', question_text = '" .  $this->db->escape($data['question_text'])  . "',   video_text = '" .  $this->db->escape($data['video_text'])  . "', video_url = '" .  $this->db->escape($data['video_url'])  . "', status = '" . (int)$data['status'] . "'";


        $sql .= "WHERE landing_page_id = '" . (int)$landing_page_id . "'";
        $this->db->query($sql);

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "landing_page SET image = '" . $this->db->escape($data['image']) . "' WHERE landing_page_id = '" . (int)$landing_page_id . "'");
        }

        if (isset($data['featured_image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "landing_page SET featured_image = '" . $this->db->escape($data['featured_image']) . "' WHERE landing_page_id = '" . (int)$landing_page_id . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_description WHERE landing_page_id = '" . (int)$landing_page_id . "'");

        foreach ($data['landing_page_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "landing_page_description SET landing_page_id = '" . (int)$landing_page_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', summary = '" . $this->db->escape($value['summary'])  . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_image WHERE landing_page_id = '" . (int)$landing_page_id . "'");

        if (isset($data['landing_page_image'])) {
            foreach ($data['landing_page_image'] as $landing_page_image) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "landing_page_image SET landing_page_id = '" . (int)$landing_page_id . "', image = '" . $this->db->escape($landing_page_image['image']) . "', sort_order = '" . (int)$landing_page_image['sort_order'] . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_product WHERE landing_page_id = '" . (int)$landing_page_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_product WHERE landing_page_product_id = '" . (int)$landing_page_id . "'");

        if (isset($data['landing_page_product'])) {
            foreach ($data['landing_page_product'] as $landing_page_product_id) {
                $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_product WHERE landing_page_id = '" . (int)$landing_page_id . "'  AND landing_page_product_id = '" . (int)$landing_page_product_id . "'");
                $this->db->query("INSERT INTO " . DB_PREFIX . "landing_page_product SET landing_page_id = '" . (int)$landing_page_id . "', landing_page_product_id = '" . (int)$landing_page_product_id . "'");
                $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_product WHERE landing_page_id = '" . (int)$landing_page_product_id . "' AND landing_page_product_id = '" . (int)$landing_page_id . "'");
                $this->db->query("INSERT INTO " . DB_PREFIX . "landing_page_product SET landing_page_id = '" . (int)$landing_page_product_id . "', landing_page_product_id = '" . (int)$landing_page_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_to_store WHERE landing_page_id = '" . (int)$landing_page_id . "'");

        if (isset($data['landing_page_store'])) {
            foreach ($data['landing_page_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "landing_page_to_store SET landing_page_id = '" . (int)$landing_page_id . "', store_id = '" . (int)$store_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_faq WHERE landing_page_id = '" . (int)$landing_page_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_faq_description WHERE landing_page_id = '" . (int)$landing_page_id . "'");

        if (isset($data['landing_page_faq'])) {
            foreach ($data['landing_page_faq'] as $faq) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "landing_page_faq SET landing_page_id = '" . (int)$landing_page_id . "', sort_order = '" . (int)$faq['sort_order'] . "'");

                $faq_id = $this->db->getLastId();

                foreach ($faq['landing_page_faq_description'] as $language_id => $landing_page_faq_description) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "landing_page_faq_description SET faq_id = '" . (int)$faq_id . "', language_id = '" . (int)$language_id . "', landing_page_id = '" . (int)$landing_page_id . "',  question = '" . $this->db->escape($landing_page_faq_description['question']) . "',  answer = '" .  $this->db->escape($landing_page_faq_description['answer']) . "'");
                }
            }
        }


        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'landing_page_id=" . (int)$landing_page_id . "'");

        if ($data['keyword']) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'landing_page_id=" . (int)$landing_page_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }


        $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_module WHERE landing_page_id = '" . (int)$landing_page_id . "'");

        if (isset($data['landing_page_module'])) {
            foreach ($data['landing_page_module'] as $landing_page_module) {
                $part = explode('.', $landing_page_module['code']);
                $this->db->query("INSERT INTO " . DB_PREFIX . "landing_page_module SET landing_page_id = '" . (int)$landing_page_id . "', module_id = '" . (int) $part[1] . "', code = '" . $this->db->escape($landing_page_module['code']) . "', position = '" . $this->db->escape($landing_page_module['position']) . "', sort_order = '" . (int)$landing_page_module['sort_order'] . "'");
            }
        }

        $this->cache->delete('landing_page');
        $this->cache->delete('inf.' . $landing_page_id);

        $this->event->trigger('post.admin.landing_page.edit', $landing_page_id);
    }

    public function deleteLandingPage($landing_page_id) {
        $this->event->trigger('pre.admin.landing_page.delete', $landing_page_id);

        $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_module WHERE landing_page_id = '" . (int)$landing_page_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page WHERE landing_page_id = '" . (int)$landing_page_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_description WHERE landing_page_id = '" . (int)$landing_page_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_image WHERE landing_page_id = '" . (int)$landing_page_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_product WHERE landing_page_id = '" . (int)$landing_page_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_product WHERE landing_page_product_id = '" . (int)$landing_page_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_to_store WHERE landing_page_id = '" . (int)$landing_page_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_faq WHERE landing_page_id = '" . (int)$landing_page_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_faq_description WHERE landing_page_id = '" . (int)$landing_page_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'landing_page_id=" . (int)$landing_page_id . "'");

        $this->cache->delete('landing_page');

        $this->event->trigger('post.admin.landing_page.delete', $landing_page_id);
    }

    public function getLandingPage($landing_page_id) {
        $query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'landing_page_id=" . (int)$landing_page_id . "') AS keyword FROM " . DB_PREFIX . "landing_page WHERE landing_page_id = '" . (int)$landing_page_id . "'");

        return $query->row;
    }

    public function getLandingPages($data = array()) {
            $sql = "SELECT * FROM " . DB_PREFIX . "landing_page i LEFT JOIN " . DB_PREFIX . "landing_page_description id ON (i.landing_page_id = id.landing_page_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";

            if (!empty($data['filter_title'])) {
                $sql .= " AND id.title LIKE '%" . $this->db->escape($data['filter_title']) . "%'";
            }

            if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
                $sql .= " AND i.status = '" . (int)$data['filter_status'] . "'";
            }

            $sort_data = array(
                'id.title',
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY id.title";
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

    public function getLandingPageDescriptions($landing_page_id) {
        $landing_page_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "landing_page_description WHERE landing_page_id = '" . (int)$landing_page_id . "'");

        foreach ($query->rows as $result) {
            $landing_page_description_data[$result['language_id']] = array(
                'title'            => $result['title'],
                'summary'          => $result['summary'],
                'description'      => $result['description'],
                'meta_title'       => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword'     => $result['meta_keyword']
            );
        }

        return $landing_page_description_data;
    }

    public function getLandingPageStores($landing_page_id) {
        $landing_page_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "landing_page_to_store WHERE landing_page_id = '" . (int)$landing_page_id . "'");

        foreach ($query->rows as $result) {
            $landing_page_store_data[] = $result['store_id'];
        }

        return $landing_page_store_data;
    }

    public function getLandingPageFaqs($landing_page_id) {
        $landing_page_faq_data = array();

        $landing_page_faq_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "landing_page_faq WHERE landing_page_id = '" . (int)$landing_page_id . "' ORDER BY sort_order ASC");

        foreach ($landing_page_faq_query->rows as $landing_page_faq) {
            $landing_page_faq_description_data = array();

            $landing_page_faq_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "landing_page_faq_description WHERE faq_id = '" . (int)$landing_page_faq['faq_id'] . "' AND landing_page_id = '" . (int)$landing_page_id . "'");

            foreach ($landing_page_faq_description_query->rows as $landing_page_faq_description) {
                $landing_page_faq_description_data[$landing_page_faq_description['language_id']] = array('question' => $landing_page_faq_description['question'],  'answer' => $landing_page_faq_description['answer'],);
            }

            $landing_page_faq_data[] = array(
                'landing_page_faq_description' => $landing_page_faq_description_data,
                'sort_order'               => $landing_page_faq['sort_order']
            );
        }

        return $landing_page_faq_data;
    }

    public function getLandingPageModules($landing_page_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "landing_page_module WHERE landing_page_id = '" . (int)$landing_page_id . "'");

        return $query->rows;
    }

    public function getLandingPageProductImages($landing_page_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "landing_page_image WHERE landing_page_id = '" . (int)$landing_page_id . "' ORDER BY sort_order ASC");

        return $query->rows;
    }

    public function getLandingPageProduct($landing_page_id) {
        $landing_page_product_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "landing_page_product WHERE landing_page_id = '" . (int)$landing_page_id . "'");

        foreach ($query->rows as $result) {
            $landing_page_product_data[] = $result['landing_page_product_id'];
        }

        return $landing_page_product_data;
    }

    public function getTotalLandingPages($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "landing_page i LEFT JOIN " . DB_PREFIX . "landing_page_description id ON (i.landing_page_id = id.landing_page_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";


        if (!empty($data['filter_title'])) {
            $sql .= " AND id.title LIKE '%" . $this->db->escape($data['filter_title']) . "%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND i.status = '" . (int)$data['filter_status'] . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

}