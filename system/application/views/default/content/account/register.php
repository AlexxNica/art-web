<? if (@$success): ?>
<h2>Registration Complete</h2>

<p>You'll need to validate your account before logging in for the first time. In a few minutes you will receive an email with further instructions.</p>
<p><?= anchor('/help/contact','Send us a message') ?> if you need further help.</p>
<p><strong>Note:</strong> You may need to check the "junk" or "spam" folder of your email account in order to find this email.</p>
<? else: ?>
<? if (!(@$openid=='true')): ?>
	<div id="openid_notice">
		<h3>Do you have an openID Identity?</h3>
		<p>
			OpenID is an open, decentralized, free framework for user-centric digital identity.
		</p>
		<p>Don't know what it is? <a href="http://janrain.com/openid"> Read about it</a> and get one <a href="http://www.myopenid.com">here</a>.</p>
		<p>Already have one? Then use it now!</p>
		<div id="verify-form">
		<?= form_open('account/try_auth')?>
		    <input type="hidden" name="action" value="verify" />
		    <input type="text" name="openid_url" value="" class="openid"/>
		    <input type="submit" value="Verify" />
		  </form>
		</div>
		<span class="hint-pointer">&nbsp;</span>
	</div>
<? endif; ?>
	<h1>Register a new account</h1>
<div id="verticalForm">
	<?= form_open('/account/register')?>
	<? if ((@$info['identity']!='')):?>
	<label for="openid_identity">
		OpenID <?= $info['identity']?>
		<input type="hidden" name="identity" value="<?= @$info['identity'] ?>"/>
	</label>
	<?endif;?>
<fieldset>
<label for="email">
	<?=@$this->validation->email_error; ?>
	Email
	<input type="text" name="email" value="<?= @$info['email']?>"/>
	<span class="form_help">
			You'll be using the email to login in AGO<br/>
			It needs to be a valid email so the account can be validated.
			<span class="hint-pointer">&nbsp;</span>
	</span>
</label>	
<label for="username">
	<?=@$this->validation->username_error; ?>
	Username
	<input type="text" name="username" value="<?= @$info['username'] ?>"/>
	<span class="form_help">
			Choose an 4 at least long username<br/>
			It must be unique.
			<span class="hint-pointer">&nbsp;</span>
	</span>
</label>
</fieldset>
<? if (!(@$openid=='true')):?>
<fieldset>
<label for="password">
	<?=@$this->validation->password_error; ?>
	Password
	<input type="password" name="password" />
</label>

<label for="re_password">
	<?=@$this->validation->re_password_error; ?>
	Confirm:
	<input type="password" name="re_password" />
</lable>
</fieldset>
<? endif; ?>

<fieldset>
<label for="real_name">
	Name:
	<input type="text" name="real_name" value="<?= @$info['real_name']?>"/>
	<span class="form_help">
			Your real name<br/>
			Will be shown in you profile.
			<span class="hint-pointer">&nbsp;</span>
	</span>
</label>

<label for="homepage">
	Homepage
	<input type="text" name="homepage" value="<?= @$info['homepage'] ?>"/>
</label>

<label for="location">
	Country
		<?= country_select('country',@$info['country'],'onchange="update_tz()" id="country"') ?>
</label>

<label for="timezone">
	Timezone
		<?= select_timezone('timezone',@$info['timezone'],'id="timezone"') ?>
</label>
</fieldset>
<input type="hidden" name="openid" value="<?= @$openid ?>" />
<input type="submit" name="register" value="Register" />
<br/>
</form>
</div>
<?endif;?>