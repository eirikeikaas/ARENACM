

/*******************************************************************************
The contents of this file are subject to the Mozilla Public License
Version 1.1 (the "License"); you may not use this file except in
compliance with the License. You may obtain a copy of the License at
http://www.mozilla.org/MPL/

Software distributed under the License is distributed on an "AS IS"
basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
License for the specific language governing rights and limitations
under the License.

The Original Code is (C) 2004-2010 Blest AS.

The Initial Developer of the Original Code is Blest AS.
Portions created by Blest AS are Copyright (C) 2004-2010
Blest AS. All Rights Reserved.

Contributor(s): Hogne Titlestad, Thomas Wollburg, Inge Jørgensen, Ola Jensen, 
Rune Nilssen
*******************************************************************************/



function checkForm ( close )
{
	if ( document.userform.Username.value.length < 1 )
	{
		alert ( 'Du må ha et brukernavn.' );
		document.userform.Username.focus ( );
		return false;
	}
	if ( document.userform.Password.value != document.userform.PasswordVerify.value )
	{
		alert ( 'Brukernavnet og passordet er ikke de samme!' );
		document.userform.PasswordVerify.focus ( );
		return false;
	}
	
	// Group
	var groups = document.getElementById ( 'groupsel' );
	var fnd = 0;
	for ( var a = 0; a < groups.options.length; a++ )
	{
		if ( groups.options[ a ].selected ) fnd = 1;
	}
	if ( fnd < 1 ) 
	{
		alert ( 'Husk å definer gruppe tilhørighet! Ingen elementer er valgt i listen.' );
		return false;
	}
	if ( close )
	{
		document.userform.action += '&close=true';
	}
	document.userform.submit ();
}

function deletePassphoto ( )
{
	if ( ( uid = document.getElementById ( 'HiddenID' ).value ) )
	{
		document.userjax = new bajax ( );
		document.userjax.openUrl (
			'admin.php?module=users&action=deletepassphoto&uid=' + uid, 'get', true
		);
		document.userjax.onload = function ( )
		{
			document.getElementById ( 'Passphoto' ).src = this.getResponseText ( );	
		}
		document.userjax.send ( );
	}
}


