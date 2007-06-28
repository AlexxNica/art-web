<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="en-us" />
	<meta name="ROBOTS" content="NONE" />
	<meta name="MSSmartTagsPreventParsing" content="true" />
	<meta name="Keywords" content="<?=property('app_keywords')?>" />
	<meta name="Description" content="<?=property('app_description')?>" />
	<meta name="Copyright" content="<?=property('app_copyright')?>" />
	<title><?=property('app_title')?></title>
	<?=style('reset.css')?>
	<?=style('type.css')?>
	<?=style('forms.css')?>
	<?=style('layout.css')?>
	<?=script('mootools.js')?>
	<?=script('init.js')?>
	<script type="text/javascript" charset="utf-8">
		window.addEvent('domready',function(){init_AGO('cenas')});
	</script>
</head>
<body id="agov3">
	<div id="universe">
		<div id="top">
				<div id="top-bar">
					<div id="gnome-menu">About GNOME · Download · Users · Art & Themes · Developers · Foundation</div>
					<div class="logo">art.gnome.org</div>
					<div id="ago-menu">
						<ul>
							<li class="marked"><a href="#">Browse</a></li>
							<li><a href="#">News</a></li>
							<li><a href="#">FAQ</a></li>
							<li><a href="#">Forum</a></li>
							<li><a href="#">About</a></li>
						</ul>
					</div>
				</div>
				<div id="sub-bar">
				<div id="login" class="login">
				<? if (!$this->authentication->is_logged_in()): ?>
				<?= form_open('/account/login',array('id' => 'mini_login','name' => 'mini'))?>
					<fieldset>
					<label for="username">Username:</label>
					<input type="text" name="username" class="inputter" />
					<label for="password">Password:</label>
					<input type="password" name="password" class="inputter" />
					<input type="hidden" name="login" value="true" />
					<span><?= anchor('/account/login/','login',array('class' => 'submitter' ))?></span>
					<input type="submit" style="display:none"/>
					</fieldset>
				</form>
				<? else: ?>
				<p><?= $this->authentication->get_username() ?> | <?= anchor('/account/logout','logout') ?></p>
				<? endif;?>
				</div>
				</div>
		</div>
		<? if (@$show_middle_bar): ?>
		<div id="middle-bar" class="clearfix">
			<div id="initial_notice">
				<h2>Welcome to AGO</h2>
				<p>a place for high quality artwork and themes for the GNOME desktop.</p>

				<p>All themes and artwork on art.gnome.org are tested and 
				moderated (see the to ensure a high standard of quality and to make certain they work with your GNOME desktop.</p>
			</div>
			
			<div id="news">
				<h2>Latest News</h2>
				<ul>
					<li>Help us with future Art.Gnome.org version.</li>
					<li>We need your GNOME 2.15 screenshots!</li>
					<li>Long Overdue Features.</li>
					<li>GUADEC Logo & Web Theme Contest</li>
					<li>art.gnome.org button</li>
				</ul>
			</div>
			
			<div id="featured_work">
				<div class="thumb"><span><img src="<?= base_url() ?>/repository/Magnifica_by_clxrr.jpg"/></span></div>
			</div>
		</div>
		<? endif;?>
		<?=$menu?>
	<div id="content">
		<div id="notice">
			<?= flashget('notice'); ?>
		</div>
