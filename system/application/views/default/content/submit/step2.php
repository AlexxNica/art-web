<h1>Submit Artwork</h1>
<div id="steps" class=""><span>1</span><span class="marked">2</span><span>3</span></div>


<dl>
	<dt>Title</dt>
	<dd><input type="text" name="name" size="20" /></dd>
</dl>

<? if (@$categories):?>
<dl>
	<dt>Category</dt>
	<dd><?= form_dropdown('category',$categories)?></dd>
</dl>
<?endif;?>
