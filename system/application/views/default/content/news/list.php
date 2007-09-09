<?php  if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<center><?php echo $pagination ?></center>

<?php foreach ($news as $news): ?>
<h2><?php echo $news->title ?></h2>
<p><?php echo $news->body ?></p>
<p><?php echo $news->date .' &middot; '. $news->author ?></p>
<hr/>
<?php endforeach; ?>

<center><?php echo $pagination ?></center>
