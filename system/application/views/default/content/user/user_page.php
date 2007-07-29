<div id="user_info">
	<h2><?= $user->username?></h2>
	<p><?= $user->real_name?></p>
</div>

<div id="artwork">
	<h1>Artwork</h1>
	<ul>
		<li>
			<div id="latest" class="big_thumb">
			<img src="<?= thumb_url($latest->id); ?>" alt="<?=$latest->name?>"/>	
			<p><?= $latest->name ?></p>
			</div>
		</li>
		<? foreach($works as $work):?>
		<li><p><?= $work->name ?></p></li>
		<?endforeach;?>
	</ul>
</div>