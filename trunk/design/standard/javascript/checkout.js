$(document).ready(function(){ 

		checkInvoiceAsDelivery();
		
		// when the user edits the delivery address
		$("#delivery-address input").keyup(function() {
			
			// if invoice address should be the same as delivery
	      	if($("#invoice_as_delivery").attr('checked'))
	      	{
	      		copyInvoiceData();
	      	}
		});
		
		// when the user edits the country
		$("#delivery_country").change(function() {
			
			// if invoice address should be the same as delivery
	      	if($("#invoice_as_delivery").attr('checked'))
	      	{
	      		copyInvoiceData();
	      	}
		});
		
		// when the "invoice as delivery" check box is clicked
		$("#invoice_as_delivery").live("click", function(event) {
		
			checkInvoiceAsDelivery();
		});
		
		
		function checkInvoiceAsDelivery()
		{
			// if the "invoice as delivery" checkbox is checked
			if($("#invoice_as_delivery").attr('checked'))
			{
				copyInvoiceData();
				
				// disable invoice fields
				$("#invoice-address input.box, #invoice-address select").each(function( intIndex ){
					$(this).attr("readonly", true);
					$(this).addClass("readonly");
					$(this).attr("tabindex", "-1");
				});			
			}
			
			else
			{
				// enable invoice fields
				$("#invoice-address input.box, #invoice-address select").each(function( intIndex ){
					$(this).attr("readonly", false);
					$(this).removeClass("readonly");
					$(this).attr("tabindex", false);
				});	
			}
		}
		
		function copyInvoiceData()
		{
			// copy data from delivery to invoice fields
	  		$("#invoice_street").attr("value", $("#delivery_street").attr('value'));
	  		$("#invoice_street2").attr("value", $("#delivery_street2").attr('value'));
	  		$("#invoice_zip").attr("value", $("#delivery_zip").attr('value'));
	  		$("#invoice_city").attr("value", $("#delivery_city").attr('value'));
	  		$("#invoice_state").attr("value", $("#delivery_state").attr('value'));
	  		$("#invoice_country").attr("value", $("#delivery_country").attr('value'));
		}

});
