<!DOCTYPE html>
<html lang="<?=$language?>">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# dinklebotapp: http://ogp.me/ns/fb/dinklebotapp#">
	<meta charset="utf-16">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<meta property="fb:app_id" content="725262000862916"/>
	<meta property="og:title" content="<?=Language::get($language, "site_name")?>"/>
	<meta property="og:url" content="<?=$config->site_root?>"/>
	<meta property="og:image" content="<?=$config->site_root?>/img/og_image.png"/>
	<meta property="og:type"   content="website" /> 
	<meta property="og:description" content="<?=Language::get($language, "site_description")?>"/>
	<title><?=Language::get($language, "site_name")?></title>

	<!-- Favicon -->
	<link rel="icon" href="<?=$config->site_root?>/favicon.png" type="image/png"/>
	<link rel="shortcut icon" href="<?=$config->site_root?>/favicon.ico" type="image/x-icon"/>

	<!-- CSS -->
	<link href="<?=$config->site_root?>/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?=$config->site_root?>/css/mastodon.min.css" rel="stylesheet">

	<!-- Custom Fonts -->
	<link href="<?=$config->site_root?>/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="http://fonts.googleapis.com/css?family=Roboto:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
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
						<h1><?=Language::get($language, "site_thankyou")?></h1>
						<p class="lead"><?=Language::get($language, "site_thankyou_f")?></p>
					</div>
				</div>
			</div>
    </div>

	<div id="destiny-container" class='hidden'>