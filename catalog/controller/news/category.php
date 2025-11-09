<?php
class ControllerNewsCategory extends Controller {

	public function index() {

		$this->load->model('blog/article');
		$this->load->model('blog/category');
        $this->load->language('blog/category');

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = null;
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int) $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get("config_article_limit");
		}
		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

        if (isset($this->request->get['blog_category_id'])) {
            $category_id = (int)$this->request->get['blog_category_id'];
        }  elseif (isset($this->request->get['blog_category_id'])) {
            $category_id = (int)$this->request->get['blog_category_id'];
        } else {
            $category_id = 0;
        }



        if($category_id) {
            $path = $this->model_blog_category->getCategoryPath($category_id);
        } else {
            $path = null;
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

        if ($path) {
            $parts = explode('_', $path);
            foreach ($parts as $path_id) {
                $category_info = $this->model_blog_category->getCategory($path_id);
                if ($category_info) {
                    $data['breadcrumbs'][] = array(
                        'text' => $category_info['name'],
                        'href' => $this->url->link('blog/category', 'blog_category_id=' . $path_id . $url)
                    );
                }
            }
        }

        $category_info = $this->model_blog_category->getCategory($category_id);

        if($category_info) {
            $this->document->setTitle($category_info['meta_title']);
            $this->document->setDescription($category_info['meta_description']);
            $this->document->setKeywords($category_info['meta_keyword']);
            $data['heading_title'] = $category_info['name'];
            $data['name'] = $category_info['name'];
            $data['blurb'] = $category_info['blurb'];
            $data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');

            $data['articles'] = array();

            if(!$sort && $category_info['sort'] == 'custom') {
                $sort = 'a2c.top DESC, a2c.sort_order';
            }

            $filter_data = array(
                'filter_category_id' => $category_id,
                'filter_filter'      => $filter,
                'sort'               => $sort,
                'order'              => $order,
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
                    'video_icon'        => $article_info['video_icon'],
                    'reporter' => $article_info['reporter'],
                    'intro_text' =>  $article_info['intro_text'],
                    'description' =>    html_entity_decode($article_info['description'], ENT_QUOTES, 'UTF-8'),
                    'date_published'  => $this->date->translate(strtotime($article_info['date_published']), $this->language->get('datetime_format_long')),
                    'href'        => $this->url->link('blog/article', 'article_id=' . $article_info['article_id'])
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
                'href'  => $this->url->link('blog/category', '&sort=p.sort_order&order=ASC&blog_category_id=' . $category_id . $url)
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

            $limits = array_unique(array($this->config->get('news_global_article_limit'), 50, 75, 100));

            sort($limits);

            foreach($limits as $value) {
                $data['limits'][] = array(
                    'text'  => $value,
                    'value' => $value,
                    'href'  => $this->url->link('blog/category', 'blog_category_id=' . $category_id . $url . '&limit=' . $value)
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

            $pagination = new Pagination($this->registry);
            $pagination->total = $article_total;
            $pagination->page = $page;
            $pagination->limit = $limit;
            $pagination->url = $this->url->link('blog/category', 'blog_category_id=' . $category_id . $url . '&page={page}');

            $data['pagination'] = $pagination->render();
            $data['button_read_more'] = $this->language->get('button_read_more');
            $data['text_empty'] = $this->language->get('text_empty');
            $data['results'] = sprintf($this->language->get('text_pagination'), ($article_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($article_total - $limit)) ? $article_total : ((($page - 1) * $limit) + $limit), $article_total, ceil($article_total / $limit));

            $data['sort'] = $sort;
            $data['order'] = $order;
            $data['limit'] = $limit;
            $data['page'] = $page;

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['column_middle'] = $this->load->controller('common/column_middle');
            $data['after_header'] = $this->load->controller('common/after_header');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['content_middle'] = $this->load->controller('common/content_middle');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

//            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/blog/category.tpl')) {
//                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/blog/category.tpl', $data));
//            } else {
//                $this->response->setOutput($this->load->view('default/template/blog/category.tpl', $data));
//            }
            if (isset($category_info['view']) && $category_info['view'] && file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/news/category_' . $category_info['view'] . '.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/news/category_' . $category_info['view'] . '.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/blog/category.tpl', $data));
            }

        }

	}

}