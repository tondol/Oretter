<?php

define('LIBRARY_DIR', PHP_DIR . "library/");
ini_set('include_path', ini_get('include_path') . ':' . LIBRARY_DIR);

class Controller {
	protected $parent;
	protected $get;
	protected $post;
	protected $config;
	protected $chain;
	protected $variables;
	
	function __construct(Application $app) {
		$this->app = $app;
		$this->get = &$this->app->get;
		$this->post = &$this->app->post;
		$this->config = &$this->app->config;
		$this->chain = $this->app->chain;
	}
	
	function run() {
		$this->render();
	}
	function render() {
		require_once $this->app->get_template_path();
	}
	
	// set variable by module
	function set($key, $value) {
		$this->variables[$key] = $value;
	}
	function set_by_ref($key, &$value) {
		$this->variables[$key] = &$value;
	}
	// get variable by template
	function get($key) {
		return $this->variables[$key];
	}
	
	// get chain of the controller
	function get_chain() {
		return $this->chain;
	}
	// get name for specified chain (default: this)
	function get_name($chain=null) {
		if (is_null($chain)) {
			$chain = $this->chain;
		}
		return $this->config["chain"][$chain];
	}
	// get title for specified chain (default: this)
	function get_title($chain=null) {
		if (is_null($chain)) {
			$chain = $this->chain;
		}
		if ($chain == $this->config["application_main"]) {
			return $this->config["application_title"];
		} else {
			return $this->config["chain"][$chain];
		}
	}
	// get urk for specified chain (default: this)
	function get_uri($chain=null, $params=null) {
		if (is_null($chain)) {
			$chain = $this->chain;
		}
		if ($chain == $this->config["application_main"]) {
			$uri = $this->config["application_uri"];
		} else {
			$uri = $this->config["application_uri"] . $chain . DIRECTORY_SEPARATOR;
		}
		if (!is_null($params)) {
			$uri .= "?" . http_build_query($params);
		}
		return $uri;
	}
	function get_link($chain=null) {
                $uri = $this->get_uri($chain);
                $name = $this->get_name($chain);
                return "<a href=\"" . $uri . "\">" . $name . "</a>";
        }
	// get url for specified path
	function get_public($path=null) {
		if (is_null($path)) {
			return $this->config["application_uri"];
		} else {
			return $this->config["application_uri"] . $path;
		}
	}
	// include template for specified path
	function include_template($path) {
		include $this->config["template_dir"] . $path;
	}
}
