<?php
class ControllerArticleDashboardPublished extends Controller {
	public function index() {
        $this->load->language('article_dashboard/published');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Articles
        $this->load->model('blog/article');

        $today = $this->model_blog_article->getTotalArticles(array(
            'filter_status' => 1,
            'filter_date_added' => date('Y-m-d', strtotime('-1 day'))
        ));

        $yesterday = $this->model_blog_article->getTotalArticles(array(
            'filter_status' => 1,
            'filter_date_added' => date('Y-m-d', strtotime('-2 day'))
        ));

        $difference = $today - $yesterday;

        if ($difference && $today) {
            $data['percentage'] = round(($difference / $today) * 100);
        } else {
            $data['percentage'] = 0;
        }

        $article_total = $this->model_blog_article->getTotalArticles();

        if ($article_total > 1000000000000) {
            $data['total'] = round($article_total / 1000000000000, 1) . 'T';
        } elseif ($article_total > 1000000000) {
            $data['total'] = round($article_total / 1000000000, 1) . 'B';
        } elseif ($article_total > 1000000) {
            $data['total'] = round($article_total / 1000000, 1) . 'M';
        } elseif ($article_total > 1000) {
            $data['total'] = round($article_total / 1000, 1) . 'K';
        } else {
            $data['total'] = $article_total;
        }

        $data['article'] = $this->url->link('blog/article', 'token=' . $this->session->data['token'], 'SSL');

        return $this->load->view('article_dashboard/published.tpl', $data);
	}
}
