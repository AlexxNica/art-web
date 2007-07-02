<h2>Reset Password</h2>

<? if (@$no_valid_key): ?>
	<p>Sorry, the link has expired!</p>
	<p>For security reasons, password reset requests will only last one hour. You may need to <?= anchor('/account/lost_password','request a new password reset') ?>.</p>
<?else:?>
<div id="verticalForm">
<?= form_open('/account/resetpwd/'.$id) ?>
<fieldset>
<label for="password">
	<?=@$this->validation->password_error; ?>
	Password
	<input type="password" name="password" />
</label>

<label for="re_password">
	<?=@$this->validation->re_password_error; ?>
	Confirm
	<input type="password" name="re_password" />
</label>

<input type="submit" name="send" value="change" />
</fieldset>
</form>
</div>
<?endif;?>