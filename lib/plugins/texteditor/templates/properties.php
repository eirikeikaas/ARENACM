<h1>
	Egenskaper for <em id="texteditornode"></em>
</h1>

<div class="SubContainer">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="50%" style="vertical-align: top; padding-right: 8px;">
				<h4>Teknisk informasjon:</h4>
				<div class="SubContainer">
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tr>
							<td style="width: 100px">
								<strong>
									Felt ID:
								</strong>
							</td>
							<td>
								<input id="txID" type="text" size="20" value="">
							</td>
						</tr>
						<tr>
							<td>
								<strong>
									Klasse navn:
								</strong>
							</td>
							<td>
								<input id="txClassName" type="text" size="20" value="">
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
									Klassenavn:
								</strong>
							</td>
							<td>
								<select onchange="ge ( 'txClassName' ).value = this.value" id="builtinclass">
								</select>
							</td>
						</tr>
					</table>
				</div>
				<h4>
					Dimensjoner:
				</h4>
				<div class="SubContainer">
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tr>
							<td style="width: 100px">
								<strong>
									Bredde:
								</strong>
							</td>
							<td>
								<input id="txWidth" type="text" size="20" value="">
							</td>
						</tr>
						<tr>
							<td>
								<strong>
									Høyde:
								</strong>
							</td>
							<td>
								<input id="txHeight" type="text" size="20" value="">
							</td>
						</tr>
					</table>
				</div>
				<h4>
					Bildebakgrunn:
				</h4>
				<div class="SubContainer">
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tr>
							<td style="width: 100px">
								<strong>
									Bakgrunn:
								</strong>
							</td>
							<td>
								<input id="txBackgroundImage" type="text" size="20" value="">
							</td>
						</tr>
					</table>
				</div>
			</td>
			<td style="vertical-align: top; padding-left: 8px; border-left: 1px solid #cccccc">
				<h4>
					Posisjonering og marg:
				</h4>
				<div class="SubContainer">
					<table id="txPositionProps" cellspacing="0" cellpadding="0" width="100%">
						<tr>
							<td style="width: 121px">
								<strong>
									Posisjonering:
								</strong>
							</td>
							<td>
								<select id="txPosition">
									<option value="static">Statisk</option>
									<option value="relative">Relativ</option>
									<option value="absolute">Absolutt</option>
									<option value="fixed">Fast</option>
								</select>
							</td>
						</tr>
						<tr>
							<td style="width: 121px">
								<strong>
									Koordinater:
								</strong>
							</td>
							<td>
								<table cellspacing="0" cellpadding="0" border="0" width="100%">
									<tr>
										<td width="50%">
											<input type="text" style="width: 80px" size="4" id="txCoordX" value="0"/>
										</td>
										<td style="text-align: center">,&nbsp;</td>
										<td width="50%">
											<input type="text" style="width: 80px" size="4" id="txCoordY" value="0"/>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table id="txTableProps" style="visibility: hidden; position: absolute; top: -1000px; left: -1000px" cellspacing="0" cellpadding="0" width="100%">
						<tr>
							<td style="width: 121px">
								<strong>
									Ramme størrelse:
								</strong>
							</td>
							<td>
								<input id="txTableBorderWidth" type="text" size="10" value="">
							</td>
						</tr>
						<tr>
							<td style="width: 121px">
								<strong>
									Felt marg:
								</strong>
							</td>
							<td>
								<input id="txTablePadding" type="text" size="10" value="">
							</td>
						</tr>
					</table>
					<table id="txTableDataProps" style="visibility: hidden; position: absolute; top: -1000px; left: -1000px" cellspacing="0" cellpadding="0" width="100%">
						<tr>
							<td style="width: 121px">
								<strong>
									Padding:
								</strong>
							</td>
							<td>
								<input id="txTableDataPadding" type="text" size="10" value="">
							</td>
						</tr>
					</table>
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tr>
							<td style="width: 121px;">
								<strong>
									Elementflyt:
								</strong>
							</td>
							<td id="txElementFloat">
								<img name="left" style="cursor: hand; cursor: pointer" src="admin/gfx/icons/text_align_left.png"/>
								<img name="right" style="cursor: hand; cursor: pointer" src="admin/gfx/icons/text_align_right.png"/>
								<img name="center" style="cursor: hand; cursor: pointer" src="admin/gfx/icons/text_align_center.png"/>
								<img name="none" style="cursor: hand; cursor: pointer" src="admin/gfx/icons/shape_square.png"/>
								<input type="hidden" value="" id="txFloat"/>
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
					</table>
				</div>
				<h4>
					Farger og tekstvalg:
				</h4>
				<div class="SubContainer">
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tr>
							<td style="width: 121px">
								<strong>
									Bakgrunn:
								</strong>
							</td>
							<td>
								<input id="txBackground" type="text" size="25" value=""/>
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
									<option value="Verdana">Verdana</option>
									<option value="Arial">Arial</option>
									<option value="Times New Roman">Times new roman</option>
									<option value="Monospace">Monospace</option>
									<option value="Calibri">Calibri</option>
									<option value="Courier">Courier</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<strong>
									Skrift størrelse:
								</strong>
							</td>
							<td>
								<select id="txFontSize">
									<option value="8px">8</option>
									<option value="10px">10</option>
									<option value="11px">11</option>
									<option value="12px">12</option>
									<option value="14px">14</option>
									<option value="16px">16</option>
									<option value="18px">18</option>
									<option value="22px">22</option>
									<option value="32px">32</option>
									<option value="48px">48</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<strong>Skrift justering:</strong>
							</td>
							<td id="txJustification">
								<img name="left" style="cursor: hand; cursor: pointer" src="admin/gfx/icons/text_align_left.png"/>
								<img name="right" style="cursor: hand; cursor: pointer" src="admin/gfx/icons/text_align_right.png"/>
								<img name="center" style="cursor: hand; cursor: pointer" src="admin/gfx/icons/text_align_center.png"/>
								<img name="justify" style="cursor: hand; cursor: pointer" src="admin/gfx/icons/text_align_justify.png"/>
								<img name="none" style="cursor: hand; cursor: pointer" src="admin/gfx/icons/shape_square.png"/>
								<input type="hidden" value="" id="txTextAlign"/>
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
				</div>
			</td>
		</tr>
	</table>
</div>

<script>
	var nn;
	var ed = texteditor.get( texteditor.activeEditorId );
	var node = ed._propertiesNodeType == 'parentnode' ?
		ed._propertiesParentNode : ed._propertiesNode;
	var it = ge ( 'txImageProperties' ).style.display = 'none'; 
		
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
			var tp = ge ( 'txTableProps' );
			tp.style.position = 'relative';
			tp.style.visibility = 'visible';
			tp.style.top = 'auto';
			tp.style.left = 'auto';
			break;
		case 'th':		nn = 'tabell header felt'; break;
		case 'td':		nn = 'tabell felt'; 
			var tp = ge ( 'txTableDataProps' );
			tp.style.position = 'relative';
			tp.style.visibility = 'visible';
			tp.style.top = 'auto';
			tp.style.left = 'auto';
			break;
		case 'a':		nn = 'lenke'; break;
		case 'em':		nn = 'uthevet tekst'; break;
		case 'strong':	nn = 'fet tekst'; break;
		case 'b':		nn = 'fet tekst'; break;
		case 'span':	nn = 'span'; break;
		case 'u':		nn = 'understreket tekst'; break;
		case 'img':
			nn = 'bilde'; 
			var it = ge ( 'txImageProperties' );
			it.style.display = '';
			break;
		case 'ul':		nn = 'unummerert liste'; break;
		case 'ol':		nn = 'nummerert liste'; break;
		case 'li':		nn = 'liste element'; break;
		case 'p':		nn = 'paragraf'; break;
		default:		nn = 'element';	break;
	}
	
	ge ( 'texteditornode' ).innerHTML = nn;
	
	// Init ---------------------------------------------
	// Font family
	if ( node.style.fontFamily )
	{
		var opts = ge ( 'txFontType' ).options;
		for ( var a = 0; a < opts.length; a++ )
		{
			if ( opts[ a ].value == node.style.fontFamily )
				opts[ a ].selected = 'selected';
			else opts[ a ].selected = false;
		}
	}
	var fs = node.style.fontSize ? node.style.fontSize : 
		computedStyle ( node ).fontSize;
	if ( parseInt ( fs ) > 0 )
	{
		fs = parseInt ( fs ) + 'px';
		var opts = ge ( 'txFontSize' ).options;
		for ( var a = 0; a < opts.length; a++ )
		{
			if ( opts[ a ].value == fs )
				opts[ a ].selected = 'selected';
			else opts[ a ].selected = false;
		}
	}
	
	if ( nn == 'tabell' )
	{
		ge ( 'txTableBorderWidth' ).value = node.getAttribute ( 'cellspacing' );
		ge ( 'txTablePadding' ).value = node.getAttribute ( 'cellpadding' );
	}
	else if ( nn == 'tabell felt' )
	{
		ge ( 'txTableDataPadding' ).value = node.style.padding;
	}
	else if ( nn == 'bilde' )
	{
		ge ( 'txAltText' ).value = node.getAttribute ( 'alt' );
	}
	
	// Position
	if ( node.style.position )
		ge ( 'txPosition' ).value = node.style.position;
	if ( node.style.top )
		ge ( 'txCoordY' ).value = node.style.top;
	if ( node.style.left )
		ge ( 'txCoordX' ).value = node.style.left;
	
	// Vertical align
	if ( node.style.verticalAlign )
	{
		var opts = ge ( 'txVAlign' ).options;
		for ( var a = 0; a < opts.length; a++ )
		{
			if ( opts[ a ].value == node.style.verticalAlign )
				opts[ a ].selected = 'selected';
			else opts[ a ].selected = false;
		}
	}
	
	
	if ( node.style.backgroundColor )
	{
		ge ( 'txBackground' ).value = node.style.backgroundColor;
	}
	if ( node.style.backgroundImage )
	{
		var bgi = ( node.style.backgroundImage + "" );
		bgi = bgi.split ( 'url(' );
		if ( bgi.length > 1 )
			bgi = bgi[1].split ( ')' )[0].split ( '\'' ).join ( '' ).split ( '"' ).join ( '' );
		ge ( 'txBackgroundImage' ).value = bgi;
	}
	
	if ( node.className )
		ge ( 'txClassName' ).value = node.className;
	if ( node.style.height )
		ge ( 'txHeight' ).value = node.style.height;
	if ( node.style.width )
		ge ( 'txWidth' ).value = node.style.width;
	if ( node.style.color )
		ge ( 'txColor' ).value = node.style.color;
	if ( node.id )
		ge ( 'txID' ).value = node.id;
		
	function txElementFloat ( mode )
	{
		mode = mode ? mode.toLowerCase () : 'none';
		var eles = ge ( 'txElementFloat' ).getElementsByTagName ( 'img' );
		var found = false;
		for ( var a = 0; a < eles.length; a++ )
		{
			if ( eles[a].name == mode || ( a == eles.length - 1 && !found ) )
			{
				found = true;
				eles[a].style.border = '1px solid black';
			}
			else eles[a].style.border = 'none';
			eles[a].onclick = function () { txElementFloat ( this.name ); }
		}
		ge ( 'txFloat' ).value = mode;
	}
	if ( nodename == 'td' || nodename == 'table' || nodename == 'img' || nodename == 'th' )
		txElementFloat ( node.getAttribute ? node.getAttribute ( 'align' ) : node.align );
	else
	{
		txElementFloat ( node.style.float ? node.style.float : 
			( computedStyle ( node ).float ? computedStyle ( node ).float : 'none' ) );
	}
	
	function txTextAlign ( mode )
	{
		mode = mode ? mode.toLowerCase () : 'none';
		var eles = ge ( 'txJustification' ).getElementsByTagName ( 'img' );
		var found = false;
		for ( var a = 0; a < eles.length; a++ )
		{
			if ( eles[a].name == mode || ( a == eles.length - 1 && !found ) )
			{
				found = true;
				eles[a].style.border = '1px solid black';
			}
			else eles[a].style.border = 'none';
			eles[a].onclick = function () { txTextAlign ( this.name ); }
		}
		ge ( 'txTextAlign' ).value = mode;
	}
	txTextAlign ( node.style.textAlign ? node.style.textAlign : 
		( computedStyle ( node ).textAlign ? computedStyle ( node ).textAlign : 'none' ) );
	
	var classes = texteditor.customCssClasses;
	var css_select = ge ( 'builtinclass' );
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
	
	ge ( 'propertiesExecuteBtn' ).onclick = function ( )
	{
		// font fam
		node.style.fontFamily = ( ge ( 'txFontType' ).value != 'normal' ) ? ge ( 'txFontType' ).value : '';
		var tw = ( ge ( 'txWidth' ).value != '' ) ? ge ( 'txWidth' ).value : '';
		if ( tw.length && tw.indexOf ( 'px' ) <= 0 && tw.indexOf ( '%' ) < 0 ) tw += 'px'; 
		node.style.width = tw;
		var th = ( ge ( 'txHeight' ).value != '' ) ? ge ( 'txHeight' ).value : '';
		if ( th.length && th.indexOf ( 'px' ) <= 0 && th.indexOf ( '%' ) < 0  ) th += 'px';
		node.style.height = th;
		try { node.style.fontSize = ge ( 'txFontSize' ).value; } catch ( e ) {};
		try { node.style.verticalAlign = ge ( 'txVAlign' ).value; } catch ( e ) {};
		try { node.className = ge ( 'txClassName' ).value; } catch ( e ){};
		try { node.style.backgroundColor = ge ( 'txBackground' ).value;	} catch ( e ){};
		var bgi = ge('txBackgroundImage').value;
		if ( bgi.indexOf ( '.jpg' ) > 0 || bgi.indexOf ( '.png' ) > 0 || bgi.indexOf ( '.gif' ) > 0 )
		{
			try { node.style.backgroundImage = 'url("'+bgi+'")'; } catch ( e ){};
		}
		else try { node.style.backgroundImage = ''; } catch ( e ){};
		try { node.style.color = ge ( 'txColor' ).value; } catch ( e ){};
		if ( ge ( 'txFloat' ).value )
		{
			if ( nodename == 'img' || nodename == 'td' || nodename == 'table' || nodename == 'th' )
			{
				node.setAttribute ( 'align', ge ( 'txFloat' ).value );
			}
			else node.style.float = ge ( 'txFloat' ).value;
		}
		else 
		{
			node.align = '';
			node.style.float = '';
		}
		if ( ge ( 'txTextAlign' ).value )
		{
			try { node.style.textAlign = ge ( 'txTextAlign' ).value; } catch ( e ) {};
		}
		else 
		{
			node.style.textAlign = '';
		}
		
		// TABLE properties
		if ( ge ( 'txTableProps' ).style.visibility == 'visible' )
		{
			var bw = ge ( 'txTableBorderWidth' ).value;
			bw = bw.split ( 'px' ).join ( '' );
			var bp = ge ( 'txTablePadding' ).value;
			bp = bp.split ( 'px' ).join ( '' );
			node.setAttribute ( 'cellspacing', bw ? ( bw + 'px' ) : '' );
			node.setAttribute ( 'cellpadding', bp ? ( bp + 'px' ) : '' );
		}
		if ( ge ( 'txTableDataProps' ).style.visibility == 'visible' )
		{
			var pd = ge ( 'txTableDataPadding' ).value;
			pd = pd.split ( 'px' ).join ( '' );
			if ( pd.length && parseInt ( pd ) >= 0 )
				node.style.padding = parseInt ( pd ) + 'px';
		}
		if ( ge ( 'txImageProperties' ).style.display == '' )
		{
			node.setAttribute ( 'alt', ge ( 'txAltText' ).value );
		}
		node.id = ge ( 'txID' ).value;
		var ed = texteditor.get ( texteditor.activeEditorId );
		ed.area.value = ed.getContent ( );
		
		// Position
		node.style.position = ge ( 'txPosition' ).value;
		var cx = ge ( 'txCoordX' ).value;
		var cy = ge ( 'txCoordY' ).value;
		node.style.top = cy.indexOf ( 'px' ) > 0 || cy.indexOf ( '%' ) > 0 ? cy : (cy+'px');
		node.style.left = cx.indexOf ( 'px' ) > 0 || cx.indexOf ( '%' ) > 0 ? cx : (cx+'px');
		
		// end
		removeModalDialogue ( 'elementproperties' );
	}
	
	<?= file_get_contents ( 'lib/javascript/gui/common.js' ) ?>
	
	if ( ge ( 'txBackground' ) ) GuiColorBox ( ge ( 'txBackground' ) );
	if ( ge ( 'txColor' ) ) GuiColorBox ( ge ( 'txColor' ) );
	if ( ge ( 'txWidth' ) ) GuiSizeWidget ( ge ( 'txWidth' ) );
	if ( ge ( 'txHeight' ) ) GuiSizeWidget ( ge ( 'txHeight' ) );
	if ( ge ( 'txCoordY' ) ) GuiSizeWidget ( ge ( 'txCoordY' ) );
	if ( ge ( 'txCoordX' ) ) GuiSizeWidget ( ge ( 'txCoordX' ) );
</script>

<div class="SpacerSmallColored"></div>
<button type="button" id="propertiesExecuteBtn">
	<img src="admin/gfx/icons/accept.png"/> Utfør
</button>
<button type="button" onclick="removeModalDialogue ( 'elementproperties' )">
	<img src="admin/gfx/icons/cancel.png"/> Avbryt
</button>
