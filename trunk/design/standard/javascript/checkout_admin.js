jQuery(document).ready(function(){ 

	// prepare autocompleter
	jQuery("#product_search").autocomplete("/checkout/findproduct/", { delay:100, width: 400 });
	
	jQuery("#product_search").result(function(event, data, formatted) {
	if (data)
		jQuery("#product_search_id").val(data[1]);
	});

});