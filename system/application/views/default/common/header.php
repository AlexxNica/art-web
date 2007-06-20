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
		<div id="top_bar">&nbsp;</div>
		<div id="top">
				<div id="logo">&nbsp;</div>
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
	<div id="content">
