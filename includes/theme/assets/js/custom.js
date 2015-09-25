////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// jQuery
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var $ = jQuery.noConflict();

var ajaxurl   = ZonerGlobal.ajaxurl;

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

	
});

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// On RESIZE
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$(window).on('resize', function(){
    setNavigationPosition();
});

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// On LOAD
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$(window).load(function(){
	
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

