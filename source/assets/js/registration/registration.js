
var callbackEmailExists = function(json) {
	var e = jQuery.parseJSON(json);
	if (e.exists) $('#email_exists').removeClass('hidden');
};

var checkEmailExists = function(email) {
	// Se hace una consulta asíncrona para identificar si existe el email indicado

 	$('#email_exists').addClass('hidden');
	
	if (email.length > 0)
		$.get(getUri, { 'email': email }, callbackEmailExists);
};

$(document).ready(function() {
	checkEmailExists($('[name=email]').val());
});