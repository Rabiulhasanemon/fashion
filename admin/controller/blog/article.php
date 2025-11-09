<?php

class ControllerBlogArticle extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('blog/article');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('blog/article');
        $this->load->model('setting/setting');

        $this->getList();
    }

    public function add() {
        $this->load->language('blog/article');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('blog/article');
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_blog_article->addArticle($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_article_id'])) {
                $url .= '&filter_article_id=' . $this->request->get['filter_article_id'];
            }

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_featured'])) {
                $url .= '&filter_featured=' . $this->request->get['filter_featured'];
            }

            if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
            }

            if (isset($this->request->get['filter_user_id'])) {
                $url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
            }

            if (isset($this->request->get['filter_date_from'])) {
                $url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
            }

            if (isset($this->request->get['filter_date_to'])) {
            $url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
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

            $this->response->redirect($this->url->link('blog/article', 'token=' . $this->session->data['token'] . $url, true));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('blog/article');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('blog/article');
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_blog_article->editArticle($this->request->get['article_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';
            if (isset($this->request->get['filter_article_id'])) {
                $url .= '&filter_article_id=' . $this->request->get['filter_article_id'];
            }
            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_featured'])) {
                $url .= '&filter_featured=' . $this->request->get['filter_featured'];
            }

            if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
            }

            if (isset($this->request->get['filter_user_id'])) {
                $url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
            }

            if (isset($this->request->get['filter_date_from'])) {
                $url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
            }

            if (isset($this->request->get['filter_date_to'])) {
            $url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
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

            $this->response->redirect($this->url->link('blog/article', 'token=' . $this->session->data['token'] . $url, true));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('blog/article');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('blog/article');
        $this->load->model('setting/setting');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $article_id) {
                $this->model_blog_article->deleteArticle($article_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';
            if (isset($this->request->get['filter_article_id'])) {
                $url .= '&filter_article_id=' . $this->request->get['filter_article_id'];
            }
            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_featured'])) {
                $url .= '&filter_featured=' . $this->request->get['filter_featured'];
            }

            if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
            }

            if (isset($this->request->get['filter_user_id'])) {
                $url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
            }

            if (isset($this->request->get['filter_date_from'])) {
                $url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
            }

            if (isset($this->request->get['filter_date_to'])) {
            $url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
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

            $this->response->redirect($this->url->link('blog/article', 'token=' . $this->session->data['token'] . $url, true));
        }

        $this->getList();
    }

    public function copy()
    {
        $this->load->language('blog/article');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('blog/article');

        if (isset($this->request->post['selected']) && $this->validateCopy()) {
            foreach ($this->request->post['selected'] as $article_id) {
                $this->model_blog_article->copyArticle($article_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';
            if (isset($this->request->get['filter_article_id'])) {
                $url .= '&filter_article_id=' . $this->request->get['filter_article_id'];
            }

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_featured'])) {
                $url .= '&filter_featured=' . $this->request->get['filter_featured'];
            }

            if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
            }

            if (isset($this->request->get['filter_user_id'])) {
                $url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
            }

            if (isset($this->request->get['filter_date_from'])) {
                $url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
            }

            if (isset($this->request->get['filter_date_to'])) {
            $url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
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

            $this->response->redirect($this->url->link('blog/article', 'token=' . $this->session->data['token'] . $url, true));
        }

        $this->getList();
    }

    public function sort() {
        $this->load->language('blog/article');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('blog/article');
        $this->load->model('setting/setting');


        if (isset($this->request->get['filter_category_id'])) {
            $filter_category_id = $this->request->get['filter_category_id'];
        } else {
            $filter_category_id = null;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateSort()) {
            if($filter_category_id) {
                $this->model_blog_article->editTopArticles($filter_category_id, $this->request->post['article_id']);
            } else {
                $this->model_blog_article->editFeaturedArticles($this->request->post['article_id']);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_article_id'])) {
                $url .= '&filter_article_id=' . $this->request->get['filter_article_id'];
            }

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_featured'])) {
                $url .= '&filter_featured=' . $this->request->get['filter_featured'];
            }

            if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
            }

            if (isset($this->request->get['filter_user_id'])) {
                $url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
            }

            if (isset($this->request->get['filter_date_from'])) {
                $url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
            }

            if (isset($this->request->get['filter_date_to'])) {
            $url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
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

            $this->response->redirect($this->url->link('blog/article', 'token=' . $this->session->data['token'] . $url, true));
        }

        if($filter_category_id) {
            $this->load->model('blog/category');
            $category_info = $this->model_blog_category->getCategory($filter_category_id);
        } else {
            $category_info = null;
        }

        if($category_info) {
            $data['heading_title'] = $this->language->get('text_sort') . ' - ' . $category_info['name'] ;
        } else {
            $data['heading_title'] = $this->language->get('text_sort') . ' - ' . $this->language->get('text_lead_news');
        }

        $data['text_form'] = $this->language->get('text_sort');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $url = '';
        if (isset($this->request->get['filter_article_id'])) {
            $url .= '&filter_article_id=' . $this->request->get['filter_article_id'];
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_featured'])) {
            $url .= '&filter_featured=' . $this->request->get['filter_featured'];
        }

        if (isset($this->request->get['filter_category_id'])) {
            $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
        }


        if (isset($this->request->get['filter_user_id'])) {
            $url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
        }

        if (isset($this->request->get['filter_date_from'])) {
            $url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
        }

        if (isset($this->request->get['filter_date_to'])) {
            $url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
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

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('blog/article', 'token=' . $this->session->data['token'] . $url, true)
        );

        $data['action'] = $this->url->link('blog/article/sort', 'token=' . $this->session->data['token'] . $url, true);
        $data['cancel'] = $this->url->link('blog/article', 'token=' . $this->session->data['token'] . $url, true);


        $data['token'] = $this->session->data['token'];

        $filter_data = array(
            'filter_status' => true,
            'start' => 0,
            'limit' => 30
        );

        if($filter_category_id) {
            $filter_data['filter_category_id'] = $filter_category_id;
            $filter_data['sort'] = 'a2c.top DESC, a2c.sort_order';
            $filter_data['order'] = "DESC";
        } else{
            $filter_data['filter_featured'] = true;
            $filter_data['sort'] = 'a.featured_order';
            $filter_data['order'] = "DESC";
        }

        $data['articles'] = $this->model_blog_article->getArticles($filter_data);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('blog/article_sort.tpl', $data));
    }

    public function history() {
        $this->load->language('blog/article');

        $this->document->setTitle($this->language->get('text_history'));

        $this->load->model('blog/article');

        if (isset($this->request->get['filter_article_id'])) {
            $filter_article_id = $this->request->get['filter_article_id'];
        } else {
            $filter_article_id = 0;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
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

        $url = '';

        if (isset($this->request->get['filter_article_id'])) {
            $url .= '&filter_article_id=' . $this->request->get['filter_article_id'];
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

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_article'),
            'href' => $this->url->link('blog/article', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_history'),
            'href' => $this->url->link('blog/article/history', 'token=' . $this->session->data['token'] . $url, true)
        );


        $data['histories'] = array();

        $filter_data = array(
            'filter_article_id'  => $filter_article_id,
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $history_total = $this->model_blog_article->getTotalArticleHistories();

        $results = $this->model_blog_article->getArticleHistories($filter_data);

        foreach ($results as $result) {
            $data['histories'][] = array(
                'name'        => $result['name'],
                'user'        => $result['user'],
                'action'  => $result['action'],
                'date_added'  =>  date($this->language->get('datetime_format'), strtotime($result['date_added'])),
            );
        }

        $data['heading_title'] = $this->language->get('text_history');

        $data['text_list'] = $this->language->get('text_history');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_action'] = $this->language->get('column_action');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_user'] = $this->language->get('column_user');
        $data['column_date_added'] = $this->language->get('column_date_added');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination($this->registry);
        $pagination->total = $history_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('blog/article/history', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($history_total - $this->config->get('config_limit_admin'))) ? $history_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $history_total, ceil($history_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('blog/article_history.tpl', $data));
    }

    protected function getList() {
        if (isset($this->request->get['filter_article_id'])) {
            $filter_article_id = $this->request->get['filter_article_id'];
        } else {
            $filter_article_id = null;
        }

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['filter_featured'])) {
            $filter_featured = $this->request->get['filter_featured'];
        } else {
            $filter_featured = null;
        }

        if (isset($this->request->get['filter_user_id'])) {
            $filter_user_id = $this->request->get['filter_user_id'];
        } else {
            $filter_user_id = null;
        }

        if (isset($this->request->get['filter_date_from'])) {
            $filter_date_from = $this->request->get['filter_date_from'];
        } else {
            $filter_date_from = null;
        }

        if (isset($this->request->get['filter_date_to'])) {
            $filter_date_to = $this->request->get['filter_date_to'];
        } else {
            $filter_date_to = null;
        }

        if (isset($this->request->get['filter_category_id'])) {
            $filter_category_id = $this->request->get['filter_category_id'];
        } else {
            $filter_category_id = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'a.date_added';
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

        $url = '';

        if (isset($this->request->get['filter_article_id'])) {
            $url .= '&filter_article_id=' . $this->request->get['filter_article_id'];
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_featured'])) {
            $url .= '&filter_featured=' . $this->request->get['filter_featured'];
        }

        if (isset($this->request->get['filter_category_id'])) {
            $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
        }

        if (isset($this->request->get['filter_user_id'])) {
            $url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
        }

        if (isset($this->request->get['filter_date_from'])) {
            $url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
        }

        if (isset($this->request->get['filter_date_to'])) {
            $url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
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

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('blog/article', 'token=' . $this->session->data['token'] . $url, true)
        );

        $data['sorter'] = $this->url->link('blog/article/sort', 'token=' . $this->session->data['token'] . $url, true);
        $data['add'] = $this->url->link('blog/article/add', 'token=' . $this->session->data['token'] . $url, true);
        $data['copy'] = $this->url->link('blog/article/copy', 'token=' . $this->session->data['token'] . $url, true);
        $data['delete'] = $this->url->link('blog/article/delete', 'token=' . $this->session->data['token'] . $url, true);

        $data['articles'] = array();

        $filter_data = array(
            'filter_article_id' => $filter_article_id,
            'filter_name' => $filter_name,
            'filter_category_id' => $filter_category_id,
            'filter_status' => $filter_status,
            'filter_featured' => $filter_featured,
            'filter_user_id' => $filter_user_id,
            'filter_date_from' => $filter_date_from,
            'filter_date_to' => $filter_date_to,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );


        $article_config = $this->model_setting_setting->getSetting("article");
        $article_total = $this->model_blog_article->getTotalArticles($filter_data);
        $results = $this->model_blog_article->getArticles($filter_data);

        foreach ($results as $result) {
            $data['articles'][] = array(
                'article_id' => $result['article_id'],
                'name' => $result['name'],
                'on_lead' => (isset($article_config['article_lead']) && $article_config['article_lead'] == $result['article_id']) ? $this->language->get('text_lead_news') : ($result['featured'] ? $this->language->get('text_show_news') : $this->language->get('text_none')),
                'viewed' => $result['viewed'],
                'user_created' => $result['user_created'],
                'user_modified' => $result['user_modified'],
                'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
                'date_modified' => $result['date_modified'] ? date($this->language->get('datetime_format'), strtotime($result['date_modified'])) : "",
                'status' => ($result['status']) ? $this->language->get('text_yes') : $this->language->get('text_no'),
                'history' => $this->url->link('blog/article/history', 'token=' . $this->session->data['token'] . '&filter_article_id=' . $result['article_id'], true),
                'edit' => $this->url->link('blog/article/edit', 'token=' . $this->session->data['token'] . '&article_id=' . $result['article_id'] . $url, true)
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_none'] = $this->language->get('text_none');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_viewed'] = $this->language->get('column_viewed');
        $data['column_on_lead'] = $this->language->get('column_on_lead');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_date_modified'] = $this->language->get('column_date_modified');
        $data['column_user_created'] = $this->language->get('column_user_created');
        $data['column_user_modified'] = $this->language->get('column_user_modified');
        $data['column_last_update_info'] = $this->language->get('column_last_update_info');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_article_id'] = $this->language->get('entry_article_id');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_featured'] = $this->language->get('entry_featured');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_user'] = $this->language->get('entry_user');
        $data['entry_date_from'] = $this->language->get('entry_date_from');
        $data['entry_date_to'] = $this->language->get('entry_date_to');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_copy'] = $this->language->get('button_copy');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_sort'] = $this->language->get('button_sort');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_history'] = $this->language->get('button_history');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');

        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';
        if (isset($this->request->get['filter_article_id'])) {
            $url .= '&filter_article_id=' . $this->request->get['filter_article_id'];
        }
        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_featured'])) {
            $url .= '&filter_featured=' . $this->request->get['filter_featured'];
        }

        if (isset($this->request->get['filter_category_id'])) {
            $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
        }

        if (isset($this->request->get['filter_user_id'])) {
            $url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
        }

        if (isset($this->request->get['filter_date_from'])) {
            $url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
        }

        if (isset($this->request->get['filter_date_to'])) {
            $url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('blog/article', 'token=' . $this->session->data['token'] . '&sort=ad.name' . $url, true);
        $data['sort_status'] = $this->url->link('blog/article', 'token=' . $this->session->data['token'] . '&sort=a.status' . $url, true);
        $data['sort_date_added'] = $this->url->link('blog/article', 'token=' . $this->session->data['token'] . '&sort=a.date_added' . $url, true);
        $data['sort_order'] = $this->url->link('blog/article', 'token=' . $this->session->data['token'] . '&sort=a.sort_order' . $url, true);

        $this->load->model('blog/category');

        $data['categories'] = $this->model_blog_category->getCategories();

        $url = '';

        if (isset($this->request->get['filter_article_id'])) {
            $url .= '&filter_article_id=' . $this->request->get['filter_article_id'];
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_featured'])) {
            $url .= '&filter_featured=' . $this->request->get['filter_featured'];
        }

        if (isset($this->request->get['filter_category_id'])) {
            $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
        }

        if (isset($this->request->get['filter_user_id'])) {
            $url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
        }

        if (isset($this->request->get['filter_date_from'])) {
            $url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
        }

        if (isset($this->request->get['filter_date_to'])) {
            $url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
        }
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination($this->registry);
        $pagination->total = $article_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('blog/article', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($article_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($article_total - $this->config->get('config_limit_admin'))) ? $article_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $article_total, ceil($article_total / $this->config->get('config_limit_admin')));

        $data['filter_article_id'] = $filter_article_id;
        $data['filter_name'] = $filter_name;
        $data['filter_status'] = $filter_status;
        $data['filter_featured'] = $filter_featured;
        $data['filter_category_id'] = $filter_category_id;
        $data['filter_user_id'] = $filter_user_id;
        $data['filter_date_from'] = $filter_date_from;
        $data['filter_date_to'] = $filter_date_to;

        if($filter_user_id) {
            $this->load->model("user/user");
            $user_info = $this->model_user_user->getUser($filter_user_id);
            $data['filter_user'] = $user_info['firstname'] . ' ' . $user_info['lastname'];
        } else {
            $data['filter_user'] = '';
        }

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('blog/article_list.tpl', $data));
    }

    protected function getForm() {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['article_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_lead_news'] = $this->language->get('text_lead_news');
        $data['text_show_news'] = $this->language->get('text_show_news');
        $data['text_plus'] = $this->language->get('text_plus');
        $data['text_minus'] = $this->language->get('text_minus');
        $data['text_default'] = $this->language->get('text_default');

        $data['entry_parent'] = $this->language->get('entry_parent');
        $data['entry_upazila'] = $this->language->get('entry_upazila');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_headline_for_details'] = $this->language->get('entry_headline_for_details');
        $data['entry_shoulder'] = $this->language->get('entry_shoulder');
        $data['entry_hanger'] = $this->language->get('entry_hanger');
        $data['entry_reporter'] = $this->language->get('entry_reporter');
        $data['entry_intro_text'] = $this->language->get('entry_intro_text');
        $data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_meta_title'] = $this->language->get('entry_meta_title');
        $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
        $data['entry_event_stream'] = $this->language->get('entry_event_stream');
        $data['entry_tags'] = $this->language->get('entry_tags');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_on_lead'] = $this->language->get('entry_on_lead');
        $data['entry_show_in_headline'] = $this->language->get('entry_show_in_headline');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_store'] = $this->language->get('entry_store');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_thumb'] = $this->language->get('entry_thumb');
        $data['entry_video_url'] = $this->language->get('entry_video_url');
        $data['entry_video_icon'] = $this->language->get('entry_video_icon');
        $data['entry_keyword'] = $this->language->get('entry_keyword');
        $data['entry_layout'] = $this->language->get('entry_layout');
        $data['entry_view'] = $this->language->get('entry_view');

        $data['help_category'] = $this->language->get('help_category');
        $data['help_store'] = $this->language->get('help_category');
        $data['help_intro_text'] = $this->language->get('help_intro_text');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_remove'] = $this->language->get('button_remove');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_data'] = $this->language->get('tab_data');
        $data['tab_design'] = $this->language->get('tab_design');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = array();
        }

        if (isset($this->error['reporter'])) {
            $data['error_reporter'] = $this->error['reporter'];
        } else {
            $data['error_reporter'] = array();
        }

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = array();
        }

        if (isset($this->error['meta_description'])) {
            $data['error_meta_description'] = $this->error['meta_description'];
        } else {
            $data['error_meta_description'] = array();
        }

        if (isset($this->error['meta_keyword'])) {
            $data['error_meta_keyword'] = $this->error['meta_keyword'];
        } else {
            $data['error_meta_keyword'] = array();
        }

        if (isset($this->error['parent'])) {
            $data['error_parent'] = $this->error['parent'];
        } else {
            $data['error_parent'] = array();
        }

        if (isset($this->error['upazila'])) {
            $data['error_upazila'] = $this->error['upazila'];
        } else {
            $data['error_upazila'] = array();
        }

        if (isset($this->error['tags'])) {
            $data['error_tags'] = $this->error['tags'];
        } else {
            $data['error_tags'] = array();
        }

        if (isset($this->error['keyword'])) {
            $data['error_keyword'] = $this->error['keyword'];
        } else {
            $data['error_keyword'] = array();
        }

        $url = '';

        if (isset($this->request->get['filter_article_id'])) {
            $url .= '&filter_article_id=' . $this->request->get['filter_article_id'];
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_featured'])) {
            $url .= '&filter_featured=' . $this->request->get['filter_featured'];
        }

        if (isset($this->request->get['filter_category_id'])) {
            $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
        }

        if (isset($this->request->get['filter_user_id'])) {
            $url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
        }

        if (isset($this->request->get['filter_date_from'])) {
            $url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
        }

        if (isset($this->request->get['filter_date_to'])) {
            $url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
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

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('blog/article', 'token=' . $this->session->data['token'] . $url, true)
        );

        if (!isset($this->request->get['article_id'])) {
            $data['action'] = $this->url->link('blog/article/add', 'token=' . $this->session->data['token'] . $url, true);
        } else {
            $data['action'] = $this->url->link('blog/article/edit', 'token=' . $this->session->data['token'] . '&article_id=' . $this->request->get['article_id'] . $url, true);
        }

        $data['cancel'] = $this->url->link('blog/article', 'token=' . $this->session->data['token'] . $url, true);

        if (isset($this->request->get['article_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $article_info = $this->model_blog_article->getArticle($this->request->get['article_id']);
        }

        $data['token'] = $this->session->data['token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['article_description'])) {
            $data['article_description'] = $this->request->post['article_description'];
        } elseif (isset($this->request->get['article_id'])) {
            $data['article_description'] = $this->model_blog_article->getArticleDescriptions($this->request->get['article_id']);
        } else {
            $data['article_description'] = array();
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($article_info)) {
            $data['sort_order'] = $article_info['sort_order'];
        } else {
            $data['sort_order'] = 1;
        }

        if (isset($this->request->post['keyword'])) {
            $data['keyword'] = $this->request->post['keyword'];
        } elseif (!empty($article_info)) {
            $data['keyword'] = $article_info['keyword'];
        } else {
            $data['keyword'] = "";
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($article_info)) {
            $data['status'] = $article_info['status'];
        } else {
            $data['status'] = true;
        }


        if (isset($this->request->post['article_layout'])) {
            $data['article_layout'] = $this->request->post['article_layout'];
        } elseif (isset($this->request->get['article_id'])) {
            $data['article_layout'] = $this->model_blog_article->geArticleLayouts($this->request->get['article_id']);
        } else {
            $data['article_layout'] = array();
        }

        if (isset($this->request->post['video_icon'])) {
            $data['video_icon'] = $this->request->post['video_icon'];
        } elseif (!empty($article_info)) {
            $data['video_icon'] = $article_info['video_icon'];
        } else {
            $data['video_icon'] = false;
        }

        if (isset($this->request->post['show_in_headline'])) {
            $data['show_in_headline'] = $this->request->post['show_in_headline'];
        } elseif (!empty($article_info)) {
            $data['show_in_headline'] = $article_info['show_in_headline'];
        } else {
            $data['show_in_headline'] = false;
        }

        $article_config = $this->model_setting_setting->getSetting("article");

        if (isset($this->request->post['on_lead'])) {
            $data['on_lead'] = $this->request->post['on_lead'];
        } elseif (!empty($article_info)) {
            $data['on_lead'] = (isset($article_config['article_lead']) && $article_config['article_lead'] == $article_info['article_id']) ? "lead" : ($article_info['featured'] ? "featured" : "none");
        } else {
            $data['on_lead'] = "none";
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($article_info)) {
            $data['image'] = $article_info['image'];
        } else {
            $data['image'] = '';
        }

        if (isset($this->request->post['video_url'])) {
            $data['video_url'] = $this->request->post['video_url'];
        } elseif (!empty($article_info)) {
            $data['video_url'] = $article_info['video_url'];
        } else {
            $data['video_url'] = '';
        }

        if (isset($this->request->post['thumb'])) {
            $data['thumb'] = $this->request->post['thumb'];
        } elseif (!empty($article_info)) {
            $data['thumb'] = $article_info['thumb'];
        } else {
            $data['thumb'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['image_thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($article_info) && is_file(DIR_IMAGE . $article_info['image'])) {
            $data['image_thumb'] = $this->model_tool_image->resize($article_info['image'], 100, 100);
        } else {
            $data['image_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (isset($this->request->post['thumb']) && is_file(DIR_IMAGE . $this->request->post['thumb'])) {
            $data['thumb_thumb'] = $this->model_tool_image->resize($this->request->post['thumb'], 100, 100);
        } elseif (!empty($article_info) && is_file(DIR_IMAGE . $article_info['thumb'])) {
            $data['thumb_thumb'] = $this->model_tool_image->resize($article_info['thumb'], 100, 100);
        } else {
            $data['thumb_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        $this->load->model('blog/category');

        if (isset($this->request->post['parent_id'])) {
            $data['parent_id'] = $this->request->post['parent_id'];
        } elseif (!empty($article_info)) {
            $data['parent_id'] = $article_info['parent_id'];
        } else {
            $data['parent_id'] = 0;
        }


        if($data['parent_id']) {
            $category_info = $this->model_blog_category->getCategory($data['parent_id']);
            $data['path'] =  ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name'];
        } else {
            $data['path'] = '';
        }

        // Categories
        if (isset($this->request->post['article_category'])) {
            $categories = $this->request->post['article_category'];
        } elseif (isset($this->request->get['article_id'])) {
            $categories = $this->model_blog_article->getArticleCategories($this->request->get['article_id']);
        } else {
            $categories = array();
        }

        $data['article_categories'] = array();

        foreach ($categories as $category_id) {
            $category_info = $this->model_blog_category->getCategory($category_id);

            if ($category_info) {
                $data['article_categories'][] = array(
                    'category_id' => $category_info['category_id'],
                    'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
                );
            }
        }

        // Event Streams
        $this->load->model('blog/event_stream');

        if (isset($this->request->post['article_event_stream'])) {
            $event_streams = $this->request->post['article_event_stream'];
        } elseif (isset($this->request->get['article_id'])) {
            $event_streams = $this->model_blog_article->getArticleEventStreams($this->request->get['article_id']);
        } else {
            $event_streams = array();
        }

        $data['article_event_streams'] = array();

        foreach ($event_streams as $event_stream_id) {
            $event_stream_info = $this->model_blog_event_stream->getEventStream($event_stream_id);

            if ($event_stream_info) {
                $data['article_event_streams'][] = array(
                    'event_stream_id' => $event_stream_info['event_stream_id'],
                    'name' => $event_stream_info['name']
                );
            }
        }

        // Upazila TODO: Fix will later
//        $this->load->model('localisation/upazila');

        if (isset($this->request->post['upazila_id'])) {
            $data['upazila_id'] = $this->request->post['upazila_id'];
        } elseif (!empty($article_info)) {
            $data['upazila_id'] = $article_info['upazila_id'];
        } else {
            $data['upazila_id'] = 0;
        }


        if($data['upazila_id']) {
//            $upazila_info = $this->model_localisation_upazila->getUpazila($data['upazila_id']);
            $data['upazila'] = '';
        } else {
            $data['upazila'] = '';
        }

        //Stores
        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        if (isset($this->request->post['article_store'])) {
            $data['article_store'] = $this->request->post['article_store'];
        } elseif (isset($this->request->get['article_id'])) {
            $data['article_store'] = $this->model_blog_article->getArticleStores($this->request->get['article_id']);
        } else {
            $data['article_store'] = array(0);
        }

        $this->load->model('design/layout');
        $data['layouts'] = $this->model_design_layout->getLayouts();
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('blog/article_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'blog/article')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['article_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 120)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }

            if ((utf8_strlen($value['reporter']) < 3) || (utf8_strlen($value['reporter']) > 255)) {
                $this->error['reporter'][$language_id] = $this->language->get('error_reporter');
            }

            if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
                $this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
            }

            if ((utf8_strlen($value['meta_description']) < 3) || (utf8_strlen($value['meta_description']) > 255)) {
                $this->error['meta_description'][$language_id] = $this->language->get('error_meta_description');
            }

            if ((utf8_strlen($value['meta_keyword']) < 3) || (utf8_strlen($value['meta_keyword']) > 255)) {
                $this->error['meta_keyword'][$language_id] = $this->language->get('error_meta_keyword');
            }

            if ((utf8_strlen($value['tags']) < 3) || (utf8_strlen($value['tags']) > 255)) {
                $this->error['tags'][$language_id] = $this->language->get('error_tags');
            }
        }

        if (utf8_strlen($this->request->post['keyword']) > 0) {
            $this->load->model('catalog/url_alias');

            $url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

            if ($url_alias_info && isset($this->request->get['article_id']) && $url_alias_info['query'] != 'article_id=' . $this->request->get['article_id']) {
                $this->error['keyword'] = sprintf($this->language->get('error_keyword'));
            }

            if ($url_alias_info && !isset($this->request->get['article_id'])) {
                $this->error['keyword'] = sprintf($this->language->get('error_keyword'));
            }
        }

        $parent_id = (int) $this->request->post['parent_id'];
        $this->load->model('blog/category');
        $parent_info = $this->model_blog_category->getCategory($parent_id);

        if (!$parent_info) {
            $this->error['parent'] = $this->language->get('error_parent');
        }

//        $upazila_id = (int) $this->request->post['upazila_id'];
//        if($parent_info && $parent_info['local'] && !$upazila_id) {
//            $this->error['upazila'] = $this->language->get('error_upazila');
//        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'blog/article')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateSort()
    {
        if (!$this->user->hasPermission('modify', 'blog/article')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateCopy()
    {
        if (!$this->user->hasPermission('modify', 'blog/article')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function autocomplete()
    {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('blog/article');

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 5;
            }

            $filter_data = array(
                'filter_name' => $filter_name,
                'start' => 0,
                'limit' => $limit
            );

            $results = $this->model_blog_article->getArticles($filter_data);

            foreach ($results as $result) {
                $option_data = array();

                $json[] = array(
                    'article_id' => $result['article_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}