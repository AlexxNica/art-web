			<div id="column">
				<? if ($this->authentication->is_logged_in()): ?>
				<div id="admin_menu" class="menu_field">
					<i class="lt"></i><i class="rt"></i>
					<ul class="iconset">
					<? if ($this->authentication->is_allowed(EDIT_USER) || $this->authentication->is_allowed(DEL_USER)): ?>
						<li><i class="i_users"></i><a href="#">Users</a></li>
					<?endif;?>
					<? if ($this->authentication->is_allowed(MODERATE_ARTWORK)): ?>
						<li><i class="i_moderate"></i><?= anchor('admin/moderate','Moderate Artwork')?></li>
					<?endif;?>
					<? if ($this->authentication->is_allowed(ADD_NEWS) || $this->authentication->is_allowed(EDIT_NEWS) || $this->authentication->is_allowed(DEL_NEWS)): ?>
						<li><i class="i_news"></i><a href="#">News</a></li>
					<?endif;?>
						
						<li><i class="i_submit"></i><?= anchor('submit','Submit Artwork') ?></li>
					</ul>
					<i class="lb"></i><i class="rb"></i>
				</div>
				<?endif;?>
				<div id="resources_menu" class="menu_field">
					<i class="lt"></i><i class="rt"></i>
					<h2>Resources</h2>
					<ul>
						<li>&raquo; <a href="#">Links</a></li>
						<li>&raquo; <a href="#">Applications</a></li>
						<li>&raquo; <a href="#">Licences</a></li>
						<li>&raquo; <a href="#">Submition Policy</a></li>
						<li>&raquo; <a href="#">Copyright</a></li>
					</ul>
					<i class="lb"></i><i class="rb"></i>
				</div>
			</div>
