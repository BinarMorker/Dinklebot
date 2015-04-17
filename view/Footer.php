	</div>
	<div id="footer">
		<span>For feedback, suggestions, bug reports or anything related with this website, <a href="https://www.facebook.com/mastodonparking">contact us on Facebook!</a></span><br/>
		<span>Mastodon Parking is in no way affiliated with Bungie.</span><br/>
		<span><a href="http://kitaiweb.ca">KitaiWeb (François Allard)</a> &copy; <?=date('Y', time())?></span>
		<br/>
		<br/>
		<form>
			<label for="language">Language:</label>
			<select name="language" id="language">
				<?php foreach(Language::get_languages() as $name => $code) { 
					$selected = ($code == $language) ? " selected='selected'" : ""; ?>
				<option <?=$selected?>value="<?=$code?>"><?=$name?></option>
				<?php } ?>
			</select>
		</form>
	</div>
</body>