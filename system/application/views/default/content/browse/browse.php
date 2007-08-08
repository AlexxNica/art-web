<div id="browse_box">
	<div id="browse_options">
		// insert browse options here
	</div>
	
	<div id="browse_image_list">
		<?foreach($artworks as $artwork):?>
			<span class="thumb"><a href="<?= artwork_url($artwork->id,$artwork->category_id) ?>"><img src="<?= thumb_url($artwork->id) ?>" alt="<?= $artwork->name ?>" title="<?= $artwork->name ?>"/></a></span>
		<?endforeach;?>
	</div>
	
	<?= $pagination ?>
</div>