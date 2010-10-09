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



ob_clean ( );

if ( $_REQUEST[ "settpllang" ] )
	$tpllang = $_REQUEST[ "settpllang" ];
else $tpllang = $GLOBALS[ "Session" ]->CurrentLanguage;

$content = new dbContent ( );
$content->addClause ( "WHERE", "IsTemplate" );
$content->addClause ( "WHERE", "Language='{$tpllang}'" );
if ( $content = $content->find ( ) )
{
	$str = "";
	foreach ( $content as $cnt )
	{
		$sw = $sw == 1 ? 2 : 1;
		$str .= "
		<tr class=\"sw$sw\">
			<td width=\"20px\"><input type=\"checkbox\" onchange=\"if ( this.checked ) addToUniqueList ( 'tpldel', '{$cnt->ID}' ); else remFromUniqueList ( 'tpldel', '{$cnt->ID}' )\" /></td>
			<td>{$cnt->Title}</td>
		</tr>
		";
	}
}
else $str = "<tr><td>Ingen maler finnes her.</td></tr>";
die ( "<table class=\"Gui\" style=\"width: 100%\">$str</table>" );
?>
