////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// jQuery
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var Dropdown=function(){function h(a){for(var b=!1,d=0;d<e.length;d++)e[d].isOpen&&(b=!0);if(b){for(a=a.target;null!=a;){if(/\bdropdown\b/.test(a.className))return;a=a.parentNode}f()}}function f(a){for(var b=0;b<e.length;b++)e[b]!=a&&e[b].close()}function g(a){"string"==typeof a&&(a=document.getElementById(a));e.push(new c(a))}function c(a){this.node=a;a.className+=" dropdownJavaScript";"addEventListener"in a?(a.addEventListener("mouseover",this.bind(this.handleMouseOver),!1),a.addEventListener("mouseout",this.bind(this.handleMouseOut),!1),a.addEventListener("click",this.bind(this.handleClick),!1)):(a.attachEvent("onmouseover",this.bind(this.handleMouseOver)),a.attachEvent("onmouseout",this.bind(this.handleMouseOut)),a.attachEvent("onclick",this.bind(this.handleClick)));"createTouch"in document&&a.addEventListener("touchstart",this.bind(this.handleClick),!1)}var e=[];c.prototype.isOpen=!1;c.prototype.timeout=null;c.prototype.bind=function(a){var b=this;return function(){a.apply(b,arguments)}};c.prototype.handleMouseOver=function(a,b){this.clearTimeout();var d="target"in a?a.target:a.srcElement;for(;"LI"!=d.nodeName&&d!=this.node;)d=d.parentNode;"LI"==d.nodeName&&(this.toOpen=d,this.timeout=window.setTimeout(this.bind(this.open),b?0:250))};c.prototype.handleMouseOut=function(){this.clearTimeout();this.timeout=window.setTimeout(this.bind(this.close),250)};c.prototype.handleClick=function(a){f(this);var b="target"in a?a.target:a.srcElement;for(;"LI"!=b.nodeName&&b!=this.node;)b=b.parentNode;"LI"==b.nodeName&&0<this.getChildrenByTagName(b,"UL").length&&!/\bdropdownOpen\b/.test(b.className)&&(this.handleMouseOver(a,!0),"preventDefault"in a?a.preventDefault():a.returnValue=!1)};c.prototype.clearTimeout=function(){this.timeout&&(window.clearTimeout(this.timeout),this.timeout=null)};c.prototype.open=function(){this.isOpen=!0;for(var a=this.getChildrenByTagName(this.toOpen.parentNode,"LI"),b=0;b<a.length;b++){var d=this.getChildrenByTagName(a[b],"UL");if(0<d.length)if(a[b]!=this.toOpen)a[b].className=a[b].className.replace(/\bdropdownOpen\b/g,""),this.close(a[b]);else if(!/\bdropdownOpen\b/.test(a[b].className)){a[b].className+=" dropdownOpen";for(var c=0,e=d[0];e;)c+=e.offsetLeft,e=e.offsetParent;right=c+d[0].offsetWidth;0>c&&(a[b].className+=" dropdownLeftToRight");right>document.body.clientWidth&&(a[b].className+=" dropdownRightToLeft")}}};c.prototype.close=function(a){a||(this.isOpen=!1,a=this.node);a=a.getElementsByTagName("li");for(var b=0;b<a.length;b++)a[b].className=a[b].className.replace(/\bdropdownOpen\b/g,"")};c.prototype.getChildrenByTagName=function(a,b){for(var d=[],c=0;c<a.childNodes.length;c++)a.childNodes[c].nodeName==b&&d.push(a.childNodes[c]);return d};return{initialise:function(){"createTouch"in document&&document.body.addEventListener("touchstart",h,!1);for(var a=document.querySelectorAll("ul.dropdown"),b=0;b<a.length;b++)g(a[b])},applyTo:g}}();
var $ = jQuery.noConflict();

var is_rtl  = ZonerGlobal.is_rtl;
var is_mobile  		= ZonerGlobal.is_mobile;

if (is_rtl == 1) {
	is_rtl = true;
} else {
	is_rtl = false;
}

$(document).ready(function($) {
    "use strict";
	
	if (is_mobile == 1) {
		var vMainMenuID = $('.navigation .nav.navbar-nav').attr('id');
		Dropdown.applyTo(vMainMenuID);
	}
	
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

    $('.nav > li > ul li > ul').css('left', $('.nav > li > ul').width());
	
	// Focus styles for menus.
	$( '.primary-navigation' ).find( 'a' ).on( 'focus blur', function() {
		$( this ).parents('li').toggleClass( 'focus' );
	} );

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
	
	equalHeight('.ffs-info-box');
	
});

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// On RESIZE
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$(window).on('resize', function(){
    setNavigationPosition();
	setCarouselWidth();
	centerSlider();
	centerSliderContent();
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
		centerSliderContent();
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
    if ($(window).width() < 1025) {
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
	if ($(window).width() >= 1025) {
		$(".homepage-slider").css('margin-top', -$('.navigation').outerHeight() - 10);
	}
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
