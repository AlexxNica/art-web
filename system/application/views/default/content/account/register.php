<? if (!(@$openid=='true')): ?>
	<div id="openid_notice">
		<i class="lt">&nbsp;</i><i class="rt">&nbsp;</i>
		<h3>Do you have an openID Identity?</h3>
		<p>Use it now!</p>
		<div id="verify-form">
		<?= form_open('account/try_auth')?>
		    <input type="hidden" name="action" value="verify" />
		    <input type="text" name="openid_url" value="" class="openid"/>
		    <input type="submit" value="Verify" />
		  </form>
		</div>
		<i class="lb">&nbsp;</i><i class="rb">&nbsp;</i>
	</div>
<? endif; ?>
<h2>Register</h2>
<div id="verticalForm">
	<?= form_open('/account/register')?>
	<? if ((@$info['identity']!='')):?>
	<label for="openid_identity">
		openID: <?= $info['identity']?>
		<input type="hidden" name="identity" value="<?= @$info['identity'] ?>"/>
	</label>
	<?endif;?>
<fieldset>
<label for="username">
	<?=@$this->validation->username_error; ?>
	Username:
	<input type="text" name="username" value="<?= @$info['username'] ?>"/>
</label>

<label for="email">
	<?=@$this->validation->email_error; ?>
	Email:
	<input type="text" name="email" value="<?= @$info['email']?>"/>
</label>
</fieldset>
<? if (!(@$openid=='true')):?>
<fieldset>
<label for="password">
	<?=@$this->validation->password_error; ?>
	Password:
	<input type="password" name="password" />
</label>

<lable for="re_password">
	<?=@$this->validation->re_password_error; ?>
	Confirm:
	<input type="password" name="re_password" />
</lable>
</fieldset>
<? endif; ?>

<fieldset>
	<legend>Personal Info</legend>
<label for="real_name">
	Name:
	<input type="text" name="real_name" value="<?= @$info['real_name']?>"/>
</label>

<label for="location">
	Country
		<?= country_select('country',@$info['country'],'onchange="update_tz()" id="country"') ?>
</label>

<label for="timezone">
	Timezone
		<?= select_timezone('timezone',@$info['timezone'],'id="timezone"') ?>
</label>

<label for="homepage">
	Homepage
	<input type="text" name="homepage" value="<?= @$info['homepage'] ?>"/>
</label>
</fieldset>

<input type="hidden" name="openid" value="<?= @$openid ?>"/>
<input type="submit" name="register" value="Register" />
</form>
</div>