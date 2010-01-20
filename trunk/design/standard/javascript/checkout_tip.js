$(document).ready(function(){ 
	// select all desired input fields and attach tooltips to them 
	$("#register-order input.tip").tooltip({ 
	 
	    // place tooltip on the right edge 
	    position: "center right", 
	 
	    // a little tweaking of the position 
	    offset: [-2, 10], 
	 
	    // use a simple show/hide effect 
	    effect: "fade", 
	     
	    // custom opacity setting 
	    opacity: 0.7, 
	     
	    // use this single tooltip element 
	    tip: '.tooltip'   
	});
});