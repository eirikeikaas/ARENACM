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



include_once ( 'admin/modules/contents/include/main.php' );
$module = new cPTemplate ( "$tplDir/maincontent.php" );
/**
 * Fetch content
**/

$module->content = new dbContent ( );
$module->content->load ( $Session->contentID );

// Get published content
$module->publishedContent = new dbContent ( );
$module->publishedContent->load ( $module->content->MainID );

// This will trigger the sure generation of extra fields for editable copies
// TODO: Is this safe? Perhaps i need to uncomment? 2009-04-25
//$module->content->loadExtraFields ( );

// Fix strings that might include illegal characters
$module->content->Title = str_replace ( '"', '&quot;', $module->content->Title );
$module->content->MenuTitle = str_replace ( '"', '&quot;', $module->content->MenuTitle );
$module->content->SystemName = str_replace ( '"', '&quot;', $module->content->SystemName );

/**
 * Fetch templates
 */
$module->templates = '';
if ( is_dir ( 'templates' ) && $tdir = opendir ( 'templates' ) )
{
	while ( $file = readdir ( $tdir ) )
	{
		if ( $file{0} != "." && strtolower ( substr ( $file, 0, 4 ) ) == "page" )
		{
			$f = str_replace ( "page_", "", strtolower ( $file ) );
			$f = str_replace ( ".php", "", $f );
			$f = strtoupper ( $f{0} ) . substr ( $f, 1, strlen ( $f ) - 1 );
			if ( $module->content->Template == $file )
				$s = " selected=\"selected\"";
			else $s = "";
			$module->templates .= "<option value=\"{$file}\"$s>$f</option>";
		}
	}
	closedir ( $tdir );
}

/**
 * Access list
 */
$module->accesslist = showAccessList ( $module->content->ID );

/**
 * Symlink for pages to show instead of current page if access denied
 */
$setting = new dbObject ( 'Setting' );
$setting->SettingType = 'ContentsProtectedSymlink';
$setting->Key = $module->content->MainID;
if ( !$setting->load ( ) )
{
	$module->protectedSymlink = false;
}
else $module->protectedSymlink = $setting->Value;

/**
 * Fetch templates
 */
$module->templatesArchived = '';
if ( is_dir ( 'templates' ) && $tdir = opendir ( 'templates' ) )
{
	while ( $file = readdir ( $tdir ) )
	{
		if ( $file{0} != "." && strtolower ( substr ( $file, 0, 4 ) ) == "page" )
		{
			$f = str_replace ( "page_", "", strtolower ( $file ) );
			$f = str_replace ( ".php", "", $f );
			$f = strtoupper ( $f{0} ) . substr ( $f, 1, strlen ( $f ) - 1 );
			if ( $module->content->TemplateArchived == $file )
				$s = " selected=\"selected\"";
			else $s = "";
			$module->templatesArchived .= "<option value=\"{$file}\"$s>$f</option>";
		}
	}
	closedir ( $tdir );
}

/**
 * Add extra fields
 */
$module->extraFields = "Laster inn..";
$extraFields = getPluginFunction ( 
	"extrafields", 
	"adminrender", 
	Array ( 
		"ContentType"=>"ContentElement", 
		"ContentID"=>$module->content->ID 
	) 
);

switch ( $module->content->ContentType )
{
	case 'news':
		$module->contentgui = getPageModuleConfig ( "news", $module->content ) . $extraFields;
		break;
	case 'extensions':
		$module->contentgui = getPageModuleConfig ( "extensions", $module->content ) . $extraFields;
		break;
	case 'extrafields':
		$module->contentgui = $extraFields;
		break;
	case 'link':
		$tpl = new cPTemplate ( "admin/modules/contents/templates/maincontent_link.php" );
		$tpl->content =& $module->content;
		$module->contentgui = $tpl->render ( ) . $extraFields;
		break;
	default:
		$tpl = new cPTemplate ( "admin/modules/contents/templates/maincontent_text.php" );
		$tpl->content =& $module->content;
		$output = $tpl->render ( ) . $extraFields;
		$module->contentgui = $output;
		break;
}

/**
 * Add languages
**/
$languages = new dbObject ( "Languages" );

if ( $languages = $languages->find ( ) )
{
	foreach ( $languages as $language )
	{
		if ( $language->ID == $GLOBALS[ "Session" ]->CurrentLanguage ) $s = " selected=\"selected\"";
		else $s = "";
		$module->languages .= "<option value=\"{$language->ID}\"$s>{$language->NativeName} ({$language->Name})</option>";
	}
}

ob_clean ( );
die ( $module->render ( ) );
?>
