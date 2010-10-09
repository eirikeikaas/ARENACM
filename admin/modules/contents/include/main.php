<?


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



// Template functions
require_once ( "lib/classes/dbObjects/dbContent.php" );

function loadSubElementsRO ( $obj )
{
	if ( !$obj->ID )
		return false;
	$db =& dbObject::globalValue ( "database" );
	
	if ( $obj->_editmode )
	{
		$obj->loadSubElements ( array ( 'editmode' => 1 ) );
		return $obj->subElements;
	}
	else
	{
		if ( $subElements = $db->fetchObjectRows ( "
			SELECT ID, Parent, MainID, Title, MenuTitle, IsSystem, IsPublished, DateModified FROM ContentElement
			WHERE
				Parent='{$obj->MainID}' AND
				!IsDeleted AND MainID != ID AND
				VersionPublished = Version
			ORDER BY
				IsSystem ASC, SortOrder ASC, ID DESC
		" ) )
		{
			return $subElements;
		}
	}
	return false;
}
 
 
function renderTemplateControls ( $sid )
{
	$db =& dbObject::globalValue ( "database" );
	$content = new dbContent (  );
	if ( $content->load ( $sid ) )
	{
		$tpl = new cPTemplate ( 'admin/modules/contents/templates/maincontent_templatecontrols.php' );
		
		if ( $r = $db->fetchObjectRow ( "
			SELECT MenuTitle FROM ContentElement WHERE ID='{$content->TemplateID}'
		" ) ) 
		{
			$tpl->TemplateTitle = "\"" . $r->MenuTitle . "\"";
		}
		else $tpl->TemplateTitle = "Ingen"; 
		
		// Get templates
		$tpls = '';
		$tpla = '';
		if ( file_exists ( 'templates' ) && is_dir ( 'templates' ) )
		{
			if ( $dir = opendir ( 'templates' ) )
			{
				$tpls = Array ( );
				$tpla = Array ( );
				while ( $file = readdir ( $dir ) )
				{
					if ( $file{0} == '.' ) continue;
					if ( substr ( $file, 0, 5 ) == 'page_' )
					{
						$str = str_replace ( Array ( 'page_', '.php' ), '', $file );
						$str = strtoupper ( $str{0} ) . substr ( $str, 1, strlen ( $str ) - 1 );
						$str = str_replace ( '_', ' ', $str );
						if ( $content->Template == $file ) $s = ' selected="selected"'; else $s = '';
						if ( $content->TemplateArchived == $file ) $sa = ' selected="selected"'; else $sa = '';
						$tpls[] = '<option value="' . $file . '"' . $s . '>' . $str . '</option>';
						$tpla[] = '<option value="' . $file . '"' . $sa . '>' . $str . '</option>';
					}
				}
				closedir ( $dir );
				$tpls = implode ( "\n", $tpls );
				$tpla = implode ( "\n", $tpla );
			}
		}	
		$tpl->templates = $tpls;
		$tpl->templatesArchived = $tpla;
		
		/**
		 * Fetch content types
		 */
		$tpl->contenttypes = "";
		if ( $tdir = opendir ( "admin/modules" ) )
		{
			while ( $file = readdir ( $tdir ) )
			{
				if ( $file != "settings" && $file != "contents" && is_dir ( "admin/modules/$file" ) && $file{0} != "." )
				{
					// TODO: Fetch descriptions directly from modules
					switch ( $file )
					{
						case "users":
							$filedesc = "Brukerliste";
							break;
						case "news":
							$filedesc = "Bruk nyhetsmodulen";
							break;
						case "extensions":
							$filedesc = "Bruk utvidelse";
							break;
						case "product":
							$filedesc = "Bruk produktmodulen";
							break;
						case "productcategory":
							$filedesc = "Produktkategori";
							break;
						case "symlink":
							$filedesc = "Symlenke til en annen side";
							break;
						default:
							$filedesc = "Innhold av type: $file";
							continue;
					}
					if ( $filedesc )
					{
						if ( $content->ContentType == $file ) $s = " selected=\"selected\"";
						else $s = "";
						$tpl->contenttypes .= "<option value=\"{$file}\"$s>$filedesc</option>";
					}
				}
			}
			closedir ( $tdir );
		}
		$tpl->content =& $content;
		return $tpl->render ( );
	}
	return 'failed to initialize template controls..';
}
 
/**
 * Generate the content structure list
**/
function contentToList ( $content, $url, $current, $rootPageId = false, $depth = 0 )
{
	global $pool, $Session;
	$db =& dbObject::globalValue ( 'database' );
	
	$output .= '<ul>' . getLn ( );
	$content->_tableName = 'ContentElement';
	$content->_primaryKey = 'ID';
	$content->_isLoaded = true;
	
	// Make sure we have a readable menu title, and not some fancy pants stuff
	$test = trim ( strip_tags ( $content->MenuTitle ) );
	$content->MenuTitle = $test ? $test : trim ( $content->Title );
	
	if ( $content->IsSystem && !$content->IsPublished )
	{ 
		$extra = ' class="Systemarchived"';
		$sx = '('; $ex = ') <img src="admin/gfx/icons/flag_red.png" />';
	}
	else if ( !$content->IsPublished )
	{
		$extra = ' class="Archived"';
		$sx = '('; $ex = ') (ikke synlig)';
	}
	else if ( $content->IsSystem )
	{
		$extra = ' class="System"';
		$ex = ' <img src="admin/gfx/icons/flag_red.png" border="0" />';
	}
	else 
	{
		$extra = ' class="Normal"'; $sx = $ex = '';
	}
	
	if ( $depth < 2 ) $dsw = 1; else $dsw = $depth; // <- when depth is 0 || 1 use the same bg switcher
	$GLOBALS[ 'listsw' . $depth ] = ( $GLOBALS[ 'listsw' . $dsw ] == '2' ) ? '1' : '2';
	
	if ( !$Session->AdminUser->checkPermission ( $content, 'Read', 'admin' ) )
		return '';
	
	if ( !$GLOBALS[ '___system' ] && $content->IsSystem && $content->Parent == $rootPageId )
	{
		$GLOBALS[ '___system' ] = true;
		$output .= '<li class="separator"><em></em></li>';
		$output .= '<li class="system sw' . $GLOBALS[ 'listsw' . $dsw ] . ' header">System sider:</li>';
		$output .= '<li class="system separator"><em></em></li>';
	}
	else if ( !$GLOBALS[ '___normal' ] && !$content->IsSystem )
	{
		$GLOBALS[ '___normal' ] = true;
		$output .= '<li class="sw' . $GLOBALS[ 'listsw' . $dsw ] . ' header">';
		$output .= '<div style="display: block; padding: 4px">';
		if ( $Session->AdminUser->checkPermission ( $content, 'Structure', 'admin' ) )
		{
			$output .= '<div class="NewPageToplink">';
			$output .= '<button type="button" class="Small" style="width: 80px" "onclick="newPage()">';
			$output .= '<div class="NewPageImage"><em> </em></div>';
			$output .= 'Ny side';
			$output .= '</button>';
			$output .= '</div>';
		}
		$output .= '<h2>Sider i ' . $GLOBALS[ 'siteData' ]->SiteName . ':</h2>';
		$output .= '</div>';
		$output .= '</li>';
		$output .= '<li class="separator"><em></em></li>';
	}
	
	$main = $db->fetchObjectRow ( 'SELECT * FROM ContentElement WHERE MainID=ID AND MainID=' . $content->MainID );
	
	if ( $content->ID == $current ) 
	{
		if ( $content->DateModified != $main->DateModified )
			$ex .= ' <small style="color: #0a0">(TIL PUBLISERING)</small>';
		
		$output .= '<li class="current sw' . $GLOBALS[ 'listsw' . $dsw ] . ( $content->IsSystem ? ' system' : '' ) . '">';
		if ( $content->Parent > 0 )
		{
			if ( $Session->AdminUser->checkPermission ( $content, 'Structure', 'admin' ) )
			{
				$addbutton = "<button type=\"button\" id=\"underinnhold\" onclick=\"newPage()\"" . (  isIE () ? " style=\"width: 32px\"" : "" ) . "><img src=\"admin/gfx/icons/page_add.png\"></button>";
				$delbutton = "<button type=\"button\" id=\"delpage\" onclick=\"deletePage({$content->ID})\"" . (  isIE () ? " style=\"width: 32px\"" : "" ) . "><img src=\"admin/gfx/icons/bin.png\"/></button>";
				$updownmove = "<button type=\"button\" onclick=\"changeOrder(-1,'{$content->ID}')\"><img src=\"admin/gfx/icons/bullet_arrow_up.png\" title=\"Flytt opp\"></button>
								<button type=\"button\" onclick=\"changeOrder(1,'{$content->ID}')\"><img src=\"admin/gfx/icons/bullet_arrow_down.png\" title=\"Flytt ned\"></button>
								<button type=\"button\" onclick=\"initModalDialogue ( 'MoveDialog', 320, 480, 'admin.php?module=contents&function=movecontent&cid=" . $content->ID . "' )\"><img src=\"admin/gfx/icons/folder_go.png\" title=\"Flytt\"> Flytt</button>";
			}
			else $addbutton = $updownmove = $delbutton = '';
			$output .= "
				<div class=\"ButtonBox\">
					<table style=\"width: 100%\" class=\"Buttons\">
						<tr>
							<td style=\"vertical-align: middle\">
								$sx<em>" . str_replace ( array ( "<p>", "</p>" ), "", $content->MenuTitle ) . "</em>$ex
								| <span style=\"font-weight: bold\">Sist oppdatert:</span> <em>" . ArenaDate ( DATE_FORMAT, strtotime ( $content->DateModified ) ) . " kl. " . date ( 'H:i', strtotime ( $content->DateModified ) ) . "</em>
							</td>
							<td style=\"vertical-align: middle; width: 290px; text-align: right\">
								{$updownmove}
								<button type=\"button\" id=\"sidetilarbeidsbenk\" onclick=\"addToWorkbench('" . ( $content->ID ) . "','ContentElement')\"" . ( isIE () ? " style=\"width: 32px\"" : "" ) . "><img src=\"admin/gfx/icons/plugin.png\" /></button>
								{$addbutton} {$delbutton}
								<button type=\"button\" onclick=\"document.getElementById('tabContent').onclick(); showMainContent ( '{$content->ID}' )\"><img src=\"admin/gfx/icons/page_edit.png\" title=\"Endre\" /></button>
							</td>
						</tr>
					</table>
				</div>";
		}
		else
		{		
			if ( $Session->AdminUser->checkPermission ( $content, 'Structure', 'admin' ) )
			{
				$addbutton = "<button type=\"button\" id=\"underinnhold\" onclick=\"newPage()\"" . (  isIE () ? " style=\"width: 32px\"" : "" ) . "><img src=\"admin/gfx/icons/page_add.png\" /></button>";
				$delbutton = "<button type=\"button\" id=\"delpage\" onclick=\"deletePage({$content->ID})\"" . (  isIE () ? " style=\"width: 32px\"" : "" ) . "><img src=\"admin/gfx/icons/bin.png\"/></button>";
			}
			else $addbutton = $delbutton = '';
			
			$output .= "
				<div class=\"ButtonBox\">
					<table style=\"width: 100%\" class=\"Buttons\">
						<tr>
							<td style=\"vertical-align: middle\">
								$sx<em>" . str_replace ( array ( "<p>", "</p>" ), "", $content->MenuTitle ) . "</em>$ex
								| <span style=\"font-weight: bold\">Sist oppdatert:</span> <em>" . ArenaDate ( DATE_FORMAT, strtotime ( $content->DateModified ) ) . " kl. " . date ( 'H:i', strtotime ( $content->DateModified ) ) . "</em>
							</td>
							<td style=\"vertical-align: middle; width: 290px; text-align: right\">
								<button type=\"button\" id=\"sidetilarbeidsbenk\" onclick=\"addToWorkbench('" . ( $content->ID ) . "','ContentElement')\"" . ( isIE () ? " style=\"width: 32px\"" : "" ) . "><img src=\"admin/gfx/icons/plugin.png\" /></button>
								{$addbutton} {$delbutton}
								<button type=\"button\" onclick=\"showMainContent ( '{$content->ID}' ); document.getElementById('tabContent').onclick();\"><img src=\"admin/gfx/icons/page_edit.png\" title=\"Endre\" /></button>
							</td>
						</tr>
					</table>
				</div>
			";
		}
	}
	else
	{
		if ( $content->DateModified != $main->DateModified )
			$ex .= ' <small style="color: #0a0">(TIL PUBLISERING, oppdatert <span style="color: #a00">' . ArenaDate ( DATE_FORMAT, $content->DateModified ) . '</span>)</small>';
		$menutitle = strip_tags ( $content->MenuTitle );
		$output .= '<li class="sw' . $GLOBALS[ 'listsw' . $dsw ] . ( $content->IsSystem ? ' system' : '' ) . '"><a href="javascript:;" onclick="javascript:showStructure(\'' . $content->ID . '\')"' . $extra . '>' . $sx . $menutitle . $ex . '</a>';
	}
		
	if ( $pool )
	{
		$plen = count ( $pool );
		for ( $a = 0; $a < $plen; $a++ )
		{
			$pool[ $a ]->_tableName = 'ContentElement';
			$pool[ $a ]->_primaryKey = 'ID';
			$pool[ $a ]->_isLoaded = true;

			if ( $pool[ $a ]->Parent == $content->MainID && $Session->AdminUser->checkPermission ( &$pool[ $a ], 'Read', 'admin' ) )
			{
				$output .= contentToList ( $pool[ $a ], $url, $current, $rootPageId, $depth + 1 );
			}
		}
	}
	else if ( $elements = loadSubElementsRO ( $content ) )
	{
		foreach ( $elements as $subpage ) 
		{
			$output .= contentToList ( $subpage, $url, $current, $rootPageId, $depth + 1 );
		}
	}
	$output .= '</li>';
	
	$output .= '</ul>';
	
	return $output;
}

function showAccessList ( $contentid )
{
	if ( !$contentid )
		return "<div class=\"SubContainer\">Ingen contentid.</div>";
	$objs = new dbObject ( "ObjectPermission" );
	$objs->addClause ( "WHERE", "AuthType='ContentElement' AND AuthID='{$contentid}'" );
	if ( $objs = $objs->find ( ) )
	{
		$tpl = new cPTemplate ( "admin/modules/contents/templates/access_div.php" );
		foreach ( $objs as $obj )
		{
			$tpl->data = $obj; 
			$oStr .= $tpl->render ( );
		}
		return "<div class=\"SubContainer\">$oStr</div>";
	}
	return "<div class=\"SubContainer\">Ingen elementer er lagt til her.</div>";
}

/**
 * Parse content elements
**/
function getContentStructureOptions ( $parent, $language, $r = "" )
{
	$content = $GLOBALS[ "content" ];
	$oStr = "";
	if ( !$content ) return "";
	foreach ( $content as $cnt )
	{
		if ( ( $cnt->Language == $language || $parent != 0 || !$language ) && $cnt->Parent == $parent )
		{
			if ( $cnt->ID )
			{
				if ( !$cnt->Title ) $cnt->Title = $cnt->MenuTitle;
				if ( !$cnt->Title ) $cnt->Title = $cnt->SystemName;
				if ( !$cnt->Title ) $cnt->Title = $cnt->ID;
				$oStr .= "<option value=\"{$cnt->ID}\">$r{$cnt->Title}</option>";
				$oStr .= getContentStructureOptions ( $cnt->ID, $language, $r . "&nbsp;&nbsp;&nbsp;&nbsp;" );
			}
		}
	}
	return $oStr;
}
?>
