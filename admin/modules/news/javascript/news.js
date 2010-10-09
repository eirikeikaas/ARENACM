

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
	if ( !close ) close = false;
	if ( document.newsform )
	{
		if ( document.newsform.NewsTitle.length <= 0 )
		{
			alert ( "Du må fylle inn en nyhetstittel." );
			document.newsform.NewsTitle.focus ( );
			return false;
		}
		if ( close )
			document.newsform.action += '&close=true';
		document.newsform.submit ( );
	}
}

function getTranslations ( )
{
	document.trjax = new bajax ( );
	document.trjax.openUrl (
		'admin.php?module=news&action=gettranslations&nid=' + 
		document.getElementById ( 'newsid' ).value, 
		'get', true
	);
	document.trjax.onload = function ( )
	{
		document.getElementById ( 'Translations' ).innerHTML = this.getResponseText ( );
		document.trjax = 0;
	}
	document.trjax.send ( );
}

function deleteTranslation ( varid )
{
	if ( confirm ( 'Are you sure?' ) )
	{
		document.deljax = new bajax ( );
		document.deljax.openUrl ( 
			'admin.php?module=news&action=deletetranslation&tid=' + varid + 
			'&nid=' + document.getElementById ( 'newsid' ).value, 
			'get', true
		);
		document.deljax.onload = function ( )
		{
			getTranslations ( );
			document.deljax = 0;
		}
		document.deljax.send ( );
	}
}

addOnload ( function ( )
	{
		if ( document.getElementById ( 'newsid' ).value <= 0 ) return false;
		dragger.addTarget ( document.getElementById ( 'DropTranslations' ) );
		document.getElementById ( 'DropTranslations' ).onDragDrop = function ( )
		{
			if ( dragger.config.objectType != 'News' )
				alert ( 'Du kan bare slippe nyheter her.' );
			else
			{
				document.tjax = new bajax ( );
				document.tjax.openUrl (
					'admin.php?module=news&action=addtranslation&nid=' +
					document.getElementById ( 'newsid' ).value +
					'&tid=' + dragger.config.objectID,
					'get', true
				);
				document.tjax.onload = function ( )
				{
					if ( this.getResponseText ( ) != "OK" ) alert ( this.getResponseText ( ) );
					else getTranslations ( );
				}
				document.tjax.send ( );
			}
		}
		getTranslations ( );
	}
);
