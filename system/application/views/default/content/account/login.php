<? if (isset($refer)): ?>
<p class="notice">Attention: After logging in you will be redirected to '<?= $refer ?>'!</p>
<?endif;?>
<? if (!$this->authentication->is_logged_in()): ?>
<?= form_open('/account/login') ?>
<fieldset>	
<p><label for="username">Username</label></p>
<input type="text" name="username" value="" size="20"/>

<p><label for="password">Password</label></p>
<input type="password" name="password" value="" size="20"/>


<p><label for="remember">Remember me</label>
<input type="checkbox" name="remember" /></p>

<input type="hidden" name="login" value="true"/>
<input type="hidden" name="refer" value="<? if (isset($refer)): ?><?= $refer?><? endif; ?>"/>
<input type="submit" name="submit" value="submit" />
</fieldset>
</form>
<br/>
<h2>OpenID</h2>
<p>
	Do you have an openID account? Sign in bellow.<br/>
	<small>(If you aren't registered yet, there's not problem)</small>
</p>

<div id="verify-form">
<?= form_open('account/try_auth')?>
    <input type="hidden" name="action" value="verify" />
    <input type="text" name="openid_url" value="" class="openid"/>
    <input type="submit" value="Verify" />
  </form>
</div>

<?= anchor('/account/register','Register')?>

<? else:?>
<p>You're already logged in!</p>

<p><?= anchor('/account/logout','Logout')?></p>
<? endif;?>

