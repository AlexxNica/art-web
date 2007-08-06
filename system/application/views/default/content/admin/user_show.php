<? if (@$user):?>
<div id="user_info">
	<h3>User Info</h3>
	<ul>
		<li><?= $user->username?></li>
		<li><?= $user->real_name?></li>
		<li><?= $user->email?></li>
	</ul>
</div>
<?else:?>
<p>Não foi encontrado esse utilizador</p>
<?endif;?>
<br/>

<? if ($this->authentication->is_allowed(CHANGE_USER_PERMISSIONS)): ?>
<? if ($this->authentication->is_it_me($user->uid)):?>
	<p>You can't edit your own permissions!</p>
<?else:?>
<div id="change_user ">
	<h3>Change User Permissions</h3>
	<small>You were honoured with the power of The Gods! Use it wisely!</small>
	<?= form_open('/admin/users/edit')?>
	<input type="hidden" name="user_id" value="<?= $user->uid ?>" />
	<ul>
	<? foreach($this->config->config['authentication']['constants'] as $key=>$value): ?>
		<li><?= ucwords(strtolower(str_replace('_',' ',$value))) ?> <input type="checkbox" name="permissions[<?=$key?>]" <? if ($permissions[$key]):?>checked<?endif?>/></li>
	<?endforeach;?>
	</ul>
	<input type="submit" name="update" value="Change">
	</form>
</div>
<?endif;?>
<?endif;?>