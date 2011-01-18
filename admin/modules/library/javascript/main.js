
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

var modulepath = "admin.php?module=library";
var editButtonHTML = '';			// to be used by editLibraryLevel and abortLibraryLevelEdit

var udFileTitle;
var defaultMainCol = '';

function updateLibraryLevelTree ( html )
{
	var tree = document.createElement ( 'ul' );
	tree.id = 'LibraryLevelTree';
	tree.className = 'Collapsable';
	tree.innerHTML = html;
		
	if ( !isIE ) makeCollapsable ( tree );
	document.getElementById ( 'LibraryLevelTree' ).parentNode.replaceChild ( tree, document.getElementById ( 'LibraryLevelTree' ) );
	if ( isIE ) makeCollapsable ( tree );
}

/** 
 * execute search
**/
function ModuleLibrarySearch()
{
	if( document.getElementById( 'libSearchKeywords' ) &&  document.getElementById( 'libSearchKeywords' ).value != '' )
	{

		document.searchjax = new bajax ( );
		document.searchjax.openUrl ( modulepath + "&function=search&libSearchKeywords=" + encodeURIComponent( document.getElementById( 'libSearchKeywords' ).value ), 'GET', true );
		document.searchjax.onload = function ( )
		{
			var response = this.getResponseText ( ).split( '<!--SEPERATOR-->' );
			
			// Folders
			if( response[0] && response[0] != '' )
			{
				document.getElementById ( 'searchResults' ).innerHTML = '<div class="Container" style="margin-top: 2px"><h2>Søkresultat / Mapper</h2>' + response[0] + '</div>';
			}
			else
			{
				document.getElementById ( 'searchResults' ).innerHTML = '<div class="Container" style="margin-top: 2px"><p class="Info">Ingen mapper funnet til søket.</p></div>';
			}
			
			// Files
			if( response[1] && response[1] != '' )
			{
				if( defaultMainCol == '' ) defaultMainCol = document.getElementById ( 'libMainCol' ).innerHTML;
				
				document.getElementById ( 'libMainCol' ).innerHTML = '<h1>Søkresultat / Innhold</h1><div class="Container">' + response[1] + '<div class="SpacerSmall"></div></div>';
				eval( extractScripts( response[1] ).join("\n") );
				document.getElementById ( 'libNullStillSoek' ).style.position = 'relative';
				document.getElementById ( 'libNullStillSoek' ).style.visibility = 'visible';
			}
			else
			{
				document.getElementById ( 'libMainCol' ).innerHTML = '<h1>Søkeresultat</h1><div class="Container"><p>Ingen filer matchet ditt søk.</p></div>';
			}
			document.searchjax = 0;	
		}
		document.searchjax.send ( );

	}
} // end of librarySearch

function ModuleResetLibrarySearch ( )
{
	document.getElementById( 'libSearchKeywords' ).value = '';
	document.getElementById( 'libNullStillSoek' ).style.visibility = 'hidden';
	document.getElementById( 'libNullStillSoek' ).style.position = 'absolute';
	if ( document.getElementById ( 'searchResults' ) )
		document.getElementById ( 'searchResults' ).innerHTML = '';
	showLibraryContent();
}

/** 
 * move item to another folder
**/
function moveItem( sourceType, sourceID, targetType, targetID )
{
	
	var targetItem = targetType+":"+targetID;
	var sourceItem = sourceType+":"+sourceID;
	var turl = modulepath + "&action=moveitems&target="+targetItem+"&items="+sourceItem;
			
	var movejax = new bajax ( );
	movejax.openUrl ( turl, 'GET', true );
	movejax.onload = function ( )
	{
		showLibraryContent();
		checkLibraryTooltips();
		if ( sourceType == 'Folder' )
		{
			setLibraryLevel ( false );
		}
	}
	movejax.send ( );
	
	

} // end of moveItem

/** 
 * successfully edit / addded image
**/	
function showUploadSuccess()
{
	removeModalDialogue ( 'EditLevel' );
	showLibraryContent();
	checkLibraryTooltips();
	reloadTags ();
}

/** 
 * error on imagesave
**/
function showUploadError( emsg )
{
	if( document.getElementById( "uploadInfoBox" ) ) document.getElementById( "uploadInfoBox" ).innerHTML = '<div class="SpacerSmall"></div><p class="error">'+ emsg +'</p>';
	else alert( emsg );
}

function reloadTags ()
{
	var t = new bajax ();
	t.openUrl ( 'admin.php?module=library&action=gettags', 'get', true );
	t.onload = function ()
	{
		if ( document.getElementById ( 'TagList' ) )
			document.getElementById ( 'TagList' ).innerHTML = this.getResponseText ();
	}
	t.send ();
}

function getByTag ( t )
{
	document.location = 'admin.php?module=library&tag='+t;
}


/**
 * edit library level => save data
**/
function saveLibraryLevelEdit( lid )
{
	if( !document.getElementById( 'editLevelForm' ) ) { alert("Siden er ikke helt lastet inn. Vent eller reload siden."); return; }
	
	var savejax = new bajax ( );
	savejax.openUrl ( modulepath + '&action=savelevel&lid=' + lid, 'POST', true );
	savejax.onload = function ( )
	{		
		removeModalDialogue ( 'EditLevel' );
		var r = this.getResponseText ( );
		updateLibraryLevelTree ( r );
		eval( extractScripts( r ).join("\n") );
		document.lid = lid;
		showLibraryContent();
		checkLibraryTooltips();
	}
	savejax.addVarsFromForm( 'editLevelForm' );
	savejax.send ( );	
} // end of saveLibraryLevelEdit

/**
 * delete library level => get form to delete it and choose what going to happen with the files.
**/
function deleteLibraryLevel( lid )
{
	document.ModalSelection = false;
	initModalDialogue ( 'EditLevel', 320, 365, modulepath + '&action=deletelevel&step=1&lid=' + lid );	
}

function doDeleteLibraryLevelEdit( lid )
{

	if( !document.getElementById( "deleteLevelForm" ) ) 
	{ 
		alert( "Teknisk feil! Feilkode: DELETE CALLED WITHOUT DELETEFORM" ); 
		removeModalDialogue ( 'EditLevel' ); 
		return; 
	}

	var deljax = new bajax ( );
	deljax.openUrl ( modulepath + '&action=deletelevel&step=2&lid=' + lid, 'POST', true );
	deljax.onload = function ( )
	{
		removeModalDialogue ( 'EditLevel' );
		var data = this.getResponseText ( ).split ( '<!--SEPARATE-->' );
		updateLibraryLevelTree ( data[0] );
		document.lid = data[ 1 ];
	
		// redo gui ....		
		showLibraryContent();
		checkLibraryTooltips();
	}
	deljax.addVarsFromForm( 'deleteLevelForm' );
	deljax.send ( );
}

/**
 * edit library level => get form to change edit it
**/
function editLibraryLevel( lid )
{
	initModalDialogue ( 'EditLevel', 730, 590, 'admin.php?module=library&function=editlevel&lid=' + lid, initEditLevel );
} 
function initEditLevel ( )
{
	editor.addControl ( document.getElementById ( 'folderDescription' ) );
}
// end of editLibraryLevel


// Get the library levels
function getLibraryLevelTree ( )
{
	var g = new bajax ( );
	g.openUrl ( 'admin.php?module=library&action=setlevel', 'get', true );
	g.onload = function ( )
	{
		updateLibraryLevelTree ( this.getResponseText ( ) );
	}
	g.send ( );
}

/**
 * close that dialogue
**/
function abortLibraryLevelEdit( lid )
{
	removeModalDialogue ( 'EditLevel' );
} // end of abortLibraryLevelEdit



/** 
 * add new library level under currently chosen one
 * update tree and content
**/
function addLibraryLevel(cid)
{
	var nm = prompt ( i18n ( 'Name the new folder' ), '' );
	if ( nm.length )
	{
		var addjax = new bajax ( );
		addjax.openUrl ( modulepath + '&action=addlevel&Name='+nm+'&Parent='+cid, 'POST', true );
		addjax.onload = function ( )
		{		
			var r = this.getResponseText ( ).split( "<!--SEPERATOR-->" );
			eval( extractScripts( r[0] ).join("\n") );
			if( r[1] ) 
			{
				currentLibraryLevel = r[1];
				if ( document.getElementById ( 'libraryParent' ) )
					document.getElementById ( 'libraryParent' ).value = r[ 1 ];
				document.lid = r[ 1 ];
			}
			// reset form field
			if( document.getElementById( 'libraryNewLevel' ) ) document.getElementById( 'libraryNewLevel' ).value = '';		
			updateLibraryLevelTree ( r[0] );
			showLibraryContent();
			checkLibraryTooltips();
		}
		addjax.send ( );
	}	
}

/**
 * set library level
 * update tree and content
**/
function setLibraryLevel ( varlev )
{
	if ( !varlev ) varlev = document.lid;
	document.lid = varlev;
	
	var updjax = new bajax ( );
	updjax.openUrl ( modulepath + '&action=setlevel&lid=' + varlev, 'get', true );
	updjax.onload = function ( )
	{		
		var r = this.getResponseText ( );
		eval( extractScripts( this.getResponseText ( ) ).join("\n") );
		updateLibraryLevelTree ( r );
		showLibraryContent( );
		checkLibraryTooltips();
		if ( document.getElementById ( 'libraryParent' ) )
			document.getElementById ( 'libraryParent' ).value = varlev;
	}
	currentLibraryLevel = varlev;
	if( document.getElementById( "newlibrarylevelparentlevel" ) ) 
		document.getElementById( "newlibrarylevelparentlevel" ).value = varlev;
	updjax.send ( );
} // end of setLibraryLevel

/**
 * Show the content of the current level in the library
**/
function showLibraryContent ( pos )
{
	
	if( !pos ) pos = 0;

	showContentButtons ( );

	var libjax = new bajax ( );
	libjax.openUrl ( modulepath + '&function=listcontents&position=' + pos + '&lid=' + document.lid, 'get', true );
	libjax.onload = function ( )
	{
		var cn = document.createElement ( 'div' );
		cn.id = 'LibraryContentDiv';
		cn.innerHTML = this.getResponseText ( );
		document.getElementById ( 'LibraryContentDiv' ).parentNode.replaceChild ( cn, document.getElementById ( 'LibraryContentDiv' ) );
		eval( extractScripts( this.getResponseText ( ) ).join("\n") );
		initContentDropTarget ( );
		checkLibraryTooltips ( );
		var b =  document.getElementById ( 'currentlevel' ).getElementsByTagName ( 'B' );
		if ( b.length )
			document.getElementById ( 'Innholdsheader' ).innerHTML = 'Innhold i "' + b[ 0 ].innerHTML + '":';
		else document.getElementById ( 'Innholdsheader' ).innerHTML = 'Innhold i hovedmappen:';
	}
	
	if( !document.getElementById ( 'LibraryContentDiv' ) )
		document.getElementById ( 'libMainCol' ).innerHTML = defaultMainCol;
	
	libjax.send ( );
	
} // end of showLibraryContent

/** 
 * Check what tooltips are there to be diplayed
**/
function checkLibraryTooltips()
{
		if( document.getElementById( "editliblevel" ) )	
			addToolTip ( 'Rediger', 'Klikk for å redigere biblioteksnivået.', 'editliblevel' );
		if( document.getElementById( "deleteliblevel" ) )	
			addToolTip ( 'Slett', 'Klikk for å slette biblioteksnivået. Du må bekrefte sletting og velge hva som skal skje med under-mappene & filene i mappen.', 'deleteliblevel' );	
		if( document.getElementById( "moveliblevel" ) )	
			addToolTip ( 'Flytt', 'Klikk for å flytte biblioteksnivået.', 'moveliblevel' );	
		if( document.getElementById( "libleveltoworkbench" ) )	
			addToolTip ( 'Legg til arbeidsbenken', 'Klikk for legge til nivået til arbeidsbenken.', 'libleveltoworkbench' );
} // end of checkLibraryTooltips


/**
 * Show buttons for adding images etc
**/
function showContentButtons ( )
{
	document.lgjax = new bajax ( );
	document.lgjax.openUrl ( 'admin.php?module=library&function=showcontentbuttons&lid=' + document.lid, 'get', true );
	document.lgjax.onload = function ( )
	{
		var r = this.getResponseText ( ) + '';
		r = r.split ( '!!!' );
	
		if ( r[1] )
		{
			var dv1 = document.createElement ( 'span' );
			dv1.id = 'ContentButtonsSmall';
			dv1.innerHTML = r[ 1 ];
			document.getElementById ( 'ContentButtonsSmall' ).parentNode.replaceChild ( dv1, document.getElementById ( 'ContentButtonsSmall' ) );
		}
		if ( r[0] )
		{
			var dv2 = document.createElement ( 'div' );
			dv2.id = 'ContentButtons';
			dv2.innerHTML = r[ 0 ];
			document.getElementById ( 'ContentButtons' ).parentNode.replaceChild ( dv2, document.getElementById ( 'ContentButtons' ) );
		}
		else
		{
			document.getElementById ( 'ContentButtons' ).innerHTML = '';
			document.getElementById ( 'ContentButtonsSmall' ).innerHTML = '';
		}
		
		document.lgjax = 0;
	}
	document.lgjax.send ( );
}

/**
 * Drop target for moving files and images
**/
function initContentDropTarget ( )
{
	var lcd = document.getElementById ( 'LibraryContentDiv' )
	dragger.addTarget( lcd );


	lcd.onDragDrop = function ( element )
	{
		if ( 
			typeof ( dragger.config.objectType ) != "undefined" && 
			typeof ( dragger.config.objectID ) != "undefined"
		)
		{
			if ( 
				( dragger.config.objectType == "Image"	) || ( dragger.config.objectType == "File" ) || ( dragger.config.objectType == "Folder" )
			)
			{
				moveItem( 
					dragger.config.objectType, dragger.config.objectID, 
					'Folder', currentLibraryLevel // currentLibraryLevel is global variable
				);
			}
			else
			{
				document.getElementById( 'LibraryMessage' ).innerHTML = "<p class='error'>Du kan kun flytte mapper, bilder eller filer.</p>";
			}
		}
	}
}

addEvent ( 'onkeydown', function ( e )
{
	e = e ? e : ( window.Event ? window.Event : window.event );
	document.libraryKey = e.keyCode ? e.keyCode : e.which;
} );
addEvent ( 'onkeyup', function ( e )
{
	document.libraryKey = '';
} );

function toggleSelectedImage ( ele )
{
	// With ctrl key down or shift key
	if ( document.libraryKey == 17 || document.libraryKey == 16 )
	{
		if ( hasClass ( ele, 'Selected' ) )
		{ 
			var classes = ele.className.split ( ' ' );
			var out = new Array ( );
			for ( var a = 0; a < classes.length; a++ )
			{
				if ( classes[ a ] != 'Selected' )
					out.push ( classes[ a ] );
			}
			ele.className = out.join ( ' ' );
		}
		else
		{
			ele.className += ' Selected';
		}
	}
	// Without keys down
	else
	{
		// Remove selected from others
		if ( !hasClass ( ele, 'Selected' ) )
		{	
			ele.className += ' Selected';
		}
		var elesr = Array ( getElementsByClassName ( 'Imagecontainer' ), getElementsByClassName ( 'Listedcontainer' ) );
		for ( var zz = 0; zz < elesr.length; zz++ )
		{
			var eles = elesr[ zz ];
			for ( var a = 0; a < eles.length; a++ )
			{
				if ( eles[ a ] != ele )
				{
					if ( hasClass ( eles[ a ], 'Selected' ) )
					{
						var classes = eles[ a ].className.split ( ' ' );
						var out = new Array ( );
						for ( var b = 0; b < classes.length; b++ )
						{
							if ( classes[ b ] != 'Selected' )
								out.push ( classes[ b ] );
						}
						eles[ a ].className = out.join ( ' ' );
					} 
				}
			}
		 }
	}
}

function createLibraryFile ( complete )
{
	if ( !complete ) 
	{
		complete = false;
		initModalDialogue ( 'newfile', 540, 400, 'admin.php?module=library&action=create_library_file' );
	}
	// Create it
	else
	{
		if ( document.getElementById ( 'nfFilename' ).value.length < 1 )
		{
			alert ( 'Du må skrive inn et filnavn!' );
			document.getElementById ( 'nfFilename' ).focus ( );
			return false;
		}
		var jax = new bajax ( );
		jax.openUrl ( 'admin.php?module=library&action=create_library_file', 'post', true );
		jax.addVar ( 'Filename', document.getElementById ( 'nfFilename' ).value );
		jax.addVar ( 'Filetype', document.getElementById ( 'nfFiletype' ).value );
		jax.addVar ( 'Content', document.getElementById ( 'nfContent' ).value );
		jax.onload = function ( )
		{
			if ( this.getResponseText ( ) == 'ok' )
			{
				removeModalDialogue ( 'newfile' );
				showLibraryContent ( );
			}
			else alert ( 'unknown response! ' + this.getResponseText ( )  );
		}
		jax.send ( );
	}
}

function saveFileContents ( fid )
{
	var jax = new bajax ( );
	jax.openUrl ( 'admin.php?module=library&action=savefilecontents&fid=' + fid, 'post', true );
	if ( document.getElementById ( 'advfileContents_cp' ) )
	{
		var html = document.getElementById ( 'pageProperties' ).getElementsByTagName ( 'iframe' )[0].getCode ( );
		jax.addVar ( 'contents', html );
	}
	else jax.addVar ( 'contents', document.getElementById ( 'advfileContents' ).value );
	jax.onload = function ( )
	{
		if ( this.getResponseText ( ) == 'ok' )
		{
			if ( ge ( 'advfileContents' ) )
			{
				document.getElementById ( 'advfileContents' ).style.background = '#ff3333';
				setTimeout ( "document.getElementById ( 'advfileContents' ).style.background = '#15191c';", 250 );
			}
		}
		else alert ( this.getResponseText ( ) );
	}
	jax.send ( );
}

function deleteSelected ( )
{
	var elesr = Array ( getElementsByClassName ( 'Imagecontainer' ), getElementsByClassName ( 'Listedcontainer' ) );
	var ids = new Array ( );
	for ( var zz = 0; zz < elesr.length; zz++ )
	{
		var eles = elesr[ zz ];
		for ( var a = 0; a < eles.length; a++ )
		{
			if ( hasClass ( eles[ a ], 'Selected' ) )
			{
				if ( eles[ a ].id.substr ( 0, 5 ) == 'image' )
				{
					ids.push ( 'image_' + eles[ a ].id.split ( 'imagecontainer' ).join ( '' ) );
				}
				else
				{
					ids.push ( 'file_' + eles[ a ].id.split ( 'tfilecontainer' ).join ( '' ) );
				}
			}
		}
	}
	if ( ids.length > 0 )
	{
		if ( confirm ( 'Er du sikker?' ) )
		{
			var jax = new bajax ( );
			jax.openUrl ( 'admin.php?module=library&action=deletebyids&ids=' + ids.join ( ',' ), 'get', true );
			jax.onload = function ( )
			{
				showLibraryContent ( );
			}
			jax.send ( );
		}
	}
}
	
function editLevelPermissions ( cid )
{
	initModalDialogue ( 'permissions', 640, 570, 'admin.php?module=library&function=levelpermissions&cid=' + cid );
}

function cleanCache ( )
{
	if ( confirm ( 'Are you sure you want to clear the image cache?' ) )
	{
		var jax = new bajax ( );
		jax.openUrl ( 'admin.php?module=library&action=clearcache', 'get', true );
		jax.onload = function ( )
		{
			if ( this.getResponseText ( ) == 'ok' )
			{
				alert ( 'All done!' );
				document.location = 'admin.php?module=library';
			}
		}
		jax.send ( );
	}
}

/** Multiple file upload etc **************************************************/

function mulDecreaseImages ( )
{
	var trs = document.getElementById ( 'MultipleFilesTable' ).getElementsByTagName ( 'tr' );
	if ( trs.length > 1 )
		trs[ trs.length - 1 ].parentNode.removeChild ( trs[ trs.length - 1 ] );
}
function mulIncreaseImages ( )
{
	var tr = document.createElement ( 'tr' );
	var index = document.getElementById ( 'MultipleFilesTable' ).getElementsByTagName ( 'tr' ).length;
	while ( document.uploadForm[ 'file_' + index ] )
		index++;
	var td = document.createElement ( 'td' );
	td.innerHTML = '' +
		'Bildetittel' + ( index + 1 ) + ': ' +
		'<input type="text" size="20" name="filename_' + index + '"/>';
	var td2 = document.createElement ( 'td' );
	td2.innerHTML = '' +
		'Bilde ' + ( index + 1 ) + ':' +
		'<input type="file" name="image_' + index + '"/>';
	tr.className = 'sw'+(index % 2 + 1);
	tr.appendChild ( td ); tr.appendChild ( td2 );
	var par = document.getElementById ( 'MultipleFilesTable' );
	if ( par.firstChild.nodeName.toLowerCase ( ) == 'tbody' )
		par.firstChild.appendChild ( tr );
	else par.appendChild ( tr );
}
function mulDecreaseFiles ( )
{
	var trs = document.getElementById ( 'MultipleFilesTable' ).getElementsByTagName ( 'tr' );
	if ( trs.length > 1 )
		trs[ trs.length - 1 ].parentNode.removeChild ( trs[ trs.length - 1 ] );
}
function mulIncreaseFiles ( )
{
	var tr = document.createElement ( 'tr' );
	var index = document.getElementById ( 'MultipleFilesTable' ).getElementsByTagName ( 'tr' ).length;
	while ( document.uploadForm[ 'file_' + index ] )
		index++;
	var td = document.createElement ( 'td' );
	td.innerHTML = '' +
		'Filtittel ' + ( index + 1 ) + ': ' +
		'<input type="text" size="20" name="filename_' + index + '"/>';
	var td2 = document.createElement ( 'td' );
	td2.innerHTML = '' +
		'Fil ' + ( index + 1 ) + ': ' +
		'<input type="file" name="file_' + index + '"/>';
	tr.className = 'sw'+(index % 2 + 1);
	tr.appendChild ( td ); tr.appendChild ( td2 );
	var par = document.getElementById ( 'MultipleFilesTable' );
	if ( par.firstChild.nodeName.toLowerCase ( ) == 'tbody' )
		par.firstChild.appendChild ( tr );
	else par.appendChild ( tr );
}

function submitSwf ( )
{
	var form = document.getElementById ( 'uploadForm' );
	var eles = document.getElementById ( 'SwfProperties' ).getElementsByTagName ( 'input' );
	if ( eles )
	{
		for ( var a = 0; a < eles.length; a++ )
		{
			var clone = document.createElement ( 'input' );
			clone.name = eles[ a ].name;
			clone.value = eles[ a ].value;
			clone.type = 'hidden';
			form.appendChild ( clone );
		}
	}
	submitFileUpload ( );
}

/** Ok here we go with an image cutting feature *******************************/

var sliceZoom = 1;
var sliceOffX = 0;
var sliceOffY = 0;
var sliceClickX = 0;
var sliceClickY = 0;

var sliceMouseDown = false;
var sliceShiftDown = false;

function initializeImageSlice ( iid )
{
	initModalDialogue ( 'slice', 800, 512, 
		'admin.php?module=library&function=imageslice&iid='+iid, setupSliceUI );
}

function resizeSliceUI ()
{
	var ui = document.getElementById ( 'SliceUI' );
	var im = document.getElementById ( 'SliceImage' );
	var tb = document.getElementById ( 'SliceToolbar' );
	
	ui.style.margin = '-8px';
	ui.style.height = ( ui.parentNode.offsetHeight - 2 ) + 'px';
	ui.style.top = '0px';
	ui.style.left = '0px';
	
	tb.style.width = '80px';
	tb.style.height = ui.style.height;
	tb.style.right = '0px';
	tb.style.top = '0px';
	
	im.style.top = '0px';
	im.style.left = '0px';
}

function zoomSlice ( m )
{
	if ( m == 'in' ) sliceZoom *= 1.5;
	else sliceZoom /= 1.5;
	
	var im = document.getElementById ( 'SliceImage' ).getElementsByTagName ( 'img' )[0];
	if ( !im.owidth ) im.owidth = im.offsetWidth;
	if ( !im.oheight ) im.oheight = im.offsetHeight;
	im.width = im.owidth * sliceZoom;
	im.height = im.oheight * sliceZoom;
}

function sliceHideSelection ()
{
	var elements = [ 'TL', 'TM', 'TR', 'ML', 'MM', 'MR', 'BL', 'BM', 'BR' ];
	for ( var a = 0; a < elements.length; a++ )
	{
		document.getElementById ( 'Slice' + elements[a] ).style.visibility = 'hidden';
	}
	elements = [
		document.getElementById ( 'SliceTop' ),
		document.getElementById ( 'SliceLeft' ),
		document.getElementById ( 'SliceRight' ),
		document.getElementById ( 'SliceBottom' ),
		document.getElementById ( 'SliceRect' )
	];
	for ( var a = 0; a < elements.length; a++ )
	{
		elements[a].style.display = 'none';
	}
}
function setupSliceUI ()
{
	var ui = document.getElementById ( 'SliceUI' );
	var im = document.getElementById ( 'SliceImage' );
	var tb = document.getElementById ( 'SliceToolbar' );
	var rk = document.getElementById ( 'SliceRect' );
	
	// Main positioning / cfg
	ui.style.position = 'relative';
	ui.style.display = 'block';
	ui.style.overflow = 'hidden';
	ui.style.background = 'url(admin/gfx/checkers.png)';
	tb.style.position = 'absolute';
	tb.style.border = '1px solid #c8c8c8';
	tb.style.background = '#c8c8c8';
	tb.style.zIndex = 40;
	im.style.position = 'absolute';
	im.style.zIndex = 10;
	
	var elements = [ 'TL', 'TM', 'TR', 'ML', 'MM', 'MR', 'BL', 'BM', 'BR' ];
	for ( var a = 0; a < elements.length; a++ )
	{
		var e = document.getElementById ( 'Slice' + elements[a] );
		e.style.position = 'absolute';
		e.style.visibility = 'hidden';
		e.style.border = '1px solid #fff';
		e.style.width = '8px';
		e.style.height = '8px';
		e.style.background = 'rgb(128,140,160)';
		e.style.opacity = 0.6;
		e.style.zIndex = 30;
	}
	im.onmousedown = function ()
	{
		sliceHideSelection ();
		sliceMouseDown = true;
		sliceClickX = mousex;
		sliceClickY = mousey;
		return false;
	}
	rk.onmousedown = im.onmousedown;
	im.onclick = function ( ) { return false; }
	addEvent ( 'onkeydown', function ( e )
	{
		if ( e.which == 16 )
			sliceShiftDown = true;
	});
	addEvent ( 'onkeyup', function ( e )
	{
		sliceShiftDown = false;
	} );
	addEvent ( 'onmouseup', function () 
	{ 
		sliceOffX = parseInt ( im.style.left );
		sliceOffY = parseInt ( im.style.top );
		sliceMouseDown = false; 
	} );
	addEvent ( 'onmousemove', function ( e ) 
	{
		// Scroll image with shift down
		if ( sliceMouseDown && sliceShiftDown )
		{
			var i = im.getElementsByTagName ( 'img')[0];
			var eh = ui.offsetHeight;
			var ew = ui.offsetWidth - tb.offsetWidth;
			var diffx = mousex - sliceClickX + sliceOffX;
			var diffy = mousey - sliceClickY + sliceOffY;
			if ( diffy > 0 ) diffy = 0;
			else if ( i.height < eh )
				diffy = 0;
			else if ( diffy < 0 - ( i.height - eh ) )
				diffy = 0 - ( i.height - eh );
			if ( diffx > 0 ) diffx = 0;
			else if ( i.width < ew )
				diffx = 0;
			else if ( diffx < 0 - ( i.width - ew ) )
				diffx = 0 - ( i.width - ew );
			im.style.left = diffx + 'px';
			im.style.top = diffy + 'px';
		}
		// Selections on image without shift down
		else if ( sliceMouseDown )
		{
			// Coords / vars
			var offx = getElementLeft ( ui ); // offset of ui element on page
			var offy = getElementTop ( ui );
			var clx = sliceClickX - offx; // coords clicked
			var cly = sliceClickY - offy;
			var mpx = mousex - offx; // coords now
			var mpy = mousey - offy;
			var mnx = Math.min ( clx, mpx );
			var mxx = Math.max ( clx, mpx );
			var mny = Math.min ( cly, mpy );
			var mxy = Math.max ( cly, mpy );
			var mw = mxx-mnx;
			var mh = mxy-mny;
			
			// Position corners of rect
			var elements = [ 'TL', 'TM', 'TR', 'ML', 'MM', 'MR', 'BL', 'BM', 'BR' ];
			for ( var a = 0; a < elements.length; a++ )
			{
				var el = document.getElementById ( 'Slice' + elements[a] );
				el.style.visibility = 'visible';
				switch ( elements[a].substr ( 0, 1 ) )
				{
					case 'T':
						el.style.top = (cly-4) + 'px';
						break;
					case 'M':
						el.style.top = (mny+Math.round(mh*0.5)-4)+'px';
						break;
					case 'B':
						el.style.top = (mpy-4) + 'px';
						break;
				}
				switch ( elements[a].substr ( 1, 1 ) )
				{
					case 'L':
						el.style.left = (clx-4) + 'px';
						break;
					case 'M':
						el.style.left = (mnx+Math.round(mw*0.5)-4)+'px';
						break;
					case 'R':
						el.style.left = (mpx-4) + 'px';
						break;
				}
			}
			
			// Shade out around rect
			var srk = document.getElementById ( 'SliceRect' );
			var st = document.getElementById ( 'SliceTop' );
			var sl = document.getElementById ( 'SliceLeft' );
			var sr = document.getElementById ( 'SliceRight' );
			var sb = document.getElementById ( 'SliceBottom' );
			st.style.display = ''; sb.style.display = '';
			sl.style.display = ''; sr.style.display = '';
			st.style.visibility = 'visible'; st.style.top = '0px'; st.style.left = '0px';
			st.style.width = ui.offsetWidth + 'px'; st.style.height = mny + 'px';
			sb.style.visibility = 'visible'; sb.style.top = mxy + 'px'; st.style.left = '0px';
			sb.style.width = ui.offsetWidth + 'px'; sb.style.height = (ui.offsetHeight-mxy) + 'px';
			sl.style.visibility = 'visible'; sl.style.top = mny + 'px'; sl.style.left = '0px';
			sl.style.width = mnx + 'px'; sl.style.height = mh + 'px';
			sr.style.visibility = 'visible'; sr.style.top = mny + 'px'; sr.style.left = mxx + 'px';
			sr.style.width = (ui.offsetWidth - mxx) + 'px'; sr.style.height = mh + 'px';
			srk.style.display = '';
			srk.style.top = (mny) + 'px';
			srk.style.left = (mnx) + 'px';
			srk.style.width = (mw-2) + 'px';
			srk.style.height = (mh-2) + 'px';
		}
	} );
	
	// Resize
	resizeSliceUI ();
}

function setSortOrder ( fid, typ, order )
{
	document.location = 'admin.php?module=library&action=setsortorder&t='+typ+'&i='+fid+'&o='+order;
}

