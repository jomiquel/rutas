
if (!String.prototype.format) {
    String.prototype.format = function() {
      var args = arguments;
      return this.replace(/{(\d+)}/g, function(match, number) { 
            return typeof args[number] != 'undefined'
          ? args[number]
          : match
        ;
      });
    };
}

$(document).ready(function()
{
	var name = $( "#login_email" ),
		password = $( "#login_password" ),
		allFields = $( [] ).add( name ).add( password ),
		tips = $( ".validateTips" );


	function updateTips( t ) 
	{
		tips
			.html( t )
			.addClass( "error" )
			.show();
	}


	function checkLength( o, n, min, max ) 
	{
		if ( o.val().length > max || o.val().length < min ) 
		{
			o.addClass( "ui-state-error" );
			updateTips( n + " debe tener entre " +
				min + " y " + max + " caracteres." );

			return false;
		} 
		else 
		{
			return true;
		}
	}


	function checkRequired( o, n )
	{
		if ( o.val().length < 1 ) 
		{
			o.addClass( "ui-state-error" );
			updateTips( getLanguage('required_field').format(n) );

			return false;
		} 
		else 
		{
			return true;
		}
	}


	function checkRegexp( o, regexp, n ) 
	{
		if ( !( regexp.test( o.val() ) ) ) 
		{
			o.addClass( "ui-state-error" );
			updateTips( n );

			return false;
		}
		else
		{
			return true;
		}
	}

	function callbackLogin(json)
	{
		var validated = $.parseJSON(json);

		if ( 1 == validated.validated )
		{
			$( "#login-form form" ).submit();
		}
		else
		{
			allFields.addClass( "ui-state-error" );
			
			updateTips( validated.error_message.replace("\\n", "<br />") );
		}
	}

	function showLogin()
	{
		tips.hide().html( "" );
		$( "#login-form" ).removeClass("hidden").dialog( "open" );
	}

	function showLogout()
	{
		$( "#logout-form" ).removeClass("hidden").dialog( "open" );
	}


	$( "#login-form" ).dialog({
		autoOpen: false,
		height: 315,
		width: 450,
		modal: true,
		buttons: [
			{
				text: getLanguage('main_ok'),
				click: function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );
					bValid = bValid && checkRequired( name, getLanguage('email_label'), 1, 36 );
					bValid = bValid && checkRequired( password, getLanguage('password_label'), 1, 36 );

					if ( bValid ) 
					{
						// password.val(b64_md5(password.val()));
						// $( "#login-form form" ).submit();
				
						$.ajax({
							type: "POST",
							url: $( "#login-form form" ).attr('action'),
							data: { 'login_email': name.val(), 'login_password': password.val(), 'ajax': 1 }
						}).done(callbackLogin);
				
					}
				}
			},

			{
				text: getLanguage('main_cancel'),
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		],

		close: function() {
			allFields.val( "" ).removeClass( "ui-state-error" );
		}
	});


	$( "#logout-form" ).dialog({
		autoOpen: false,
		height: 160,
		width: 400,
		modal: true,
		buttons: [
			{
				text: getLanguage('main_ok'),
				click: function() {
					$( "#logout-form form" ).submit();
				}
			},
			{
				text: getLanguage('main_cancel'),
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		],

		close: function() {
			allFields.val( "" ).removeClass( "ui-state-error" );
		}
	});


	$( "#login" )
		.click(showLogin);
		

	$( "#logout" )
		.click(showLogout);

	if (typeof show_login !== 'undefined') {
		if ( show_login ) showLogin();
	}


});

