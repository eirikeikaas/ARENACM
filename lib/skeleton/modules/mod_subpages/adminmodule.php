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

i18nAddLocalePath ( 'lib/skeleton/modules/mod_subpages/locale' );

if ( $_REQUEST[ 'action' ] && $_REQUEST[ 'action' ] == 'subpages_saveoption' )
{
	$fieldObject->DataInt = $_REQUEST[ 'sid' ];
	$fieldObject->DataMixed = $_REQUEST[ 'mixed' ];
	$fieldObject->save ();
	die ('ok' );
}

$options = CreateObjectFromString ( $fieldObject->DataMixed );

$mode_Options = '';
foreach ( 
	array ( 
		'mode_full'=>'Vis alt undersideinnhold', 
		'mode_brief'=>'Vis kun lenker til sidene' ) as $mode_=>$val
)
{
	$s = $options->Mode == $mode_ ? ' selected="selected"' : '';
	$mode_Options .= '<option value="' . $mode_ . '"' . $s . '>' . $val . '</option>';
}

$module = '
	<table>
		<tr>
			<td valign="middle"><label>' . i18n ( 'subpages_Subpages of page' ) . ':</label></td>
			<td valign="middle">
				<select id="subpages_' . $fieldObject->ID . '">
					' . getSiteStructureOptions ( $fieldObject->DataInt ) . '
				</select>
			</td>
			<td valign="middle">
				<select id="subpagesmode_' . $fieldObject->ID . '">
				' . $mode_Options . '
				</select>
			</td>
		</tr>
	</table>
	<script type="text/javascript">
		AddSaveFunction ( function ()
		{ 
			var j = new bajax ();
			j.openUrl ( \'admin.php?module='.$_REQUEST['module'].'&extension='.$_REQUEST['extension'].'&action=subpages_saveoption\', \'post\', true );
			j.addVar ( \'sid\', document.getElementById ( \'subpages_'.$fieldObject->ID.'\' ).value );
			var s = "Mode\t"+ge(\'subpagesmode_' . $fieldObject->ID . '\').value;
			j.addVar ( \'mixed\', s );
			j.onload = function (){};
			j.send ();
		}
		);
	</script>
';

?>
