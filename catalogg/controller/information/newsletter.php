<?php

class ControllerInformationNewsletter extends Controller
{
    private $error = array();

    public function index() {
        $json = array();
        $this->load->language('information/newsletter');

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
            $this->load->model("information/newsletter");
            $json = $this->model_information_newsletter->addNewsletter($this->request->post['email']);
            $json['success'] = $this->language->get('text_success');
        } else {
            $json['error'] = $this->error['email'] ?? '';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate() {
        $this->load->language('information/newsletter');

        if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
            $this->error['email'] = $this->language->get('error_email');
        }

        $this->load->model("information/newsletter");
        if ($this->model_information_newsletter->getTotalNewsletterSubscriberByEmail($this->request->post['email'])) {
            $this->error['email'] = $this->language->get('error_exists');
        }

        return !$this->error;
    }
}

?>
