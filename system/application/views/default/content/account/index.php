<?= $auth ?>

<? if ($this->authentication->is_logged_in()): ?>

<? if ($this->authentication->is_allowed(ADD_ARTWORK)): ?>
Add_artwork
<?endif;?>

<p><?= anchor('/account/logout','Logout')?></p>
<? endif;?>

