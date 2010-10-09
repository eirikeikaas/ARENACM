

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



function savePage ( )
{
	ExecuteSaveFunctions ( );
	var editor = document.editor;
	var content = document.getElementById ( "ContentForm" );
	
	var texts = document.getElementById ( "ContentForm" ).getElementsByTagName ( "textarea" );
	if ( texts ) for ( var a = 0; a < texts.length; a++ )
	{
		if ( texts[ a ].className == 'mceSelector' )
		{	 
			if ( !texts[ a ].id )
				continue;
				
			// MSIE has problems with hidden editor fields. This is a workaround.
			if ( isIE && texts[ a ].parentNode.style.visibility == 'hidden' )
			{
				if ( !texts[ a ].value.length && document.getElementById ( texts[ a ].id + '_hidden' ) )
				{
					texts[ a ].value = document.getElementById ( texts[ a ].id + '_hidden' ).innerHTML;
				}
			}
			else
			{
				if ( editor.get ( texts[ a ].id ) )
					texts[ a ].value = editor.get ( texts[ a ].id ).getContent ( );
			}
		}
	}
	if ( content.MenuTitle.value.length <= 0 )
	{	
		alert ( "Du må fylle inn en menytittel." );
		content.MenuTitle.focus ( );
		return false;
	}
	content.submit ();
}

function getVisibilityState ( cid )
{
	var pjax = new bajax ( );
	pjax.openUrl ( 'admin.php?module=contents&action=getvisibilitystate&cid=' + cid, 'get', true );
	pjax.onload = function ( )
	{
		if ( document.getElementById ( 'visibilitystate' ) )
			document.getElementById ( 'visibilitystate' ).innerHTML = this.getResponseText ( );
	}
	pjax.send ( );
}

// Roll back published version to work copy
function rollBack ( cid )
{
	var pjax = new bajax ( );
	pjax.openUrl ( 'admin.php?module=contents&action=unpublish&cid=' + cid, 'get', true );
	pjax.onload = function ( )
	{
		showMainContent ( this.getResponseText ( ), 'tabEditBox' );
		showStructure ( this.getResponseText ( ) );
	}
	pjax.send ( );
}

// Publish work copy
function publishPage ( cid )
{
	var pjax = new bajax ( );
	pjax.openUrl ( 'admin.php?module=contents&action=publish&cid=' + cid, 'get', true );
	pjax.onload = function ( )
	{
		var id = this.getResponseText ( ).split ( '|' )[ 0 ];
		var error = this.getResponseText ( ).split ( '|' )[ 1 ];
		
		if ( id )
		{
			showEditButtons ( id );
			getPublishButton ( id );
			showStructure ( id );
		}
		else alert ( 'Failed to publish page!' );
	}
	pjax.send ( );
}

function newPage ( )
{
	document.ModalSelection = false;
	initModalDialogue ( 'SelectTemplate', 320, 340, 'admin.php?module=contents&function=selecttemplate' );	
}
function makeNewPage ( sel )
{
	var url = 'admin.php?module=contents&action=newpage&template=' + sel + '&' +
		'menutitle=' + document.getElementById ( 'NewPageMenuTitle' ).value + '&' +
		'title=' + document.getElementById ( 'NewPageTitle' ).value;
	document.location = url;
}

function makeCopy ( )
{
	document.location = 'admin.php?module=contents&action=makecopy&cid=' + ContentID;
}

function deletePage ( varid )
{
	if ( !varid ) varid = false;
	if ( confirm ( 'Er du sikker? Dette vil også fjerne all historikk og alle oversettelser!' ) )
	{
		var djax = new bajax ( );
		djax.openUrl ( 'admin.php?module=contents&action=delete' + ( varid ? ( '&varid=' + varid ) : '' ), 'get', true );
		djax.onload = function ( )
		{
			showMainContent ( this.getResponseText ( ), 'tabEditBox' );
			showStructure ( this.getResponseText ( ) );
		}
		djax.send ( );
	}
}

function changeOrder ( offset, contentid )
{
	document.orderjax = new bajax ( );
	document.orderjax.openUrl (
		'admin.php?module=contents&action=changeorder&offset=' + offset + '&cid=' + contentid,
		'get', true
	);
	document.orderjax.onload = function ( )
	{
		if ( isIE )
		{
			document.getElementById ( 'Structure' ).innerHTML = this.getResponseText ( );
			makeCollapsable ( document.getElementById ( 'Structure' ) );
		}
		else
		{
			var struct = document.createElement ( "UL" );
			struct.innerHTML = this.getResponseText ( );
			struct.id = "Structure";
			struct.className = "Collapsable";
			makeCollapsable ( struct );
			document.getElementById ( 'Structure' ).parentNode.replaceChild ( struct, document.getElementById ( 'Structure' ) );
		}
		document.orderjax = 0;
	}
	document.orderjax.send ( );
}

function makeTemplate ( )
{
	var request = prompt ( "Skriv navn på template: ", "" );
	if ( request && request.length > 0 && ContentID )
		document.location = 'admin.php?module=contents&action=maketemplate&cid=' + ContentID + '&templatename=' + encodeURIComponent ( request ) + '&template=' + document.ModalSelection;
	else alert ( "Du må ha et navn på templaten." );
}

function showStructure ( varid, langid )
{
	if ( !langid ) langid = ""; 
	else 
	{
		langid = "&setlang=" + langid;
		varid = varid ? varid : 'root';
	}
	ContentID = varid;
	var structjax = new bajax ( );
	structjax.openUrl ( 'admin.php?module=contents&action=showstructure&needspeed=true&cid=' + varid + langid, 'get', true );
	structjax.onload = function ( )
	{
		var struct = document.createElement ( "UL" );
		struct.innerHTML = this.getResponseText ( );
		struct.id = "Structure";
		struct.className = "Collapsable";
		makeCollapsable ( struct );
		document.getElementById ( 'Structure' ).parentNode.replaceChild ( struct, document.getElementById ( 'Structure' ) );
	}
	structjax.send ( );
}

function showDeletedContent ( )
{
	deljax = new bajax ( );
	deljax.openUrl ( 'admin.php?module=contents&action=showdeletedcontent', 'get', true );
	deljax.onload = function ( )
	{
		document.getElementById ( 'DeletedContent' ).innerHTML = this.getResponseText ( );
	}
	deljax.send ( );
}

function showTemplates ( varlang )
{
	if ( !varlang ) varlang = '';
	else varlang = '&settpllang=' + varlang;
	document.tpljax = new bajax ( );
	document.tpljax.openUrl ( 'admin.php?module=contents&action=showtemplates' + varlang, 'get', true );
	document.tpljax.onload = function ( )
	{
		document.getElementById ( 'TplContent' ).innerHTML = this.getResponseText ( );
		document.tpljax = 0;
	}
	document.tpljax.send ( );
}

function showTemplateSetup ( cid )
{
	var tjax = new bajax ( );
	tjax.openUrl ( 'admin.php?module=contents&action=showtemplatesetup&cid=' + cid, 'get', true );
	tjax.onload = function ( )
	{
		var pt = document.getElementById ( 'pageTemplate' );
		if ( pt )
		{
			var n = document.createElement ( 'div' );
			n.id = pt.id;
			n.className = pt.className;
			n.innerHTML = this.getResponseText ( );
			pt.parentNode.replaceChild ( n, pt );
		}
	}
	tjax.send ( );
}


function undeleteSelected ( varmode )
{
	if ( !varmode ) varmode = "";
	switch ( varmode )
	{
		default: 
			var entr;
			if ( entr = getUniqueListEntries ( 'cntdel' ) )
			{
				entr = entr.join ( ',' );
				document.delsel = new bajax ( );
				document.delsel.openUrl ( 'admin.php?module=contents&action=undeletecontent&ids=' + entr, 'get', true );
				document.delsel.onload = function ( ){ showDeletedContent ( ); showStructure ( ); document.delsel = 0; }
				document.delsel.send ( );
			} else alert ( 'Ingen elementer er valgt.' );
			break;
	}
}

function deleteSelected ( varmode )
{
	if ( !varmode ) varmode = "";
	switch ( varmode )
	{
		case 'tpldel':
			var entr;
			if ( entr = getUniqueListEntries ( 'tpldel' ) )
			{
				if ( confirm ( 'Er du sikker? Disse får du ikke tilbake!' ) )
				{
					entr = entr.join ( ',' );
					document.delsel = new bajax ( );
					document.delsel.openUrl ( 'admin.php?module=contents&action=deletetemplates&ids=' + entr, 'get', true );
					document.delsel.onload = function ( ){ showTemplates ( ); document.delsel = 0; }
					document.delsel.send ( );
				}
			} else alert ( 'Ingen maler er valgt.' );
			break;
		default: 
			var entr;
			if ( entr = getUniqueListEntries ( 'cntdel' ) )
			{
				if ( confirm ( 'Er du sikker? Disse får du ikke tilbake!' ) )
				{
					entr = entr.join ( ',' );
					document.delsel = new bajax ( );
					document.delsel.openUrl ( 'admin.php?module=contents&action=deletecontent&ids=' + entr, 'get', true );
					document.delsel.onload = function ( ){ showDeletedContent ( ); document.delsel = 0; }
					document.delsel.send ( );
				}
			} else alert ( 'Ingen elementer er valgt.' );
			break;
	}
}

function alterTemplateLanguage ( )
{
	var entr;
	if ( entr = getUniqueListEntries ( 'tpldel' ) )
	{
		entr = entr.join ( ',' );
		initModalDialogue ( 'MoveToLanguage', 300, 300, 'admin.php?module=contents&action=movetemplateslanguage&ids=' + entr );
	}
	else
	{
		alert ( 'Ingen elementer er valgt.' );
	}
}

function moveContent ( cid, pid )
{
	document.movejax = new bajax ( );
	document.movejax.openUrl ( 'admin.php?module=contents&action=movecontent&cid=' + cid + '&pid=' + pid, 'get', true );
	document.movejax.onload = function ( )
	{
		showStructure ( cid );
		document.movejax = 0;
	}
	document.movejax.send ( );
}

var dynamicscripts = new Array ( );
var mainscripts = new Array ( );

function showMainContent ( cid, activetab )
{
	if ( !document.getElementById ( 'ColumnMiddleTd' ) )
	{
		setTimeout ( "showMainContent ( '" + cid + "', '" + activetab + "' )", 10 );
		return;
	}
	var showloading = false;
	if ( !activetab )
	{
		showloading = true;
		activetab = 'tabEditBox';
	}
	if ( !cid ) cid = '';
	
	/*
		Clear function queue
	*/
	ClearSaveFunctions ( );
	
	/* 
		Remove old editors and clean up
	*/
	var editors = document.getElementById ( 'ColumnMiddleTd' ).getElementsByTagName ( 'TEXTAREA' );
	for ( var a = 0; a < editors.length; a++ )
	{
		if ( editors[ a ].className == 'mceSelector' )
		{
			editor.removeControl ( editors[ a ].id );
		}
	}
	
	// Deletable?
	//t_inyMCEmode = false;

	/* Uninit droptargets */
	if ( !document.getElementById ( 'MainContentPlaceholder' ) || showloading )
	{
		document.getElementById ( 'ColumnMiddleTd' ).innerHTML = '<div class="Container" id="MainContentPlaceholder">Laster inn innhold...</div>';
	}
	
	/*
		Fetch new content!
	*/
	document.mainjax = new bajax ( );
	document.mainjax.openUrl ( 'admin.php?module=contents&action=showmaincontent&needspeed=true' + ( cid ? ( '&cid=' + cid ) : '' ), 'get', true );	
	document.mainjax.onload = function ( )
	{
		/* Add new HTML */
		var MiddleContent = document.createElement ( "DIV" );
		var Switch = document.getElementById ( 'MainContentPlaceholder' );
		MiddleContent.id = Switch.id;
		var Result = this.getResponseText ( );
		if ( Result.indexOf ( "<script" ) > 0 )
		{
			var ScriptStart = "<script";
			var Script = Result.split ( ScriptStart );
			for ( var a = 0; a < Script.length; a++ )
			{
				if ( Script[ a ].substr ( 0, 23 ) == ' type="text/javascript"' )
				{
					var Source = Script[ a ].split ( "javascript\" src=\"" );
					if ( Source[ 1 ] )
					{
						Source = Source[ 1 ].split ( "\"" );
						var pos = dynamicscripts.length;
						dynamicscripts[ pos ] = new bajax ( );
						dynamicscripts[ pos ].openUrl ( Source[ 0 ], 'get', true );
						dynamicscripts[ pos ].ind = pos;
						dynamicscripts[ pos ].onload = function ( )
						{
							eval ( this.getResponseText ( ) );
							var osc = new Array ( );
							for ( var a = 0; a < dynamicscripts.length; a++ )
							{
								if ( a != this.pos )
									osc.push ( dynamicscripts[ a ] );
							}
							dynamicscripts = osc;
						}
						dynamicscripts[ pos ].send ( );
					}
					else
					{
						// Eval inline javascript
						var Source = Script[ a ].split ( ">" );
						Source = Source[ 1 ].split ( "</" );
						if ( Source[ 0 ] )
						{
							mainscripts[ mainscripts.length ] = Source[ 0 ];
						}
					}
				}
			}
		}
		MiddleContent.innerHTML = Result;
		
		/* Try to init more sub tabs */
		initToggleBoxes ( MiddleContent );
		
		var elements = getElementsByClassName ( 'subTabs', MiddleContent );
		if ( elements ) for ( var a = 0; a < elements.length; a++ )
			initTabSystem ( elements[ a ], 'subtabs' );
		initTabSystem ( MiddleContent );
		/* Init toggle boxes */
		var subs = MiddleContent.getElementsByTagName ( 'div' );
		for ( var sa = 0; sa < subs.length; sa++ )
		{
			if ( subs[ sa ].id == activetab )
			{
				activateTab ( subs[ sa ] );
			}
		}
		Switch.parentNode.replaceChild ( MiddleContent, Switch );
		
		// Execute scripts found
		if ( mainscripts.length )
		{
			for ( var z = 0; z < mainscripts.length; z++ )
			{
				eval ( mainscripts[ z ] );
			}
			mainscripts = false;
		}
			
		/* Update ContentID */
		if ( document.getElementById ( 'ContentID_Value' ) )
		{
			ContentID = document.getElementById ( 'ContentID_Value' ).value;
			ContentType = document.getElementById ( 'ContentTable_Value' ).value;
			
			// Get button to publish ( if needed )
			getPublishButton ( cid );
			showEditButtons ( cid );
			getVisibilityState ( cid );
			
			/* Init editors */
			editor.idCounter = 0;
			if ( !isKonqueror && !isSafari )
			{
				var editors = document.getElementById ( 'ColumnMiddleTd' ).getElementsByTagName ( 'TEXTAREA' );
				if ( editors ) for ( var a = 0; a < editors.length; a++ )
				{	
					if ( editors[ a ].className == 'mceSelector')
					{
						if ( editors[ a ].initialized ) continue;
						editors[ a ].initialized = true;
						editor.addControl ( editors[ a ].id );
					}
				}
			}
			
			// Deletable?
			//t_inyMCEmode = true;
		}
		
		document.mainjax = 0;
	}
	document.mainjax.send ( );
}

function showEditButtons ( cid )
{
	// Content buttons
	buttonjax = new bajax ( );
	buttonjax.openUrl ( 'admin.php?module=contents&action=getcontentbuttons&needspeed=true&cid=' + cid, 'post', true );
	buttonjax.onload = function ( )
	{
		document._contentbuttonHTML = this.getResponseText ( );
		showEditButtonsQueued ( );
	}
	buttonjax.send ( );
	
	// Small buttons
	suttonjax = new bajax ( );
	suttonjax.openUrl ( 'admin.php?module=contents&action=getsmalltopbuttons&needspeed=true&cid=' + cid, 'post', true );
	suttonjax.onload = function ( )
	{
		document._smalltopbuttonHTML = this.getResponseText ( );
		showSmallEditButtonsQueued ( );
	}
	suttonjax.send ( );
}

/**
 * Big ones with text
**/
function showEditButtonsQueued ( )
{
	if ( document.getElementById ( 'ContentButtons' ) )
	{
		document.getElementById ( 'ContentButtons' ).innerHTML = document._contentbuttonHTML;
		document._contentbuttonHTML = 0;
		
		if ( document.getElementById ( 'Publishbutton' ) )
		{
			if ( document.getElementById ( 'Publishbutton' ) )
			{
				addToolTip ( 'Publiser siden', 'Klikk her for å publisere versjonen du arbeider med.', 'MainPublishbutton' );
				addToolTip ( 'Rull tilbake siden', 'Klikk her for å rulle tilbake den publiserte versjonen over arbeidsversjonen.', 'MainRollbackbutton' );
			}
		}
		
		addToolTip ( 'Lagre', 'Klikk her for å lagre alle endringene dine på denne siden.', 'savepage' );
		addToolTip ( 'Slett', 'Klikk her for å sende siden til søppel.', 'deletepage' );
		addToolTip ( 'Lag en blank kopi', 'Lag en blank kopi av siden du nå har valgt. Siden vil ha samme mal og felter, men med tomt innhold.', 'makeblankcopy' );
		addToolTip ( 'Nytt underinnhold', 'Lag underinhold under den aktive siden. Underinnhold kan spesifiseres med en spesifik mal eller tom mal.', 'underinnhold' );
		addToolTip ( 'Til arbeidsbenken', 'Legg ned en lenke på arbeidsbenken som representerer denne siden. Lenken kan du koble til på andre elementer.', 'sidetilarbeidsbenk' );
	}
	else setTimeout ( 'showEditButtonsQueued ( )', 25 );
}

/**
 * Small buttons on top
**/
function showSmallEditButtonsQueued ( )
{
	if ( document.getElementById ( 'SmallTopButtons' ) )
	{
		document.getElementById ( 'SmallTopButtons' ).innerHTML = document._smalltopbuttonHTML;
		document._contentbuttonHTML = 0;
		
		if ( document.getElementById ( 'SmallMainPublishbutton' ) )
		{
			addToolTip ( 'Publiser siden', 'Klikk her for &ariong; publisere versjonen du arbeider med.', 'SmallMainPublishbutton' );
			addToolTip ( 'Rull tilbake siden', 'Klikk her for å rulle tilbake den publiserte versjonen over arbeidsversjonen.', 'SmallMainRollbackbutton' );
		}
		addToolTip ( 'Lagre', 'Klikk her for å lagre alle endringene dine på denne siden.', 'Smallsavepage' );
		addToolTip ( 'Slett', 'Klikk her for å sende siden til søppel.', 'Smalldeletepage' );
		addToolTip ( 'Lag en blank kopi', 'Lag en blank kopi av siden du nå har valgt. Siden vil ha samme mal og felter, men med tomt innhold.', 'Smallmakeblankcopy' );
		addToolTip ( 'Nytt underinnhold', 'Lag underinhold under den aktive siden. Underinnhold kan spesifiseres med en spesifik mal eller tom mal.', 'Smallunderinnhold' );
		addToolTip ( 'Til arbeidsbenken', 'Legg ned en lenke på arbeidsbenken som representerer denne siden. Lenken kan du koble til på andre elementer.', 'Smallsidetilarbeidsbenk' );
	}
	else setTimeout ( 'showSmallEditButtonsQueued ( )', 25 );
}

function changeLanguage ( langid )
{
	activateTab ( document.getElementById ( 'tabStructure' ), document.getElementById ( 'sitemaptabs' ) );
	showStructure ( 'root', langid );
}

function showPreview ( url )
{
	var width = getDocumentWidth ( );
	var height = getDocumentHeight ( );
	var win = window.open ( url, '', 'width=' + width + ',height=' + height +',topbar=no,toolbar=no,extratoolbars=no,scrollbars=yes,resize=yes,status=no' );
}

function getPublishButton ( cid )
{
	document.pjax = new bajax ( );
	document.pjax.openUrl ( 'admin.php', 'post', true );
	document.pjax.addVar ( 'module', 'contents' );
	document.pjax.addVar ( 'action', 'getpublishbutton' );
	document.pjax.addVar ( 'needspeed', 'true' );
	document.pjax.addVar ( 'cid', cid );
	document.pjax.onload = function ( )
	{
		if ( document.getElementById ( 'Publishbutton' ) )
		{
			document.getElementById ( 'Publishbutton' ).innerHTML = this.getResponseText ( );
			if ( document.getElementById ( 'MainPublishbutton' ) )
			{
				addToolTip ( 'Publiser siden', 'Klikk her for å publisere versjonen du arbeider med.', 'MainPublishbutton' );
				addToolTip ( 'Rull tilbake siden', 'Klikk her for å rulle tilbake den publiserte versjonen over arbeidsversjonen.', 'MainRollbackbutton' );
			}
			if ( document.getElementById ( 'SmallMainPublishbutton' ) )
			{
				addToolTip ( 'Publiser siden', 'Klikk her for å publisere versjonen du arbeider med.', 'SmallMainPublishbutton' );
				addToolTip ( 'Rull tilbake siden', 'Klikk her for å rulle tilbake den publiserte versjonen over arbeidsversjonen.', 'SmallMainRollbackbutton' );
			}
		}
		document.pjax = 0;
	}
	document.pjax.send ( );
}

function publishEverything ( )
{
	document.location = 'admin.php?module=contents&action=publisheverything';
}

function toolsCheckFieldsOptions ( )
{
	if ( document.getElementById ( 'CheckFieldsFields' ) )
	{
		document.fopjax = new bajax ( );
		document.fopjax.openUrl ( 'admin.php?module=contents&action=getcheckfieldsoptions', 'get', true );
		document.fopjax.onload = function ( )
		{
			var child = document.getElementById ( 'CheckFieldsFields' );
			var newop = document.createElement ( 'select' );
			newop.id = child.id;
			newop.name = child.name;
			newop.size = '15';
			newop.multiple = 'multiple';
			if ( !isIE )
			{
				newop.style.boxSizing = 'border-box';
				newop.style.mozBoxSizing = 'border-box';
			}
			newop.style.width = '100%';
			newop.innerHTML = this.getResponseText ( );
			child.parentNode.replaceChild ( newop, child );
		}
		document.fopjax.send ( );
	}
}

function toolsCheckFieldsPageOptions ( )
{
	if ( document.getElementById ( 'CheckFieldsPages' ) )
	{
		document.fppjax = new bajax ( );
		document.fppjax.openUrl ( 'admin.php?module=contents&action=getcheckfieldspageoptions', 'get', true );
		document.fppjax.onload = function ( )
		{
			var child = document.getElementById ( 'CheckFieldsPages' );
			var newop = document.createElement ( 'select' );
			newop.id = child.id;
			newop.name = child.name;
			newop.size = '15';
			newop.multiple = 'multiple';
			if ( !isIE )
			{
				newop.style.boxSizing = 'border-box';
				newop.style.mozBoxSizing = 'border-box';
			}
			newop.style.width = '100%';
			newop.innerHTML = this.getResponseText ( );
			child.parentNode.replaceChild ( newop, child );
		}
		document.fppjax.send ( );
	}
}

/**
 * Save functions happens when clicking "Save" on the content element
**/
__savefunctions = new Array ( );

function AddSaveFunction ( func )
{
	__savefunctions[ __savefunctions.length ] = func;
}

function ExecuteSaveFunctions ( )
{
	for ( var a = 0; a < __savefunctions.length; a++ )
	{
		__savefunctions[ a ] ( );
	}
}

function ClearSaveFunctions ( )
{
	__savefunctions = new Array ( );
}

function rSync ( )
{
	document.location = 'admin.php?module=contents&action=rsync';
}


/** START!!! module ***********************************************************/

var bajaxLoading = false;
document.bajaxSurpressScrollbars = function ( )
{
	if ( globalBajaxProcesses <= 0 && bajaxLoading )
	{
		document.body.style.overflow = "auto";
		return;
	}
	else if ( globalBajaxProcesses > 0 )
	{
		bajaxLoading = true;
	}
	setTimeout ( "document.bajaxSurpressScrollbars ( )", true );
}

document.bajaxSurpressScrollbars ( );

function init_contents ( )
{
	if ( document.getElementById ( 'tabContent' ) )
	{
		toolsCheckFieldsOptions ( );
		toolsCheckFieldsPageOptions ( );
		if ( getCookie ( 'contentmoduleleft' ) == 'hidden' )
			ToggleContents ( "left", "contentmodule" );
		if ( getCookie ( 'contentmoduleright' ) == 'hidden' )
			ToggleContents ( "right", "contentmodule" );
	}
	else setTimeout ( 'init_contents ()', 25 );
}
init_contents ( );

