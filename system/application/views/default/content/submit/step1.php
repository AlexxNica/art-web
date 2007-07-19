<h1>Submit Artwork</h1>
<div id="steps" class=""><span class="marked">1</span><span>2</span><span>3</span></div>	

<?= form_open('submit/step2') ?>

<div id="wallpaper" class="artwork_type">
	<i class="lt"></i><i class="rt"></i>
	<h3>Wallpaper</h3>
	<p>Choose this category if you want to share your wallpaper</p>
	<input type="submit" name="backgrounds" value="choose" />
	<i class="lb"></i><i class="rb"></i>
</div>
<div id="theme" class="artwork_type">
	<i class="lt"></i><i class="rt"></i>
	<h3>Theme</h3>
	<p>Have a new look for the community. Go right ahead!</p>
	<input type="submit" name="themes" value="choose" />
	<i class="lb"></i><i class="rb"></i>
</div>
<div id="screenshot" class="artwork_type">
	<i class="lt"></i><i class="rt"></i>
	<h3>Screenshot</h3>
	<p>Want to show you new eye candy desktop? You're in the right place!</p>
	<input type="submit" name="screenshots" value="choose" />
	<i class="lb"></i><i class="rb"></i>
</div>



</form>