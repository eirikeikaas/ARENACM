

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



/** ===================================================================================================================================

*/	
function editNews ( nid )
{
	if ( !nid )
		nid = "";
	else nid = "&nid=" + nid;
	document.location = 'admin.php?module=news&function=news' + nid + '&cid=' + currentCategoryID;
}


function editEvent ( nid )
{
	if ( !nid )
		nid = "";
	else nid = "&nid=" + nid;
	document.location = 'admin.php?module=news&function=event' + nid + '&cid=' + currentCategoryID;
}
/** ===================================================================================================================================

*/
function editCategory ( cid, parentcat )
{
	if ( !cid )
		cid = "";
	else cid = "&cid=" + cid;
	if ( !parentcat ) parentcat = "";
	else parentcat = "&parentcat=" + parentcat;
	document.location = 'admin.php?module=news&function=category' + cid + parentcat;
	
}
/** ===================================================================================================================================

*/
function deleteCategory ( cid )
{
	if ( cid )
	{
		
		document.ModalSelection = false;
		initModalDialogue ( 'EditLevel', 480, 400, 'admin.php?module=news&action=deletecategory&cid=' + cid );		
	}
	else
		alert ( "Velg en kategori." );
}

/** ===================================================================================================================================

*/
function doDeleteCategory( cid )
{
	if( document.getElementById( 'newsMoveContent' ) )
	{
		if( document.getElementById( 'newsMoveContent' ).value == ''  )
		{
			var msg = 'Du må velge hvis du vil flytte eller slette innholdet i kategorien først!';
			if( document.getElementById( 'deleteCatMsg' ) ) document.getElementById( 'deleteCatMsg' ).innerHTML = '<p class="error">' + msg + '</p>';
			else alert( msg )
		}
		else
		{
			document.getElementById( 'deleteLevelForm' ).submit();
		}
	}
} // end of doDeleteCategory
/** ===================================================================================================================================

*/
function abortNewsEdit()
{
	removeModalDialogue ( 'EditLevel' );
}

/** ===================================================================================================================================

*/
function deleteNews ( nid )
{
	if ( confirm ( 'Er du sikker på at du ønsker å slette nyheten/hendelsen?' ) )
	{
		document.location = 'admin.php?module=news&action=deletenews&nid=' + nid + '&pos=' + getUrlVar ( 'pos' );
	}
}
