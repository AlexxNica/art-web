<h2><?= $user->username ?>'s Gallery</h2>
<ul>
	<? foreach($works as $work):?>
	<li><p><?= $work->name ?></p></li>
	<?endforeach;?>
</ul>