<?= $auth ?>

<? if ($this->authentication->is_logged_in()): ?>

<p><?= anchor('/account/logout','Logout')?></p>
<? endif;?>

