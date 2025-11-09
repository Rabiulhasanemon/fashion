<?php
class ControllerModuleArticle extends Controller {

    private function getArticleData($article_info, $setting) {
        if ($article_info['thumb']) {
            $image = $this->model_tool_image->resize($article_info['thumb'], $setting['width'], $setting['height']);
        } else if ($article_info['image']) {
            $image = $this->model_tool_image->resize($article_info['image'], $setting['width'], $setting['height']);
        } else {
            $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
        }

        if ($article_info['image']) {
            $featured_image = $this->model_tool_image->resize($article_info['image'], $setting['featured_width'], $setting['featured_height']);
        } else {
            $featured_image = $this->model_tool_image->resize('placeholder.png', $setting['featured_width'], $setting['featured_height']);
        }

        $date_published = new DateTime($article_info['date_published']);
        $interval = (new DateTime())->diff($date_published);

        if ($interval->m && $interval->d) {
            $format = "%m " . $this->language->get("text_month") . " %d " . $this->language->get("text_day");
        } elseif ($interval->m) {
            $format = "%m " . $this->language->get("text_month");
        } elseif ($interval->d && $interval->h) {
            $format = "%a " . $this->language->get("text_day") . " %h " . $this->language->get("text_hour");
        } elseif ($interval->d) {
            $format = "%a " . $this->language->get("text_day");
        } elseif ($interval->h && $interval->i) {
            $format = "%h " . $this->language->get("text_hour") . " %i " . $this->language->get("text_minute");
        } else {
            $format = "%i " . $this->language->get("text_minute");
        }
        $elapsed = $interval->format($format . " " . $this->language->get("text_ago"));

        return array(
            'article_id'    => $article_info['article_id'],
            'thumb'         => $image,
            'featured_image'=> $featured_image,
            'name'          => $article_info['name'],
            'video_icon'    => $article_info['video_icon'],
            'description'   => utf8_substr(trim(strip_tags(html_entity_decode($article_info['description'], ENT_QUOTES, 'UTF-8'))), 0, 150) . '..',
            'reporter'      => $article_info['reporter'],
            'intro_text'    =>  $article_info['intro_text'] ?: utf8_substr(trim(strip_tags(html_entity_decode($article_info['description'], ENT_QUOTES, 'UTF-8'))), 0, 150) . '..',
            'date_published'=> $this->translator->number($elapsed),
            'href'          => $this->url->link('blog/article', 'article_id=' . $article_info['article_id'])
        );


    }

    public function index($setting) {
        $this->load->language('module/article');

        $data['heading_title'] = $this->language->get('heading_title');
        $this->load->model('blog/article');

        $this->load->model('tool/image');

        $data['articles'] = array();
        $data['name'] = $setting['name'];
        $data['blurb'] = $setting['blurb'];
        $data['class'] = $setting['class'];

        $data['show_title'] = $setting['show_title'];
        if($setting['category_id']) {
            $this->load->model('blog/category');
            $data["see_all"] = $this->url->link('blog/category', 'blog_category_id=' . $setting['category_id']);
        } else {
            $data["see_all"] = "";
        }
        $data["button_read_more"] = $this->language->get('button_read_more');

        if (!$setting['limit']) {
            $setting['limit'] = 3;
        }

        if(!$setting['filter']) {
            $setting['filter'] = "latest";
        }

        if($setting['filter'] == "latest" || $setting['filter'] == "featured") {
            $filter_data = array(
                'filter_category_id' => isset($setting['category_id']) ? $setting['category_id'] : null,
            );

            if($setting['filter'] == "featured") {
                $article_info = $this->model_blog_article->getArticle($this->config->get("article_lead"));
                if ($article_info) {
                    $data['articles'][] = $this->getArticleData($article_info, $setting);
                }
                $filter_data['filter_featured'] = true;
                $filter_data['sort'] = "a.featured_order";
                $filter_data['limit'] = $article_info ? $setting['limit'] - 1 : $setting['limit'];
            }

            else {
                $filter_data['sort'] = isset($setting['sort']) && $setting['sort'] == 'custom' ? 'a2c.top DESC, a2c.sort_order' : 'a.sort_order';
                $filter_data['limit'] = $setting['limit'];

            }

            $articles = $this->model_blog_article->getArticles($filter_data);
            foreach ($articles as $article_info) {
                $data['articles'][] = $this->getArticleData($article_info, $setting);
            }
        } else if (!empty($setting['article'])) {
            $articles = array_slice($setting['article'], 0, (int)$setting['limit']);
            foreach ($articles as $article_id) {
                $article_info = $this->model_blog_article->getArticle($article_id);
                if ($article_info) {
                    $data['articles'][] = $this->getArticleData($article_info,$setting);
                }
            }
        }
        else if($setting['filter'] == "popular") {

            $data['articles'] = array();
            $filter_data['limit'] = $setting['limit'];

            $results = $this->model_blog_article->getPopularArticles($filter_data);
            if ($results) {
                foreach ($results as $article_info) {
                    $data['articles'][] = $this->getArticleData($article_info, $setting);
                }
            }
        }


        if(isset($setting['ads_position_id']) && $setting['ads_position_id']) {
            $this->load->model('design/ads');
            $data['ads'] = $this->model_design_ads->getAdsByPositionId($setting['ads_position_id']);
        } else {
            $data['ads'] = null;
        }

        if (!$data['articles']) {
            return null;
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/article_' . $setting['view'] . '.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/article_' . $setting['view'] . '.tpl', $data);
        } else if (file_exists(DIR_TEMPLATE . 'default/template/module/article_' . $setting['view'] . '.tpl')) {
            return $this->load->view('default/template/module/article_' . $setting['view'] . '.tpl', $data);
        } else {
            return $this->load->view('default/template/module/article.tpl', $data);
        }
    }
}