<?php

class Module
{
	var $parent;
	var $get;
	var $post;
	var $config;
	var $current;
	var $variables;
	
	function __construct(Application $parent)
	{
		$this->parent = $parent;
		$this->get = &$this->parent->get;
		$this->post = &$this->parent->post;
		$this->config = &$this->parent->config;
		$this->current = $this->parent->current;
	}
	
	function action()
	{
		$this->render();
	}
	function render()
	{
		require_once $this->parent->get_template_path();
	}
	
	//set variable by module
	function set_assign($key, $value)
	{
		$this->variables[$key] = $value;
	}
	function set_assign_by_ref($key, &$value)
	{
		$this->variables[$key] = &$value;
	}
	//get variable by template
	function get_assign($key)
	{
		return $this->variables[$key];
	}
	
	//get id of current page
	function get_current()
	{
		return $this->current;
	}
	//get name of specified page (default: current)
	function get_name($id=null)
	{
		if (is_null($id)) {
			$id = $this->current;
		}
		return $this->config['pages'][$id];
	}
	//get title of specified page (default: current)
	function get_title($id=null)
	{
		if (is_null($id)) {
			$id = $this->current;
		}
		if ($id == $this->config['application_main']) {
			return $this->config['application_name'];
		} else {
			return $this->config['pages'][$id] . " - " . $this->config['application_name'];
		}
	}
	//get uri of specified page (default: current)
	function get_uri($id=null)
	{
		if (is_null($id)) {
			$id = $this->current;
		}
		if ($id == $this->config['application_main']) {
			return $this->config['application_uri'];
		} else {
			//return $this->config['application_uri'] . $id . DIRECTORY_SEPARATOR;
			return $this->config['application_uri'] . '?page=' . $id;
		}
	}
	//get link of specified path
	function get_link($id=null)
	{
		$uri = $this->get_uri($id);
		$name = $this->get_name($id);
		return "<a href=\"{$uri}\">{$name}</a>";
	}
	//get uri of specified path
	function get_static($path)
	{
		return $this->config['application_uri'] . $path;
	}
	//include specified template
	function include_template($path)
	{
		include_once $this->config['template_dir'] . $path;
	}
	
	function __destruct()
	{
		
	}
}

?>
