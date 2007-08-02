<ul id="moderation_queue">
<? foreach($moderation_queue as $artwork):?>
	<li class="moderation_li clearfix">
		<div class="thumb">
			<img src="<?= thumb_url($artwork->id) ?>" />
		</div>
		<div class="info">
			<ul>
				<li class="name"><?= $artwork->name ?> <i>by</i> <?= $artwork->user_username?></li>
				<li>Rating: <?= $artwork->votes_score?$artwork->votes_score:0 ?></li>
				<li>Votes: <?= $artwork->votes_count?$artwork->votes_count:0 ?></li>
				<li class="moderation_vote">
				<ul>
					<li class="vote_pos <?= (@$artwork->own_vote>0)?'vote_choose':'' ?>"><?= anchor('/admin/moderation/vote_up/'.$artwork->id,' ')?></li>
					<li class="vote_neutral <?= (@$artwork->own_vote == 0 AND isset($artwork->own_vote))?'vote_choose':'' ?>"><?= anchor('/admin/moderation/vote_nil/'.$artwork->id,' ')?></li>
					<li class="vote_neg <?= (@$artwork->own_vote<0)?'vote_choose':'' ?>"><?= anchor('/admin/moderation/vote_down/'.$artwork->id,' ')?></li>
				</ul>
				</li>
			</ul>
		</div>
	</li>
<?endforeach;?>
</ul>

<?= $pagination ?>
