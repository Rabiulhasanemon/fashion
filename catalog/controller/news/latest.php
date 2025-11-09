<?php
class ControllerNewsLatest extends Controller {

	public function index() {

		$this->load->model('blog/article');
		$this->load->model('blog/category');
        $this->load->language('blog/latest');


		if (isset($this->request->get['page'])) {
			$page = (int) $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 16;
		}
		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('blog/latest')
		);

        $this->document->setTitle( $this->language->get('heading_title'));
        $this->document->setDescription( $this->language->get('meta_description'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['articles'] = array();

        $filter_data = array(
            'sort'               => "p.date_added",
            'order'              => "DESC",
            'start'              => ($page - 1) * $limit,
            'limit'              => $limit
        );

        $article_total = $this->model_blog_article->getTotalArticles($filter_data);

        $results = $this->model_blog_article->getArticles($filter_data);
        $this->load->model('tool/image');
        foreach ($results as $article_info) {
            if ($article_info['thumb']) {
                $image = $this->model_tool_image->resize($article_info['thumb'], $this->config->get("config_image_thumb_width"), $this->config->get("config_image_thumb_height"));
            } else if ($article_info['image']) {
                $image = $this->model_tool_image->resize($article_info['image'], $this->config->get("config_image_thumb_width"), $this->config->get("config_image_thumb_height"));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get("config_image_thumb_width"), $this->config->get("config_image_thumb_height"));
            }

            if ($article_info['image']) {
                $featured_image = $this->model_tool_image->resize($article_info['image'], $this->config->get("config_image_category_width"), $this->config->get("config_image_category_height"));
            } else {
                $featured_image = $this->model_tool_image->resize('placeholder.png', $this->config->get("config_image_category_width"), $this->config->get("config_image_category_height"));
            }

            $data['articles'][] = array(
                'article_id'  => $article_info['article_id'],
                'thumb'       => $image,
                'featured_image'       => $featured_image,
                'name'        => $article_info['name'],
                'intro_text' =>  $article_info['intro_text'],
                'date_published'  => $this->date->translate(strtotime($article_info['date_published']), $this->language->get('datetime_format_long')),
                'href'        => $this->url->link('blog/article', 'article_id=' . $article_info['article_id'])
            );
        }


        $data['limits'] = array();

        $limits = array_unique(array($this->config->get('news_global_article_limit'), 50, 75, 100));

        sort($limits);

        foreach($limits as $value) {
            $data['limits'][] = array(
                'text'  => $value,
                'value' => $value,
                'href'  => $this->url->link('blog/latest', 'limit=' . $value)
            );
        }

        $url = '';

        if (isset($this->request->get['limit'])) {
            $url .= 'limit=' . $this->request->get['limit'];
        }

        $pagination = new Pagination($this->registry);
        $pagination->total = $article_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('blog/latest', $url . 'page={page}');

        $data['pagination'] = $pagination->render();
        $data['button_read_more'] = $this->language->get('button_read_more');
        $data['text_empty'] = $this->language->get('text_empty');
        $data['results'] = sprintf($this->language->get('text_pagination'), ($article_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($article_total - $limit)) ? $article_total : ((($page - 1) * $limit) + $limit), $article_total, ceil($article_total / $limit));

        $data['limit'] = $limit;
        $data['page'] = $page;

        $data['after_header'] = $this->load->controller('common/after_header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['after_header'] = $this->load->controller('common/after_header');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/blog/latest.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/blog/latest.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/blog/latest.tpl', $data));
        }

	}
}