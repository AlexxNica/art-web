<div id="search_form">
	<?= form_open('admin/users')?>
	<label for="search">
		Search
		<input type="text" name="search" />
		<input type="submit" name="search_bt" value="Go »" />
	</form>
</div>

<div id="user_search_list" >
	<? if (!$users): ?>
		<p> Não foram encontrados utilizadores </p>
	<? else: ?>
		<ul>
			<?foreach($users as $user):?>
			<li><?= anchor('admin/users/'.$user->uid,$user->username)?></li>
			<?endforeach;?>
		</ul>
	<?endif;?>
</div>