<?php
class ControllerModuleLatestBlog extends Controller {
    private function getArticleData($article_info, $setting) {
        if ($article_info['thumb']) {
            $image = $this->model_tool_image->resize($article_info['thumb'], $setting['width'], $setting['height']);
        } else if ($article_info['image']) {
            $image = $this->model_tool_image->resize($article_info['image'], $setting['width'], $setting['height']);
        } else {
            $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
        }

        if ($article_info['image']) {
            $featured_image = $this->model_tool_image->resize($article_info['image'], $setting['featured_width'], $setting['featured_height']);
        } else {
            $featured_image = $this->model_tool_image->resize('placeholder.png', $setting['featured_width'], $setting['featured_height']);
        }

        return array(
            'article_id'  => $article_info['article_id'],
            'thumb'       => $image,
            'featured_image'       => $featured_image,
            'name'        => $article_info['name'],
            'intro_text' =>  $article_info['intro_text'],
            'date_published'  => $this->date->translate(strtotime($article_info['date_published']), $this->language->get('datetime_format_long')),
            'href'        => $this->url->link('blog/article', 'article_id=' . $article_info['article_id'])
        );
    }

    public function index($setting) {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_tax'] = $this->language->get('text_tax');
        $data['name'] = $setting["name"];
        $data['class'] = $setting["class"];

        if($setting['category_id']) {
            $this->load->model('blog/category');
            $data["see_all"] = $this->url->link('blog/category', 'blog_category_id=' . $setting['category_id']);
        } else {
            $data["see_all"] =  $this->url->link('blog/latest');
        }

        $this->load->model('blog/article');

        $this->load->model('tool/image');

        $data['articles'] = array();

        $filter_data = array(
            'sort'  => 'p.date_added',
            'order' => 'DESC',
            'start' => 0,
            'filter_category_id' => isset($setting["category_id"]) ? (int) $setting["category_id"] : 0,
            'limit' => $setting['limit']
        );

        $results = $this->model_blog_article->getArticles($filter_data);

        if ($results) {
            foreach ($results as $article_info) {
                $data['articles'][] = $this->getArticleData($article_info, $setting);
            }
        }

        $data['populars'] = array();
        $filter_data = array(
            'limit' => $setting['limit'],
            'today' => true
        );

        $results = $this->model_blog_article->getPopularArticles($filter_data);
        if ($results) {
            foreach ($results as $article_info) {
                $data['populars'][] = $this->getArticleData($article_info, $setting);
            }
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/latest_' . $setting['view'] . '.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/latest_' . $setting['view'] . '.tpl', $data);
        } else {
            return $this->load->view('default/template/module/latest_' . $setting['view'] . '.tpl', $data);
        }
    }
}