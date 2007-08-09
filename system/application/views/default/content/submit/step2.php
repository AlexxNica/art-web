<h1>Submit Artwork</h1>
<div id="steps" class=""><span>1</span><span class="marked">2</span><span>3</span></div>

<div class="stylled_form">
	
	<?=@$error;?>
	
<?= form_open_multipart('submit/step2')?>
<fieldset >
<label for="name">
	<?=@$this->validation->name_error; ?>
	Title
	<input type="text" name="name" size="15" value="<?=@$this->validation->name;?>"/>
</label>

<? if (@$categories):?>
<label for="category">
	Category
	<?= form_dropdown('category',$categories,@$info['category'])?>
</label>
<?endif;?>

<? if (@$type != 'screenshots'):?>
Is this the first version of the artwork?
<input type="radio" name="is_original" value="yes" <?= $this->validation->set_radio('is_original', 'yes'); ?> >Yes
<input type="radio" name="is_original" value="no" <?= $this->validation->set_radio('is_original', 'no'); ?> >No

<label for="version">
	<?=@$this->validation->version_error; ?>
	Version 
	<input type="text" name="version" size="15" value="<?= @$this->validation->version?@$this->validation->version:"1.0" ?>"/>
</label>

<label for="original">
	<?=@$this->validation->original_error; ?>
	Parent ID
	<input type="text" name="original" value="<?= @$this->validation->original?>"/>
</label>

<label for="license">
	License
	<?= form_dropdown('license',$licenses,@$info['license']) ?>
</label>
<?endif;?>

<label for="description">
	Description
	<textarea name="description"><?=@$info['description']?></textarea>
</label>

<label for="keywords">
	<?=@$this->validation->keywords_error; ?>
	Keywords 
	<input type="text" name="keywords" size="15" value="<?= @$this->validation->keywords; ?>"/>
</label>

<? $i = 0;?>
<? if (@$type == 'backgrounds'):?>
<h3>Available Resolutions</h3>
<? foreach($resolutions_available as $resolution):?>
	<span><?=$resolution->width?>x<?=$resolution->height?> <input type="file" name="userfile<?=$i++?>" /></span>
<? endforeach; ?>
<? else:?>
<label for="userfile">
File
<input type="file" name="userfile" />
</label>
<?endif;?>

<label for="upload">
	<input type="submit" name="upload" value="submit"/>
</label>

<input type="hidden" name="<?= @$type ?>" value="1" />
</fieldset>
</form>

</div>