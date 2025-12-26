<?php
class ModelBlogComment extends Model {


	public function addComment($article_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "blog_comment SET author = '" . $this->db->escape($data['name']) . "', ".
			"customer_id = '" . (int)$this->customer->getId() . "', article_id = '" . (int)$article_id . "', ".
			"text = '" . $this->db->escape($data['text']) . "', date_added = NOW()");

		$comment_id = $this->db->getLastId();

		if ($this->config->get('config_comment_mail')) {
			$this->load->language('mail/comment');
			$this->load->model('blog/article');
			
			$article_info = $this->model_blog_article->getArticle($article_id);

			$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

			$message  = $this->language->get('text_waiting') . "\n";
			$message .= sprintf($this->language->get('text_article'), html_entity_decode($article_info['name'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= sprintf($this->language->get('text_commenter'), html_entity_decode($data['name'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= $this->language->get('text_comment') . "\n";
			$message .= html_entity_decode($data['text'], ENT_QUOTES, 'UTF-8') . "\n\n";

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->send();

			// Send to additional alert emails
			$emails = explode(',', $this->config->get('config_mail_alert'));

			foreach ($emails as $email) {
				if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}
	}

	public function getCommentsByArticleId($article_id, $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		$query = $this->db->query("SELECT r.comment_id, r.author, r.text, p.article_id, pd.name, p.image, r.date_added FROM " . DB_PREFIX . "blog_comment r LEFT JOIN " .
			DB_PREFIX . "blog_article p ON (r.article_id = p.article_id) LEFT JOIN " . DB_PREFIX . "blog_article_description pd ON (p.article_id = pd.article_id) WHERE p.article_id = '" .
			(int)$article_id . "' AND p.status = '1' AND r.status = '1' AND pd.language_id = '" .
			(int)$this->config->get('config_language_id') .
			"' ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalCommentsByArticleId($article_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_comment r LEFT JOIN " .
			DB_PREFIX . "blog_article p ON (r.article_id = p.article_id) LEFT JOIN " . DB_PREFIX . "blog_article_description pd ON (p.article_id = pd.article_id) WHERE p.article_id = '" .
			(int)$article_id . "' AND p.status = '1' AND r.status = '1' AND pd.language_id = '" .
			(int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}
}