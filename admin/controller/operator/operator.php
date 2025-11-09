<?php

class ControllerOperatorOperator extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('operator/operator');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('operator/operator');

        $this->getList();
    }

    public function add() {
        $this->load->language('operator/operator');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('operator/operator');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_operator_operator->addOperator($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_email'])) {
                $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_operator_group_id'])) {
                $url .= '&filter_operator_group_id=' . $this->request->get['filter_operator_group_id'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_approved'])) {
                $url .= '&filter_approved=' . $this->request->get['filter_approved'];
            }

            if (isset($this->request->get['filter_ip'])) {
                $url .= '&filter_ip=' . $this->request->get['filter_ip'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

            $this->response->redirect($this->url->link('operator/operator', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('operator/operator');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('operator/operator');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_operator_operator->editOperator($this->request->get['operator_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_email'])) {
                $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_operator_group_id'])) {
                $url .= '&filter_operator_group_id=' . $this->request->get['filter_operator_group_id'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_approved'])) {
                $url .= '&filter_approved=' . $this->request->get['filter_approved'];
            }

            if (isset($this->request->get['filter_ip'])) {
                $url .= '&filter_ip=' . $this->request->get['filter_ip'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

            $this->response->redirect($this->url->link('operator/operator', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('operator/operator');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('operator/operator');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $operator_id) {
                $this->model_operator_operator->deleteOperator($operator_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_email'])) {
                $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_operator_group_id'])) {
                $url .= '&filter_operator_group_id=' . $this->request->get['filter_operator_group_id'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_approved'])) {
                $url .= '&filter_approved=' . $this->request->get['filter_approved'];
            }

            if (isset($this->request->get['filter_ip'])) {
                $url .= '&filter_ip=' . $this->request->get['filter_ip'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

            $this->response->redirect($this->url->link('operator/operator', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function approve() {
        $this->load->language('operator/operator');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('operator/operator');

        $operators = array();

        if (isset($this->request->post['selected'])) {
            $operators = $this->request->post['selected'];
        } elseif (isset($this->request->get['operator_id'])) {
            $operators[] = $this->request->get['operator_id'];
        }

        if ($operators && $this->validateApprove()) {
            $this->model_operator_operator->approve($this->request->get['operator_id']);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_email'])) {
                $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_approved'])) {
                $url .= '&filter_approved=' . $this->request->get['filter_approved'];
            }

            if (isset($this->request->get['filter_ip'])) {
                $url .= '&filter_ip=' . $this->request->get['filter_ip'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

            $this->response->redirect($this->url->link('operator/operator', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function unlock()
    {
        $this->load->language('operator/operator');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('operator/operator');

        if (isset($this->request->get['email']) && $this->validateUnlock()) {
            $this->model_operator_operator->deleteLoginAttempts($this->request->get['email']);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_email'])) {
                $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_operator_group_id'])) {
                $url .= '&filter_operator_group_id=' . $this->request->get['filter_operator_group_id'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_approved'])) {
                $url .= '&filter_approved=' . $this->request->get['filter_approved'];
            }

            if (isset($this->request->get['filter_ip'])) {
                $url .= '&filter_ip=' . $this->request->get['filter_ip'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

            $this->response->redirect($this->url->link('operator/operator', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList() {
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_email'])) {
            $filter_email = $this->request->get['filter_email'];
        } else {
            $filter_email = null;
        }


        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['filter_approved'])) {
            $filter_approved = $this->request->get['filter_approved'];
        } else {
            $filter_approved = null;
        }

        if (isset($this->request->get['filter_ip'])) {
            $filter_ip = $this->request->get['filter_ip'];
        } else {
            $filter_ip = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_approved'])) {
            $url .= '&filter_approved=' . $this->request->get['filter_approved'];
        }

        if (isset($this->request->get['filter_ip'])) {
            $url .= '&filter_ip=' . $this->request->get['filter_ip'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('operator/operator', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $data['add'] = $this->url->link('operator/operator/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('operator/operator/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['operators'] = array();

        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_email' => $filter_email,
            'filter_status' => $filter_status,
            'filter_approved' => $filter_approved,
            'filter_date_added' => $filter_date_added,
            'filter_ip' => $filter_ip,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $operator_total = $this->model_operator_operator->getTotalOperators($filter_data);

        $results = $this->model_operator_operator->getOperators($filter_data);

        foreach ($results as $result) {
            if (!$result['approved']) {
                $approve = $this->url->link('operator/operator/approve', 'token=' . $this->session->data['token'] . '&operator_id=' . $result['operator_id'] . $url, 'SSL');
            } else {
                $approve = '';
            }

            $login_info = $this->model_operator_operator->getTotalLoginAttempts($result['email']);

            if ($login_info && $login_info['total'] >= $this->config->get('config_login_attempts')) {
                $unlock = $this->url->link('operator/operator/unlock', 'token=' . $this->session->data['token'] . '&email=' . $result['email'] . $url, 'SSL');
            } else {
                $unlock = '';
            }

            $data['operators'][] = array(
                'operator_id' => $result['operator_id'],
                'name' => $result['name'],
                'email' => $result['email'],
                'designation' => $result['designation'],
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'ip' => $result['ip'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'approve' => $approve,
                'unlock' => $unlock,
                'edit' => $this->url->link('operator/operator/edit', 'token=' . $this->session->data['token'] . '&operator_id=' . $result['operator_id'] . $url, 'SSL')
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_email'] = $this->language->get('column_email');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_approved'] = $this->language->get('column_approved');
        $data['column_designation'] = $this->language->get('column_designation');
        $data['column_ip'] = $this->language->get('column_ip');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_designation'] = $this->language->get('entry_designation');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_approved'] = $this->language->get('entry_approved');
        $data['entry_ip'] = $this->language->get('entry_ip');
        $data['entry_date_added'] = $this->language->get('entry_date_added');

        $data['button_approve'] = $this->language->get('button_approve');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_login'] = $this->language->get('button_login');
        $data['button_unlock'] = $this->language->get('button_unlock');

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

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }


        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_approved'])) {
            $url .= '&filter_approved=' . $this->request->get['filter_approved'];
        }

        if (isset($this->request->get['filter_ip'])) {
            $url .= '&filter_ip=' . $this->request->get['filter_ip'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('operator/operator', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
        $data['sort_designation'] = $this->url->link('operator/operator', 'token=' . $this->session->data['token'] . '&sort=o.designation' . $url, 'SSL');
        $data['sort_email'] = $this->url->link('operator/operator', 'token=' . $this->session->data['token'] . '&sort=o.email' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('operator/operator', 'token=' . $this->session->data['token'] . '&sort=o.status' . $url, 'SSL');
        $data['sort_ip'] = $this->url->link('operator/operator', 'token=' . $this->session->data['token'] . '&sort=o.ip' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('operator/operator', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_approved'])) {
            $url .= '&filter_approved=' . $this->request->get['filter_approved'];
        }

        if (isset($this->request->get['filter_ip'])) {
            $url .= '&filter_ip=' . $this->request->get['filter_ip'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $operator_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('operator/operator', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($operator_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($operator_total - $this->config->get('config_limit_admin'))) ? $operator_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $operator_total, ceil($operator_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_email'] = $filter_email;
        $data['filter_status'] = $filter_status;
        $data['filter_approved'] = $filter_approved;
        $data['filter_ip'] = $filter_ip;
        $data['filter_date_added'] = $filter_date_added;

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('operator/operator_list.tpl', $data));
    }

    protected function getForm() {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['operator_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_add_ban_ip'] = $this->language->get('text_add_ban_ip');
        $data['text_remove_ban_ip'] = $this->language->get('text_remove_ban_ip');

        $data['entry_operator_group'] = $this->language->get('entry_operator_group');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_designation'] = $this->language->get('entry_designation');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm'] = $this->language->get('entry_confirm');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_approved'] = $this->language->get('entry_approved');
        $data['entry_safe'] = $this->language->get('entry_safe');
        $data['entry_default'] = $this->language->get('entry_default');
        $data['entry_ip'] = $this->language->get('entry_ip');

        $data['help_safe'] = $this->language->get('help_safe');
        $data['help_points'] = $this->language->get('help_points');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_ip_add'] = $this->language->get('button_ip_add');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_allowed_ip'] = $this->language->get('tab_allowed_ip');
        $data['tab_ip'] = $this->language->get('tab_ip');

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->get['operator_id'])) {
            $data['operator_id'] = $this->request->get['operator_id'];
        } else {
            $data['operator_id'] = 0;
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['firstname'])) {
            $data['error_firstname'] = $this->error['firstname'];
        } else {
            $data['error_firstname'] = '';
        }

        if (isset($this->error['lastname'])) {
            $data['error_lastname'] = $this->error['lastname'];
        } else {
            $data['error_lastname'] = '';
        }

        if (isset($this->error['designation'])) {
            $data['error_designation'] = $this->error['designation'];
        } else {
            $data['error_designation'] = '';
        }

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        if (isset($this->error['telephone'])) {
            $data['error_telephone'] = $this->error['telephone'];
        } else {
            $data['error_telephone'] = '';
        }

        if (isset($this->error['password'])) {
            $data['error_password'] = $this->error['password'];
        } else {
            $data['error_password'] = '';
        }

        if (isset($this->error['confirm'])) {
            $data['error_confirm'] = $this->error['confirm'];
        } else {
            $data['error_confirm'] = '';
        }


        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_operator_group_id'])) {
            $url .= '&filter_operator_group_id=' . $this->request->get['filter_operator_group_id'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_approved'])) {
            $url .= '&filter_approved=' . $this->request->get['filter_approved'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('operator/operator', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        if (!isset($this->request->get['operator_id'])) {
            $data['action'] = $this->url->link('operator/operator/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('operator/operator/edit', 'token=' . $this->session->data['token'] . '&operator_id=' . $this->request->get['operator_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('operator/operator', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['operator_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $operator_info = $this->model_operator_operator->getOperator($this->request->get['operator_id']);
        }

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } elseif (!empty($operator_info)) {
            $data['firstname'] = $operator_info['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        } elseif (!empty($operator_info)) {
            $data['lastname'] = $operator_info['lastname'];
        } else {
            $data['lastname'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } elseif (!empty($operator_info)) {
            $data['email'] = $operator_info['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->request->post['telephone'])) {
            $data['telephone'] = $this->request->post['telephone'];
        } elseif (!empty($operator_info)) {
            $data['telephone'] = $operator_info['telephone'];
        } else {
            $data['telephone'] = '';
        }

        if (isset($this->request->post['designation'])) {
            $data['designation'] = $this->request->post['designation'];
        } elseif (!empty($operator_info)) {
            $data['designation'] = $operator_info['designation'];
        } else {
            $data['designation'] = '';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($operator_info)) {
            $data['status'] = $operator_info['status'];
        } else {
            $data['status'] = true;
        }

        if (isset($this->request->post['approved'])) {
            $data['approved'] = $this->request->post['approved'];
        } elseif (!empty($operator_info)) {
            $data['approved'] = $operator_info['approved'];
        } else {
            $data['approved'] = true;
        }

        if (isset($this->request->post['safe'])) {
            $data['safe'] = $this->request->post['safe'];
        } elseif (!empty($operator_info)) {
            $data['safe'] = $operator_info['safe'];
        } else {
            $data['safe'] = 0;
        }

        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } else {
            $data['password'] = '';
        }

        if (isset($this->request->post['confirm'])) {
            $data['confirm'] = $this->request->post['confirm'];
        } else {
            $data['confirm'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('operator/operator_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'operator/operator')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen($this->request->post['designation']) < 1) || (utf8_strlen(trim($this->request->post['designation'])) > 200)) {
            $this->error['designation'] = $this->language->get('error_designation');
        }

        if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
            $this->error['email'] = $this->language->get('error_email');
        }

        $operator_info = $this->model_operator_operator->getOperatorByEmail($this->request->post['email']);

        if (!isset($this->request->get['operator_id'])) {
            if ($operator_info) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        } else {
            if ($operator_info && ($this->request->get['operator_id'] != $operator_info['operator_id'])) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        }

        if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }


        if ($this->request->post['password'] || (!isset($this->request->get['operator_id']))) {
            if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
                $this->error['password'] = $this->language->get('error_password');
            }

            if ($this->request->post['password'] != $this->request->post['confirm']) {
                $this->error['confirm'] = $this->language->get('error_confirm');
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'operator/operator')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateApprove()
    {
        if (!$this->user->hasPermission('modify', 'operator/operator')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateUnlock()
    {
        if (!$this->user->hasPermission('modify', 'operator/operator')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateAllowedIp() {
        if (!$this->user->hasPermission('modify', 'operator/operator')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if ((utf8_strlen($this->request->post['ip']) < 1) || (utf8_strlen($this->request->post['ip']) > 40)) {
            $this->error['warning'] = $this->language->get('error_ip');
        }

        return !$this->error;
    }

    public function login()
    {
        $json = array();

        if (isset($this->request->get['operator_id'])) {
            $operator_id = $this->request->get['operator_id'];
        } else {
            $operator_id = 0;
        }

        $this->load->model('operator/operator');

        $operator_info = $this->model_operator_operator->getOperator($operator_id);

        if ($operator_info) {
            $token = md5(mt_rand());

            $this->model_operator_operator->editToken($operator_id, $token);

            if (isset($this->request->get['store_id'])) {
                $store_id = $this->request->get['store_id'];
            } else {
                $store_id = 0;
            }

            $this->load->model('setting/store');

            $store_info = $this->model_setting_store->getStore($store_id);

            if ($store_info) {
                $this->response->redirect($store_info['url'] . 'index.php?route=operator/login&token=' . $token);
            } else {
                $this->response->redirect(HTTP_CATALOG . 'index.php?route=operator/login&token=' . $token);
            }
        } else {
            $this->load->language('error/not_found');

            $this->document->setTitle($this->language->get('heading_title'));

            $data['heading_title'] = $this->language->get('heading_title');

            $data['text_not_found'] = $this->language->get('text_not_found');

            $data['breadcrumbs'] = array();

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
            );

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], 'SSL')
            );

            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->load->view('error/not_found.tpl', $data));
        }
    }

    public function allowedIp()
    {
        $this->load->language('operator/operator');

        $this->load->model('operator/operator');

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_button_remove'] = $this->language->get('text_button_remove');
        $data['text_loading'] = $this->language->get('text_loading');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->request->post['action'] == "add" && $this->validateAllowedIp()) {
            $this->model_operator_operator->addAllowedIp($this->request->get['operator_id'], $this->request->post['ip']);
            $data['success'] = $this->language->get('text_success');
        } elseif (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->request->post['action'] == "remove") {
            $this->model_operator_operator->deleteAllowedIp($this->request->get['operator_id'], $this->request->post['operator_allowed_ip_id']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_comment'] = $this->language->get('column_comment');
        $data['column_ip'] = $this->language->get('column_ip');
        $data['column_action'] = $this->language->get('column_action');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['ips'] = array();

        $results = $this->model_operator_operator->getAllowedIps($this->request->get['operator_id'], ($page - 1) * 10, 10);

        foreach ($results as $result) {
            $data['ips'][] = array(
                'operator_allowed_ip_id'  => $result['operator_allowed_ip_id'],
                'ip' => $result['ip'],
                'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
            );
        }

        $allowed_id_total = $this->model_operator_operator->getAllowedTotalIps($this->request->get['operator_id']);

        $pagination = new Pagination();
        $pagination->total = $allowed_id_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('operator/operator/allowedIp', 'token=' . $this->session->data['token'] . '&operator_id=' . $this->request->get['operator_id'] . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($allowed_id_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($allowed_id_total - 10)) ? $allowed_id_total : ((($page - 1) * 10) + 10), $allowed_id_total, ceil($allowed_id_total / 10));

        $this->response->setOutput($this->load->view('operator/operator_allowed_ip.tpl', $data));
    }

    public function ip()
    {
        $this->load->language('operator/operator');

        $this->load->model('operator/operator');

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_add_ban_ip'] = $this->language->get('text_add_ban_ip');
        $data['text_remove_ban_ip'] = $this->language->get('text_remove_ban_ip');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['column_ip'] = $this->language->get('column_ip');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['ips'] = array();

        $results = $this->model_operator_operator->getIps($this->request->get['operator_id'], ($page - 1) * 10, 10);

        foreach ($results as $result) {
            $ban_ip_total = $this->model_operator_operator->getTotalBanIpsByIp($result['ip']);

            $data['ips'][] = array(
                'ip' => $result['ip'],
                'total' => $this->model_operator_operator->getTotalOperatorsByIp($result['ip']),
                'date_added' => date('d/m/y', strtotime($result['date_added'])),
                'filter_ip' => $this->url->link('operator/operator', 'token=' . $this->session->data['token'] . '&filter_ip=' . $result['ip'], 'SSL'),
                'ban_ip' => $ban_ip_total
            );
        }

        $ip_total = $this->model_operator_operator->getTotalIps($this->request->get['operator_id']);

        $pagination = new Pagination();
        $pagination->total = $ip_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('operator/operator/ip', 'token=' . $this->session->data['token'] . '&operator_id=' . $this->request->get['operator_id'] . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($ip_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($ip_total - 10)) ? $ip_total : ((($page - 1) * 10) + 10), $ip_total, ceil($ip_total / 10));

        $this->response->setOutput($this->load->view('operator/operator_ip.tpl', $data));
    }

    public function addBanIp()
    {
        $this->load->language('operator/operator');

        $json = array();

        if (isset($this->request->post['ip'])) {
            if (!$this->user->hasPermission('modify', 'operator/operator')) {
                $json['error'] = $this->language->get('error_permission');
            } else {
                $this->load->model('operator/operator');

                $this->model_operator_operator->addBanIp($this->request->post['ip']);

                $json['success'] = $this->language->get('text_success');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function removeBanIp()
    {
        $this->load->language('operator/operator');

        $json = array();

        if (isset($this->request->post['ip'])) {
            if (!$this->user->hasPermission('modify', 'operator/operator')) {
                $json['error'] = $this->language->get('error_permission');
            } else {
                $this->load->model('operator/operator');

                $this->model_operator_operator->removeBanIp($this->request->post['ip']);

                $json['success'] = $this->language->get('text_success');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email'])) {
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_email'])) {
                $filter_email = $this->request->get['filter_email'];
            } else {
                $filter_email = '';
            }

            $this->load->model('operator/operator');

            $filter_data = array(
                'filter_name' => $filter_name,
                'filter_email' => $filter_email,
                'start' => 0,
                'limit' => 5
            );

            $results = $this->model_operator_operator->getOperators($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'operator_id' => $result['operator_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'firstname' => $result['firstname'],
                    'lastname' => $result['lastname'],
                    'email' => $result['email'],
                    'telephone' => $result['telephone'],
                    'designation' => $result['designation'],
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
