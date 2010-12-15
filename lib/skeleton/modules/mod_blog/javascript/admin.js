
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

function mod_blog_pos ( pos )
{
	var jax = new bajax ( );
	jax.openUrl ( ACTION_URL + 'mod=mod_blog&modaction=standard&bpos=' + pos, 'get', true );
	jax.onload = function ( )
	{
		ge ( 'mod_blog_content' ).innerHTML = this.getResponseText ( );
	}
	jax.send ( );
}
function mod_blog_new ( )
{
	var jax = new bajax ( );
	jax.openUrl ( ACTION_URL + 'mod=mod_blog&modaction=new', 'get', true );
	jax.onload = function ( )
	{
		var dom = document.createElement ( 'div' );
		dom.innerHTML = this.getResponseText ( );
		var old = ge ( 'mod_blog_content' ).getElementsByTagName ( 'div' )[0];
		var pnode = old.parentNode;
		pnode.replaceChild ( dom, old );
		mod_blog_setform ( );
	}
	jax.send ( );
}
function mod_blog_setform ( id )
{
	var elements = ge ( 'mod_blog_content' ).getElementsByTagName ( 'input' );
	for ( var a = 0; a < elements.length; a++ )
	{
		if ( elements[ a ].type == 'hidden' )
		{
			elements[ a ].style.position = 'absolute';
			elements[ a ].style.left = '-1000px';
			elements[ a ].style.visibility = 'hidden';
		}
	}
	editor.addControl ( ge ( 'BlogLeadin' ) );
	editor.addControl ( ge ( 'BlogBody' ) );
	if ( id )
		ge ( 'mod_blog_content' ).style.borderColor = '#3E719C';
	else ge ( 'mod_blog_content' ).style.borderColor = '#a00';
	ge ( 'mod_blog_content' ).style.borderWidth = '2px';
}
function mod_blog_edit ( id )
{
	var jax = new bajax ( );
	jax.openUrl ( ACTION_URL + 'mod=mod_blog&modaction=edit&bid=' + id, 'get', true );
	jax.id = id;
	jax.onload = function ( )
	{
		var dom = document.createElement ( 'div' );
		dom.innerHTML = this.getResponseText ( );
		var old = ge ( 'mod_blog_content' ).getElementsByTagName ( 'div' )[0];
		var pnode = old.parentNode;
		pnode.replaceChild ( dom, old );
		mod_blog_setform ( this.id );
	}
	jax.send ( );
}
function mod_blog_delete ( id )
{
	if ( confirm ( 'Er du sikker på at du ønsker å slette denne artikkelen?' ) )
	{
		var jax = new bajax ( );
		jax.openUrl ( ACTION_URL + 'mod=mod_blog&modaction=delete&bid=' + id, 'get', true );
		jax.onload = function ( )
		{
			ge ( 'mod_blog_content' ).innerHTML = this.getResponseText ( );
		}
		jax.send ( );
	}
}
function mod_blog_save ( )
{
	ge ( 'mod_blog_saveblog' ).innerHTML = 'Lagrer...';
	ge ( 'BlogLeadin' ).value = editor.get ( 'BlogLeadin' ).getContent ( );
	ge ( 'BlogBody' ).value = editor.get ( 'BlogBody' ).getContent ( );
	var elements = Array (
		ge ( 'mod_blog_content' ).getElementsByTagName ( 'input' ),
		ge ( 'mod_blog_content' ).getElementsByTagName ( 'textarea' )
	);
	var sjax = new bajax ( );
	sjax.openUrl ( ACTION_URL + 'mod=mod_blog&modaction=save', 'post', true );
	if ( ge ( 'BlogIdentifier' ).value > 0 )
		sjax.addVar ( 'bid', ge ( 'BlogIdentifier' ).value );
	for ( var b = 0; b < elements.length; b++ )
	{
		var eles = elements[ b ];
		for ( var a = 0; a < eles.length; a++ )
		{
			if ( eles[ a ].id && eles[ a ].id.indexOf ( 'Blog' ) >= 0 )
			{
				var idliteral =  eles[ a ].id.substr ( 4, eles[ a ].id.length - 4 );
				idliteral = idliteral.split ( '_id' ).join ( '' );
				sjax.addVar ( idliteral, eles[ a ].value );
			}
		}
	}
	sjax.onload = function ( )
	{
		ge ( 'mod_blog_saveblog' ).innerHTML = 'Lagre blog artikkel';
		if ( !ge ( 'BlogIdentifier' ) ) return;
		if ( ge ( 'BlogIdentifier' ).value <= 0 )
		{
			if ( ge ( 'BlogLeadin' ) )
			{
				editor.removeControl ( 'BlogLeadin' );
				editor.removeControl ( 'BlogBody' );
			}
			if ( parseInt ( this.getResponseText ( ) ) > 0 )
			{
				mod_blog_edit ( this.getResponseText ( ) );
			}
			else alert ( 'Kunne ikke lagre blog artikkelen.' );
		}
	}
	sjax.send ( );
}
function mod_blog_removeimage ( bid )
{
	var i = new bajax ( );
	i.openUrl ( ACTION_URL + 'mod=mod_blog&modaction=removeimage&bid=' + bid, 'get', true );
	i.onload = function ( )
	{
		if ( ge ( 'BlogLeadin' ) )
		{
			editor.removeControl ( 'BlogLeadin' );
			editor.removeControl ( 'BlogBody' );
		}
		mod_blog_edit ( bid );
	}
	i.send ( );
}
function mod_blog_settings()
{
	initModalDialogue ( 'blogsettings', 640, 480, ACTION_URL + 'mod=mod_blog&modaction=settings', null );
}
function mod_blog_savesettings ( ) 
{
	ge ( 'mod_blog_savetext' ).innerHTML = 'Lagrer innstillingene';
	var sj = new bajax ( );
	sj.openUrl ( ACTION_URL + 'mod=mod_blog&modaction=savesettings', 'post', true );
	sj.addVar ( 'limit', ge ( 'mod_blog_limit' ).value );
	sj.addVar ( 'comments', ge ( 'mod_blog_comments' ).checked ? '1' : '0' );
	sj.addVar ( 'showauthor', ge ( 'mod_blog_showauthor' ).checked ? '1' : '0' );
	sj.addVar ( 'tagbox', ge ( 'mod_blog_tagbox' ).checked ? '1' : '0' );
	sj.addVar ( 'tagbox_placement', ge ( 'mod_tagbox_placement' ).value );
	sj.addVar ( 'searchbox', ge ( 'mod_blog_searchbox' ).checked ? '1' : '0' );
	sj.addVar ( 'detailpage', ge ( 'mod_blog_detailpage' ).getElementsByTagName ( 'select' )[0].value );
	sj.addVar ( 'sourcepage', ge ( 'mod_blog_sourcepage' ).getElementsByTagName ( 'select' )[0].value );
	sj.addVar ( 'leadinlength', ge ( 'mod_blog_leadinlength' ).value );
	sj.addVar ( 'titlelength', ge ( 'mod_blog_titlelength' ).value );
	sj.addVar ( 'sizex', ge ( 'mod_blog_sizex' ).value );
	sj.addVar ( 'sizey', ge ( 'mod_blog_sizey' ).value );
	sj.addVar ( 'headertext', ge ( 'mod_blog_headertext' ).value );
	sj.addVar ( 'hidedetails', ge ( 'mod_blog_hide_details' ).checked ? '1' : '0' );
	sj.addVar ( 'facebooklike', ge ( 'mod_blog_facebooklike' ).checked ? 1 : '0' );
	sj.addVar ( 'facebooklikedimensions', ge ( 'mod_blog_facebooklikewidth' ).value + ':' + ge ( 'mod_blog_facebooklikeheight' ).value );
	sj.onload = function ( )
	{
		if ( ge ( 'mod_blog_savetext' ) )
		{
			ge ( 'mod_blog_savetext' ).innerHTML = i18n ( 'saved' );
			setTimeout ( "ge ( 'mod_blog_savetext' ).innerHTML = '"+i18n('Save')+"';", 200 );
		}
	}
	sj.send ( );
}

function mod_blog_preview ( bid )
{
	window.open ( 'admin.php?module=extensions&extension=editor&mod=mod_blog&modaction=preview&bid=' + bid, '', 'width=640,height=480,status=no,resize=yes,scrollbars=1,topbar=no' );
}

function mod_blog_authentication ( )
{
	var j = new bajax ( );
	j.openUrl ( 'admin.php?module=extensions&extension=editor&mod=mod_blog&modaction=authentication', 'get', true );
	j.onload = function ( )
	{
		ge ( 'mod_blog_content' ).innerHTML = this.getResponseText ( );
	}
	j.send ( );
}

