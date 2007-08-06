<div id="latest" class="container_g1 clearfix">
<i class="lt"></i><i class="rt"></i>
<h2>Latest Additions</h2>
<div class="thumb_list clearfix">
	<?foreach($latest_artwork as $artwork):?>
	<span class="thumb"><a href="<?= artwork_url($artwork->id,$artwork->category_id) ?>"><img src="<?= thumb_url($artwork->id) ?>" alt="<?= $artwork->name ?>" title="<?= $artwork->name ?>"/></a></span>
	<?endforeach;?>
</div>
<i class="lb"></i><i class="rb"></i>
</div>

<div id="top_rated" class="container_g1">
<i class="lt"></i><i class="rt"></i>
<h2>Top Rated</h2>
<div class="thumb_list clearfix">
	<?foreach($top_rated as $artwork):?>
	<span class="thumb"><a href="<?= artwork_url($artwork->id,$artwork->category_id) ?>"><img src="<?= thumb_url($artwork->id) ?>" alt="<?= $artwork->name ?>" title="<?= $artwork->name ?>"/></a></span>
	<?endforeach;?>
</div>
<i class="lb"></i><i class="rb"></i>
</div>
