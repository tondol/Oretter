<?php echo '<?xml version="1.0" encoding="utf-8"?>' . "\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<link rel="stylesheet" type="text/css" href="<?= $this->get_public('static/style/fonts-min.css') ?>" />
	<link rel="stylesheet" type="text/css" href="<?= $this->get_public('static/style/default.css') ?>" />
	<title><?= $this->get_title() ?></title>
</head>
<body>

<?php if ($this->get_chain() == 'top'): ?>
	<h1><a name="top" id="top"><?= $this->config['application_title'] ?></a></h1>
<?php else: ?>
	<h1><a name="top" id="top"><?= $this->get_name() ?></a></h1>
<?php endif; ?>
