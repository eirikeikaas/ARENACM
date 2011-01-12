
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

function setContent ( cid )
{
	document.location = 'admin.php?module=extensions&extension=editor&cid=' + cid;
}

function savePage ( )
{
	if ( document.getElementById ( 'MenuTitle' ).value.length <= 0 )
	{
		alert ( i18n ( 'You need to fill in a menu title.' ) );
		document.getElementById ( 'MenuTitle' ).focus ( );
		return false;
	}
	if ( document.getElementById ( 'Title' ).value.length <= 0 )
	{
		alert ( i18n ( 'You need to fill in a searchable page title.' ) );
		document.getElementById ( 'Title' ).focus ( );
		return false;
	}
	
	for ( var a = 0; a < savefuncs.length; a++ )
		savefuncs[a]();
	
	var cid = document.getElementById ( 'PageID' ).value;
	var pjax = new bajax ( );
	pjax.openUrl ( 
		'admin.php?module=extensions&extension=editor&action=save', 
		'post', true );
	pjax.addVar ( 'cid', cid );
	if ( document.getElementById ( 'IntroText' ) )
		pjax.addVar ( 'Intro', document.getElementById ( 'IntroText' ).value );
	if ( document.getElementById ( 'BodyText' ) )
		pjax.addVar ( 'Body', document.getElementById ( 'BodyText' ).value );
	pjax.addVar ( 'Title', document.getElementById ( 'Title' ).value );
	pjax.addVar ( 'MenuTitle', document.getElementById ( 'MenuTitle' ).value );
	var notes = document.getElementById ( 'PageNotes' ).value;
	if ( typeof ( notes ) != 'undefined' && notes.length )
		pjax.addVar ( 'Notes', document.getElementById ( 'PageNotes' ).value );
	if ( document.getElementById ( 'LinkText' ) )
		pjax.addVar ( 'Link', document.getElementById ( 'LinkText' ).value );
	if ( ge ( 'LinkTarget' ) )
		pjax.addVar ( 'LinkTarget', ge ( 'LinkTarget' ).value );
	
	// Extra fields
	var elements = document.getElementById ( 'ContentForm' ).getElementsByTagName ( '*' );
	var extrafields = new Array ( );
	if ( elements )
	{
		for ( var a = 0; a < elements.length; a++ )
			if ( elements[ a ].className.indexOf ( 'ExtraFieldData' ) >= 0 )
				extrafields.push ( elements[ a ] );
	}
	for ( var a = 0; a < extrafields.length; a++ )
	{
		var ed;
		if ( ed = editor.get ( extrafields[ a ].id ) )
			extrafields[ a ].value = ed.getContent ( );
		pjax.addVar ( extrafields[ a ].id, extrafields[ a ].value );
	}
	
	pjax.onload = function ( )
	{
		var structure = this.getResponseText ( ).split ( '<!-- separate -->' );
		var pageUrl = structure[1];
		structure = structure[0];
		if ( structure.indexOf ( '<error>' ) >= 0 )
		{
			structure = structure.split ( '<error>' )[1].split ( '</error>' )[0];
			alert ( structure );
		}
		else
		{
			document.getElementById ( 'PageUrl' ).value = pageUrl;
			document.getElementById ( 'StructureContainer' ).innerHTML = structure;
			makeCollapsable ( document.getElementById ( 'Structure' ) );
			var as = document.getElementById ( 'Structure' ).getElementsByTagName ( 'a' );
			for ( var z = 0; z < as.length; z++ )
			{
				if ( as[z].className == 'current' )
				{
					document.getElementById ( 'EditHeadlineDiv' ).innerHTML = as[z].firstChild.innerHTML;
					break;
				}
			}
		}
		updateButtons ( );
	}
	pjax.send ( );
}

function subPage ( )
{
	var cid = document.getElementById ( 'PageID' ).value;
	var pjax = new bajax ( );
	pjax.openUrl (
		'admin.php?module=extensions&extension=editor&action=subpage',
		'post', true );
	pjax.addVar ( 'cid', cid );
	pjax.onload = function ( )
	{
		var structure = this.getResponseText ( );
		if ( structure.indexOf ( '<error>' ) >= 0 )
		{
			structure = structure.split ( '<error>' )[1].split ( '</error>' )[0];
			alert ( structure );
		}
		else
		{
			document.getElementById ( 'StructureContainer' ).innerHTML = structure;
			makeCollapsable ( document.getElementById ( 'Structure' ) );
		}
		updateButtons ( );
	}
	pjax.send ( );
}

var publishQueue = new Array ( );
function checkPublishQueue ( cid, ch )
{
	if ( !ch.checked )
	{
		var o = new Array ( );
		for ( var a = 0; a < publishQueue.length; a++ )
		{
			if ( publishQueue[ a ] != cid )
				o.push ( publishQueue[ a ] );
		}
		publishQueue = o;
	}
	else
	{
		publishQueue.push ( cid );
	}
}
function publishPageElements ( )
{
	removeModalDialogue ( 'publishqueue' );
	var p = new bajax ( );
	p.openUrl ( 'admin.php?module=extensions&extension=editor&action=publishqueue', 'post', true );
	p.addVar ( 'oids', publishQueue.join ( ',' ) );
	p.onload = function ( )
	{
		publishPage ( 1, true );
	}
	p.send ( );
}

function publishPage ( doReload, ignoreUnpublished )
{
	if ( document.getElementById ( 'MenuTitle' ).value.length <= 0 )
	{
		alert ( i18n ( 'You need to fill in a menu title.' ) );
		document.getElementById ( 'MenuTitle' ).focus ( );
		return false;
	}
	if ( document.getElementById ( 'Title' ).value.length <= 0 )
	{
		alert ( i18n ( 'You need to fill in a searchable page title.' ) );
		document.getElementById ( 'Title' ).focus ( );
		return false;
	}
	
	var cid = document.getElementById ( 'PageID' ).value;
	var testj = new bajax ( );
	testj.openUrl (
		'admin.php?module=extensions&extension=editor&action=testpublishqueue',
		'post', true
	);
	testj.addVar ( 'cid', cid );
	testj.onload = function ( )
	{
		if ( this.getResponseText ( ) == 'ok' || ignoreUnpublished )
		{
			var pjax = new bajax ( );
			pjax.openUrl ( 
				'admin.php?module=extensions&extension=editor&action=publish', 
				'post', true );
			pjax.addVar ( 'cid', cid );
			pjax.onload = function ( )
			{
				var structure = this.getResponseText ( );
				if ( structure.indexOf ( '<error>' ) >= 0 )
				{
					structure = structure.split ( '<error>' )[1].split ( '</error>' )[0];
					alert ( structure );
				}
				else
				{
					document.getElementById ( 'StructureContainer' ).innerHTML = structure;
					makeCollapsable ( document.getElementById ( 'Structure' ) );
				}
				if ( doReload )
				{
					document.location = 'admin.php?module=extensions&extension=editor';
				}
				else updateButtons ( );
			}
			pjax.send ( );
		}
		else
		{
			showPublishQueue ( cid );
		}
	}
	testj.send ( );
}

function revertPage ( )
{
	if ( confirm ( i18n ( 'Are you sure you want to roll back the\npublished version? The current work copy\nwill be erased.' ) ) )
	{
		var cid = document.getElementById ( 'PageID' ).value;
		var pjax = new bajax ( );
		pjax.openUrl ( 
			'admin.php?module=extensions&extension=editor&action=revert', 
			'post', true );
		pjax.addVar ( 'cid', cid );
		pjax.onload = function ( )
		{
			document.location = 'admin.php?module=extensions&extension=editor';
		}
		pjax.send ( );
	}
}

function showPublishQueue ( cid )
{
	initModalDialogue ( 'publishqueue', '480', '200', 'admin.php?module=extensions&extension=editor&action=publishqueue&cid=' + cid );
}

function deletePage ( )
{
	if ( confirm ( i18n ( 'Are you sure you want to delete this page?' ) ) )
	{
		var cid = document.getElementById ( 'PageID' ).value;
		var pjax = new bajax ( );
		pjax.openUrl ( 
			'admin.php?module=extensions&extension=editor&action=delete', 
			'post', true );
		pjax.addVar ( 'cid', cid );
		pjax.onload = function ( )
		{
			var cid = this.getResponseText ( );
			if ( cid.indexOf ( '<error>' ) >= 0 )
			{
				cid = cid.split ( '<error>' )[1].split ( '</error>' )[0];
				alert ( cid );
			}
			else
			{
				document.location = 'admin.php?module=extensions&extension=editor&cid=' + cid;
			}
		}
		pjax.send ( );
	}
}

function updateStructure ( )
{
	var cid = document.getElementById ( 'PageID' ).value;
	var pjax = new bajax ( );
	pjax.openUrl ( 
		'admin.php?module=extensions&extension=editor&action=structure', 
		'post', true );
	pjax.addVar ( 'cid', cid );
	pjax.onload = function ( )
	{
		var structure = this.getResponseText ( );
		if ( structure.indexOf ( '<error>' ) >= 0 )
		{
			structure = structure.split ( '<error>' )[1].split ( '</error>' )[0];
			alert ( structure );
		}
		else
		{
			document.getElementById ( 'StructureContainer' ).innerHTML = structure;
			makeCollapsable ( document.getElementById ( 'Structure' ) );
		}
	}
	pjax.send ( );
	checkOrderChanged ( )
}

function updateButtons ( )
{
	var cid = document.getElementById ( 'PageID' ).value;
	var pjax = new bajax ( );
	pjax.openUrl ( 
		'admin.php?module=extensions&extension=editor&action=buttons', 
		'post', true );
	pjax.addVar ( 'cid', cid );
	pjax.onload = function ( )
	{
		var data = this.getResponseText ( ).split ( '<!-- separate -->' );
		document.getElementById ( 'StructureButtons' ).innerHTML = data[2];
		document.getElementById ( 'SmallButtons' ).innerHTML = data[1];
		document.getElementById ( 'BottomButtons' ).innerHTML = data[0];
	}
	pjax.send ( );
}

function checkOrderChanged ( )
{
	var cjax = new bajax ( );
	cjax.openUrl ( 'admin.php?module=extensions&extension=editor&action=orderchangedquery', 'get', true );
	cjax.onload = function ( )
	{
		document.getElementById ( 'StructureChangedButton' ).innerHTML = this.getResponseText ();
	}
	cjax.send ( );
}

function reorder ( dir )
{
	var sel = document.getElementById ( 'ReorderSelect' );
	var opts = sel.options;
	var index = sel.selectedIndex;
	
	// give ids
	for ( var a = 0; a < opts.length; a++ ) { opts[ a ].id = a + 1; }
	
	// Backtrack to where level starts
	for ( var a = index; a > 0 && checkOptionDepth ( opts[ a ] ) >= checkOptionDepth ( opts[ index ] ); a-- )
	{ }; 
	var start = ++a;
	
	// The other element to move against
	var target = index + dir;
	if ( target < 0 || target >= opts.length )
		return false;
	// Find the root of the target block if it is a block and we're moving up
	while ( dir < 0 && checkOptionDepth ( opts[ target ] ) > checkOptionDepth ( opts[ index ] ) )
	{ target--; }
	// Find the root of the target block if it is a block and we're moving down
	while ( dir > 0 && checkOptionDepth ( opts[ target ] ) > checkOptionDepth ( opts[ index ] ) )
	{ target++; }
	// We might have gone astray, so double check
	if ( target < 0 || target >= opts.length )
		return false;
	
	// Set objects involved
	var targetObj = opts[ target ];
	var sourceObj = opts[ index ];
	var targetID = targetObj.id;
	var sourceID = sourceObj.id;
	
	if ( checkOptionDepth ( targetObj ) < checkOptionDepth ( sourceObj ) )
		return false;
	
	// Save contents of eventual blocks
	var targetBlock = new Array ( );
	var sourceBlock = new Array ( );
	// We are moving the source block, remove children for the time being
	if ( checkOptionDepth ( opts[ index + 1 ] ) > checkOptionDepth ( opts[ index ] ) )
	{
		for ( var a = index + 1; a < opts.length && checkOptionDepth ( opts[ a ] ) > checkOptionDepth ( opts[ index ] ); a++ )
			sourceBlock.push ( opts[ a ] );
		for ( var a = 0; a < sourceBlock.length; a++ )
			sel.removeChild ( sourceBlock[ a ] );
	}
	
	// Find our indices again
	for ( var a = 0; a < opts.length; a++ )
	{
		if ( opts[ a ].id == targetID ) target = a;
		if ( opts[ a ].id == sourceID ) index = a;
	}
	
	// We are moving the target block, remove children for the time being
	if ( checkOptionDepth ( opts[ target + 1 ] ) > checkOptionDepth ( opts[ target ] ) )
	{
		for ( var a = target + 1; a < opts.length && checkOptionDepth ( opts[ a ] ) > checkOptionDepth ( opts[ target ] ); a++ )
			targetBlock.push ( opts[ a ] );
		for ( var a = 0; a < targetBlock.length; a++ ) 
			sel.removeChild ( targetBlock[ a ] );
	}
	
	// Find our indices again
	for ( var a = 0; a < opts.length; a++ )
	{
		if ( opts[ a ].id == targetID ) target = a;
		if ( opts[ a ].id == sourceID ) index = a;
	}
	
	// Make sure we only move on our own level
	if ( checkOptionDepth ( opts[ index + dir ] ) == checkOptionDepth( opts[ index ] ) )
	{
		// Move only a single option
		var t = opts[ index ];
		sel.removeChild ( opts[ index ] );
		sel.insertBefore ( t, opts[ index + dir ] );
	}
	
	// Find our indices again
	for ( var a = 0; a < opts.length; a++ )
	{
		if ( opts[ a ].id == targetID ) target = a;
		if ( opts[ a ].id == sourceID ) index = a;
	}
	
	// Add children on source if it was a block
	if ( sourceBlock.length )
	{
		if ( index + 1 >= opts.length )
		{
			for ( var a = 0; a < sourceBlock.length; a++ )
				sel.appendChild ( sourceBlock[ a ] );
		}
		else
		{
			var inext = opts[ index + 1 ];
			for ( var a = 0; a < sourceBlock.length; a++ )
				sel.insertBefore ( sourceBlock[ a ], inext );
		}
		delete sourceBlock;
	}
	
	// Find our indices again
	for ( var a = 0; a < opts.length; a++ )
	{
		if ( opts[ a ].id == targetID ) target = a;
		if ( opts[ a ].id == sourceID ) index = a;
	}
	
	// Add targetblocks
	if ( targetBlock.length )
	{
		//alert ( opts[ target ].text );
		if ( target + 1 >= opts.length )
		{
			for ( var a = 0; a < targetBlock.length; a++ )
				sel.appendChild ( targetBlock[ a ] );
		}
		else
		{
			var tnext = opts[ target + 1 ];
			for ( var a = 0; a < targetBlock.length; a++ )
				sel.insertBefore ( targetBlock[ a ], tnext );
		}
		delete targetBlock;
	}
	
	return;
}
function checkOptionDepth ( opt )
{
	depth = 0;
	if ( !opt )
		return false;
	for ( var a = 0; a < opt.innerHTML.length; a += 6 )
	{
		if ( opt.innerHTML.substr ( a, 6 ) == '&nbsp;' )
		{
			depth++;
		}
		else return depth;
	}
	return depth;
}

function saveorder ( )
{
	var sel = document.getElementById ( 'ReorderSelect' );
	var str = '';
	for ( var a = 0; a < sel.options.length; a++ )
	{
		str += a + ':' + sel.options[ a ].value + ';';
	}
	str = str.substr ( 0, str.length - 1 );
	var orderj = new bajax ( );
	orderj.openUrl ( 'admin.php?module=extensions&extension=editor&action=saveorder', 'post', true );
	orderj.addVar ( 'ids', str );
	orderj.onload = function ( )
	{
		updateStructure ( );	
	}
	orderj.send ( );
}


function publishSortOrder ( )
{
	var cjax = new bajax ( );
	cjax.openUrl ( 'admin.php?module=extensions&extension=editor&action=publishsortorder', 'get', true );
	cjax.onload = function ( )
	{
		checkOrderChanged ( );
	}
	cjax.send ( );
}

function movePage ( )
{
	var cid = document.getElementById ( 'PageID' ).value;
	initModalDialogue ( 
		'move',
		480, 375,
		'admin.php?module=extensions&extension=editor&action=movepage&cid=' + cid
	);
}

function executeMove ( )
{
	var target = document.getElementById ( 'StructureMove' ).getElementsByTagName ( 'select' )[0].value;
	var cid = document.getElementById ( 'PageID' ).value;
	if ( cid == target )
	{
		alert ( i18n ( 'You can not move a page onto itself.' ) );
		return false;
	}
	var mjax = new bajax ( );
	mjax.openUrl ( 
		'admin.php?module=extensions&extension=editor&action=executemovepage' +
		'&cid=' + cid + '&target=' + target,
		'get', true );
	mjax.onload = function ( )
	{
		var res = this.getResponseText ( ).split ( '<!-- separate -->' );
		if ( res[ 0 ] == 'ok' )
		{
			document.getElementById ( 'StructureMove' ).innerHTML = res[ 1 ];
			updateStructure ( );
		}
		else
		{
			alert ( res[ 1 ] );
		}
	}
	mjax.send ( );
}

function reorderPage ( )
{
	var cid = document.getElementById ( 'PageID' ).value;
	initModalDialogue ( 
		'reorder', 
		480, 375,
		'admin.php?module=extensions&extension=editor&action=reorder&cid=' + cid
	);
}

function previewPage ( )
{
	var cid = document.getElementById ( 'PageID' ).value;
	window.open ( document.getElementById ( 'PageUrl' ).value + '?editmode=1', '', 'width=920,height=600,topbar=no,scrollbars=yes,resize=yes,status=no' );
}

/** Showing module blocks *****************************************************/

function showConnectedModules ( )
{
	var jax = new bajax ( );
	jax.openUrl ( 'admin.php?module=extensions&extension=editor&action=showmodules', 'post', true );
	jax.addVar ( 'type', 'connected' );
	jax.addVar ( 'pid', document.getElementById ( 'PageID' ).value );
	jax.onload = function ( )
	{
		document.getElementById ( 'pageModulesConnected' ).innerHTML = this.getResponseText ( );
	}
	jax.send ();
}

function showFreeModules ( )
{
	var jax = new bajax ( );
	jax.openUrl ( 'admin.php?module=extensions&extension=editor&action=showmodules', 'post', true );
	jax.addVar ( 'type', 'free' );
	jax.addVar ( 'pid', document.getElementById ( 'PageID' ).value );
	jax.onload = function ( )
	{
		document.getElementById ( 'pageModulesAvailable' ).innerHTML = this.getResponseText ( );
	}
	jax.send ();
}

function showProModules ( )
{
	var jax = new bajax ( );
	jax.openUrl ( 'admin.php?module=extensions&extension=editor&action=showmodules', 'post', true );
	jax.addVar ( 'type', 'pro' );
	jax.addVar ( 'pid', document.getElementById ( 'PageID' ).value );
	jax.onload = function ( )
	{
		document.getElementById ( 'pageModulesPro' ).innerHTML = this.getResponseText ( );
	}
	jax.send ();
}

/** Start Free modules **/

function addModule ( modname )
{
	var ajax = new bajax ( );
	ajax.openUrl ( 
		'admin.php?module=extensions&extension=editor&action=addmodule',
		'post', true );
	ajax.addVar ( 'mod', modname );
	ajax.onload = function ( )
	{
		var res = this.getResponseText ( ).split ( '<!-- separate -->' );
		if ( res[0] == 'ok' )
		{
			showConnectedModules ( );
			showFreeModules ( );
			document.getElementById ( 'tabModulesConnected' ).onclick ();
		}
		else if ( res[0] == 'okreload' )
		{
			document.getElementById ( 'tabModulesConnected' ).onclick ();
			document.location = 'admin.php?module=extensions&extension=editor';
		}
		else alert ( res[1] );
	}
	ajax.send ( );
}

function delModule ( modname )
{
	var ajax = new bajax ( );
	ajax.openUrl (
		'admin.php?module=extensions&extension=editor&action=delmodule',
		'post', true );
	ajax.addVar ( 'mod', modname );
	ajax.onload = function ( )
	{
		if ( this.getResponseText ( ) == 'okreload' )
		{
			document.location = 'admin.php?module=extensions&extension=editor';
		}
		else
		{
			showConnectedModules ( );
			showFreeModules ( );
			showProModules ( );
		}
	}
	ajax.send ( );
}

function activateModule ( modname )
{
	var ajax = new bajax ( );
	ajax.openUrl ( 
		'admin.php?module=extensions&extension=editor&action=activatemodule',
		'post', true );
	ajax.addVar ( 'mod', modname );
	ajax.addVar ( 'cid', document.getElementById ( 'PageID' ).value );
	ajax.onload = function ( )
	{
		if ( this.getResponseText ( ) == 'ok' )
		{
			document.location = 'admin.php?module=extensions&extension=editor&cid=' + document.getElementById ( 'PageID' ).value;
		}
		else alert ( i18n ( 'Unexpected error.' ) );
	}
	ajax.send ( );
}

function deactivateModule ( modname )
{
	var ajax = new bajax ( );
	ajax.openUrl ( 
		'admin.php?module=extensions&extension=editor&action=deactivatemodule',
		'post', true );
	ajax.addVar ( 'mod', modname );
	ajax.addVar ( 'cid', document.getElementById ( 'PageID' ).value );
	ajax.onload = function ( )
	{
		if ( this.getResponseText ( ) == 'ok' )
		{
			document.location = 'admin.php?module=extensions&extension=editor&cid=' + document.getElementById ( 'PageID' ).value;
		}
		else alert ( i18n ( 'Unexpected error.' ) );
	}
	ajax.send ( );
}

/** End free modules **/

/** Start pro modules **/

function addProModule ( modname )
{
	if ( confirm ( i18n ( 'Are you sure you want to add this module?\nYou will be charged on your next invoice\nwith the amount shown.' ) ) )
	{
		var ajax = new bajax ( );
		ajax.openUrl ( 
			'admin.php?module=extensions&extension=editor&action=addpromodule',
			'post', true );
		ajax.addVar ( 'mod', modname );
		ajax.onload = function ( )
		{
			var res = this.getResponseText ( ).split ( '<!-- separate -->' );
			if ( res[0] == 'ok' )
			{
				showConnectedModules ( );
				showProModules ( );
				document.getElementById ( 'tabModulesConnected' ).onclick ();
			}
			else if ( res[0] == 'okreload' )
			{
				document.getElementById ( 'tabModulesConnected' ).onclick ();
				document.location = 'admin.php?module=extensions&extension=editor';
			}
			else alert ( res[1] );
		}
		ajax.send ( );
	}
}

function activateProModule ( modname )
{
	var jax = new bajax ( );
	jax.openUrl ( 
		'admin.php?module=extensions&extension=editor&action=addpromodule',
		'post', true );
	jax.addVar ( 'mod', modname );
	jax.addVar ( 'cid', document.getElementById ( 'PageID' ).value );
	jax.onload = function ( )
	{
		if ( this.getResponseText ( ) == 'ok' )
		{
			document.location = 'admin.php?module=extensions&extension=editor&cid=' + document.getElementById ( 'PageID' ).value;
		}
		else alert ( i18n ( 'Unexpected error.' ) );
	}
	jax.send ( );
}

/** End pro modules **/

function changeLanguage( id )
{
	document.location = 'admin.php?module=extensions&extension=editor&languageid=' + id;
}

function addField ( )
{
	initModalDialogue ( 'addfield', 400, 530, 'admin.php?module=extensions&extension=editor&action=dlg_addfield&cid=' + document.getElementById ( 'PageID' ).value, function ( ){ document.getElementById ( 'diaform' ).Name.focus ( ); } );
}

function executeAddField ( )
{
	var frm = document.getElementById ( 'diaform' );
	var type = getRadioValue ( frm.type );
	var global = getRadioValue ( frm.global );
	var contentgroup = getRadioValue ( frm.contentgroup );
	if ( !contentgroup )
	{
		alert ( i18n ( 'You need to choose a content group.' ) );
		return false;
	}
	var aja = new bajax ( );
	aja.openUrl ( 'admin.php?module=extensions&extension=editor&action=addfield', 'post', true );
	aja.addVar ( 'Name', frm.Name.value );
	aja.addVar ( 'ContentGroup', contentgroup );
	aja.addVar ( 'Type', type );
	aja.addVar ( 'IsGlobal', global );
	aja.addVar ( 'cid', document.getElementById ( 'PageID' ).value );
	if ( frm.fieldextension )
		aja.addVar ( 'fieldextension', frm.fieldextension.value );
	aja.onload = function ( )
	{
		var r = this.getResponseText ( ).split ( '<!-- separate -->' );
		switch ( r[0] )
		{
			case 'ok':
				document.location = 'admin.php?module=extensions&extension=editor&cid=' + document.getElementById ( 'PageID' ).value;
				break;
			default:
				if ( r[ 1 ] ) alert ( r[ 1 ] );
				break;
		}
	}
	aja.send ( );
}

function reorderField ( fid, ft, dir )
{
	if ( confirm ( i18n ( 'Are you sure you want to move the field?' ) ) )
	{
		document.location = 'admin.php?module=extensions&extension=editor&action=reorderfield&dir=' + dir + '&fid=' + fid + '&cid=' + document.getElementById ( 'PageID' ).value + '&ft=' + ft;
	}
}

function removeField ( fid, ft, dir )
{
	if ( confirm ( i18n ( 'Are you sure you want to remove the field?' ) ) )
	{
		document.location = 'admin.php?module=extensions&extension=editor&action=delfield&dir=' + dir + '&fid=' + fid + '&cid=' + document.getElementById ( 'PageID' ).value + '&ft=' + ft;
	}
}

function editEditorField ( fid, ft )
{
	initModalDialogue ( 'editfield', 400, 530, 'admin.php?module=extensions&extension=editor&action=dlg_editfield&cid=' + document.getElementById ( 'PageID' ).value + '&ft=' + ft + '&fid=' + fid, function ( ){ document.getElementById ( 'diaform' ).Name.focus ( ); } );
}

function executeEditField ( )
{
	var frm = document.getElementById ( 'diaform' );
	var type = '';
	if ( frm.type ) type = getRadioValue ( frm.type );
	var global = getRadioValue ( frm.global );
	var contentgroup = getRadioValue ( frm.contentgroup );
	if ( !contentgroup )
	{
		alert ( i18n ( 'You need to choose a content group.' ) );
		return false;
	}
	var aja = new bajax ( );
	aja.openUrl ( 'admin.php?module=extensions&extension=editor&action=editfield', 'post', true );
	aja.addVar ( 'fid', frm.field_id.value );
	aja.addVar ( 'ft', frm.field_type.value );
	aja.addVar ( 'Name', frm.Name.value );
	aja.addVar ( 'SortOrder', frm.SortOrder.value );

	if ( type == 'extension' && frm.fieldextension )
		aja.addVar ( 'fieldextension', frm.fieldextension.value );
	
	aja.addVar ( 'ContentGroup', contentgroup );
	if ( type.length )
		aja.addVar ( 'Type', type );
	aja.addVar ( 'IsGlobal', global );
	aja.addVar ( 'adminvisibility', document.getElementById ( 'fieldadminvisibility' ).checked ? '1' : '0' );
	aja.addVar ( 'cid', document.getElementById ( 'PageID' ).value );
	aja.onload = function ( )
	{
		alert ( this.getResponseText () );
		/*var r = this.getResponseText ( ).split ( '<!-- separate -->' );
		switch ( r[0] )
		{
			case 'ok':
				document.location = 'admin.php?module=extensions&extension=editor&cid=' + document.getElementById ( 'PageID' ).value;
				break;
			default:
				if ( r[ 1 ] ) alert ( r[ 1 ] );
				break;
		}*/
	}
	aja.send ( );
}

function advancedSettings ( )
{
	initModalDialogue ( 'advanced', 480, 340, 
		'admin.php?module=extensions&extension=editor&action=dlg_advanced_settings&cid=' + document.getElementById ( 'PageID' ).value 
	);
}

function executeAdvanced ( )
{
	var frm = document.getElementById ( 'advanced_form' );
	var jax = new bajax ( );
	jax.openUrl (
		'admin.php?module=extensions&extension=editor&action=dlg_advanced_settings',
		'post', true 
	);
	jax.addVar ( 'cid', document.getElementById ( 'PageID' ).value );
	jax.addVar ( 'system', getRadioValue ( frm.IsSystem ) );
	jax.addVar ( 'published', getRadioValue ( frm.IsPublished ) );
	jax.addVar ( 'contenttype', frm.ContentType.value );
	jax.addVar ( 'systemname', frm.SystemName.value );
	jax.addVar ( 'contentgroups', frm.ContentGroups.value );
	jax.addVar ( 'contenttemplateid', frm.ContentTemplateID.value );
	if ( frm.Template )
		jax.addVar ( 'template', frm.Template.value );
	if ( frm.ModuleName )
	{
		jax.addVar ( 'modulename', frm.ModuleName.value );
		jax.addVar ( 'modulecontentgroup', frm.ModuleContentGroup.value );
	}
	jax.onload = function ()
	{
		if ( this.getResponseText ( ) == 'ok' )
		{
			document.location = 'admin.php?module=extensions&extension=editor&cid=' + document.getElementById ( 'PageID' ).value;
		}
		else
		{
			alert ( i18n ( 'Failed.' ) );
		}
	}
	jax.send ( );
}

function createTemplate ( )
{
	var i = prompt ( i18n ( 'Templatename' ) + ':', '' );
	if ( i.length )
	{
		var j = new bajax ( );
		j.openUrl ( 'admin.php?module=extensions&extension=editor&action=createtemplate&cid=' + document.getElementById ( 'PageID' ).value, 'post', true );
		j.addVar ( 'name', i );
		j.onload = function ( )
		{
			alert ( this.getResponseText ( ) );
		}
		j.send ( );
		
	}
}

function deleteTemplates ()
{
	var inps = document.getElementsByTagName ( 'input' );
	var checks = new Array ();
	for ( var a = 0; a < inps.length; a++ )
	{
		if ( inps[a].type.toLowerCase() != 'checkbox' ) continue;
		var id = inps[a].id ? inps[a].id.split('_') : false;
		if ( id && inps[a].checked )
		{
			checks.push ( id[1] );
		}
	}
	if ( checks.length )
	{
		if ( confirm ( i18n ( 'Are you sure?' ) ) )
		{
			var tj = new bajax ();
			tj.openUrl ( 'admin.php?module=extensions&extension=editor&action=deletetemplates&ids=' + checks.join ( ',' ), 'get', true );
			tj.onload = function () 
			{
				document.getElementById ( 'pageTemplates' ).innerHTML = this.getResponseText ();
			}
			tj.send ();
		}
	}
}

function eraseSelected ()
{
	
	var ids = new Array ();
	var eles = document.getElementsByTagName ( 'input' );
	for ( var a = 0; a < eles.length; a++ )
	{
		if ( eles[a].id.substr ( 0, 6 ) == 'trash_' && eles[a].checked )
		{
			ids.push ( eles[a].id.split ( '_' )[1] );
		}
	}
	if ( ids.length )
	{
		if ( confirm ( i18n ( 'Do you really want to erase the selected items?' ) ) )
		{
			document.location = 'admin.php?module=extensions&extension=editor&action=erase&ids=' + ids.join ( ',' );
		}
	}
	else alert ( i18n ( 'You need to select some items.' ) );
}

function restoreSelected ()
{
	var ids = new Array ();
	var eles = document.getElementsByTagName ( 'input' );
	for ( var a = 0; a < eles.length; a++ )
	{
		if ( eles[a].id.substr ( 0, 6 ) == 'trash_' && eles[a].checked )
		{
			ids.push ( eles[a].id.split ( '_' )[1] );
		}
	}
	if ( ids.length )
	{
		document.location = 'admin.php?module=extensions&extension=editor&action=restoredeleted&ids=' + ids.join ( ',' );
	}
	else alert ( i18n ( 'You need to select some items.' ) );
}

var savefuncs = new Array ( );
function AddSaveFunction ( func )
{
	savefuncs.push ( func );
}

addOnload ( checkOrderChanged );
