
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

function ActivateModule ( fn, evt )
{
	if ( fn == document.currentActiveModule )
	{
		return;
	}
	if ( confirm ( 'Er du sikker?' ) )
	{
		document.location = 'admin.php?module=extensions&extension=easyeditor&action=activatemodule&file=' + fn + '&pageid='+document.getElementById('pageID').value;
	}
	return false;
}

function NoModule ( )
{
	document.location = 'admin.php?module=extensions&extension=easyeditor&action=nomodule&pageid='+document.getElementById('pageID').value;
}

function GetSupport ()
{
	if ( confirm ( 'Er du sikker?' ) )
	{
		var j = new bajax ( );
		j.openUrl('admin.php?module=extensions&extension=easyeditor&action=sendsalemail', 'get', true );
		j.onload = function ()
		{
			if ( this.getResponseText () == 'OK' )
			{
				alert ( 'En selger har blitt notifisert. Takk for din interesse!' );
			}
			else alert ( 'Det oppsto en feil.' );
		} 
		j.send();
	}
}

function UploadFile ()
{
	initModalDialogue ( 'uploadfile', 320, 150, 'admin.php?module=extensions&extension=easyeditor&action=uploadfile&pid=' + document.getElementById('pageID').value );
}

function RemovePageAttachment ( oid )
{
	if ( confirm ( 'Er du sikker?' ) )
	{
		document.location = 'admin.php?module=extensions&extension=easyeditor&action=removepageattachment&oid='+oid+'&pid=' + document.getElementById('pageID').value;
	}
}

