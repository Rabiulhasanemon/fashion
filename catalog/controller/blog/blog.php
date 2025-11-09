<?php
class ControllerBlogBlog extends Controller {

	public function index() {

		$this->load->model('blog/article');
		$this->load->model('blog/category');
        $this->load->language('blog/blog');

        $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/blog.min.1.css');

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 6;
		}
		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		
		
        $url = '';
        if (isset($this->request->get['filter'])) {
            $url .= '&filter=' . $this->request->get['filter'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_blog'),
            'href' => $this->url->link('blog/blog')
        );

        if (isset($this->request->get['blog_path'])) {
            $path = '';
            $parts = explode('_', (string)$this->request->get['blog_path']);
            $category_id = (int)array_pop($parts);
            foreach ($parts as $path_id) {
                if (!$path) {
                    $path = (int)$path_id;
                } else {
                    $path .= '_' . (int)$path_id;
                }

                $category_info = $this->model_blog_category->getCategory($path_id);

                if ($category_info) {
                    $data['breadcrumbs'][] = array(
                        'text' => $category_info['name'],
                        'href' => $this->url->link('blog/blog', 'blog_path=' . $path . $url)
                    );
                }
            }
        } else {
            $category_id = 0;
        }

        $category_info = $this->model_blog_category->getCategory($category_id);

        if($category_info) {
            $this->document->setTitle($category_info['meta_title']);
            $this->document->setDescription($category_info['meta_description']);
            $this->document->setKeywords($category_info['meta_keyword']);
            $data['heading_title'] = $category_info['name'];
            $data['name'] = $category_info['name'];
            $data['breadcrumbs'][] = array(
                'text' => $category_info['name'],
                'href' => $this->url->link('blog/blog', 'blog_path=' . $this->request->get['blog_path'])
            );
        } else {
            $this->document->setTitle($this->language->get('meta_title'));
            $this->document->setDescription($this->config->get('meta_description'));
            $this->document->setKeywords($this->language->get('meta_keywords'));
            $data['heading_title'] = $this->language->get('text_heading');
            $data['name'] = $this->language->get('text_heading');
        }

        $this->document->addLink($this->url->link('blog/blog'),'');

        $data['articles'] = array();

        $filter_data = array(
            'filter_filter'      => $filter,
            'sort'               => $sort,
            'order'              => $order,
            'start'              => ($page - 1) * $limit,
            'limit'              => $limit
        );

        if($category_info) {
            $filter_data['filter_category_id'] = $category_id;
        }

        $article_total = $this->model_blog_article->getTotalArticles($filter_data);

        $results = $this->model_blog_article->getArticles($filter_data);
        $this->load->model('tool/image');
        foreach ($results as $result) {
            if ($result['thumb']) {
                $thumb = $this->model_tool_image->resize($result['thumb'], 330, 240);
            } else if ($result['image']) {
                $thumb = $this->model_tool_image->resize($result['image'], 330, 240);
            } else {
                $thumb = $this->model_tool_image->resize('placeholder.png', 330, 240);
            }
            $data['articles'][] = array(
                'article_id'  => $result['article_id'],
                'name'        => $result['name'],
                'video_url'        => $result['video_url'],
                'thumb' => $thumb,
                'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'intro_text' => html_entity_decode($result['intro_text'], ENT_QUOTES, 'UTF-8'),
                'href'        => $this->url->link('blog/article', 'article_id=' . $result['article_id'] . $url)
            );
        }

        $url = '';

        if (isset($this->request->get['filter'])) {
            $url .= '&filter=' . $this->request->get['filter'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        $data['sorts'] = array();

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_default'),
            'value' => 'p.sort_order-ASC',
            'href'  => $this->url->link('blog/blog', '&sort=p.sort_order&order=ASC' . $url)
        );

        $url = '';

        if (isset($this->request->get['filter'])) {
            $url .= '&filter=' . $this->request->get['filter'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $data['limits'] = array();

        $limits = array_unique(array($this->config->get('blog_global_article_limit'), 50, 75, 100));

        sort($limits);

        foreach($limits as $value) {
            $data['limits'][] = array(
                'text'  => $value,
                'value' => $value,
                'href'  => $this->url->link('blog/blog', $url . '&limit=' . $value)
            );
        }

        $url = '';

        if (isset($this->request->get['filter'])) {
            $url .= '&filter=' . $this->request->get['filter'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        $pagination = new Pagination();
        $pagination->total = $article_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('blog/blog', $url . '&page={page}');

        $data['pagination'] = $pagination->render();
        $data['button_read_more'] = $this->language->get('button_read_more');
        $data['text_empty'] = $this->language->get('text_empty');
        $data['results'] = sprintf($this->language->get('text_pagination'), ($article_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($article_total - $limit)) ? $article_total : ((($page - 1) * $limit) + $limit), $article_total, ceil($article_total / $limit));

        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['limit'] = $limit;

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/blog/blog.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/blog/blog.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/blog/blog.tpl', $data));
        }
	}

}