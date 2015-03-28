$('#submit .no-spinner').show();
$('#submit .spinner').hide();
$('#submit .oops').hide();
$('#refresh .no-spinner').show();
$('#refresh .spinner').hide();
$('#refresh .oops').hide();

$("#refresh").click(function(event) {
	event.preventDefault();
	if ($("#destiny-input").val() != "") {
		$('#submit .no-spinner').show();
		$('#submit .spinner').hide();
		$('#submit .oops').hide();
		$('#refresh .no-spinner').hide();
		$('#refresh .spinner').show();
		$('#refresh .oops').hide();
		
		$('#destiny-input').blur();
		$('#destiny input, #destiny button').attr('disabled','disabled');
		
		var machine = "xbox";
		if (!$("#console-choice").hasClass("xb_icon")) {
			machine = "playstation";
		}
		$("#destiny-container").load("//mastodon.tk/destiny/"+machine+"/"+escape($("#destiny-input").val())+"/noformat:refresh", function( response, status, xhr ) {
			if ( status != "error" && $("#destiny-container").html().indexOf("Data could not be fetched.") < 0 ) {
				$('#refresh .no-spinner').show();
				$('#refresh .spinner').hide();
				$('#refresh .oops').hide();
				startDiagrams();
			} else {
				$('#refresh .no-spinner').hide();
				$('#refresh .spinner').hide();
				$('#refresh .oops').show();
			}
			$('#destiny input, #destiny button').removeAttr('disabled');
		});
	}
});

$("#destiny").submit(function(event) {
	event.preventDefault();
	if ($("#destiny-input").val() != "") {
		$('#refresh .no-spinner').show();
		$('#refresh .spinner').hide();
		$('#refresh .oops').hide();
		$('#submit .no-spinner').hide();
		$('#submit .spinner').show();
		$('#submit .oops').hide();
		
		$('#destiny-input').blur();
		$('#destiny input, #destiny button').attr('disabled','disabled');
		
		var machine = "xbox";
		if (!$("#console-choice").hasClass("xb_icon")) {
			machine = "playstation";
		}
		$("#destiny-container").load("//mastodon.tk/destiny/"+machine+"/"+escape($("#destiny-input").val())+"/noformat", function( response, status, xhr ) {
			if ( status != "error" && $("#destiny-container").html().indexOf("Data could not be fetched.") < 0 ) {
				$('#submit .no-spinner').show();
				$('#submit .spinner').hide();
				$('#submit .oops').hide();
				$('html, body').animate({
					scrollTop: $("#destiny-container").offset().top
				}, 1000);
				startDiagrams();
			} else {
				$('#submit .no-spinner').hide();
				$('#submit .spinner').hide();
				$('#submit .oops').show();
			}
			$('#destiny input, #destiny button').removeAttr('disabled');
		});
	}
});

$("#psn-choice").click(function(event) {
	event.preventDefault();
	$("#console-choice").attr("src", "//mastodon.tk/img/playstation-icon.png");
	$("#console-choice").removeClass("xb_icon");
});

$("#xbl-choice").click(function(event) {
	event.preventDefault();
	$("#console-choice").attr("src", "//mastodon.tk/img/xbox-icon.png");
	$("#console-choice").addClass("xb_icon");
});
