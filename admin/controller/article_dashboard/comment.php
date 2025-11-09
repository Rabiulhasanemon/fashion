<?php
class ControllerArticleDashboardComment extends Controller {
	public function index() {
		$this->load->language('article_dashboard/comment');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_view'] = $this->language->get('text_view');

		$data['token'] = $this->session->data['token'];

		// Total Orders
		$this->load->model('blog/comment');

		// Customers Online
		$comment_total = $this->model_blog_comment->getTotalComments();

		if ($comment_total > 1000000000000) {
			$data['total'] = round($comment_total / 1000000000000, 1) . 'T';
		} elseif ($comment_total > 1000000000) {
			$data['total'] = round($comment_total / 1000000000, 1) . 'B';
		} elseif ($comment_total > 1000000) {
			$data['total'] = round($comment_total / 1000000, 1) . 'M';
		} elseif ($comment_total > 1000) {
			$data['total'] = round($comment_total / 1000, 1) . 'K';
		} else {
			$data['total'] = $comment_total;
		}

		$data['comment'] = $this->url->link('blog/comment', 'token=' . $this->session->data['token'], 'SSL');

		return $this->load->view('article_dashboard/comment.tpl', $data);
	}
}