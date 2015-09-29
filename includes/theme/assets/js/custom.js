////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// jQuery
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var $ = jQuery.noConflict();

var ajaxurl   = ZonerGlobal.ajaxurl;
var is_rtl  = ZonerGlobal.is_rtl;

if (is_rtl == 1) {
	is_rtl = true;
} else {
	is_rtl = false;
}

$(document).ready(function($) {
    "use strict";
	
	if ($('#searchform').length > 0) {
		$('#searchform button').on('click', function() {
			$('#searchform').submit();
		});
	}
	
	if ($('.blog-post .meta .tags').length > 0) {
		$('.blog-post .meta .tags').each(function() {
			  if ($(this).outerHeight() > 26) {
				   $(this).css({'margin-top':'10px'});
				   $(this).css({'margin-bottom':'10px'});
				   $(this).find('a').css({'margin-bottom':'10px'});
			  }
		});
	}
	
	/*Profile - remove profile avatar*/
	$('#profile .avatar-wrapper .remove-btn').on('click', function() {
		$(this).parent().remove();
		$('#form-account-avatar').val('');
		$('#form-account-avatar-id').val('');
		
		return false;
	});

	function readURL(input, img) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$(img).attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	if ($('.file-inputs').length > 0) {
		$('.file-inputs').bootstrapFileInput();
	}	
	
	$('#form-account-avatar-file').change (function() {
		readURL(this, '#avatar-image');
	});
	
	if ($('#form-account-password').length > 0) {
		$('#form-account-password').validate({});		
		
		$('#form-account-password-current').rules( "add", {
			required: true,
			minlength : 6,
			remote: {
				url: ajaxurl,
				type: "post",
				data: {
					action: 'zoner_check_user_password'
				}
			}
		});
		
		$( "#form-account-password-new" ).rules( "add", {
			minlength : 6,
			required: true,
		});

		$( "#form-account-password-confirm-new" ).rules( "add", {
			required: true,
			minlength: 6,
			equalTo : "#form-account-password-new"
		});
	}	
	

    $('.nav > li > ul li > ul').css('left', $('.nav > li > ul').width());

    setNavigationPosition();

    $('.tool-tip').tooltip();

    var select = $('select');
    if (select.length > 0 ) {
        select.selectpicker({size: 10, style:''});
    }

    var bootstrapSelect = $('.bootstrap-select');
    var dropDownMenu = $('.dropdown-menu');

    bootstrapSelect.on('shown.bs.dropdown', function () {
        dropDownMenu.removeClass('animation-fade-out');
        dropDownMenu.addClass('animation-fade-in');
    });

    bootstrapSelect.on('hide.bs.dropdown', function () {
        dropDownMenu.removeClass('animation-fade-in');
        dropDownMenu.addClass('animation-fade-out');
    });

    bootstrapSelect.on('hidden.bs.dropdown', function () {
        var _this = $(this);
        $(_this).addClass('open');
        setTimeout(function() {
            $(_this).removeClass('open');
        }, 100);
    });

    select.change(function() {
        if ($(this).val() != '') {
            $('.form-search .bootstrap-select.open').addClass('selected-option-check');
        }else {
            $('.form-search  .bootstrap-select.open').removeClass('selected-option-check');
        }
    });
	
	if ($('.tool-tip-info').length > 0) {
		$('.tool-tip-info').tooltip({
			placement : 'bottom'
		});
	}
	
	

//  Fit videos width and height

    if($(".video").length > 0) {
       $(".video").fitVids();
    }


//  Smooth Navigation Scrolling

    $('.navigation .nav a[href^="#"], a[href^="#"].roll').on('click',function (e) {
        e.preventDefault();
        var target = this.hash,
            $target = $(target);
        if (target.length > 1) {
			if ($(window).width() > 768) {
				$('html, body').stop().animate({ 'scrollTop': $target.offset().top - $('.navigation').height()}, 2000)
			} else {
				$('html, body').stop().animate({ 'scrollTop': $target.offset().top}, 2000)
			}
		}	
    });

//  iCheck

    if ($('.checkbox').length > 0) {
        $('input').iCheck();
    }

    if ($('.radio').length > 0) {
        $('input').iCheck();
    }
	
	// parallax
	
	 $(window).scroll(function () {
        var scrollAmount = $(window).scrollTop() / 1.5;
        scrollAmount = Math.round(scrollAmount);
		
        if ($(window).width() > 768) {
            if($('#slider').hasClass('has-parallax')){
                $(".homepage-slider").css('top', scrollAmount + 'px');
            }
        }
    });
	
	centerSliderContent();
	
	equalHeight('.ffs-info-box');
	
});

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// On RESIZE
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$(window).on('resize', function(){
    setNavigationPosition();
	setCarouselWidth();
	centerSliderContent();
	centerSlider();
	equalHeight('.ffs-info-box');
});

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// On LOAD
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$(window).load(function(){
	
	//  Owl Carousel

    if ($('.owl-carousel').length > 0) {
		if ($('.carousel-full-width').length > 0) {
            setCarouselWidth();
		}
		
		if ($(".homepage-slider").length > 0) {
			var is_loop = true;
			
			if ($(".homepage-slider").find('.slide').length == 1) {
				is_loop = false;
			}
		
			$(".homepage-slider").owlCarousel({
				mouseDrag:false,
				autoplayTimeout: 10000,
				autoplay:true,
				nav:true,
				rtl:is_rtl,
				loop:is_loop,
				mouseDrag: false,
				items: 1,
				responsive:{  	  0:{ items:1 },
								640:{ items:1 },
							   1700:{ items:1 },
							   1900:{ items:1 }
							},
				responsiveClass:true,
				responsiveBaseElement: ".slide",
				smartSpeed:600,
				navText: ["",""],
				onInitialize :   sliderLoaded,
				onInitialized :  animateDescription,
				onDrag  : animateDescription
			});
		}	
    }
	
	function sliderLoaded(){
        $('#slider').removeClass('loading');
        $("#loading-icon").remove();
        centerSlider();
    }
	
    function animateDescription(){
        var $description = $(".slide .overlay .info");
        $description.addClass('animate-description-out');
        $description.removeClass('animate-description-in');
        setTimeout(function() {
            $description.addClass('animate-description-in');
        }, 400);
    }
	
});

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Functions
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function setNavigationPosition(){
    $('.nav > li').each(function () {
        if($(this).hasClass('has-child')){
            var fullNavigationWidth = $(this).children('.child-navigation').width() + $(this).children('.child-navigation').children('li').children('.child-navigation').width();
            if(($(this).children('.child-navigation').offset().left + fullNavigationWidth) > $(window).width()){
                $(this).children('.child-navigation').addClass('navigation-to-left');
            }
        }
    });
}

// Set Owl Carousel width

function setCarouselWidth(){
    $('.carousel-full-width').each(function() {
		$(this).css('width', $(window).width());	
	});
}

// Mobile Slider

function centerSlider(){
    if ($(window).width() < 979) {
        var $navigation = $('.navigation');
        $('#slider .slide').height($(window).height() - $navigation.height());
        $('#slider').height($(window).height() - $navigation.height());

    }
    var imageWidth = $('#slider .slide img').width();
    var viewPortWidth = $(window).width();
    var centerImage = ( imageWidth/2 ) - ( viewPortWidth/2 );
    $('#slider .slide img').css('left', -centerImage);
}

function centerSliderContent() {
	$(".homepage-slider").css('margin-top', -$('.navigation header').outerHeight() - 10);
}

//  Equal heights

function equalHeight(container){

    var currentTallest = 0,
        currentRowStart = 0,
        rowDivs = new Array(),
        $el,
        topPosition = 0;
    $(container).each(function() {

        $el = $(this);
        $($el).height('auto');
        topPostion = $el.position().top;

        if (currentRowStart != topPostion) {
            for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
                rowDivs[currentDiv].height(currentTallest);
            }
            rowDivs.length = 0; // empty the array
            currentRowStart = topPostion;
            currentTallest = $el.height();
            rowDivs.push($el);
        } else {
            rowDivs.push($el);
            currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
        }
        for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
            rowDivs[currentDiv].height(currentTallest);
        }
    });
}
