<?php
class ControllerFeedGoogleSitemap extends Controller {
	public function index() {
		if ($this->config->get('google_sitemap_status')) {
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

			$this->load->model('catalog/product');
			$this->load->model('tool/image');



			$this->load->model('catalog/category');
			$this->load->model('blog/category');
			$this->load->model('blog/article');

			$output .= $this->getCategories(0);
			$output .= $this->getBlogCategories(0);
			$output .= $this->getProducts();
			$output .= $this->getArticles();

			$output .= '</urlset>';

			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($output);
		}
	}

	protected function getCategories($parent_id) {
		$output = '';

		$results = $this->model_catalog_category->getChildren($parent_id);

		foreach ($results as $result) {

			$output .= '<url>';
			$output .= '<loc>' . $result["href"] . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>0.7</priority>';
			$output .= '</url>';
			if(isset($result['category_id'])) {
			    $output .= $this->getCategories($result['category_id']);
            }
		}

		return $output;
	}

	protected function getProducts() {
        $products = $this->model_catalog_product->getProducts();
        $output = '';
        foreach ($products as $product) {
            if ($product['image']) {
                $output .= '<url>';
                $output .= '<loc>' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . '</loc>';
                $output .= '<changefreq>weekly</changefreq>';
                $output .= '<priority>1.0</priority>';
                $output .= '<image:image>';
                $output .= '<image:loc>' . $this->model_tool_image->resize($product['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')) . '</image:loc>';
                $output .= '<image:caption>' . $product['name'] . '</image:caption>';
                $output .= '<image:title>' . $product['name'] . '</image:title>';
                $output .= '</image:image>';
                $output .= '</url>';
            }
        }
        return $output;
    }

    protected function getBlogCategories($parent_id) {
        $output = '';

        $results = $this->model_blog_category->getChildren($parent_id);

        foreach ($results as $result) {

            $output .= '<url>';
            $output .= '<loc>' . $result["href"] . '</loc>';
            $output .= '<changefreq>weekly</changefreq>';
            $output .= '<priority>0.7</priority>';
            $output .= '</url>';
            if(isset($result['category_id'])) {
                $output .= $this->getBlogCategories($result['category_id']);
            }
        }

        return $output;
    }
    protected function getArticles() {
        $articles = $this->model_blog_article->getArticles();
        $output = '';
        foreach ($articles as $article) {
            if ($article['image']) {
                $output .= '<url>';
                $output .= '<loc>' . $this->url->link('blog/article', 'article_id=' . $article['article_id']) . '</loc>';
                $output .= '<changefreq>weekly</changefreq>';
                $output .= '<priority>1.0</priority>';
                $output .= '<image:image>';
                $output .= '<image:loc>' . $this->model_tool_image->resize($article['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')) . '</image:loc>';
                $output .= '<image:caption>' . $article['name'] . '</image:caption>';
                $output .= '<image:title>' . $article['name'] . '</image:title>';
                $output .= '</image:image>';
                $output .= '</url>';
            }
        }
        return $output;
    }
	protected function getManufacturers() {
        $output ='';
        $this->load->model('catalog/manufacturer');

        $manufacturers = $this->model_catalog_manufacturer->getManufacturers();

        foreach ($manufacturers as $manufacturer) {
            $output .= '<url>';
            $output .= '<loc>' . $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']) . '</loc>';
            $output .= '<changefreq>weekly</changefreq>';
            $output .= '<priority>0.7</priority>';
            $output .= '</url>';

            $products = $this->model_catalog_product->getProducts(array('filter_manufacturer_id' => $manufacturer['manufacturer_id']));

            foreach ($products as $product) {
                $output .= '<url>';
                $output .= '<loc>' . $this->url->link('product/product', 'manufacturer_id=' . $manufacturer['manufacturer_id'] . '&product_id=' . $product['product_id']) . '</loc>';
                $output .= '<changefreq>weekly</changefreq>';
                $output .= '<priority>1.0</priority>';
                $output .= '</url>';
            }
        }
        return $output;
    }

	protected function getInformations() {
        $this->load->model('catalog/information');
        $output = '';
        $informations = $this->model_catalog_information->getInformations();

        foreach ($informations as $information) {
            $output .= '<url>';
            $output .= '<loc>' . $this->url->link('information/information', 'information_id=' . $information['information_id']) . '</loc>';
            $output .= '<changefreq>weekly</changefreq>';
            $output .= '<priority>0.5</priority>';
            $output .= '</url>';
        }
        return $output;
    }
}
