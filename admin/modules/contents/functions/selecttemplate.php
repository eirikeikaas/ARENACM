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



$module = new cPTemplate ( "$tplDir/select_template.php" );
$obj = new dbContent ( );
$obj->addClause ( "WHERE", "IsTemplate='1'" );
$obj->addClause ( "ORDER BY", "Language ASC, Title ASC" );
$module->options = "";
$languages = new dbObject ( "Languages" );
$languages->addClause ( "ORDER BY", "IsDefault DESC, NativeName ASC" );
$languages = $languages->find ( );
$langs = Array ( );
foreach ( $languages as $k=>$v )
	$langs[ $v->ID ] = $v;

if ( $objs = $obj->find ( ) )
{
	include_once ( "lib/classes/time/ctime.php" );
	$time = new cTime ( );
	$lang = false;
	foreach ( $objs as $obj )
	{
		
		if ( $obj->Language != $lang )
		{
			$module->options .= "<p><a name=\"#language" . $langs[ $obj->ID ] . "\"><strong>" . $langs[ $obj->Language ]->NativeName . ":</strong></a></p>";
			$module->languages .= "<option value=\"{$obj->Language}\">" . $langs[ $obj->Language ]->NativeName . "</option>";
			$lang = $obj->Language;
		}
		
		if( $obj->IsDefault && $lang == $GLOBALS[ "Session" ]->CurrentLanguage ) $selString = ' checked="checked"'; else $selString = '';
		
		$date = date ( "d.m.y", strtotime ( $obj->DateCreated ) ); 
		$module->options .= "<p>";
		$module->options .= "<input type=\"radio\" {$selString} name=\"template\" onclick=\"document.ModalSelection = '{$obj->ID}'\" /><strong>{$obj->Title}</strong>";
		
		if( $selString != '' )
		{
			$module->options.= '';
			$module->options.= '<script language="JavaScript" type="text/javascript">';
			$module->options.= '	document.ModalSelection = "' . $obj->ID . '";';
			$module->options.= '</script>';				

			$module->options.= '';
		}
		
		
		$module->options .= "</p>";
	}
}
else $module->selectedDefault = ' checked="checked"';
ob_clean ( );
die ( $module->render ( ) );
?>
