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


Is this the first version of the artwork?
<input type="radio" name="is_original" value="yes" <?= $this->validation->set_radio('is_original', 'yes'); ?> >Yes
<input type="radio" name="is_original" value="no" <?= $this->validation->set_radio('is_original', 'no'); ?> >No

<label for="version">
	<?=@$this->validation->version_error; ?>
	Version 
	<input type="text" name="version" size="15" value="<?= @$this->validation->version;?>"/>
</label>

<label for="original">
	of
	<?= form_dropdown('original',$originals,@$info['original']) ?>
</label>

<label for="license">
	License
	<?= form_dropdown('license',$licenses,@$info['license']) ?>
</label>

<label for="description">
	Description
	<textarea name="description"><?=@$info['description']?></textarea>
</label>

<label for="keywords">
	<?=@$this->validation->keywords_error; ?>
	Keywords 
	<input type="text" name="keywords" size="15" value="<?= @$this->validation->keywords; ?>"/>
</label>

<label for="file">
	File
	<input type="file" name="userfile" />
</label>

<label for="upload">
	<input type="submit" name="upload" value="submit"/>
</label>

<input type="hidden" name="<?= @$type ?>" value="1" />
</fieldset>
</form>

</div>