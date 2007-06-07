<?= $auth ?>

<? if (!$this->authentication->is_logged_in()): ?>
<?= form_open('/account/login') ?>
<p><label for="username">Username</label></p>
<input type="text" name="username" value="" size="20"/>

<p><label for="password">Password</label></p>
<input type="password" name="password" value="" size="20"/>

<input type="submit" name="submit" value="submit" />
</form>
<? else:?>

<? if ($this->authentication->is_allowed(ADD_ARTWORK)): ?>
Add_artwork
<?endif;?>

<p><?= anchor('/account/logout','Logout')?></p>
<? endif;?>

