jQuery(document).ready(function(){
	

	jQuery(".clear-filter").click(function(e){
		jQuery('.job_search_form input[type="checkbox"]').removeAttr('checked');
		jQuery('.job_search_form input[type="text"]').attr('value','');
		jQuery('.job_search_form input[type="hidden"]').attr('value','');
	});
	
	jQuery('.less_more a').click(function(e){
		console.log('click');
		e.preventDefault();
		var content_elm = jQuery(this).parent().prev('.filter-content');
		if(jQuery(content_elm).hasClass('show')){
			jQuery(content_elm).removeClass('show');
		} else {
			jQuery(content_elm).addClass('show');
		}
		
	});
	jQuery('.job_search_form .list.countries input[type="checkbox"]').change(function(e){
		console.log('country selection changed');
		var checkValues = 	jQuery('.job_search_form .list.countries input[type="checkbox"]:checked').map(function(){
								return jQuery(this).val();
							}).get();
		console.log(checkValues);
		jQuery.post('/states', {countries:checkValues}, function(resp){
			var states_html = '';
			for(var i in resp){
				var country_states = resp[i];
				for(var k in country_states){
					var state = country_states[k];
					states_html += '<label ><input name="states[]" type="checkbox" value="'+k+'" /> '+state+'</label>';
				}
				states_html += '<hr />';
			}
			jQuery(".job_search_form .list.states").html(states_html);
		},'json');
	});
	
	
	if(jQuery('.job-search-block .selectpicker').length){
		jQuery('.job-search-block .selectpicker').selectpicker();
	}
});

function manageHeights(){
	var hl1 = jQuery(".job-search-left #job_search_search_tab").height();
	var hl2 = jQuery(".job-search-left #job_search_browse_tab").height();
	var hl = (hl1 > hl2)?hl1:hl2;
	
	var hr = jQuery(".job-search-listing").height();
	if(hl > hr){
		jQuery(".job-search-listing").height(hl + 84);
	} else if(hl < hr){
		jQuery(".job-search-left").height(hr);
	}
}
function load_page(page){
	jQuery('.job_search_form input[name="page"]').val(page);
	jQuery('.job_search_form').submit();
}
function goto_location(city, state, country){ 
	jQuery('.job_search_form input[name="city"]').val(city);
	jQuery('.job_search_form .list.states input[type="checkbox"]').prop('checked',false);
	jQuery('.job_search_form .list.countries input[type="checkbox"]').prop('checked',false);
	
	jQuery('.job_search_form .list.states input[value="'+state+'"]').prop('checked', true);
	jQuery('.job_search_form .list.countries input[value="'+country+'"]').prop('checked', true);
	
	jQuery('.job_search_form').submit();
	
}
function goto_clearance(clearance){
	jQuery('.job_search_form .list.clearance input[type="checkbox"]').prop('checked',false);
	jQuery('.job_search_form .list.clearance input[value="'+clearance+'"]').prop('checked', true);
	jQuery('.job_search_form').submit();
}
function goto_categories(category){
	jQuery('.job_search_form .list.categories input[type="checkbox"]').prop('checked',false);
	//console.log(jQuery('.job_search_form .list.categories input[value="'+category+'"]'));
	jQuery('.job_search_form .list.categories input[value="'+category+'"]').prop('checked', true);
	jQuery('.job_search_form').submit();
}