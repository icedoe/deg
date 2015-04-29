<?php if(isset($title)): ?>
	<h1><?=$title?></h1>
<?php elseif(isset($subtitle)): ?>
	<h2><?=$subtitle?></h2>
<?php endif; ?>
<?php if(isset($code)): ?>
	<?=$code?>
<?php endif; ?>
<?php if(isset($table)): ?>
	<div><?=$table?></div>
<?php endif; ?>