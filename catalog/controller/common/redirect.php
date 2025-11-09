<?php
class ControllerCommonRedirect extends Controller {
	public function index() {
        $this->load->model("catalog/permanent_redirect");
        if(isset($_REQUEST['_route_'])) {
            $permanent_redirect = $this->model_catalog_permanent_redirect->getPermanentRedirect($_REQUEST['_route_']);
        } else {
            $permanent_redirect = $this->model_catalog_permanent_redirect->getPermanentRedirect($this->request->server["REQUEST_URI"]);
        }

        if($permanent_redirect) {
            $this->response->redirect($permanent_redirect['new_url'], 301);
        }
	}
}
