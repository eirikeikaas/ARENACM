<h1>
	Egenskaper for <em id="texteditornode"></em>
</h1>

<div class="SubContainer">
	<table>
		<tr>
			<td style="width: 140px">
				<strong>
					Felt ID:
				</strong>
			</td>
			<td>
				<input id="txID" type="text" size="25" value="">
			</td>
		</tr>
		<tr>
			<td>
				<strong>
					Klasse navn:
				</strong>
			</td>
			<td>
				<input id="txClassName" type="text" size="25" value="">
			</td>
		</tr>
		<tr id="txImageProperties">
			<td>
				<strong>
					ALT tekst:
				</strong>
			</td>
			<td>
				<input id="txAltText" type="text" size="25" value="">
			</td>
		</tr>
		<tr>
			<td>
				<strong>
					Velg innebygget klasse:
				</strong>
			</td>
			<td>
				<select onchange="document.getElementById ( 'txClassName' ).value = this.value" id="builtinclass">
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<strong>
					Bredde:
				</strong>
			</td>
			<td>
				<input id="txWidth" type="text" size="25" value="">
			</td>
		</tr>
		<tr>
			<td>
				<strong>
					Høyde:
				</strong>
			</td>
			<td>
				<input id="txHeight" type="text" size="25" value="">
			</td>
		</tr>
		<tr>
			<td>
				<strong>
					Vertikal posisjon:
				</strong>
			</td>
			<td>
				<select id="txVAlign">
					<option value="">Ingen</option>
					<option value="middle">Midten</option>
					<option value="top">Topp</option>
					<option value="bottom">Bunn</option>
					<option value="baseline">På linje</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<strong>
					Tekstflyting:
				</strong>
			</td>
			<td>
				<select id="txFloat">
					<option value="">Ingen</option>
					<option value="left">Venstre</option>
					<option value="center">Sentrert</option>
					<option value="right">Høyre</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<strong>
					Bakgrunn:
				</strong>
			</td>
			<td>
				<input id="txBackground" type="text" size="25" value="">
			</td>
		</tr>
		<tr>
			<td>
				<strong>
					Skrift type:
				</strong>
			</td>
			<td>
				<select id="txFontType">
					<option value="normal">Standard</option>
					<option value="Times New Roman">Times new roman</option>
					<option value="Verdana">Verdana</option>
					<option value="Arial">Arial</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<strong>
					Tekstfarge:
				</strong>
			</td>
			<td>
				<input id="txColor" type="text" size="25" value="">
			</td>
		</tr>
	</table>
	<table id="txTableProps" style="visibility: hidden; position: absolute; top: -1000px; left: -1000px">
		<tr>
			<td style="width: 140px">
				<strong>
					Ramme størrelse
				</strong>
			</td>
			<td>
				<input id="txTableBorderWidth" type="text" size="20" value="">
			</td>
		</tr>
		<tr>
			<td style="width: 140px">
				<strong>
					Felt marg
				</strong>
			</td>
			<td>
				<input id="txTablePadding" type="text" size="20" value="">
			</td>
		</tr>
	</table>
</div>

<script>
	var nn;
	var ed = texteditor.get( texteditor.activeEditorId );
	var node = ed._propertiesNodeType == 'parentnode' ?
		ed._propertiesParentNode : ed._propertiesNode;
	var it = document.getElementById ( 'txImageProperties' ).style.display = 'none'; 
		
	var nodename = node.nodeName.toLowerCase ( );
	
	switch ( nodename )
	{
		case 'h1':
		case 'h2':
		case 'h3':
		case 'h4':
		case 'h5':
		case 'h6':		nn = 'overskrift'; break;
		case 'table':   
			nn = 'tabell'; 
			var tp = document.getElementById ( 'txTableProps' );
			tp.style.position = 'relative';
			tp.style.visibility = 'visible';
			tp.style.top = 'auto';
			tp.style.left = 'auto';
			break;
		case 'th':		nn = 'tabell header felt'; break;
		case 'td':		nn = 'tabell felt'; break;
		case 'a':		nn = 'lenke'; break;
		case 'em':		nn = 'uthevet tekst'; break;
		case 'strong':	nn = 'fet tekst'; break;
		case 'b':		nn = 'fet tekst'; break;
		case 'span':	nn = 'span'; break;
		case 'u':		nn = 'understreket tekst'; break;
		case 'img':
			nn = 'bilde'; 
			var it = document.getElementById ( 'txImageProperties' );
			it.style.display = '';
			break;
		case 'ul':		nn = 'unummerert liste'; break;
		case 'ol':		nn = 'nummerert liste'; break;
		case 'li':		nn = 'liste element'; break;
		case 'p':		nn = 'paragraf'; break;
		default:		nn = 'element';	break;
	}
	
	document.getElementById ( 'texteditornode' ).innerHTML = nn;
	
	// Init ---------------------------------------------
	// Font family
	if ( node.style.fontFamily )
	{
		var opts = document.getElementById ( 'txFontType' ).options;
		for ( var a = 0; a < opts.length; a++ )
		{
			if ( opts[ a ].value == node.style.fontFamily )
				opts[ a ].selected = 'selected';
			else opts[ a ].selected = false;
		}
	}
	
	if ( nn == 'tabell' )
	{
		document.getElementById ( 'txTableBorderWidth' ).value = node.getAttribute ( 'cellspacing' );
		document.getElementById ( 'txTablePadding' ).value = node.getAttribute ( 'cellpadding' );
	}
	else if ( nn == 'bilde' )
	{
		document.getElementById ( 'txAltText' ).value = node.getAttribute ( 'alt' );
	}
	
	// Vertical align
	if ( node.style.verticalAlign )
	{
		var opts = document.getElementById ( 'txVAlign' ).options;
		for ( var a = 0; a < opts.length; a++ )
		{
			if ( opts[ a ].value == node.style.verticalAlign )
				opts[ a ].selected = 'selected';
			else opts[ a ].selected = false;
		}
	}
	
	
	if ( node.style.background )
		document.getElementById ( 'txBackground' ).value = node.style.background;
	
	if ( node.className )
		document.getElementById ( 'txClassName' ).value = node.className;
	if ( node.style.height )
		document.getElementById ( 'txHeight' ).value = node.style.height;
	if ( node.style.width )
		document.getElementById ( 'txWidth' ).value = node.style.width;
	if ( node.style.color )
		document.getElementById ( 'txColor' ).value = node.style.color;
	if ( node.id )
		document.getElementById ( 'txID' ).value = node.id;
	if ( node.style.float || node.align )
	{
		var val = node.align = node.align ? node.align : node.style.float;
		var opts = document.getElementById ( 'txFloat' ).options;
		for ( var a = 0; a < opts.length; a++ )
		{
			if ( opts[ a ].value == val )
				opts[ a ].selected = 'selected';
			else opts[ a ].selected = false;
		}
	}
	var classes = texteditor.customCssClasses;
	var css_select = document.getElementById ( 'builtinclass' );
	var opts = '<option value="">Velg:</option>'; 
	var s = '';
	for ( var a = 0; a < classes.length; a++ )
	{
		if ( classes[ a ] == node.className ) s = ' selected="selected"';
		else s = '';
		opts += '<option value="' + classes[ a ] + '"' + s + '>' + classes[ a ] + '</option>';
	}
	css_select.innerHTML = opts;

	// Table background etc
	var bg = node.style.backgroundColor + '';
	if ( bg.indexOf ( 'rgb' ) >= 0 )
	{
		// conv to hex
		bg = bg.split ( ' ' ).join ( '' ).split ( 'rgb' ).join ( '' );
		bg = bg.split ( '(' ).join ( '' ).split ( ')' ).join ( '' );
		bg = bg.split ( ',' );
		bg = '#' + toHex ( bg[ 0 ] ) + toHex ( bg[ 1 ] ) + toHex ( bg[ 2 ] );
	}
	
</script>

<div class="SpacerSmallColored"></div>
<button type="button" id="propertiesExecuteBtn">
	<img src="admin/gfx/icons/accept.png"/> Utfør
</button>
<button type="button" onclick="removeModalDialogue ( 'elementproperties' )">
	<img src="admin/gfx/icons/cancel.png"/> Avbryt
</button>

<script>
	document.getElementById ( 'propertiesExecuteBtn' ).onclick = function ( )
	{
		// font fam
		node.style.fontFamily = ( document.getElementById ( 'txFontType' ).value != 'normal' ) ? document.getElementById ( 'txFontType' ).value : '';
		node.style.verticalAlign = document.getElementById ( 'txVAlign' ).value;
		node.className = document.getElementById ( 'txClassName' ).value;
		node.style.width = ( document.getElementById ( 'txWidth' ).value != '' ) ? document.getElementById ( 'txWidth' ).value : '';
		node.style.height = ( document.getElementById ( 'txHeight' ).value != '' ) ? document.getElementById ( 'txHeight' ).value : '';
		node.style.background = document.getElementById ( 'txBackground' ).value;	
		node.style.color = document.getElementById ( 'txColor' ).value;
		if ( document.getElementById ( 'txFloat' ).value )
		{
			if ( nodename == 'img' )
				node.align = document.getElementById ( 'txFloat' ).value;
			node.style.float = document.getElementById ( 'txFloat' ).value;
		}
		else 
		{
			node.align = '';
			node.style.float = '';
		}
		
		// TABLE properties
		if ( document.getElementById ( 'txTableProps' ).style.visibility == 'visible' )
		{
			var bw = document.getElementById ( 'txTableBorderWidth' ).value;
			bw = bw.split ( 'px' ).join ( '' );
			var bp = document.getElementById ( 'txTablePadding' ).value;
			bp = bp.split ( 'px' ).join ( '' );
			node.setAttribute ( 'cellspacing', bw ? ( bw + 'px' ) : '' );
			node.setAttribute ( 'cellpadding', bp ? ( bp + 'px' ) : '' );
		}
		if ( document.getElementById ( 'txImageProperties' ).style.display == '' )
		{
			node.setAttribute ( 'alt', document.getElementById ( 'txAltText' ).value );
		}
		
		node.id = document.getElementById ( 'txID' ).value;
		var ed = texteditor.get ( texteditor.activeEditorId );
		ed.area.value = ed.getContent ( );
		
		// end
		removeModalDialogue ( 'elementproperties' )
	}
</script>
