<?php

class ControllerFormEventParticipant extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('form/event_participant');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('form/event_participant');

        $this->getList();
    }

    public function delete() {
        $this->load->language('form/event_participant');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('form/event_participant');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $event_participant_id) {
                $this->model_form_event_participant->deleteEventParticipant($event_participant_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_email'])) {
                $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
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

            $this->response->redirect($this->url->link('form/event_participant', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }


    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'form/event_participant')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function getList()
    {
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
            'href' => $this->url->link('form/event_participant', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $data['export'] = $this->url->link('form/event_participant/export', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('form/event_participant/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['event_participants'] = array();

        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_email' => $filter_email,
            'filter_date_added' => $filter_date_added,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $event_participant_total = $this->model_form_event_participant->getTotalEventParticipants($filter_data);

        $results = $this->model_form_event_participant->getEventParticipants($filter_data);

        foreach ($results as $result) {
            $data['event_participants'][] = array(
                'event_participant_id' => $result['event_participant_id'],
                'full_name' => $result['full_name'],
                'email' => $result['email'],
                'phone' => $result['phone'],
                'university' => $result['university'],
                'student_id' => $result["student_id"],
                'is_want_to_experience' => ($result['is_want_to_experience'] ? "Yes" : "No"),
                'is_want_to_play' => ($result['is_want_to_play'] ? "Yes" : "No"),
                'game' => $result["game"],
                'gamer_type' => $result["gamer_type"],
                'is_participant_played_before' => $result["is_participant_played_before"],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['column_id'] = $this->language->get('column_id');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_email'] = $this->language->get('column_email');
        $data['column_phone'] = $this->language->get('column_phone');
        $data['column_university'] = $this->language->get('column_university');
        $data['column_vr_experience'] = $this->language->get('column_vr_experience');
        $data['column_is_want_to_play'] = $this->language->get('column_is_want_to_play');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_phone'] = $this->language->get('entry_email');
        $data['entry_date_added'] = $this->language->get('entry_date_added');

        $data['button_export'] = $this->language->get('button_export');
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

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
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

        $data['sort_name'] = $this->url->link('form/event_participant', 'token=' . $this->session->data['token'] . '&sort=c.full_name' . $url, 'SSL');
        $data['sort_email'] = $this->url->link('form/event_participant', 'token=' . $this->session->data['token'] . '&sort=c.email' . $url, 'SSL');
        $data['sort_phone'] = $this->url->link('form/event_participant', 'token=' . $this->session->data['token'] . '&sort=c.phone' . $url, 'SSL');
        $data['sort_university'] = $this->url->link('form/event_participant', 'token=' . $this->session->data['token'] . '&sort=c.university' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('form/event_participant', 'token=' . $this->session->data['token'] . '&sort=c.date_added' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
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
        $pagination->total = $event_participant_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('form/event_participant', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($event_participant_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($event_participant_total - $this->config->get('config_limit_admin'))) ? $event_participant_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $event_participant_total, ceil($event_participant_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_email'] = $filter_email;
        $data['filter_date_added'] = $filter_date_added;


        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('form/event_participant_list.tpl', $data));
    }

    public function export() {
        $arrayToCsvLine = function(array $values) {
            $line = '';

            $values = array_map(function ($v) {
                return '"' . str_replace('"', '""', $v) . '"';
            }, $values);

            $line .= implode(',', $values);

            return $line;
        };
        $str = "id,Name,Email,Phone,University,Student ID,Is participant want to experience VR,Is participant in tournament, Game, Have Played Before, Gamer Type, How did participant Know".PHP_EOL;
        $this->load->model('form/event_participant');
        $filter_data = array(
            'start' => 0,
            'limit' => 1000000
        );
        $results = $this->model_form_event_participant->getEventParticipants($filter_data);
        foreach ($results as $result) {
            $str .= $arrayToCsvLine([
                    $result['event_participant_id'],
                    $result['full_name'],
                    $result['email'],
                    $result['phone'],
                    $result["university"],
                    $result["student_id"],
                    ($result['is_want_to_experience'] ? "Yes" : "No"),
                    ($result['is_want_to_play'] ? "Yes" : "No"),
                    $result["game"],
                    $result["is_participant_played_before"],
                    $result["gamer_type"],
                    $result["how_did_participant_know"]
                ]).PHP_EOL;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="event_participant.csv"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        echo($str);

    }
}
