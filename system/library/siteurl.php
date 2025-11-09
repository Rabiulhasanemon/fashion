<?php
class SiteUrl {
	private $domain;
	private $ssl;
	private $rewrite = array();

	public function __construct($domain, $ssl = '') {
		$this->domain = $domain;
		$this->ssl = $ssl;
	}

	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}

	public function link($route, $args = '', $secure = true) {
	    $url_info = array();
		if (!$secure) {
            $url_info['host_path'] = $this->domain;
		} else {
            $url_info['host_path'] = $this->ssl;
		}
		$url_info['route'] = $route;

        $url_info['query'] = $args ? ltrim($args, '&') : "";

		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url_info);
		}
		if(!isset($url)) {
		    $url = $url_info['host_path'] . "/" . $route . ($args ? "?" . $args : "");

        }
		return $url;
	}
}
