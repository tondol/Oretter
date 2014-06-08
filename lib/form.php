<?php

require_once 'utilities.php';

class Form
{
	var $input;
	var $error;
	var $config;
	var $is_valid;
	var $is_freezed;
	
	/////////////////////////////////////////////////
	//コンストラクタ
	/////////////////////////////////////////////////
	
	function __construct($config)
	{
		$this->input = array();
		$this->error = array();
		$this->notice = array();
		$this->config = $config;
		$this->is_valid = true;
		$this->is_freezed = false;
		
		//初期化
		foreach ($this->config as $key => $conf) {
			if (empty($conf['error_key'])) {
				//エラーキーが設定されていなければ設定
				$this->config[$key]['error_key'] = $key;
			}
			if (empty($conf['notice'])) {
				//注意事項が設定されていなければ設定
				$this->config[$key]['notice'] = array();
			}
		}
		foreach ($this->config as $key => $conf) {
			//各種配列を初期化
			$error_key = $conf['error_key'];
			$this->error[$error_key] = array();
			$this->notice[$key] = $conf['notice'];
		}
	}
	
	/////////////////////////////////////////////////
	//インターフェイス
	/////////////////////////////////////////////////
	
	function load($input)
	{
		foreach ($this->config as $key => $conf) {
			$type = array_at($conf, 'type');
			$default = array_at($conf, 'default');
			if (!empty($_FILES[$key]) && $type == 'file') {
				//ファイルの場合
				$this->input[$key] = $_FILES[$key];
			} else if (!is_null($default) && empty($input[$key])) {
				//デフォルト値が設定されている場合
				$this->input[$key] = $default;
			} else {
				//そのまま読み込む
				$this->input[$key] = array_at($input, $key);
			}
		}
	}
	function store()
	{
		$store = array();
		foreach ($this->config as $key => $conf) {
			$type = array_at($conf, 'type');
			if (method_exists($this, "store_" . $type)) {
				//ユーザー定義メソッド
				$store[$key] = call_user_func(array($this, "store_" . $type), $key);
			} else {
				//デフォルト
				$store[$key] = array_at($this->input, $key);
			}
		}
		return $store;
	}
	function get_record()
	{
		$record = array();
		foreach ($this->config as $key => $conf) {
			$type = array_at($conf, 'type');
			if ($conf['disable']) {
				//DBには出力しない設定
				continue;
			} else if (method_exists($this, "get_record_" . $type)) {
				//ユーザー定義メソッド
				$record[$key] = call_user_func(array($this, "get_record_" . $type), $key);
			} else {
				//デフォルト
				$record[$key] = array_at($this->input, $key);
			}
		}
		return $record;
	}
	function add_error($key, $str)
	{
		if (!empty($this->config[$key])) {
			//設定で定義済み
			$conf = $this->config[$key];
			$error_key = $conf['error_key'];
		} else {
			//設定にないキー
			$error_key = $key;
		}
		$this->error[$error_key][] = $str;
		$this->is_valid = false;
	}
	function validate()
	{
		foreach ($this->config as $key => $conf) {
			$type = array_at($conf, 'type');
			if (!empty($conf['required'])) {
				//ユーザー定義メソッド
				call_user_func(array($this, "required_" . $type), $key);
			}
		}
	}
	function freeze()
	{
		$this->is_freezed = true;
	}
	function is_valid()
	{
		return $this->is_valid;
	}
	function is_freezed()
	{
		return $this->is_freezed;
	}
	function render()
	{
		$controls = array();
		foreach ($this->config as $key => $conf) {
			$type = array_at($conf, 'type');
			$error_key = array_at($conf, 'error_key');
			$controls[$key]['html'] = call_user_func(array($this, "render_" . $type), $key);
			$controls[$key]['label'] = array_at($conf, 'label');
			
			//凍結していないときのみ出力
			if (!$this->is_freezed) {
				$controls[$key]['notice'] = implode("<br />", $this->notice[$key]);
				$controls[$error_key]['error'] = implode("<br />", $this->error[$error_key]);
			}
		}
		return $controls;
	}
	
	/////////////////////////////////////////////////
	//コントロールごとのレンダリング
	/////////////////////////////////////////////////
	
	private function render_text($key)
	{
		$conf = $this->config[$key];
		$input = escape(array_at($this->input, $key));
		
		if ($this->is_freezed) {
			if ($input == "") {
				return "未入力";
			} else {
				return $input;
			}
		} else {
			$attrs_array = array_at_default($conf, 'attributes', array());
			$attrs_array['type'] = 'text';
			$attrs_array['name'] = $key;
			$attrs_array['value'] = $input;
			$attrs = $this->render_attributes($attrs_array);
			return "<input {$attrs} />";
		}
	}
	private function render_textarea($key)
	{
		$conf = $this->config[$key];
		$input = escape(array_at($this->input, $key));
		
		if ($this->is_freezed) {
			if ($input == "") {
				return "未入力";
			} else {
				return nl2br($input);
			}
		} else {
			$attrs_array = array_at_default($conf, 'attributes', array());
			$attrs_array['name'] = $key;
			$attrs = $this->render_attributes($attrs_array);
			return "<textarea {$attrs}>{$input}</textarea>";
		}
	}
	private function render_password($key)
	{
		$conf = $this->config[$key];
		$input = escape(array_at($this->input, $key));
		
		if ($this->is_freezed) {
			return "非表示";
		} else {
			$attrs_array = array_at_default($conf, 'attributes', array());
			$attrs_array['type'] = 'password';
			$attrs_array['name'] = $key;
			$attrs = $this->render_attributes($attrs_array);
			return "<input {$attrs} />";
		}
	}
	private function render_select($key)
	{
		$conf = $this->config[$key];
		$input = escape(array_at($this->input, $key));
		$options = $conf['options'];
		
		if ($this->is_freezed) {
			$k = array_search($input, $options);
			if ($k === false) {
				return "未選択";
			} else {
				return $k;
			}
		} else {
			$attrs_array = array_at_default($conf, 'attributes', array());
			$attrs_array['name'] = $key;
			$attrs = $this->render_attributes($attrs_array);
			$html = "<select {$attrs}>";
			//dummy
			if (!empty($conf['dummy'])) {
				$dummy = $conf['dummy'];
				$attrs_array = array();
				$attrs_array['value'] = '';
				$attrs = $this->render_attributes($attrs_array);
				$html .= "<option {$attrs}>{$dummy}</option>";
			}
			//options
			foreach ($options as $k => $v) {
				$attrs_array = array();
				$attrs_array['value'] = $v;
				if ($input == $v) {
					$attrs_array['selected'] = 'selected';
				} else {
					unset($attrs_array['selected']);
				}
				$attrs = $this->render_attributes($attrs_array);
				$html .= "<option {$attrs}>{$k}</option>";
			}
			$html .= "</select>";
			return $html;
		}
	}
	private function render_radio($key)
	{
		$conf = $this->config[$key];
		$input = escape(array_at($this->input, $key));
		$options = $conf['options'];
		
		if ($this->is_freezed) {
			$k = array_search($input, $options);
			if ($k === false) {
				return "未選択";
			} else {
				return $k;
			}
		} else {
			$radios = array();
			$attrs_array = array_at_default($conf, 'attributes', array());
			$attrs_array['type'] = 'radio';
			$attrs_array['name'] = $key;
			//options
			foreach ($options as $k => $v) {
				$attrs_array['value'] = $v;
				if ($input == $v) {
					$attrs_array['checked'] = 'checked';
				} else {
					unset($attrs_array['checked']);
				}
				$attrs = $this->render_attributes($attrs_array);
				$radios[] = "<label><input {$attrs} />{$k}</label>";
			}
			return implode($conf['separator'], $radios);
		}
	}
	private function render_checkbox($key)
	{
		$conf = $this->config[$key];
		$input = escape(array_at($this->input, $key));
		$label = $conf['label'];
		
		if ($this->is_freezed) {
			if ($input != "true") {
				return "未チェック";
			} else {
				return $label;
			}
		} else {
			$attrs_array = array_at_default($conf, 'attributes', array());
			$attrs_array['type'] = 'checkbox';
			$attrs_array['name'] = $key;
			$attrs_array['value'] = "true";
			if ($input == "true") {
				$attrs_array['checked'] = 'checked';
			}
			$attrs = $this->render_attributes($attrs_array);
			return "<label><input {$attrs} />{$label}</label>";
		}
	}
	private function render_checkboxes($key)
	{
		$conf = $this->config[$key];
		$input = escape(array_at_default($this->input, $key, array()));
		$options = $conf['options'];
		
		if ($this->is_freezed) {
			$values = array();
			foreach ($input as $v) {
				$k = array_search($v, $options);
				if ($k !== false) {
					$values[] = $k;
				}
			}
			if (empty($values)) {
				return "未チェック";
			} else {
				return implode($conf['separator'], $values);
			}
		} else {
			$checkboxs = array();
			$attrs_array = array_at_default($conf, 'attributes', array());
			$attrs_array['type'] = 'checkbox';
			$attrs_array['name'] = $key . "[]";
			foreach ($options as $k => $v) {
				$attrs_array['value'] = $v;
				if (array_search($v, $input) !== false) {
					$attrs_array['checked'] = 'checked';
				} else {
					unset($attrs_array['checked']);
				}
				$attrs = $this->render_attributes($attrs_array);
				$checkboxes[] = "<label><input {$attrs} />{$k}</label>";
			}
			return implode($conf['separator'], $checkboxes);
		}
	}
	private function render_file($key)
	{
		$conf = $this->config[$key];
		$input = escape(array_at($this->input, $key));
		
		if ($this->is_freezed) {
			//アップロードしたファイル名を返す
			if ($input['error'] != UPLOAD_ERR_OK || $input['size'] == 0) {
				return "未選択";
			} else {
				return $input['name'];
			}
		} else {
			//MAX_FILE_SIZE
			$attrs_array = array();
			$attrs_array['type'] = 'hidden';
			$attrs_array['name'] = 'MAX_FILE_SIZE';
			$attrs_array['value'] = array_at_default($conf, 'max_file_size', 0);
			$attrs = $this->render_attributes($attrs_array);
			$html .= "<input {$attrs} />";
			//コントロールを返す
			$attrs_array = array();
			$attrs_array['type'] = 'file';
			$attrs_array['name'] = $key;
			$attrs = $this->render_attributes($attrs_array);
			$html .= "<input {$attrs} />";
			return $html;
		}
	}
	
	/////////////////////////////////////////////////
	//コントロールごとの入力検査
	/////////////////////////////////////////////////
	
	private function required_text($key)
	{
		$conf = $this->config[$key];
		$label = $conf['label'];

		if (empty($this->input[$key])) {
			$this->add_error($key, "{$label}が入力されていません。");
		}
	}
	private function required_textarea($key)
	{
		$conf = $this->config[$key];
		$label = $conf['label'];
		
		if (empty($this->input[$key])) {
			$this->add_error($key, "{$label}が入力されていません。");
		}
	}
	private function required_password($key)
	{
		$conf = $this->config[$key];
		$label = $conf['label'];
		
		if (empty($this->input[$key])) {
			$this->add_error($key, "{$label}が入力されていません。");
		}
	}
	private function required_select($key)
	{
		$conf = $this->config[$key];
		$input = array_at($this->input, $key);
		$label = $conf['label'];
		$options = $conf['options'];
		
		if (array_search($input, $options) === false) {
			$this->add_error($key, "{$label}が選択されていません。");
			$this->is_valid = false;
		}
	}
	private function required_radio($key)
	{
		$conf = $this->config[$key];
		$input = array_at($this->input, $key);
		$label = $conf['label'];
		$options = $conf['options'];
		
		if (array_search($input, $options) === false) {
			$this->add_error($key, "{$label}が選択されていません。");
			$this->is_valid = false;
		}
	}
	private function required_checkbox($key)
	{
		$conf = $this->config[$key];
		$label = $conf['label'];
		$options = $conf['options'];
		
		if (empty($this->input[$key])) {
			$this->add_error($key, "{$label}がチェックされていません。");
		}
	}
	private function required_checkboxes($key)
	{
		$conf = $this->config[$key];
		$input = array_at_default($this->input, $key, array());
		$options = $conf['options'];
		$label = $conf['label'];
		
		if (empty($input)) {
			$this->add_error($key, "{$label}がチェックされていません。");
		} else {
			foreach ($input as $v) {
				if (array_search($v, $options) === false) {
					$this->add_error($key, "{$label}がチェックされていません。");
					break;
				}
			}
		}
	}
	private function required_file($key)
	{
		$conf = $this->config[$key];
		$input = array_at_default($this->input, $key, array());
		$label = $conf['label'];
		
		if ($input['error'] != UPLOAD_ERR_OK || $input['size'] == 0) {
			$this->add_error($key, "{$label}が正しくアップロードされていません。");
		}
	}
	
	/////////////////////////////////////////////////
	//コントロールごとの出力
	/////////////////////////////////////////////////
	
	private function store_checkbox($key)
	{
		if (empty($this->input[$key])) {
			return "false";
		} else {
			return "true";
		}
	}
	private function store_file($key)
	{
		$input = array_at_default($this->input, $key, array());
		$conf = $this->config[$key];
		
		//アップロード後処理
		if ($input['error'] == UPLOAD_ERR_OK && $input['size'] != 0) {
			$filename = sha1($input['tmp_name']);
			$pathinfo = pathinfo($input['name']);
			$directory = $conf['directory'];
			$extension = $pathinfo['extension'];
			$destname = $filename . '.' . $extension;
			move_uploaded_file($input['tmp_name'], $directory . $destname);
		}
		
		return $input;
	}
	
	/////////////////////////////////////////////////
	//DB登録用の出力
	/////////////////////////////////////////////////
	
	private function get_record_radio($key)
	{
		$input = array_at($this->input, $key);
		$conf = $this->config[$key];
		$options = $conf['options'];
		$k = array_search($input, $options);
		
		//空文字列を返さないとNULLとして扱われる
		if ($k == false) {
			return "";
		} else {
			return $input;
		}
	}
	private function get_record_file($key)
	{
		$input = array_at_default($this->input, $key, array());
		$conf = $this->config[$key];
		
		if ($input['error'] == UPLOAD_ERR_OK && $input['size'] != 0) {
			//アップロード後のファイル名を返す
			$filename = sha1($input['tmp_name']);
			$pathinfo = pathinfo($input['name']);
			$directory = $conf['directory'];
			$extension = $pathinfo['extension'];
			$destname = $filename . '.' . $extension;
			return $destname;
		} else {
			//ファイルがアップロードされていない
			return "";
		}
	}
	
	/////////////////////////////////////////////////
	//ユーティリティメソッド
	/////////////////////////////////////////////////
	
	private function render_attributes($attrs)
	{
		$attrs_array = array();
		foreach ($attrs as $attr => $value) {
			$attrs_array[] = "{$attr}=\"{$value}\"";
		}
		return implode(" ", $attrs_array);
	}
	
	/////////////////////////////////////////////////
	//デストラクタ
	/////////////////////////////////////////////////
	
	function __destruct()
	{
		
	}
}

?>
