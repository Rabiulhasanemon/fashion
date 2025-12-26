<?php
class ControllerFormEventRegister extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('form/event_register');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('form/event_participant');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$event_participant_id  = $this->model_form_event_participant->addEventParticipant($this->request->post);
            $this->session->data["event_participant_id"] = $this->request->post["university"] . "-" . $event_participant_id;
            $this->response->redirect($this->url->link('form/event_register/success'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_register'),
			'href' => $this->url->link('form/event_register', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_confirm'] = $this->language->get('entry_confirm');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_upload'] = $this->language->get('button_upload');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['full_name'])) {
			$data['error_full_name'] = $this->error['full_name'];
		} else {
			$data['error_full_name'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['phone'])) {
			$data['error_phone'] = $this->error['phone'];
		} else {
			$data['error_phone'] = '';
		}

		if (isset($this->error['error_participation'])) {
			$data['error_participation'] = $this->error['error_participation'];
		} else {
			$data['error_participation'] = '';
		}

		if (isset($this->error['game'])) {
			$data['error_game'] = $this->error['game'];
		} else {
			$data['error_game'] = '';
		}

		if (isset($this->error['gamer_type'])) {
			$data['error_gamer_type'] = $this->error['gamer_type'];
		} else {
			$data['error_gamer_type'] = '';
		}

		if (isset($this->error['how_did_participant_know'])) {
			$data['error_how_did_participant_know'] = $this->error['how_did_participant_know'];
		} else {
			$data['error_how_did_participant_know'] = '';
		}

		if (isset($this->error['university'])) {
			$data['error_university'] = $this->error['university'];
		} else {
			$data['error_university'] = '';
		}

		if (isset($this->error['student_id'])) {
			$data['error_student_id'] = $this->error['student_id'];
		} else {
			$data['error_student_id'] = '';
		}

		if (isset($this->error['is_participant_played_before'])) {
			$data['error_is_participant_played_before'] = $this->error['is_participant_played_before'];
		} else {
			$data['error_is_participant_played_before'] = '';
		}

		$data['action'] = $this->url->link('form/event_register', '', 'SSL');


		if (isset($this->request->post['full_name'])) {
			$data['full_name'] = $this->request->post['full_name'];
		} else {
			$data['full_name'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['phone'])) {
			$data['phone'] = $this->request->post['phone'];
		} else {
			$data['phone'] = '';
		}

		if (isset($this->request->post['university'])) {
			$data['university'] = $this->request->post['university'];
		} else {
			$data['university'] = '';
		}

		if (isset($this->request->post['student_id'])) {
			$data['student_id'] = $this->request->post['student_id'];
		} else {
			$data['student_id'] = '';
		}

		if (isset($this->request->post['is_want_to_experience'])) {
			$data['is_want_to_experience'] = $this->request->post['is_want_to_experience'];
		} else {
			$data['is_want_to_experience'] = '';
		}

		if (isset($this->request->post['is_want_to_play'])) {
			$data['is_want_to_play'] = $this->request->post['is_want_to_play'];
		} else {
			$data['is_want_to_play'] = '';
		}

        if (isset($this->request->post['game'])) {
            $data['game'] = $this->request->post['game'];
        } else {
            $data['game'] = '';
        }

        if (isset($this->request->post['game_type'])) {
            $data['gamer_type'] = $this->request->post['gamer_type'];
        } else {
            $data['gamer_type'] = '';
        }

        if (isset($this->request->post['is_participant_played_before'])) {
            $data['is_participant_played_before'] = $this->request->post['is_participant_played_before'];
        } else {
            $data['is_participant_played_before'] = '';
        }

        if (isset($this->request->post['how_did_participant_know'])) {
            $data['how_did_participant_know'] = $this->request->post['how_did_participant_know'];
        } else {
            $data['how_did_participant_know'] = '';
        }

        $this->load->model('catalog/information');
        $information_info = $this->model_catalog_information->getInformation($this->config->get('config_form_id'));
        $data['text_agree'] = $this->language->get('text_agree');


		if (isset($this->request->post['agree'])) {
			$data['agree'] = $this->request->post['agree'];
		} else {
			$data['agree'] = true;
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/form/register.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/form/register.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/form/register.tpl', $data));
		}
	}

	public function validate() {
		if ((utf8_strlen(trim($this->request->post['full_name'])) < 1) || (utf8_strlen(trim($this->request->post['full_name'])) > 50)) {
			$this->error['full_name'] = $this->language->get('error_full_name');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ($this->model_form_event_participant->getTotalEventParticipantByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_exists');
		}

		if ((utf8_strlen($this->request->post['email']) < 11) || !preg_match('/^(016|017|018|015|019|011|013)[0-9]{8}$/i', $this->request->post['phone'])) {
            $this->error['phone'] = $this->language->get('error_phone');
        }

        if ($this->model_form_event_participant->getTotalEventParticipantByPhone($this->request->post['phone'])) {
            $this->error['warning'] = $this->language->get('error_phone_exists');
        }

        $university = array("NSU", "EWU", "IUB");
        if (!in_array($this->request->post['university'], $university)) {
            $this->error['university'] = $this->language->get('error_university');
        }

        if ((utf8_strlen(trim($this->request->post['student_id'])) < 1) || (utf8_strlen(trim($this->request->post['student_id'])) > 50)) {
            $this->error['student_id'] = $this->language->get('error_student_id');
        }

        if(!isset($this->request->post["is_want_to_experience"]) && !isset($this->request->post["is_want_to_play"]))  {
            $this->error['error_participation'] = $this->language->get('error_participation');
        }

        $game = array("MW", "FIFA2018");
        if (!in_array($this->request->post['game'], $game)) {
            $this->error['game'] = $this->language->get('error_game');
        }

        $gamer_type = array("AMATEUR", "PRO", "MEDIUM");
        if (!in_array($this->request->post['gamer_type'], $gamer_type)) {
            $this->error['gamer_type'] = $this->language->get('error_gamer_type');
        }

        $how_did_participant_know = array("SOCIAL_MEDIA", "SMS", "WEB", "UNIVERSITY_EVENT", "FROM_FRIENDS");
        if (!in_array($this->request->post['how_did_participant_know'], $how_did_participant_know)) {
            $this->error['how_did_participant_know'] = $this->language->get('error_how_did_participant_know');
        }

        $is_participant_played_before = array("YES", "NO");
        if (!in_array($this->request->post['is_participant_played_before'], $is_participant_played_before)) {
            $this->error['is_participant_played_before'] = $this->language->get('error_is_participant_played_before');
        }

        if (!isset($this->request->post['agree'])) {
            $this->error['warning'] = $this->language->get('error_agree');
        }

		return !$this->error;
	}

	function success() {
        $this->load->language('form/event_register');


        $this->document->setTitle($this->language->get('heading_title_success'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_success'),
            'href' => $this->url->link('checkout/success')
        );

        $data['heading_title'] = $this->language->get('heading_title');
        $data['event_participant_id'] = isset($this->session->data["event_participant_id"]) ? $this->session->data["event_participant_id"] : 0;

        $data['button_continue'] = $this->language->get('button_continue');

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/form/success.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/form/success.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/form/success.tpl', $data));
        }
    }
}