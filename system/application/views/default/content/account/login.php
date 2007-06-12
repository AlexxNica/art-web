<? if ($refer): ?>
<p class="notice">Attention: After logging in you will be redirected to '<?= $refer ?>'!</p>
<?endif;?>
<? if (!$this->authentication->is_logged_in()): ?>
<?= form_open('/account/login') ?>
<p><label for="username">Username</label></p>
<input type="text" name="username" value="" size="20"/>

<p><label for="password">Password</label></p>
<input type="password" name="password" value="" size="20"/>


<p><label for="remember">Remember me</label>
<input type="checkbox" name="remember" /></p>

<input type="hidden" name="login" value="true"/>
<input type="hidden" name="refer" value="<?= $refer ?>"/>
<input type="submit" name="submit" value="submit" />
</form>
<? else:?>
<p>You're already logged in!</p>

<p><?= anchor('/account/logout','Logout')?></p>
<? endif;?>

