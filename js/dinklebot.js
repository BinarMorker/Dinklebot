$(document).load(function(){
	$('.shader').each(function(){
		var canvas = document.getElementById('canvas');
		var context = canvas.getContext('2d');
		var img = $(this).children('img')[0];
		context.drawImage(img, 0, 0);
		var p = context.getImageData(0, 0, 1, 1).data;
        console.log(p);
		$(this).css("background-color", "rgb("+p[0]+","+p[1]+","+p[2]+")");
	});
});
$(document).ready(function(){
	$(".character-label").click(function(){
		$(this).next(".character-content").animate({
			height: 'toggle'
		});
	});
	$('.item-name').textfill({maxFontPixels:15,explicitWidth:150,explicitHeight:30});
	$('.progress-name').textfill({maxFontPixels:15,explicitWidth:200,explicitHeight:30});
    $('.activity-name').textfill({maxFontPixels:15,explicitWidth:200,explicitHeight:30});
    $('.grimoire-name').textfill({maxFontPixels:18});
    var grimoire = new Masonry('#grimoire', {
      itemSelector: '.grimoire'
    });
    var grimoire_lore = new Masonry('#grimoire-lore', {
      itemSelector: '.grimoire'
    });
    var collection = new Masonry('#collection', {
      itemSelector: '.collection-card'
    });
    $(".progress-card").click(function(){
    	var elem = $(this).children(".progress-data");
		if($(this).hasClass('big')) {
			elem.animate({
				height: '30px'
			}, 400, "swing", function(){
				elem.children(".progress-name").textfill({maxFontPixels:15,explicitWidth:200,explicitHeight:30});
			});
			elem.children(".progress-info").children(".progress-bar").animate({
				height: '30px',
				borderRightWidth: '30px'
			});
    		elem.children("small.more").animate({
    			marginLeft: '30px'
    		});
    		$(this).animate({
    			height: '30px'
    		}).removeClass('big');
    	} else {
    		elem.children(".progress-name").textfill({maxFontPixels:15,explicitWidth:180,explicitHeight:30});
			elem.animate({
				height: '50px'
			}, 400, "swing");
			elem.children(".progress-info").children(".progress-bar").animate({
				height: '50px',
				borderRightWidth: '50px'
			});
    		elem.children("small.more").animate({
    			marginLeft: '50px'
    		});
    		$(this).animate({
    			height: '50px'
    		}).addClass('big');
    	}
    	elem.children("small.more").fadeToggle();
    });
    $(".card-popup").click(function(){
    	var elem = $(this).children(".item-data");
        if ($(this).hasClass('collection-popup')) {
            if($(this).hasClass('big')) {
    			elem.css("height", '30px');
    			elem.children(".item-name").textfill({maxFontPixels:15,explicitWidth:150,explicitHeight:30});
        		$(this).css("height", '30px').removeClass('big');
            } else {
                elem.children(".item-name").textfill({maxFontPixels:15,explicitWidth:130,explicitHeight:30});
                elem.css("height", '50px');
                $(this).css("height", '50px').addClass('big');
            }
            elem.children("small.type").toggle();
            if(elem.children("small.dark").hasClass('down')) {
                elem.children("small.dark").css("top", '0').removeClass('down');                
            } else {
                elem.children("small.dark").css("top", '20px').addClass('down');
            }
            $(this).next("div").toggle();
            collection.layout();
        } else {
            if($(this).hasClass('big')) {
                elem.animate({
                    height: '30px'
                }, 400, "swing", function(){
                    elem.children(".item-name").textfill({maxFontPixels:15,explicitWidth:150,explicitHeight:30});
                });
                $(this).animate({
                    height: '30px'
                }).removeClass('big');
            } else {
                elem.children(".item-name").textfill({maxFontPixels:15,explicitWidth:130,explicitHeight:30});
                elem.animate({
                    height: '50px'
                }, 400, "swing");
                $(this).animate({
                    height: '50px'
                }).addClass('big');
            }
            elem.children("small.type").fadeToggle();
            if(elem.children("small.dark").hasClass('down')) {
                elem.children("small.dark").animate({
                    top: '0'
                }).removeClass('down');                
            } else {
                elem.children("small.dark").animate({
                    top: '20px'
                }).addClass('down');
            }
            $(this).next("div").animate({
                height: 'toggle',
                paddingTop: 'toggle',
                paddingBottom: 'toggle'
            });
    	}
    });
    $(".activity-popup").click(function(){
        var elem = $(this).children(".item-data");
        if($(this).hasClass('big')) {
            elem.animate({
                height: '30px'
            }, 400, "swing", function(){
                elem.children(".activity-name").textfill({maxFontPixels:15,explicitWidth:200,explicitHeight:30});
            });
            $(this).animate({
                height: '30px'
            }).removeClass('big');
        } else {
            elem.children(".activity-name").textfill({maxFontPixels:15,explicitWidth:180,explicitHeight:30});
            elem.animate({
                height: '50px'
            }, 400, "swing");
            $(this).animate({
                height: '50px'
            }).addClass('big');
        }
        elem.children("small.type").fadeToggle();
        if(elem.children("small.dark").hasClass('down')) {
            elem.children("small.dark").animate({
                top: '0'
            }).removeClass('down');                
        } else {
            elem.children("small.dark").animate({
                top: '20px'
            }).addClass('down');
        }
        $(this).next("div").animate({
            height: 'toggle',
            paddingTop: 'toggle',
            paddingBottom: 'toggle'
        });
    });
    $(".grimoire-popup").click(function(){
        $(this).next("div").toggle();
        grimoire.layout();
        grimoire_lore.layout();
    });
	$('.medal-tooltip').tooltipster({
		contentAsHTML: true,
		theme: 'tooltip-mastodon',
	});
});
