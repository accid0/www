$(document).ready(function() {
	$.validator.addMethod("placeholder", function(value, element) {
	  return value != $(element).attr("placeholder");
	}, $.validator.messages.required);
	
	$("#mws-login-form form").validate({
		rules: {
			username: {required: true, placeholder: true, minlength: 4}, 
			password: {required: true, placeholder: true, minlength: 4}
		}, 
		errorPlacement: function(error, element) {  
		}, 
		invalidHandler: function(form, validator) {
			if($.fn.effect) {
				$("#mws-login").effect("shake", {distance: 6, times: 2}, 35);
			}
		}
	});
	
	if($.fn.placeholder) {
		$('[placeholder]').placeholder();
	}
});
