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



/**
 * Prerequisites
 */
define ( 'MarginSize', '8' );
define ( 'ClassDir', 'lib/classes/' );
define ( 'ARENAMODE', 'admin' );

include_once ( 'include/funcs_module.php' );
$moduleDir = 'admin/modules/';

/** 
 * Check if there has been an ARENA update
**/
if ( !$Session->ArenaInfoChecked ) 
{
	$arenainfo = new dbObject ( 'ArenaInfo' );
	$arenainfo->addClause ( 'ORDER BY', 'DateUpdated DESC' );
	$checkdb = false;
	if ( $arenainfo = $arenainfo->findSingle ( ) )
	{
		if ( $arenainfo->Version != ARENA_VERSION ) 
			$checkdb = true;
	}
	else $checkdb = true;
	if ( $checkdb ) include_once ( 'include/sanitize_upgrade.php' );
	$Session->Set ( 'ArenaInfoChecked', 1 );
}

/** 
 * Language
**/
$document->addHeadScript ( 'admin/javascript/workbench.js' );
$document->addHeadScript ( 'admin/javascript/arena-admin.js' );
$document->addHeadScript ( 'lib/javascript/gui.js' );

/**
 * Language
 * A site must always have one default language installed to
 * work!
**/
$lang = new dbObject ( 'Languages' );
$lang->IsDefault = 1;
if ( !$lang->load ( ) )
{
	$lang->Name = 'no';
	$lang->NativeName = 'Norsk';
	$lang->IsDefault = '1';
	$lang->save ( );
}
if ( !defined ( 'ADMIN_LANGUAGE' ) )
{
	define ( 'ADMIN_LANGUAGE', $lang->Name );
}
$Session->Set ( 'AdminLanguageCode', ADMIN_LANGUAGE );

// TODO: change language

/**
 * Check if we're running a plugin action
**/
checkPluginAction ( );

/**
 * Get modules for this site
 */
$modules = new dbObject ( 'ModulesEnabled', $corebase );
$siteData = &dbObject::globalValue ( 'sitedata' );
$modules->SiteID = $siteData->ID;
$modules->addClause ( 'ORDER BY', 'SortOrder ASC, ModuleName DESC, Module DESC' );
$modules->addClause ( 'GROUP BY', 'Module' );
if ( !( $modules = $modules->find ( ) ) )
	ArenaDie ( 'Failed to initialize modules!' );
dbObject::globalValueSet ( 'modules', &$modules );

// Check that we have a current module
$modulename = getCurrentModule ( );

// Check that we're allowed!
if ( !$Session->AdminUser->modulePermission ( 'Access', $modulename ) )
	$modulename = false;
if ( !$modulename ) 
{
	foreach ( $modules as $module )
	{
		if ( !$Session->AdminUser->modulePermission ( 'Access', $module->Module ) )
			continue;
		if ( $module->Module == 'contents' && $Session->AdminUser->modulePermission ( 'Access', 'contents' ) )
		{
			setCurrentModule ( $module );
			break;
		}
		else if ( !$modulename )
		{
			setCurrentModule ( $module );
			$modulename = $module->Module;
			break;
		}
	}
	$modulename = getCurrentModule ( );
}
if ( !$modulename ) $modulename = $modules[ 0 ]->Module;
reset ( $modules );

if ( !isset ( $_REQUEST[ 'bajaxrand' ] ) )
{
	// Set active extension
	if ( 
		$_REQUEST[ 'extension' ] && 
		file_exists ( 'extensions/' . $_REQUEST[ 'extension' ] . '/extension.php' ) &&
		$Session->AdminUser->extensionPermission ( 'Access', $_REQUEST[ 'extension' ] )
	) 
	{
		$Session->set ( 'currentExtension', $_REQUEST[ 'extension' ] );
	}
	
	$tpl = new cPTemplate ( 'admin/templates/module_tab.php' );
	$oStr = '';
	$extensions = Array ( );
	foreach ( $modules as $module )
	{
		if ( $module->Module == 'settings' || $module->Module == 'core' ) continue;
		if ( !$Session->AdminUser->modulePermission ( 'Access', $module->Module ) )
			continue;
		
		// List extensions in the module list if there are few of them	
		if ( strtolower ( $module->Module ) == 'extensions' )
		{
			if ( file_exists ( 'extensions' ) )
			{
				if ( $dir = opendir ( 'extensions' ) )
				{
					while ( $file = readdir ( $dir ) )
					{
						if ( $file{0} == '.' ) continue;
						if ( $Session->AdminUser->extensionPermission ( 'Access', $file ) )
						{
							$extensions[] = $file;
						}
					}
					closedir ( $dir );
				}
			}
			// Setup extensions
			$activeModule = false;
			if ( count ( $extensions ) > 0 )
			{
				$out = Array ( );
				foreach ( $extensions as $extension )
				{
					$info = 'extensions/' . $extension . '/info.csv';
					if ( !file_exists ( $info ) ) continue;
					$out[] = $extension;
				}
				unset ( $extensions );
				if ( count ( $out ) )
				{
					$sorted = Array ( );
					foreach ( $out as $extension )
					{
						$info = 'extensions/' . $extension . '/info.csv';
						list ( $name, $priority ) = explode ( '|', file_get_contents ( $info ) );
						if ( !$priority ) $priority = "10";
						else $priority = trim ( $priority );
						$sorted[] = str_pad ( $priority, 4, '0', STR_PAD_LEFT ) . '___' . trim ( $name ) . '___' . trim ( $extension ) . $i;
					}
					arsort ( $sorted );
					$sorted = array_reverse ( $sorted );
					
					foreach ( $sorted as $sort )
					{
						list ( $priority, $name, $extension ) = explode ( '___', $sort );
						if ( file_exists ( 'extensions/' . $extension . '/extension.png' ) )
							$tpl->image = '../../../extensions/' . $extension . '/extension.png';
						else $tpl->image = 'page_white.png';
					
						$tpl->module = $extension;
						$tpl->moduleName = i18n ( $name );
						$tpl->active = false;
						
						if ( $modulename == 'extensions' )
						{
							// Activate active extension tab
							if ( !$Session->currentExtension || $extension == $Session->currentExtension )
							{
								$activeModule = $extension;
								$tpl->active = true;
								$document->extensionsOnTop = true;
								$Session->set ( 'currentExtension', $extension );
								$document->Title = $name . ' - ARENACM v2';
							}
						}
						
						$tpl->link = 'module=extensions&extension=' . $extension;
						// If module brings sub modules of its own
						if ( file_exists ( 'extensions/' . $extension . '/extension_modulelist.php' ) )
							include_once ( 'extensions/' . $extension . '/extension_modulelist.php' );
						else $oStr .= $tpl->render ( );
					}
					continue;
				}
			}
		}
		$tpl->module = $module->Module;
		$tpl->moduleName = i18n ( 'module_' . $module->Module );
		$tpl->link = '';
		if ( $tpl->module == getCurrentModule ( ) )
		{
			$tpl->active = true;
			$document->Title = $tpl->moduleName . ' - ARENACM v2';
		}
		else $tpl->active = false;
	
		// change fallback icons
		if ( !( $tpl->image = $module->ModuleIcon ) )
		{
			switch ( $module->Module )
			{
				case 'settings':
					$tpl->image = 'wrench.png';
					break;
				case 'users':
					$tpl->image = 'group.png';
					break;
				case 'contents':
					$tpl->image = 'page.png';
					break;
				case 'news':
					$tpl->image = 'newspaper.png';
					break;
				case 'extensions':
					$tpl->image = 'plugin.png';
					break;
				case 'library':
					$tpl->image = 'image.png';
					break;
				default: break;
			}
		}
		$oStr .= $tpl->render ( );
	}
	$document->moduleList = $oStr; 
}
$oStr = '';

/**
 * Setup module defaults
 */
$tplDir = $moduleDir . $modulename . '/templates';
$action = $_REQUEST[ 'action' ] ? $_REQUEST[ 'action' ] : false;
if ( $action ) 
{
	$actionFn = $moduleDir . $modulename . '/actions/' . $action . '.php';
	$action = file_exists ( $actionFn ) ? $actionFn : false;
}
$function = $_REQUEST[ 'function' ] ? $_REQUEST[ 'function' ] : 'main';
if ( $function ) 
{
	$functionFn = $moduleDir . $modulename . '/functions/' . $function . '.php';
	$function = file_exists ( $functionFn ) ? $functionFn : false;
}

/**
 * Include and run the module
 */
if ( require ( $moduleDir . $modulename . '/module.php' ) )
	$document->moduleOutput = $module->render ( );	
else ArenaDie ( 'Failed to open specified module.' );
?>
