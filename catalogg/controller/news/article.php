<?php
class ControllerNewsArticle extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('blog/article');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['article_id'])) {
			$article_id = (int)$this->request->get['article_id'];
		} else {
			$article_id = 0;
		}

		$this->load->model('blog/article');

		$article_info = $this->model_blog_article->getArticle($article_id);

		if ($article_info) {
            $this->load->model('blog/category');
            if($article_info['parent_id']) {
                $path = $this->model_blog_category->getCategoryPath($article_info['parent_id']);
            } else {
                $path = '';
            }

            if ($path) {
                $parts = explode('_', $path);
                foreach ($parts as $path_id) {
                    $category_info = $this->model_blog_category->getCategory($path_id);
                    if ($category_info) {
                        $data['breadcrumbs'][] = array(
                            'text' => $category_info['name'],
                            'href' => $this->url->link('blog/category', 'blog_category_id=' . $path_id)
                        );
                    }
                }
            }

			$article_url = $this->url->link('blog/article',  '&article_id=' . $this->request->get['article_id']);

			$data['breadcrumbs'][] = array(
				'text' => $article_info['name'],
				'href' => $article_url
			);

			$this->document->setTitle($article_info['meta_title'] ?: $article_info['name']);
			$this->document->setDescription($article_info['meta_description']);
			$this->document->setKeywords($article_info['meta_keyword']);

//            $this->model_blog_article->updateViewed($this->request->get['article_id']);

			$data["text_comments"] = $this->language->get('text_comments');
			$data["text_no_comment"] = $this->language->get('text_no_comment');
			$data["text_write"] = $this->language->get('text_write');
			$data["text_submit"] = $this->language->get('text_submit');

			$data['entry_name']      = $this->language->get('entry_name');;
			$data['entry_email']      = $this->language->get('entry_email');;
			$data['entry_comment']   = $this->language->get('entry_comment');;

			$data['heading_title'] = $article_info['headline_for_details'] ? $article_info['headline_for_details'] : $article_info['name'];
			$data['shoulder'] = $article_info['shoulder'];
			$data['hanger'] = $article_info['hanger'];
			$data['reporter'] = $article_info['reporter'];

			$data['tags'] = [];
            $tags = $article_info['tags'] ? explode(",",  $article_info['tags']) : [];
            foreach ($tags as $tag) {
                $data['tags'][] = array(
                    'name' => $tag,
                    'href' => $this->url->link('news/search', 'tag=' . $tag)
                );
            }

			$data['article_url'] = $article_url;
            $data['date_published'] = $this->date->translate(strtotime($article_info['date_published']), $this->language->get("datetime_format_long"));
            $data['date_modified'] = $article_info['date_modified'] ? $this->date->translate(strtotime($article_info['date_modified']), $this->language->get("datetime_format_long")) : null;
			$data['article_id'] = (int)$this->request->get['article_id'];

			$video_url = $article_info['video_url'];
			if($video_url) {
			    $url_queries = array();
                parse_str(parse_url($video_url, PHP_URL_QUERY), $url_queries);
                $data['video_id'] = isset($url_queries['v']) ? $url_queries['v'] : null;
            } else {
                $data['video_id'] = null;
            }

            $data['intro_text'] = $article_info['intro_text'];
			$data['description'] = html_entity_decode($article_info['description'], ENT_QUOTES, 'UTF-8');

            $this->load->model('tool/image');

            if ($article_info['image']) {
                $data['image'] = $this->model_tool_image->resize($article_info['image'], $this->config->get("config_image_article_width"), $this->config->get("config_image_article_height"));
            } else {
                $data["image"] = null;
            }

			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('comment', (array)$this->config->get('config_captcha_page'))) {
				$data['captcha'] = $this->load->controller('captcha/' . $this->config->get('config_captcha'));
			} else {
				$data['captcha'] = '';
			}


            $this->document->addMeta('fb:app_id', FB_ID);
            $this->document->addMeta('og:title', $article_info['name']);
            $this->document->addMeta('og:type', "article");
            $this->document->addMeta('og:url', $article_url);
            $this->document->addMeta('og:description', $article_info['meta_description']);
            $this->document->addMeta('og:site_name', "Ekattor TV");
            $this->document->addMeta('og:image', $data['image']);

			// Comment
			$this->load->language('blog/article');

			$this->load->model('news/comment');

			$data['text_no_comments'] = $this->language->get('text_no_comments');

			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}

			$data['comments'] = array();

			$comment_total = $this->model_news_comment->getTotalCommentsByArticleId($this->request->get['article_id']);

			$results = $this->model_news_comment->getCommentsByArticleId($this->request->get['article_id'], ($page - 1) * 5, 5);

			foreach ($results as $result) {
				$data['comments'][] = array(
					'author'     => $result['author'],
					'text'       => nl2br($result['text']),
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
				);
			}

			$pagination = new Pagination($this->registry);
			$pagination->total = $comment_total;
			$pagination->page = $page;
			$pagination->limit = 5;
			$pagination->url = $this->url->link('blog/article', 'article_id=' . $this->request->get['article_id'] . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['text_pagination'] = sprintf($this->language->get('text_pagination'),
				($comment_total) ? (($page - 1) * 5) + 1 : 0,
				((($page - 1) * 5) > ($comment_total - 5)) ? $comment_total : ((($page - 1) * 5) + 5),
				$comment_total, ceil($comment_total / 5)
			);

			$data['after_header'] = $this->load->controller('common/after_header');
			$data['column_left'] = $this->load->controller('common/column_left');
            $data['column_middle'] = $this->load->controller('common/column_middle');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
            $data['content_middle'] = $this->load->controller('common/content_middle');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/blog/article.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/blog/article.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/blog/article.tpl', $data));
            }

		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}


			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('blog/article', $url . '&article_id=' . $article_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['after_header'] = $this->load->controller('common/after_header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
            }
			
		}
	}

    public function event_stream() {
        $this->load->language('blog/article');

        $this->load->model('blog/article');

        $data['text_no_event_streams'] = $this->language->get('text_no_event_streams');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $article_id = $this->request->get['article_id'];
        $data['event_streams'] = array();
        $results = $this->model_blog_article->getArticleEventStreams($this->request->get['article_id']);

        foreach ($results as $result) {
            $filter_data = array(
                'filter_event_stream_id' => $result['event_stream_id'],
                'filter_excluded_article' => $article_id,
                'sort' => 'a.date_published',
                'limit' => 10,
                'start' =>  ($page - 1) * 10
            );
            $total_articles = $this->model_blog_article->getTotalArticles($filter_data);
            $articles = $this->model_blog_article->getArticles($filter_data);
            if(!$articles) continue;
            $event_stream = array(
                'event_stream_id'     => $result['event_stream_id'],
                'name'     => $result['name'],
                'articles' => array()
            );
            foreach ($articles as $article) {
                $event_stream['articles'][] = array(
                    'name' => $article['name'],
                    'href' =>  $this->url->link('blog/article',  '&article_id=' . $article['article_id']),
                    'date_published' =>  $this->date->translate(strtotime($article['date_published']), $this->language->get("datetime_format_long"))
                );
            }

            $pagination = new Pagination($this->registry);
            $pagination->total = $total_articles;
            $pagination->page = $page;
            $pagination->limit = 10;
            $pagination->url = $this->url->link('blog/article/event_stream', 'article_id=' . $this->request->get['article_id'] . '&page={page}');
            $event_stream['pagination'] = $pagination->render();
            $data['event_streams'][] = $event_stream;
        }


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/news/event_stream.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/news/event_stream.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/news/event_stream.tpl', $data));
        }
    }

	public function write() {
		$this->load->language('blog/article');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('comment', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('captcha/' . $this->config->get('config_captcha') . '/validate');

				if ($captcha) {
					$json['error'] = $captcha;
				}
			}

			if (!isset($json['error'])) {
				$this->load->model('news/comment');

				$this->model_news_comment->addComment($this->request->get['article_id'], $this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

}
