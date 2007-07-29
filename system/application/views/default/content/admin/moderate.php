<? foreach($moderation_queue as $artwork):?>
	<img src="<?= thumb_url($artwork->id) ?>" />
<?endforeach;?>

<pre>
<? print_r($moderation_queue); ?>
</pre>