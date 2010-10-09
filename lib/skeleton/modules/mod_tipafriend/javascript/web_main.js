function mod_tipafriend_send ( )
{
	var email = document.getElementById ( 'mod_tipafriend_email' );
	var message = document.getElementById ( 'mod_tipafriend_message' );
	var name = document.getElementById ( 'mod_tipafriend_name' );
	if ( name.value.length < 3 )
	{
		alert ( 'Du må skrive inn navnet ditt.' );
		name.focus ( );
		return false;
	}
	if ( 
		email.value.length < 2 || email.value.indexOf ( '@' ) < 0 || 
		email.value.indexOf ( '.' ) < 0
	)
	{
		alert ( 'Du skrive inn mottakers e-post adresse.' );
		email.focus ( );
		return false;
	}
	if ( message.value.length < 3 )
	{
		alert ( 'Du må skrive inn en beskjed.' );
		message.focus ( );
		return false;
	}
	var j = new bajax ( );
	j.openUrl ( document.location + '', 'post', true );
	j.addVar ( 'mod_tipafriend', '1' );
	j.addVar ( 'message', message.value );
	j.addVar ( 'email', email.value );
	j.addVar ( 'name', name.value );
	j.onload = function ( )
	{
		if ( this.getResponseText ( ) == 'ok' )
		{
			alert ( 'Tipset er sendt!' );
			closeStyledDialog ( );
		}
		else alert ( 'Det skjedde en feil ved forsendelsen. Beklager.' + this.getResponseText ( ) );
	}
	j.send ( );
}
