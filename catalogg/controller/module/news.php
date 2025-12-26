<?php
class ControllerModuleNews extends Controller {
    public function index($setting) {
        if (isset($setting['news'])) {
            $data['name'] = $setting['name'];

            if ($setting['type'] === "headline") {
                $data['articles'] = [];
                $filter_data = array(
                    'filter_headline' => true,
                    'limit' => 10
                );
                $this->load->model('news/article');
                $results = $this->model_news_article->getArticles($filter_data);
                foreach ($results as $article_info) {
                    $data['articles'][] = array(
                        'name'        => $article_info['name'],
                        'href'        => $this->url->link('blog/article', 'article_id=' . $article_info['article_id'])
                    );
                }
            } else {
                $data['news'] = $setting['news'];
            }

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/news.tpl')) {
                return $this->load->view($this->config->get('config_template') . '/template/module/news.tpl', $data);
            } else {
                return $this->load->view('default/template/module/html.tpl', $data);
            }
        }
    }
}