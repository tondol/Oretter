<?php

class Module
{
	var $parent;
	var $get;
	var $post;
	var $request;
	var $config;
	var $current;
	var $variables;
	
	function __construct(Application $parent)
	{
		$this->parent = $parent;
		$this->get = &$this->parent->get;
		$this->post = &$this->parent->post;
		$this->request = &$this->parent->request;
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
		return array_at($this->variables, $key);
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
	//use this if mod_rewrite is disabled
	function get_uri($id=null, $params=null)
	{
		if (is_null($id)) {
			$id = $this->get_current();
		}
		if (is_null($params)) {
			$params = array();
		}
		if ($id != $this->config['application_main']) {
			$params['page'] = $id;
		}
		//query
		$query = http_build_query($params);
		//if (!empty(session_id())) {
		//	$query .= empty($query) ? '' : '&';
		//	$query .= escape(session_name()) . '=' . escape(session_id());
		//}
		//create uri
		if (empty($query)) {
			return $this->config['application_uri'];
		} else {
			return $this->config['application_uri'] . '?' . $query;
		}
	}
/*
	//get uri of specified page (default: current)
	//use this if mod_rewrite is enabled
	function get_uri($id=null, $params=null)
	{
		if (is_null($id)) {
			$id = $this->get_current();
		}
		if (is_null($params)) {
			$params = array();
		}
		if ($id == $this->config['application_main']) {
			$id = "";
		} else {
			$id .= DIRECTORY_SEPARATOR;
		}
		//query
		$query = http_build_query($params);
		//if (!empty(session_id())) {
		//	$query .= empty($query) ? '' : '&';
		//	$query .= escape(session_name()) . '=' . escape(session_id());
		//}
		//create uri
		if (empty($query)) {
			return $this->config['application_uri'] . $id;
		} else {
			return $this->config['application_uri'] . $id . '?' . $query;
		}
	}
*/
	//get link of specified path
	function get_link($id=null, $params=null)
	{
		$uri = $this->get_uri($id, $params);
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
		include $this->config['template_dir'] . $path;
	}
	
	function __destruct()
	{
		
	}
}
