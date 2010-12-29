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

function showAddedModules ( $cid )
{
	global $Session;
	$content = new dbContent ( );
	$content->load ( $cid );
	$path = BASE_DIR . '/skeleton/modules';
	$objs = new dbObject ( 'Setting' );
	$objs->SettingType = 'ContentModule';
	$objs->addClause ( 'ORDER BY', '`Key` ASC' );
	if ( $objs = $objs->find() )
	{
		$str = '';
		foreach ( $objs as $ob )
		{
			if ( file_exists ( $path . '/' . $ob->Key ) && is_dir ( $path . '/' . $ob->Key ) )
			{
				// Check if module is activated
				$obj = new dbObject ( 'ContentDataSmall' );
				$obj->ContentID = $cid;
				$obj->ContentTable = 'ContentElement';
				$obj->Type = 'contentmodule';
				$obj->DataString = $ob->Key;
				if ( $obj->load ( ) )
					$checked = true;
				else $checked = false;
				
				$data = file_get_contents ( $path . '/' . $ob->Key . '/info.txt' );
				list ( $info, $description ) = explode ( getLn ( ), $data );
				list ( $info, $price, $moduletype ) = explode ( '|', $info );
				
				if ( $moduletype == 'adminmodule' && !$Session->AdminUser->isSuperUser ( ) )
					continue;
					
				$str .= '<div class="AddedModule' . ( $checked ? ' active' : '' ) . '">';
				$str .= '<table><tr><td>';
				$str .= '<div class="Image"><img src="skeleton/modules/' . $ob->Key . '/module.png" width="100px" height="110px"></div>';
				$str .= '</td><td>';
				$str .= '<h2>' . $info . '</h2>';
				$str .= '<div class="Description"><p>' . $description . '</p></div>';
				
				if ( $Session->AdminUser->checkPermission ( $content, 'Write', 'admin' ) )
				{
					$str .= '<button type="button" onclick="delModule(\'' . $ob->Key . '\')"><img src="admin/gfx/icons/bin.png"> Fjern</button>';
				}
				
				// Adminmodules do not get activated - they only linger =)
				if ( $moduletype != 'adminmodule' && $Session->AdminUser->checkPermission ( $content, 'Write', 'admin' ) )
				{
					if ( $checked )
						$str .= '<button type="button" onclick="deactivateModule(\'' . $ob->Key . '\')"><img src="admin/gfx/icons/cancel.png"> Deaktiver</button>';
					else $str .= '<button type="button" onclick="activateModule(\'' . $ob->Key . '\')"><img src="admin/gfx/icons/accept.png"> Aktiver</button>';
				}
				$str .= '</td></tr></table>';
				$str .= '</div>';
			}
			else $str .= '<p>Not existing: (' . BASE_DIR . ') ' . $path . '/' . $ob->Key . '</p>';
		}
		return $str;
	}
	else
	{
		return '
					<p>
						Du har ingen tilgjengelige moduler. For å skaffe deg moduler
						må du først bla gjennom modulbutikken. Klikk på fanen "Modulbutikk"
						for å se hvilke moduler vi har å tilby.
					</p>
		';
	}
}

function showFreeModules ()
{
	global $Session;
	$path = BASE_DIR . '/skeleton/modules';
	$db =& dbObject::globalValue ( 'database' );
	
	if ( $Session->AdminUser->isSuperUser ( ) && ( $dir = opendir ( $path ) ) )
	{
		// Get current modules
		$mods = Array ( );
		if ( $currentModules = $db->fetchObjectRows ( 'SELECT * FROM Setting WHERE SettingType="ContentModule" AND `Key` LIKE "mod%"' ) )
		{
			foreach ( $currentModules as $m )
			{
				$mods[] = $m->Key;
			}
		}
	
		// List out modules
		$str = '';
		while ( $file = readdir ( $dir ) )
		{
			if ( $file{0} == '.' )
				continue;
			if ( substr ( $file, 0, 4 ) != 'mod_' )
				continue;
			// Skip added modules
			if ( in_array ( $file, $mods ) )
				continue;
			$data = file_get_contents ( $path . '/' . $file . '/info.txt' );
			list ( $info, $description ) = explode ( getLn ( ), $data );
			$info = explode ( '|', $info );
			$str .= '<div class="BuyModule">';
			$str .= '<table><tr><td>';
			$str .= '<div class="Image"><img src="skeleton/modules/' . $file . '/module.png" width="100px" height="110px"></div>';
			$str .= '</td><td>';
			$str .= '<h2>' . $info[ 0 ] . '</h2>';
			$str .= '<div class="Description"><p>' . $description . '</p></div>';
			$str .= '<button type="button" onclick="addModule(\'' . $file . '\')"><img src="admin/gfx/icons/star.png"> Legg til</button>';
			$str .= '</td></tr></table>';
			$str .= '</div>';
		}
		closedir ( $dir );
		if ( strlen ( $str ) )
			return $str;
	}
	return '<p>Ingen moduler er tilgjengelig.</p>';
}

function showProModules ()
{
	global $Session, $corebase, $siteData;
	$path = BASE_DIR . '/skeleton/modules';
	$db =& dbObject::globalValue ( 'database' );
	
	if ( $Session->AdminUser->isSuperUser ( ) && ( $dir = opendir ( $path ) ) )
	{
		// Get current modules
		$mods = Array ( );
		if ( $currentModules = $db->fetchObjectRows ( '
			SELECT * FROM Setting WHERE SettingType="ContentModule" AND `Key` LIKE "pro%"
		' ) )
		{
			foreach ( $currentModules as $m )
			{
				$mods[] = $m->Key;
			}
		}
		
		// Get orders
		if ( $ordersp = $corebase->fetchObjectRows ( '
			SELECT * FROM ProductOrder 
			WHERE 
			SiteID=' . $siteData->ID . '
			ORDER BY DateOrdered ASC
		' ) )
		{
			$orders = array ( );
			foreach ( $ordersp as $o )
				$orders[ $o->ProductName ] = $o;
			unset ( $ordersp );
		}
		
		// List out modules
		$str = '';
		while ( $file = readdir ( $dir ) )
		{
			if ( $file{0} == '.' )
				continue;
			if ( substr ( $file, 0, 4 ) != 'pro_' )
				continue;
			// Skip added modules
			if ( in_array ( $file, $mods ) )
				continue;
			
			$data = file_get_contents ( $path . '/' . $file . '/info.txt' );
			list ( $info, $description ) = explode ( getLn ( ), $data );
			$info = explode ( '|', $info );
			$str .= '<div class="BuyModule">';
			$str .= '<table><tr><td>';
			$str .= '<div class="Image"><img src="skeleton/modules/' . 
				$file . '/module.png" width="100px" height="110px"></div>';
			$str .= '</td><td>';
			$str .= '<h2>' . $info[ 0 ] . '</h2>';
			$str .= '<p class="Price">Pris: kr. <strong>' . 
				number_format ( floatval ( $info[ 1 ] ) * 1.25, 2, ',', '.' ) . '</strong> pr. mnd.</p>';
			$str .= '<div class="Description"><p>' . $description . '</p></div>';
			$str .= '<button type="button" onclick="addProModule(\'' . 
				$file . '\')"><img src="admin/gfx/icons/money.png"> Kjøp</button>';
			$str .= '</td></tr></table>';
			$str .= '</div>';
		}
		closedir ( $dir );
		if ( strlen ( $str ) )
			return $str;
	}
	return '<p>Ingen moduler er tilgjengelig.</p>';
}

function editorStructure ( $currentcontent, $parent = 0, $depth = 0 )
{
	global $user, $Session;
	
	$content = new dbObject ( 'ContentElement' );
	$content->addClause ( 'WHERE', 'MainID != ID AND Parent = ' . $parent . ' AND !IsTemplate AND !IsDeleted' );
	$content->addClause ( 'WHERE', 'Language=' . $Session->CurrentLanguage );
	$content->addClause ( 'ORDER BY', 'IsSystem ASC, SortOrder ASC, ID ASC' );
	if ( $contents = $content->find ( ) )
	{
		unset ( $content );
		$ostr = '<ul' . ( $depth == 0 ? ' id="Structure"' : '' ) . '>';
		$isSystem = false;
		foreach ( $contents as $content )
		{	
			$main = new dbObject ( 'ContentElement' );
			$main->load ( $content->MainID );
			$class = 'content';
			$aclass = '';
			$info = '<small>';
			if ( $content->ID == $currentcontent->ID )
			{
				$author = new dbUser ( );
				$author->load ( $content->Author );
				$class .= ' current';
				$aclass = 'current';
				$info .= '<br>';
				$info .= '<div style="display: block; padding: 0 0 0 10px; border-top: 1px dotted #aabbcc;"><table class="LayoutColumns">';
				$info .= '<tr><td class="Column">Sist publisert:</td>';
				$info .= '<td class="Column">' . ( ( strtotime ( $content->DatePublish ) <= 0 ) ? 'Ikke publisert' : ArenaDate ( 'd/m/Y H:i', $content->DatePublish  ) ) . '</td></tr>';
				$info .= '<tr><td class="Column">Sist endret:</td>';
				$info .= '<td class="Column">' . ArenaDate ( 'd/m/Y H:i', $content->DateModified  ) . '</td></tr>';
				if ( $author->Name )
					$info .= '<tr><td class="Column">Endret av:</td><td class="Column">' . $author->Name . '</td></tr>';
				if ( $content->IsPublished ) 
					$info .= '<tr><td class="Column">Status:</td><td class="Column">Viser på nettsiden</td></tr>';
				else $info .= '<tr><td class="Column">Status:</td><td class="Column">Skjult</td></tr>';
				$info .= '</table></div>';
			}
			else if ( $main )
			{
				if ( $main->DateModified != $content->DateModified )
					$info .= ' (endret)';
			}
			if ( !$content->IsPublished ) $class .= ' hidden';
			$info .= '</small>';
			
			if ( $depth == 1 && $content->IsSystem && !$isSystem )
			{
				$ostr .= '<li class="Header">Sider som er skjult i menyen:</li>';
				$isSystem = true;
			}
			$drag = 'ondragstart="return false" onmousedown="dragger.startDrag ( this, { pickup: \'clone\', objectType: \'ContentElement\', objectID: \'' . $content->ID . '\' } ); return false"';
			$ostr .= '<li class="' . $class . ( $content->IsSystem ? ' system' : ' normal' ) . '">';
			if ( $user->checkPermission ( $content, 'Read', 'admin' ) )
				$ostr .= '<a class="' . $aclass . '" href="javascript: void(0)" onclick="setContent(' . $content->ID . ')"' . $drag . '>';
			else $ostr .= '<span class="inactive ' . $aclass . '"' . $drag . '>';
			
			if ( !trim ( $content->MenuTitle ) )
				$content->MenuTitle = '(Uten menytittel)';
			$ostr .= '<span class="Title">' . $content->MenuTitle . '</span>' . $info;
			
			if ( $user->checkPermission ( $content, 'Read', 'admin' ) )
				$ostr .= '</a>';
			else $ostr .= '</span>';
			
			$ostr .= editorStructure ( $currentcontent, $content->MainID, $depth + 1 );
			$ostr .= '</li>';
		}
		$ostr .= '</ul>';
	}
	return $ostr;
}

function structureButtons ( $contentid )
{
	global $user;
	if ( is_object ( $contentid ) ) $contentid = $contentid->ID;
	$cnt = new dbContent ( );
	$str = '';
	if ( $cnt->load ( $contentid ) )
	{
		if ( $user->checkPermission ( $cnt, 'Write', 'admin' ) )
		{
			$str .= '
		<button type="button" onclick="subPage ( )" title="Lag ny underside under aktiv side">
			<img src="admin/gfx/icons/page_add.png">
			Lag ny underside
		</button>';
		}
		if ( $user->checkPermission ( $cnt, 'Structure', 'admin' ) && $user->checkPermission ( $cnt, 'Write', 'admin' ) )
		{
			$str .= '
		<button type="button" onclick="reorderPage ()" title="Endre plassering av menypunktet">
			<img src="admin/gfx/icons/arrow_switch.png"> Sortering
		</button>
			';
		}
		if ( $user->checkPermission ( $cnt, 'Structure', 'admin' ) && $user->checkPermission ( $cnt, 'Write', 'admin' ) && $cnt->Parent > 0 )
		{
			$str .= '
		<button type="button" onclick="movePage ()" title="Flytt siden inn under en annen">
			<img src="admin/gfx/icons/folder_page_white.png"> Flytt
		</button>
			';
		}
	}
	return $str;
}

function renderHierarchyOptions ( $active, $parent = 0, $depth = 0 )
{
	global $user, $Session;
	$cnt = new dbObject ( 'ContentElement' );
	$cnt->addClause ( 'WHERE', 'MainID != ID AND Parent = ' . $parent . ' AND !IsTemplate AND !IsDeleted' );
	$cnt->addClause ( 'WHERE', 'Language=' . $Session->CurrentLanguage );
	$cnt->addClause ( 'ORDER BY', 'SortOrder ASC' );
	$tb = ''; for ( $a = 0; $a < $depth; $a++ )
		$tb .= '&nbsp;&nbsp;&nbsp;&nbsp;';
	if ( $depth == 0 )
		$str = '<select id="ReorderSelect" size="19" class="SubContainer" style="-moz-box-sizing: border-box; box-sizing: border-box; width: 100%; padding: 2px">';
	else $str = '';
	if ( $cnt = $cnt->find ( ) )
	{
		foreach ( $cnt as $c )
		{
			if ( !$user->checkPermission ( $c, 'Read', 'admin' ) ) continue;
			$sel = $c->ID == $active ? ' selected="selected"' : '';
			$str .= '<option value="' . $c->ID . '"' . $sel . '>' . $tb . $c->MenuTitle . '</option>';
			$str .= renderHierarchyOptions ( $active, $c->MainID, $depth + 1 );
		}
	}
	if ( $depth == 0 ) $str .= '</select>';
	return $str;
}

function contentButtons ( $contentid, $short = false )
{
	global $user, $Session;
	if ( is_object ( $contentid ) ) $contentid = $contentid->ID;
	else if ( !$contentid ) $contentid = $Session->EditorContentID;
	$cnt = new dbContent ( );
	if ( $cnt->load ( $contentid ) )
	{
		$org = new dbContent ( );
		$org->load ( $cnt->MainID );
		$str = '';
	
		if ( $user->checkPermission ( $cnt, 'Write', 'admin' ) )
		{
			$str .= '
		<button type="button" onclick="savePage ( )" title="Lagre innholdet til databasen">
			<img src="admin/gfx/icons/page_save.png">
			' . ( $short ? '' : ' Lagre' ) . '
		</button>
			';
			if ( $cnt->Parent > 0 )
			{
				$delete = '
		<button type="button" onclick="deletePage ( )" title="Slett den aktive siden">
			<img src="admin/gfx/icons/page_delete.png">
			' . ( $short ? '' : ' Slett' ) . '
		</button>
				';
			}
			else $delete = '';
		}
		if ( $user->checkPermission ( $cnt, 'Publish', 'admin' ) && $org->DateModified != $cnt->DateModified )
		{
			$str .= '
		<button type="button" onclick="publishPage ( )" title="Publiser den aktive siden">
			<img src="admin/gfx/icons/page_go.png">
			' . ( $short ? '' : 'Publiser' ) . '
		</button>
			';
		}
		if ( $user->checkPermission ( $cnt, 'Publish', 'admin' ) && $org->DateModified != $cnt->DateModified && $org->DateCreated )
		{
			$str .= '
		<button type="button" onclick="revertPage ( )" title="Hent publisert versjon til arbeidskopi">
			<img src="admin/gfx/icons/page_error.png">
			' . ( $short ? '' : 'Gjenopprett' ) . '
		</button>
			';
		}
		
		/** Check for extensions **/
		
		$objs = new dbObject ( 'Setting' );
		$objs->SettingType = 'ContentModule';
		$objs->addClause ( 'ORDER BY', '`Key` ASC' );
		if ( $objs = $objs->find ( ) )
		{
			foreach ( $objs as $obj )
			{
				if ( file_exists ( 'skeleton/modules/' . $obj->Key . '/editor_buttons.php' ) )
				{
					$buttonoutput =& $str;
					$content =& $cnt;
					include ( 'skeleton/modules/' . $obj->Key . '/editor_buttons.php' );
				}
			}
		}
		
		/** End check for extensions **/
		
		if ( $Session->AdminUser->checkPermission ( $cnt, 'Write', 'admin' ) )
		{
			$str .= '
			<button type="button" onclick="addField ( )" title="Legg til et innholdsfelt">
				<img src="admin/gfx/icons/table_row_insert.png">
				' . ( $short ? '' : ' Nytt felt' ) . '
			</button>
			';
		}
		$str .= $delete;
		if ( $user->isSuperUser ( ) )
		{
			$str .= '
		<button type="button" onclick="advancedSettings ( )" title="Se avanserte innstillinger">
			<img src="admin/gfx/icons/wrench.png">
			' . ( $short ? '' : ' Avansert' ) . '
		</button>
			';
		}
		$str .= '
		<button type="button" onclick="previewPage ( )" title="Forhåndsvis siden uten å publisere">
			<img src="admin/gfx/icons/eye.png">
			' . ( $short ? '' : 'Forhåndsvis' ) . '
		</button>
		';
	} 
	else die ( 'Error loading content: ' . $contentid );
	return $str;
}

function showPageTemplates ( )
{
	$str = '';
	$db =& dbOBject::globalValue ( 'database' );
	if ( $rows = $db->fetchObjectRows ( '
		SELECT * FROM ContentElement WHERE isTemplate ORDER BY MenuTitle ASC
	' ) )
	{
		$str = '<table class="List"><tr><th width="16">#</th><th>Navn:</th></tr>';
		foreach ( $rows as $row )
		{
			$str .= '
		<tr class="sw'. ($sw = ($sw == 1 ? 2 : 1) ) . '">
			<td>
				<input type="checkbox" id="templateCh_' . $row->ID . '"/>
			</td>
			<td>
				' . $row->MenuTitle . '
			</td>
		</tr>
			';
		}
		$str .= '</table>';
		$str .= '<div class="SpacerSmallColored"></div>';
		$str .= '<p><button type="button" onclick="deleteTemplates()">';
		$str .= '<img src="admin/gfx/icons/page_white_delete.png"/>';
		$str .= ' ' . i18n ( 'Delete templates' ) . '</button></p>';
	}
	else { $str .= '<p>'. i18n ( 'No templates defined' ) . '.</p>'; }
	return $str;
}

?>
