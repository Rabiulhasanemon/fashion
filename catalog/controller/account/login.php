<?php 

class ControllerAccountLogin extends Controller
{
    private $error = array();

    public function index() {
        $this->load->model('account/customer');

        // Login override for admin users
        if (!empty($this->request->get['token'])) {
            $this->event->trigger('pre.customer.login');

            $this->customer->logout();
            $this->cart->clear();

            unset($this->session->data['wishlist']);
            unset($this->session->data['payment_address']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['shipping_address']);
            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['comment']);
            unset($this->session->data['order_id']);
            unset($this->session->data['coupon']);
            unset($this->session->data['reward']);
            unset($this->session->data['voucher']);
            unset($this->session->data['vouchers']);

            $customer_info = $this->model_account_customer->getCustomerByToken($this->request->get['token']);

            if ($customer_info && $this->customer->login($customer_info['email'], '', true)) {
                // Default Addresses
                $this->load->model('account/address');

                if ($this->config->get('config_tax_customer') == 'payment') {
                    $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                }

                if ($this->config->get('config_tax_customer') == 'shipping') {
                    $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                }

                $this->event->trigger('post.customer.login');

                $this->response->redirect($this->url->link('account/account', '', 'SSL'));
            }
        }

        if ($this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/account', '', 'SSL'));
        }


        $this->load->language('account/login');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            unset($this->session->data['guest']);

            // Default Shipping Address
            $this->load->model('account/address');

            if ($this->config->get('config_tax_customer') == 'payment') {
                $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            if ($this->config->get('config_tax_customer') == 'shipping') {
                $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = array(
                'customer_id' => $this->customer->getId(),
                'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
            );

            $this->model_account_activity->addActivity('login', $activity_data);

            if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
                unset($this->session->data['redirect']);
                $this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
            } else {
                $this->response->redirect($this->url->link('account/account', '', 'SSL'));
            }
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_login'),
            'href' => $this->url->link('account/login', '', 'SSL')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_new_customer'] = $this->language->get('text_new_customer');
        $data['text_register'] = $this->language->get('text_register');
        $data['text_register_account'] = $this->language->get('text_register_account');
        $data['text_returning_customer'] = $this->language->get('text_returning_customer');
        $data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
        $data['text_forgotten'] = $this->language->get('text_forgotten');

        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_password'] = $this->language->get('entry_password');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_login'] = $this->language->get('button_login');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['action'] = $this->url->link('account/login', '', 'SSL');
        $data['register'] = $this->url->link('account/register/init', '', 'SSL');
        $data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');


        if(SOCIAL_LOGIN) {
              $data['fb_login_url'] = $this->getFBLoginUrl();
              $data['google_login_url'] = $this->getGoogleLoginUrl();
        } else {
              $data['fb_login_url'] = null;
              $data['google_login_url'] = null;
        }

        if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
            $data['redirect'] = $this->request->post['redirect'];
        } elseif (isset($this->session->data['redirect'])) {
            $data['redirect'] = $this->session->data['redirect'];
        } else {
            $data['redirect'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['username'])) {
            $data['username'] = $this->request->post['username'];
        } else {
            $data['username'] = '';
        }

        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } else {
            $data['password'] = '';
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/login.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/login.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/login.tpl', $data));
        }
    }

    protected function validate() {
        if (!isset($this->request->post['username']) || !isset($this->request->post['password'])) {
            $this->error['warning'] = $this->language->get('error_login');
            return false;
        }

        $this->event->trigger('pre.customer.login');

        // Check how many login attempts have been made.
        $login_info = $this->model_account_customer->getLoginAttempts($this->request->post['username']);

        if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
            $this->error['warning'] = $this->language->get('error_attempts');
        }

        // Check if customer has been approved.
        if(preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['username'])) {
            $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['username']);
        } else {
            $customer_info = $this->model_account_customer->getCustomerByTelephone($this->request->post['username']);
        }

        if ($customer_info && !$customer_info['approved']) {
            $this->error['warning'] = $this->language->get('error_approved');
        }

        if (!$this->error) {
            if (!$this->customer->login($this->request->post['username'], $this->request->post['password'])) {
                $this->error['warning'] = $this->language->get('error_login');

                $this->model_account_customer->addLoginAttempt($this->request->post['username']);
            } else {
                $this->model_account_customer->deleteLoginAttempts($this->request->post['username']);

                $this->event->trigger('post.customer.login');
            }
        }

        return !$this->error;
    }


    private function getFBLoginUrl() {
        try {
            $fb = new Facebook\Facebook([
                'app_id' => FB_APP_ID,
                'app_secret' => FB_APP_SECRET,
                'default_graph_version' => 'v3.2',
            ]);

            $helper = $fb->getRedirectLoginHelper();

            $permissions = ['email']; // Optional permissions
            $callbackUrl = htmlspecialchars($this->url->link("account/login/fb_callback", '', 'SSL'));
            return $helper->getLoginUrl($callbackUrl, $permissions);
        } catch(Exception $ex) {
            return null;
        }
    }   

    public function fb_callback() {
        $fb = new Facebook\Facebook([
            'app_id' => FB_APP_ID, // Replace {app-id} with your app id
            'app_secret' => FB_APP_SECRET,
            'default_graph_version' => 'v3.2',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
            $fb->setDefaultAccessToken($accessToken->getValue());
            $response = $fb->get('/me?locale=en_US&fields=name,email');
            $user_profile = $response->getGraphUser();

            $email = $user_profile->getField('email');
            $name = $user_profile->getField('name');
        } catch(Exception $e) {
           $name = null;
           $email = null;
        }
        if (!$email) {
            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }
        $this->load->model("account/customer");
        $customer_info = $this->model_account_customer->getCustomerByEmail($email);
        if(!$customer_info) {
              $this->model_account_customer->addCustomerByEmail($email, $name);
        }
        $this->customer->login($email, null, true);

        if (isset($this->session->data['redirect'])) {
            $redirect = $this->session->data['redirect'];
            unset($this->session->data['redirect']);
        } else {
            $redirect = $this->url->link('account/account', '', 'SSL');
        }
        $this->response->redirect($redirect);
    }

    private function getGoogleLoginUrl() {
        try {
            // init configuration
            $clientID = GOOGLE_CLIENT_ID;
            $clientSecret = GOOGLE_CLIENT_SECRET;
            $callbackUrl = htmlspecialchars($this->url->link("account/login/google_callback", '', 'SSL'));

            // create Client Request to access Google API
            $client = new Google\Client();
            $client->setClientId($clientID);
            $client->setClientSecret($clientSecret);
            $client->setRedirectUri($callbackUrl);
            $client->addScope("email");
            $client->addScope("profile");

            return $client->createAuthUrl();

        } catch(Exception $ex) {
            return null;
        }

    }

    public function google_callback(){                
        
        // create Client Request to access Google API
        // init configuration
        $clientID = '595783581942-7guui6o500aom6knksgq8isbsr6lbrc2.apps.googleusercontent.com';
        $clientSecret = 'KB8U_enr6Ag16LrDOT5LO9ku';
        $callbackUrl = htmlspecialchars($this->url->link("account/login/google_callback", '', 'SSL'));            
        
        // create Client Request to access Google API
        $client = new Google\Client();
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($callbackUrl);
        $client->addScope("email");
        $client->addScope("profile");
        

        try {
            
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($token['access_token']);
            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();
            $email =  $google_account_info->email;
            $name =  $google_account_info->name;
        } catch(Exception $e) {
           $name = null;
           $email = null;
        }
        if (!$email) {
             $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }
        $this->load->model("account/customer");
        $customer_info = $this->model_account_customer->getCustomerByEmail($email);
        if(!$customer_info) {
           $this->model_account_customer->addCustomerByEmail($email, $name);
        }
        $this->customer->login($email, null, true);

        if (isset($this->session->data['redirect'])) {
            $redirect = $this->session->data['redirect'];
            unset($this->session->data['redirect']);
        } else {
            $redirect = $this->url->link('account/account', '', 'SSL');
        }
         $this->response->redirect($redirect);
    }
}