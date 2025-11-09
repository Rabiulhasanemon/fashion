<?php

class ControllerCommonSeoUrl extends Controller
{
    public function index() {
        $this->url->addRewrite($this);
        if (isset($this->request->get['_route_'])) {
            $parts = explode('/', $this->request->get['_route_']);
            if (utf8_strlen(end($parts)) == 0) {
                array_pop($parts);
            }

            $this->coreDecoder($parts);

            if (!isset($this->request->get['route'])) {
                if(count($parts) > 1) {
                    $this->request->get['route'] = implode("/", $parts);
                } else {
                    $this->request->get['route'] = 'error/not_found';
                }
            }
            if (isset($this->request->get['route'])) {
                return new Action($this->request->get['route']);
            }
        }
    }

    public function coreDecoder($parts) {
        foreach ($parts as $part) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");
            if ($query->num_rows) {
                $url = explode('=', $query->row['query']);
                if ($url[0] == 'product_id') {
                    $this->request->get['product_id'] = $url[1];
                } elseif ($url[0] == 'category_id') {
                    $this->request->get['category_id'] = $url[1];
                } elseif ($url[0] == 'manufacturer_id') {
                    $this->request->get['manufacturer_id'] = $url[1];
                } elseif ($url[0] == 'information_id') {
                    $this->request->get['information_id'] = $url[1];
                } elseif ($url[0] == 'route' && isset($url[1])) {
                    // Support custom routes like route=common/big_offer
                    $this->request->get['route'] = $url[1];
                }  elseif ($url[0] == 'article_id') {
                    $this->request->get['article_id'] = $url[1];
                } elseif ($url[0] == 'blog_category_id') {
                    $this->request->get['blog_category_id'] = $url[1];
                }
            } else {
                return;
            }
        }

        if (!isset($this->request->get['route'])) {
            if (isset($this->request->get['product_id'])) {
                $this->request->get['route'] = 'product/product';
            } elseif (isset($this->request->get['category_id'])) {
                $this->request->get['route'] = 'product/category';
            } elseif (isset($this->request->get['manufacturer_id'])) {
                $this->request->get['route'] = 'product/manufacturer/info';
            } elseif (isset($this->request->get['information_id'])) {
                $this->request->get['route'] = 'information/information';
            } elseif (isset($this->request->get['article_id'])) {
                $this->request->get['route'] = 'blog/article';
            } elseif (isset($this->request->get['blog_category_id'])) {
                $this->request->get['route'] = 'blog/category';
            }
        }
    }

    public function blogDecoder($parts) {
        array_shift($parts);
        foreach ($parts as $part) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_url_alias WHERE keyword = '" . $this->db->escape($part) . "'");
            if ($query->num_rows) {
                $url = explode('=', $query->row['query']);

            } else {
                return;
            }
        }

        if (!isset($this->request->get['route'])) {
            if(count($parts) == 0) {
                $this->request->get['route'] = 'blog/blog';
            } elseif (isset($this->request->get['article_id'])) {
                $this->request->get['route'] = 'blog/article';
            } elseif (isset($this->request->get['blog_category_id'])) {
                $this->request->get['route'] = 'blog/category';
            }
        }
    }


    public function rewrite($url_info) {
        $data = array();
        parse_str($url_info['query'], $data);
        $route = $url_info["route"];
        $url = $this->coreRewrite($route, $data);
        if ($url) {
            $query = '';
            if ($data) {
                foreach ($data as $key => $value) {
                    $query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((string)$value);
                }

                if ($query) {
                    $query = '?' . str_replace('&', '&amp;', trim($query, '&'));
                }
            }

            return $url_info["host_path"] . $url . $query;
        } else {
            return null;
        }
    }

    function coreRewrite($route, &$data) {
        $url = '';
        if ($route == 'common/home') {
            return "/";
        }
        foreach ($data as $key => $value) {
            if (($route == 'product/product' && $key == 'product_id') || ($route == 'product/category' && ($key == 'category_id' || $key == 'manufacturer_id')) || ($route == 'product/manufacturer/info' && $key == 'manufacturer_id') || ($route == 'information/information' && $key == 'information_id')) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
                if ($query->num_rows && $query->row['keyword']) {
                    $url .= '/' . $query->row['keyword'];
                    unset($data[$key]);
                }
            } elseif ($key == 'path') { // TODO: Deprecated
                $categories = explode('_', $value);
                foreach ($categories as $category) {
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "'");
                    if ($query->num_rows && $query->row['keyword']) {
                        $url .= '/' . $query->row['keyword'];
                    } else {
                        $url = '';
                        break;
                    }
                }
                unset($data[$key]);
            } elseif (($route == 'blog/category' && $key == 'blog_category_id') || ($route == 'blog/article' && $key == 'article_id')) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
                if ($query->num_rows && $query->row['keyword']) {
                    $url .= '/' . $query->row['keyword'];
                    unset($data[$key]);
                }
            } elseif ($key == 'blog_path') {
                $categories = explode('_', $value);
                foreach ($categories as $category) {
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'blog_category_id=" . (int)$category . "'");
                    if ($query->num_rows && $query->row['keyword']) {
                        $url .= '/' . $query->row['keyword'];
                    } else {
                        $url = '';
                        break;
                    }
                }
                unset($data[$key]);
            } elseif ($route == 'common/big_offer') {
                $q = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'route=common/big_offer'");
                if ($q->num_rows && $q->row['keyword']) {
                    $url .= '/' . $q->row['keyword'];
                } else {
                    $url .= '/big-offer';
                }
            }
        }
        return $url;
    }

    function blogRewrite($route, &$data) {
        $url = '';
        foreach ($data as $key => $value) {

        }

        if($url) { $url = "/blog" . $url; }
        return $url;
    }
}
