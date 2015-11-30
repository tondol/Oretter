<?php $this->include_template('header.tpl') ?>

<?php
	$lists = $this->get('lists');
	$user = $this->get('user');
	$prev = $this->get('prev');
	$next = $this->get('next');
	$callback = $_SESSION['callback'];
?>

<h2>リスト一覧</h2>

<?php if (is_array($lists) && count($lists) != 0): ?>
	<dl>
		<?php foreach ($lists as $list): ?>
			<?php
				$lists_params = array(
					'id' => $list->id_str,
					'screen_name' => $list->user->screen_name,
				);
			?>
			<dt>
				<a href="<?= h($this->get_url('lists', $lists_params)) ?>"><!--
				--><?= h($list->full_name) ?><!--
				--></a>
			</dt>
			<dd>
				<?= h($list->description) ?>
			</dd>
		<?php endforeach; ?>
	</dl>
<?php else: ?>
	<p>リストはありません。</p>
<?php endif; ?>

<hr />

<?php
	$this->include_template('info/lists_navi.tpl');
?>

<hr />

<h2>ページナビ</h2>
<?php
	$prev_params = array(
		'cursor' => $prev,
		'id' => array_at($this->get, 'id'),
		'screen_name' => array_at($this->get, 'screen_name'),
	);
	$next_params = array(
		'cursor' => $next,
		'id' => array_at($this->get, 'id'),
		'screen_name' => array_at($this->get, 'screen_name'),
	);
?>
<ul>
	<li><a href="<?= h($callback) ?>">[0]元のページに戻る</a></li>
	<?php if ($prev): ?>
		<li><a href="<?= h($this->get_url(null, $prev_params)) ?>" accesskey="4">[4]前を見る</a></li>
	<?php endif; ?>
	<?php if ($next): ?>
		<li><a href="<?= h($this->get_url(null, $next_params)) ?>" accesskey="6">[6]次を見る</a></li>
	<?php endif; ?>
	<li><a href="#top" accesskey="2">[2]ページ先頭に戻る</a></li>
	<li><a href="#bottom" accesskey="8">[8]ページ後尾に移動</a></li>
</ul>

<?php $this->include_template('gnavi.tpl') ?>

<hr />

<?php $this->include_template('footer.tpl') ?>
