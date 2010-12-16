<?php $this->include_template('header.tpl') ?>

<?php
	$lists = $this->get_assign('lists');
	$user = $this->get_assign('user');
	$prev = $this->get_assign('prev');
	$next = $this->get_assign('next');
	$callback = $_SESSION['callback'];
?>

<h2>リスト一覧</h2>

<?php if ($lists instanceof Traversable && count($lists) != 0): ?>
	<dl>
		<?php foreach ($lists as $list): ?>
			<?php
				$lists_params = array(
					'id' => (string)$list->id,
					'screen_name' => (string)$list->user->screen_name,
				);
			?>
			<dt>
				<a href="<?= escape($this->get_uri('lists', $lists_params)) ?>"><!--
				--><?= escape($list->full_name) ?><!--
				--></a>
			</dt>
			<dd>
				<?= $this->replace_uri($list->description) ?>
			</dd>
		<?php endforeach; ?>
	</dl>
<?php else: ?>
	<p>リストはありません。</p>
<?php endif; ?>

<?php
	$this->include_template('info/lists_navi.tpl');
?>

<hr />

<h2>ページナビ</h2>
<?php
	$prev_params = array(
		'cursor' => $prev,
		'id' => $this->request['id'],
		'screen_name' => $this->request['screen_name'],
	);
	$next_params = array(
		'cursor' => $next,
		'id' => $this->request['id'],
		'screen_name' => $this->request['screen_name'],
	);
?>
<ul>
	<li><a href="<?= escape($callback) ?>">[0]元のページに戻る</a></li>
	<?php if ($prev): ?>
		<li><a href="<?= escape($this->get_uri(null, $prev_params)) ?>" accesskey="4">[4]前を見る</a></li>
	<?php endif; ?>
	<?php if ($next): ?>
		<li><a href="<?= escape($this->get_uri(null, $next_params)) ?>" accesskey="6">[6]次を見る</a></li>
	<?php endif; ?>
	<li><a href="#top" accesskey="2">[2]ページ先頭に戻る</a></li>
	<li><a href="#bottom" accesskey="8">[8]ページ後尾に移動</a></li>
</ul>

<?php $this->include_template('gnavi.tpl') ?>

<hr />

<?php $this->include_template('footer.tpl') ?>
