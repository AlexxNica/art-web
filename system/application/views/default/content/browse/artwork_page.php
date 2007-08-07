<div id="artwork_options">
	<ul>
		<li>some option</li>
	</ul>
</div>

<div id="artwork_image">
	<img src="<?= thumb_url($artwork->id)?>" alt="<?= $artwork->name ?>" title="<?= $artwork->name ?>"/>
</div>

<div id="artwork_info">
	<div id="artwork_info_rating">
	Rating: 
	<ul class="star-rating">
		<li class="current-rating" style="width:<? if ($artwork_rating->total_votes<5):?>0<?else:?> <?=($artwork_rating->rating*100)/5?><?endif;?>%;">Currently <?= $artwork_rating->rating ?>/5 Stars.</li>
		<li><span>1</span></li>
		<li><span>2</span></li>
		<li><span>3</span></li>
		<li><span>4</span></li>
		<li><span>5</span></li>
	</ul>
	<span><p>(<? if ($artwork_rating->total_votes<5):?> 5 votes required,<?endif;?> <?= $artwork_rating->total_votes ?> votes total)</p></span>
	</div>
	<div id="artwork_info_my_rating">
	My Rating:
	<?if ($this->authentication->is_logged_in()):?> 
	<ul class="star-rating">
		<li class="current-rating" style="width:<?=($artwork_my_rating->rating*100)/5?>%;">Currently <?= $artwork_my_rating->rating ?>/5 Stars.</li>
		<li><a href="?rating=1" title="Rate this 1 out of 5" class="one-star" >1</a></li>
		<li><a href="?rating=2" title="Rate this 2 out of 5" class="two-stars" >2</a></li>
		<li><a href="?rating=3" title="Rate this 3 out of 5" class="three-stars" >3</a></li>
		<li><a href="?rating=4" title="Rate this 4 out of 5" class="four-stars" >4</a></li>
		<li><a href="?rating=5" title="Rate this 5 out of 5" class="five-stars" >5</a></li>
	</ul>
	<?else:?>
	<p>You need to <?= anchor('account/login?refer='.$this->uri->uri_string(),'log in')?> to vote</p>
	<?endif;?>
	</div>
</div>
