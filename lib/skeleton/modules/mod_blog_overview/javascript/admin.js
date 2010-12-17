
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

function mod_blogoversikt_new()
{
	initModalDialogue('blogoversikt_new', 512, 512, ACTION_URL + 'mod=mod_blog_overview&modaction=new');
}


function mod_blog_overview_save()
{
	// Collect values
	var eles = document.getElementById ( 'mod_blogoverview_list' ).getElementsByTagName ( 'input' );
	var navigation = document.getElementById ('mod_blogoverview_list').getElementsByTagName('select');
	var pageAr = new Array ( );
	var amouAr = new Array ( );
	var navAr = new Array();
	var titles = new Array();

	for ( var a = 0; a < eles.length; a++ )
	{
		var ele = eles[a];
		if ( ele.type == "checkbox" )
		{
			var id = ele.id.split ( '_' )[1];
			if ( ele.checked )
			{
				pageAr.push ( id );
				amouAr.push ( document.getElementById ( 'blog_amount_' + id ).value );
				navAr.push(document.getElementById('navigateselect' + id).options[document.getElementById('navigateselect' + id).selectedIndex].value);
			}
		}
		else if ( ele.name.indexOf ( 'title' ) >= 0 )
			titles.push ( ele.value );
	}
	// Send request to server	
	var j = new bajax ( );
	var url = ACTION_URL + 'mod=mod_blog_overview&modaction=executeadd';
	j.openUrl ( url, 'post', true );
	j.addVar ( 'page', pageAr.join ( "_" ) );
	j.addVar ( 'amounts', amouAr.join ( "_"  ) );
	j.addVar ( 'titles', titles.join ( "\t\t" ).split ( '#' ).join ( '%hash%' ) );
	j.addVar ( 'listmode', document.getElementById ( 'mod_blog_listmode' ).value );
	j.addVar ( 'sizex', document.getElementById ( 'mod_blogo_sizex' ).value );
	j.addVar ( 'sizey', document.getElementById ( 'mod_blogo_sizey' ).value );
	j.addVar ('nav', navAr.join("_") );
	j.onload = function ( )
	{
		document.getElementById ( 'mod_blogoverview_content' ).innerHTML = this.getResponseText ( );
		updateStructure();
		removeModalDialogue('blogoversikt_new');
	}
	j.send ( );
}

