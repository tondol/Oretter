<?php

require_once 'module.php';
require_once 'utilities.php';

class Application
{
	var $get;
	var $post;
	var $request;
	var $config;
	var $current;
	var $module;
	var $variables;
	
	function __construct()
	{
		$this->get = &$_GET;
		$this->post = &$_POST;
		$this->request = &$_REQUEST;
		$this->config = &$GLOBALS['config'];
		$this->load();
	}
	
	function load()
	{
		//normalize parameter: page
		$chain = explode(DIRECTORY_SEPARATOR, $this->get['page']);
		foreach ($chain as $key => $value) {
			//directory traversal
			$chain[$key] = basename($value);
			//except null string
			if ($chain[$key] == "") {
				unset($chain[$key]);
			}
		}
		$imploded = implode(DIRECTORY_SEPARATOR, $chain);
		
		//check the existence
		foreach ($this->config['pages'] as $key => $value) {
			if ($imploded == $key) {
				$this->current = $imploded;
				break;
			}
		}
		
		//main or 404 not found
		if (!isset($this->current)) {
			if ($imploded == "") {
				$this->current = $this->config['application_main'];
			} else {
				header('HTTP/1.0 404 Not Found');
				$this->current = $this->config['application_missing'];
			}
		}
	}
	
	function run()
	{
		//get module-path and class-name for specified page
		$module_dir = $this->config['module_dir'];
		$module_name = "Module_" . str_replace(DIRECTORY_SEPARATOR, "_", $this->current);
		$module_path = $module_dir . $this->current . ".php";
		
		//check the existence
		if (file_exists($module_path)) {
			require_once $module_path;
		} else {
			$module_name = "Module";
		}
		
		//load module
		$module = new $module_name($this);
		$module->action();
	}
	function get_template_path()
	{
		//get template-path for specified page
		$template_dir = $this->config['template_dir'];
		$template_path = $template_dir . $this->current . ".tpl";
		
		//check the existence
		if (!file_exists($template_path)) {
			$main = $this->config['application_main'];
			$template_path = $template_dir . $main . ".tpl";
		}
		return $template_path;
	}
	
	function __destruct()
	{
		
	}
}
