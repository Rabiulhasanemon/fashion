<?php
class ModelReportArticle extends Model {

    public function getArticleTotalByParent() {
        $sql = "SELECT cd.name as parent_name, COUNT(a.article_id) AS total FROM " . DB_PREFIX. "blog_article a LEFT JOIN " . DB_PREFIX. "blog_category_description cd ON a.parent_id = cd.category_id WHERE cd.language_id = " .  (int)$this->config->get('config_language_id')  . " GROUP BY a.parent_id";

        $query = $this->db->query($sql);
        return $query->rows;
    }
}