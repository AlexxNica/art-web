<? if (@$sent):?>
<h2>Password reset email sent</h2>

<p>Delivery may take a few minutes. <?= anchor('/help/contact','Send us a message') ?> if you need further help.</p>
<p><strong>Note:</strong> You may need to check the "junk" or "spam" folder of your email account in order to find this email.</p>

<?else:?>
<h2>Lost password assistance</h2>
<p>Enter your username or email. We will send you an email with instructions for how to reset your password.</p>
<?= form_open('/account/lost_password') ?>
	<input type="text" name="username" />
	<input type="hidden" name="lost_password" /><input type="submit" name="send" value="send"/>
</form>

	<? if (@$baduser): ?>
	<div class="warning">
		<p>Sorry, but there isn't any account associated to that username/email.</p>
		<small><?= anchor('/help/contact','Send us a message') ?> if you need further help.</small>
	</div>
	<?endif;?>
<?endif;?>
<br/>
<p>&laquo; <?= anchor('/account/login','Return to login') ?></p>