	</div>
	<div id="footer">
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<!-- Dinklebot -->
		<ins class="adsbygoogle"
		     style="display:block"
		     data-ad-client="ca-pub-6332373031553935"
		     data-ad-slot="7124654099"
		     data-ad-format="auto"></ins>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
		</script>
		<span><?=Language::get($language, "site_footer_contact")?></span><br/>
		<span><?=Language::get($language, "site_name")?> <?=Language::get($language, "site_footer_mention")?></span><br/>
		<span><a href="http://kitaiweb.ca">KitaiWeb (François Allard)</a> &copy; <?=date('Y', time())?></span>
		<br/>
		<br/>
		<iframe src="https://ghbtns.com/github-btn.html?user=binarmorker&repo=dinklebot&type=fork&count=true" frameborder="0" scrolling="0" width="170px" height="20px"></iframe>
		<form style="display: inline-block" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="79CYHW3TC7BD2">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
		<br/>
		<br/>
		<form>
			<label for="language"><?=Language::get($language, "site_footer_language")?>:</label>
			<select name="language" id="language">
				<?php foreach(Language::get_languages() as $name => $code) { 
					$selected = ($code == $language) ? " selected='selected'" : ""; ?>
				<option <?=$selected?>value="<?=$code?>"><?=$name?></option>
				<?php } ?>
			</select>
		</form>
	</div>
	
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script async src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script async src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- Javascript -->
	<script src="<?=$config->site_root?>/js/jquery-1.11.2.min.js"></script>
	<script src="<?=$config->site_root?>/js/jquery.textfill.min.js"></script>
	<script src="<?=$config->site_root?>/js/jquery.tooltipster.min.js"></script>
	<script src="<?=$config->site_root?>/js/jquery.timeago.min.js"></script>
	<script src="<?=$config->site_root?>/js/jquery.timeago.<?=$language?>.js"></script>
	<script src="<?=$config->site_root?>/js/masonry.pkgd.min.js"></script>
	<script src="<?=$config->site_root?>/js/bootstrap.min.js"></script>
	<script src="<?=$config->site_root?>/js/dinklebot.min.js"></script>

	<script>
		$(document).ready(function(){
			$("#language").on('change', function(e){
				e.preventDefault();
				var optionSelected = $("option:selected", this);
	    		var valueSelected = this.value;
				var split = location.pathname.split('/');
				var lang = split[split.length - 1];
				if (lang == "<?=$language?>") {
					split.pop();
					split.push(valueSelected);
					var params = split.join('/');
					var url = location.protocol + "//" + location.hostname + ":" + location.port + params;
					window.location.replace(url);
				}
			});
			jQuery.timeago.settings.allowFuture = true;
			jQuery(".timeago").timeago();
			$("#destiny").submit(function(event) {
				event.preventDefault();
				if ($("#destiny-input").val() != "") {
					$('#loader').show();
					$('#destiny-container').hide();
					$('#destiny-input').blur();
					$('#destiny input, #destiny button').attr('disabled','disabled');
					
					var machine = "1";
					if (!$("#console-choice").hasClass("xb_icon")) {
						machine = "2";
					}
					window.location.replace("<?=$config->site_root?>/"+machine+"/"+escape($("#destiny-input").val())+"/<?=$language?>");
				}
			});
			$("#psn-choice").click(function(event) {
				event.preventDefault();
				$("#console-choice").attr("src", "<?=$config->site_root?>/img/playstation-icon.png");
				$("#console-choice").removeClass("xb_icon");
			});
			$("#xbl-choice").click(function(event) {
				event.preventDefault();
				$("#console-choice").attr("src", "<?=$config->site_root?>/img/xbox-icon.png");
				$("#console-choice").addClass("xb_icon");
			});
		});
		Url = "<?=$config->site_root?>";
		Data = {};
	</script>

	<script src="<?=$config->site_root?>/js/three.min.js"></script>
	<script src="<?=$config->site_root?>/js/gl-matrix-min.js"></script>
	<script src="<?=$config->site_root?>/js/webglstats.js"></script>
	<script src="<?=$config->site_root?>/js/bnet.spasm.js"></script>
	<script src="<?=$config->site_root?>/js/3dpreview.min.js"></script>
</body>
