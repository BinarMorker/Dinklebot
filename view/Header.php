<!DOCTYPE html>
<html lang="<?=$language?>">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# mastodonparking: http://ogp.me/ns/fb/mastodonparking#">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<meta property="fb:app_id" content="725262000862916"/>
	<?php if (!empty($membership)) { ?>
	<meta property="og:title" content="<?=$membership->displayName?>"/>
	<meta property="og:url" content="http://<?=$config->site_root.$_SERVER['REQUEST_URI']?>"/>
	<meta property="og:image" content="<?=$config->site_root?>/util/SimpleImage.php?size=100&url=http://www.bungie.net<?=$account->characters[0]->emblemPath?>"/>
	<meta property="og:type"   content="mastodonparking:player" /> 
	<meta property="og:description" content="<?php
		foreach ($account->characters as $key => $character) {
			$gender = $character->characterBase->genderType ? "Female" : "Male";
			echo $character->characterLevel . " " . $definitions['classes'][(string)$character->characterBase->classHash]['className'.$gender] . 
				" " . $definitions['races'][(string)$character->characterBase->raceHash]['raceName'.$gender];
			if ($key < count($account->characters) - 1) {
				echo " // ";
			}
		}
	?>"/>
	<title><?=$membership->displayName?></title>
	<?php } else { ?>
	<meta property="og:title" content="<?=Language::get($language, "site_name")?>"/>
	<meta property="og:url" content="http://<?=$config->site_root.$_SERVER['REQUEST_URI']?>"/>
	<meta property="og:image" content="<?=$config->site_root?>/img/header.jpg"/>
	<meta property="og:type"   content="website" /> 
	<meta property="og:description" content="<?=Language::get($language, "site_description")?>"/>
	<title><?=Language::get($language, "site_name")?></title>
	<?php } ?>

	<!-- Favicon -->
	<link rel="icon" href="<?=$config->site_root?>/favicon.png" type="image/png"/>
	<link rel="shortcut icon" href="<?=$config->site_root?>/favicon.ico" type="image/x-icon"/>

	<!-- CSS -->
	<link href="<?=$config->site_root?>/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?=$config->site_root?>/css/tooltipster.min.css" rel="stylesheet">
	<link href="<?=$config->site_root?>/css/mastodon.min.css" rel="stylesheet">

	<!-- Custom Fonts -->
	<link href="<?=$config->site_root?>/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="http://fonts.googleapis.com/css?family=Roboto:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
</head>
<body>
	<canvas id="canvas" style="display:none"></canvas>
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
	<div class="left-box"><div class="fb-like" data-href="https://www.facebook.com/dinklebotapp" data-layout="box_count" data-action="like" data-show-faces="false" data-share="false"></div></div>

    <div class="header">
      <a href="<?=$config->site_root?>">
    		<img src="<?=$config->site_root?>/img/logo_transparent.png" style="height:50%"/>
				<h1><?=Language::get($language, "site_name")?></h1>
				<p class="lead"><?=Language::get($language, "site_styled_description")?></p>
			</a>
    </div>

    <div id="input" class="input-block">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<?php
						if (isset($alert)) {
							echo $alert;
						}
					?>
					<form id="destiny" action="#">
						<div class="input-group input-group-lg">
							<div class="input-group-btn">
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span><img id="console-choice" style="height:20px;width:20px" src="<?=$config->site_root?>/img/playstation-icon.png"/></span> <span class="caret"></span></button>
								<ul class="dropdown-menu" role="menu">
									<li><a href="#" id="psn-choice">Playstation Network</a></li>
									<li><a href="#" id="xbl-choice">Xbox Live</a></li>
								</ul>
							</div>
							<input id="destiny-input" type="text" class="form-control" autocomplete="on" placeholder="<?=Language::get($language, "site_username")?>" value=<?=empty($_GET['u'])?"":$_GET['u']?>>
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