<?php
class ModelFormEventParticipant extends Model {
	public function addEventParticipant($data) {
	    $is_want_to_play = isset($data['is_want_to_play']) && $data['is_want_to_play'] == "true" ? 1 : 0;
		$this->db->query("INSERT INTO " . DB_PREFIX . "event_participant SET full_name = '" . $this->db->escape($data['full_name'])
            . "', email = '" . $this->db->escape($data['email'])
            . "', phone = '" . $this->db->escape($data['phone'])
            . "', university = '" . $this->db->escape($data['university'])
            . "', student_id = '" . $this->db->escape($data['student_id'])
            . "', is_want_to_experience = '" . (isset($data['is_want_to_experience']) && $data['is_want_to_experience'] == "true" ? 1 : 0)
            . "', is_want_to_play = '" . $is_want_to_play
            . "', game = '" . ($is_want_to_play ? $data['game'] : "")
            . "', is_participant_played_before = '" . ($is_want_to_play ? $data['is_participant_played_before'] : "")
            . "', gamer_type = '" . ($is_want_to_play ? $data['gamer_type'] : "")
            . "', how_did_participant_know = '" . $data['how_did_participant_know']
            . "', date_added = NOW()");

		$event_participant_id = $this->db->getLastId();


        $subject = "ROG Kick N' Drive Gaming Tournament Registration";
        $date = array(
            'IUB' => '12 NOV, 2018',
            'NSU' => '14 NOV, 2018',
            'EWU' => '18 NOV, 2018'
        );

        $message =  "Thank you for registering to our event. Your registration Number: " . $data['university'] . "-" . $event_participant_id . " . Please show this registration Number to collect your participation ID from your university at " . $date[$data['university']] . "\n\n";
        $message .= "Please Note: Seats are limited, we will provide and confirm the participation ID on first come first serve basis.\n\n";
        $message .= "Show this email to Branch while buying your ASUS / ROG laptop to get a free Bluetooth speaker [Validity: 15 December,2018]\n\n";

        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $mail->setTo($data['email']);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
        $mail->setSubject($subject);
        $mail->setText($message);
        $mail->send();

		return $event_participant_id;
	}

	public function getTotalEventParticipantByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "event_participant WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		return $query->row;
	}

	public function getTotalEventParticipantByPhone($phone) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "event_participant WHERE phone = '" . $this->db->escape($phone) . "'");
		return $query->row;
	}


}
