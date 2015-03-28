<!DOCTYPE html>
<html lang="fr">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# mastodonparking: http://ogp.me/ns/fb/mastodonparking#">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<meta property="fb:app_id" content="725262000862916"/>
	<meta property="og:title" content="<?=$membership->displayName?>"/>
	<meta property="og:url" content="http://localhost/mastodon-parking/<?=$console?>/<?=$username?>"/>
	<meta property="og:image" content="http://bungie.net<?=$account->characters[0]->emblemPath?>"/>
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

	<!-- Favicon -->
	<link rel="icon" href="http://localhost/mastodon-parking/favicon.png" type="image/png"/>
	<link rel="shortcut icon" href="http://localhost/mastodon-parking/favicon.ico" type="image/x-icon"/>

	<!-- Bootstrap Core CSS -->
	<link href="http://localhost/mastodon-parking/css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="http://localhost/mastodon-parking/css/stylish-portfolio.css" rel="stylesheet">

	<!-- Custom Fonts -->
	<link href="http://localhost/mastodon-parking/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

	<script src="http://localhost/mastodon-parking/js/jquery.js"></script>
	<script src="http://localhost/mastodon-parking/js/bootstrap.min.js"></script>
	<script src="http://localhost/mastodon-parking/js/jquery.diagram.js"></script>
	<script src="http://localhost/mastodon-parking/js/jquery.textfill.min.js"></script>
	<script>
	$(document).ready(function(){
		$('.item-name').textfill({maxFontPixels:15,explicitWidth:180});
	    $(".card-popup").click(function(){
			if($(this).hasClass('big')) {
				$(this).children(".item-name").textfill({maxFontPixels:15,explicitWidth:180,explicitHeight:30});
	    		$(this).animate({
	    			height: '30px'
	    		}).removeClass('big');
	    	} else {
	    	    $(this).children(".item-name").textfill({maxFontPixels:15,explicitWidth:160,explicitHeight:30});
	    		$(this).animate({
	    			height: '50px'
	    		}).addClass('big');
	    	}
	    	$(this).children("small.type").fadeToggle();
            if($(this).children("small.dark").hasClass('down')) {
                $(this).children("small.dark").animate({
                    top: '0'
                }).removeClass('down');                
            } else {
                $(this).children("small.dark").animate({
                    top: '20px'
                }).addClass('down');
            }
	        $(this).next("div").animate({
	            height: 'toggle'
	        });
	    });
	});
	</script>
</head>
<body id="destiny-container">