<!DOCTYPE html>
<html lang="<?=$language?>">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# mastodonparking: http://ogp.me/ns/fb/mastodonparking#">
	<meta charset="utf-16">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<meta property="fb:app_id" content="725262000862916"/>
	<?php if (!empty($membership)) { ?>
	<meta property="og:title" content="<?=$membership->displayName?>"/>
	<meta property="og:url" content="/mastodon/<?=$console?>/<?=$username?>"/>
	<meta property="og:image" content="http://www.bungie.net<?=$account->characters[0]->emblemPath?>"/>
	<meta property="og:type"   content="mastodonparking:player" /> 
	<meta property="og:description" content="<?php
		foreach ($account->characters as $key => $character) {
			$gender = $character->characterBase->genderType ? "Female" : "Male";
			echo $character->characterLevel . " " . $definitions['classes'][(string)$character->characterBase->classHash]['className'.$gender] . 
				" " . $definitions['races'][(string)$character->characterBase->raceHash]['raceName'.$gender];
			if ($key < count($account->characters)) {
				echo " // ";
			}
		}
	?>"/>
	<title><?=$membership->displayName?></title>
	<?php } else { ?>
	<meta property="og:title" content="Mastodon Parking"/>
	<meta property="og:url" content="/mastodon"/>
	<meta property="og:image" content="/mastodon/img/og_image.png"/>
	<meta property="og:type"   content="website" /> 
	<meta property="og:description" content="Mastodon Parking is a cross-platform whatever"/> <?php // TODO ?>
	<title>Mastodon Parking</title>
	<?php } ?>

	<!-- Favicon -->
	<link rel="icon" href="/mastodon/favicon.png" type="image/png"/>
	<link rel="shortcut icon" href="/mastodon/favicon.ico" type="image/x-icon"/>

	<!-- CSS -->
	<link href="/mastodon/css/bootstrap.min.css" rel="stylesheet">
	<link href="/mastodon/css/tooltipster.min.css" rel="stylesheet">
	<link href="/mastodon/css/mastodon.min.css" rel="stylesheet">

	<!-- Custom Fonts -->
	<link href="/mastodon/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="http://fonts.googleapis.com/css?family=Roboto:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script async src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script async src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- Javascript -->
	<script src="/mastodon/js/jquery-1.11.2.min.js"></script>
	<script async src="/mastodon/js/jquery.textfill.min.js"></script>
	<script async src="/mastodon/js/jquery.tooltipster.min.js"></script>
	<script async src="/mastodon/js/jquery.timeago.min.js"></script>
	<script async src="/mastodon/js/jquery.timeago.<?=$language?>.js"></script>
	<script async src="/mastodon/js/bootstrap.min.js"></script>
	<script async src="/mastodon/js/mastodon.min.js"></script>

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
					window.location.replace("/mastodon/"+machine+"/"+escape($("#destiny-input").val())+"/<?=$language?>");
				}
			});
			$("#psn-choice").click(function(event) {
				event.preventDefault();
				$("#console-choice").attr("src", "/mastodon/img/playstation-icon.png");
				$("#console-choice").removeClass("xb_icon");
			});
			$("#xbl-choice").click(function(event) {
				event.preventDefault();
				$("#console-choice").attr("src", "/mastodon/img/xbox-icon.png");
				$("#console-choice").addClass("xb_icon");
			});
		});
	</script>

	<canvas id="canvas" style="display:none"></canvas>
</head>
<body>
	<?php include("view/Analytics.php") ?>
	<!-- Facebook Integration -->
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=725262000862916&version=v2.0";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<div class="left-box"><div class="fb-like" data-href="https://www.facebook.com/mastodonparking" data-layout="box_count" data-action="like" data-show-faces="false" data-share="false"></div></div>

    <div class="header">
        <div>
    		<img src="/mastodon/img/logo_transparent.png" style="height:50%"/>
			<h1>Mastodon Parking</h1>
			<p class="lead">Destiny basic stats and unique achievements.</p>
		</div>
    </div>

    <div id="input" class="input-block">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<form id="destiny" action="#">
						<div class="input-group input-group-lg">
							<div class="input-group-btn">
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span><img id="console-choice" style="height:20px;width:20px" src="/mastodon/img/playstation-icon.png"/></span> <span class="caret"></span></button>
								<ul class="dropdown-menu" role="menu">
									<li><a href="#" id="psn-choice">Playstation Network</a></li>
									<li><a href="#" id="xbl-choice">Xbox Live</a></li>
								</ul>
							</div>
							<input id="destiny-input" type="text" class="form-control" autocomplete="on" placeholder="Username" value=<?=empty($_GET['u'])?"":$_GET['u']?>>
							<div class="input-group-btn">
								<button class="btn btn-default" type="submit" id="submit">Go!</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
    </div>

	<div id="loader" class="load">
		<div class="blockcont">
			<div class="block"></div><div class="block"></div><div class="block"></div>
			<div class="block"></div><div class="block"></div><div class="block"></div>
			<div class="block"></div><div class="block"></div><div class="block"></div>
		</div>
	</div>

	<div id="destiny-container"<?=empty($membership)?" class='hidden'":""?>>