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
$content = new dbContent ( );
$content->addClause ( "WHERE", "IsDeleted" );
if ( $content = $content->find ( ) )
{
	$str = "";
	foreach ( $content as $cnt )
	{
		if ( !$Session->AdminUser->checkPermission ( $cnt, 'Structure', 'admin' ) )
			continue;
		$sw = $sw == 1 ? 2 : 1;
		if ( $cnt->IsTemplate ) $t = " (mal)";
		else $t = "";
		$str .= "
		<tr class=\"sw$sw\">
			<td width=\"20px\"><input type=\"checkbox\" onchange=\"if ( this.checked ) addToUniqueList ( 'cntdel', '{$cnt->ID}' ); else remFromUniqueList ( 'cntdel', '{$cnt->ID}' )\" /></td>
			<td>{$cnt->MenuTitle} $t</td>
		</tr>
		";
	}
}
if ( !$str ) $str = "<tr><td>Ingen slettede elementer eksisterer.</td></tr>";
die ( "<table class=\"Gui\" style=\"width: 100%\">$str</table>" );
?>
