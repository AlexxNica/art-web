<? foreach($moderation_queue as $artwork):?>
	<img src="<?= base_url().substr($this->config->config['gallery']['thumb_path'],2).'thumb_'.$artwork->id.'.jpg' ?>" />
<?endforeach;?>

<pre>
<? print_r($moderation_queue); ?>
</pre>