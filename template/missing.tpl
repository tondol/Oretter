<?php $this->include_template('header.tpl') ?>

<h1><?= $this->get_name() ?></h1>

<p>お探しのページが見つからないよ。<br />
<a href="<?= $this->get_uri('top') ?>">トップに戻ってね（はぁと</a>。</p>

<?php $this->include_template('footer.tpl') ?>
