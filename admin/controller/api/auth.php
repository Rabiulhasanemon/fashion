<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 7/13/2020
 * Time: 11:57 AM
 */

class ControllerApiAuth extends Controller {
    private $error = array();

    public function index() {
        $redirect_url = isset($this->request->get["redirect_url"]) ? $this->request->get["redirect_url"] : "";

        if (!$this->user->isLogged() || !isset($this->session->data['token']) || !isset($this->request->get['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
            $this->session->data['redirect_url'] = $redirect_url;
            return new Action('common/login');
        }

        $this->load->model('user/user');

        $tokens = $this->model_user_user->getTempAuthToken($this->user->getId());
        $redirect_url = $this->session->data['redirect_url'];
        unset($this->session->data['redirect_url']);
        $this->response->redirect($redirect_url . "?refresh_token=" . $tokens['refresh_token']);

    }

    public function token() {
        $username = isset($this->request->server['PHP_AUTH_USER']) ? $this->request->server['PHP_AUTH_USER'] : '';
        $token = isset($this->request->server['PHP_AUTH_PW']) ? $this->request->server['PHP_AUTH_PW'] : '';
        $json = array();
        if($this->user->loginByRefreshToken($username, $token)) {
            $this->load->model('user/user');
            $json = $this->model_user_user->getAuthToken($this->user->getId());
        } else {
            $json['username'] = $username;
            $json['token'] = $token;
            $json['type'] = "error";
            $json['error'] = "invalid_refresh_token";
            $json['message'] = "Invalid Access";
        }

        $this->log->write(json_encode($json));

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}