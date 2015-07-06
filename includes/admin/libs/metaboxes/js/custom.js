jQuery(document).ready(function() {
	jQuery('.cmb_option').iCheck({
			  checkboxClass: 'icheckbox_minimal-grey',
			  radioClass:    'iradio_minimal-grey'
	});
	
	jQuery('select.cmb_select, cmb-type-select_timezone td select, select.states_select, select.countries_select').each (function() {
		jQuery(this).select2();
	});
	
	jQuery('select.countries_select').select2().on("change", function(e) {
		var eVal = e.val;
		if (!eVal) {
			eVal = jQuery(this).find('option:selected').val()
		}	
		
		var data = {	
			action:  'zoner_change_countries',										
			country: eVal,										
		};													
		
		jQuery.post(zoner_vars_ajax.ajaxurl, data, function(response) {							
			var states = jQuery.parseJSON(response);
			if (states) {
				jQuery('.states_select').select2("destroy")
				jQuery('.states_select option').each(function() {
					jQuery(this).remove();
				});

				jQuery.each(states, function(key, value) {   
					jQuery('.states_select').append(jQuery("<option></option>").attr("value",key).text(value)); 
				});
				
				jQuery('.states_select').select2();
			} else {
				jQuery('.states_select option').each(function() {
					jQuery(this).remove();
				});
				jQuery('.states_select').select2('data', null);
				
			}
		});			
    });
	
	if (jQuery('select.states_select option:selected').length == 0) {
		jQuery('select.countries_select').change();
	}
	
	if (jQuery( '.cmb-type-zoner_multiselect select.zoner_multiselect' ).length > 0) {
		jQuery( '.cmb-type-zoner_multiselect select.zoner_multiselect' ).each(function() {
			var instance = jQuery(this);
		 jQuery(instance).select2();
		});
	}
	
});