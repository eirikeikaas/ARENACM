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



/** =========================================================================================================
 * generate tree of levels with edit / delete
 */
function generateLevelTree ( $cats, $parent = 0, $current = 0, $r = "" )
{
	global $Session;
	$oStr = '';

	$len = count ( $cats );
	$oStr = "";
	for ( $a = 0; $a < $len; $a++ )
	{
		if ( $cats[ $a ]->Parent == $parent )
		{
			$cats[ $a ]->_tableName = 'NewsCategory';
			$cats[ $a ]->_primaryKey = 'ID';
			$cats[ $a ]->_isLoaded = true;
			if ( $Session->AdminUser->checkPermission ( &$cats[ $a ], 'Read', 'admin' ) || $cats[ $a ]->ID == 'all' )
			{
				if ( $cats[ $a ]->ID == $current || !$current && $cats[ $a ]->ID == 'all' )
				{
					$oStr.= getLn() . '<li class="current">';
					
					$oStr.= getLn () . '<div class="ButtonBox" id="levelButtons' . $content->ID . '">';
					
					if ( $cats[ $a ]->ID > 0 )
					{
						$oStr.= getLn () . '	<div class="ButtonBoxButtons">';
						$oStr.= getLn () . '		<button type="button" id="editcatbutton" title="Rediger" onclick="this.onmouseout();editCategory( ' . $cats[ $a ]->ID . ' );"><img src="admin/gfx/icons/folder_edit.png" title="Rediger" /></button>';
						if ( $Session->AdminUser->checkPermission ( &$cats[ $a ], 'Write', 'admin' ) )
						{
							$oStr.= getLn () . '		<button type="button" id="deletecatbutton" title="Slett" onclick="this.onmouseout();deleteCategory( ' . $cats[ $a ]->ID . ' );"><img src="admin/gfx/icons/folder_delete.png" title="Slett" /></button>';
						}
						$oStr.= getLn () . '		<button id="cattowbbutton" type="button" onclick="addToWorkbench ( \'' . $cats[ $a ]->ID . '\', \'NewsCategory\' )" style="width: 30px"><img src="admin/gfx/icons/plugin.png" /></button>';
		
						$oStr.= getLn () . '	</div>';
					}
					
					$oStr.= getLn() . '	<b id="levelli'.$cats[ $a ]->ID.'">'. $cats[ $a ]->Name .'</b>';
					$oStr.= getLn() . '	<div style="clear:both;"><em></em></div>';
					$oStr.= getLn() . '</div>';				
				}
				else
				{
					$oStr.= getLn() . '<li>';
					$oStr.= getLn() . '	<a id="levelli'.$cats[ $a ]->ID.'" href="javascript:void(0)" onclick="document.location=\'admin.php?module=news&amp;cid='.$cats[ $a ]->ID.'\'">'. $cats[ $a ]->Name .'</a>';				

				}
				$oStr.=  generateLevelTree ( $cats, $cats[ $a ]->ID, $current );
			}
			
			
			$oStr .= '</li>';
		}
	}
	
	if( $oStr != '' && $parent > 0 ) $oStr = getLn() ."<ul class=\"newsLevel\">{$oStr}</ul>" . getLn();
	
	return $oStr;		

} // end of generateLevelTree

function verifyNewsExtraFields ( $newsitem )
{
	if ( !$newsitem->ID ) return false;
	
	// Check for extra fields on category
	$cat = new dbObject ( 'NewsCategory' );
	if ( $cat->load ( $newsitem->CategoryID ) )
	{
		foreach ( Array ( 'ContentDataSmall', 'ContentDataBig' ) as $tname )
		{
			// Copy small data fields if they don't exist
			$sfields = new dbObject ( $tname );
			$sfields->ContentID = $cat->ID;
			$sfields->ContentTable = 'NewsCategory';
			if ( $sfields = $sfields->find ( ) )
			{
				foreach ( $sfields as $sfield )
				{
					// Try to load already existing news extrafield
					$test = new dbObject ( $sfield->_tableName );
					$test->ContentID = $newsitem->ID;
					$test->ContentTable = 'News';
					$test->Name = $sfield->Name;
					$test->Type = $sfield->Type;
					
					// If it exists, continue
					if ( $test->load ( ) )
						continue;
					
					// If it does not exist, then copy extra field by
					// saving it with the right value and forcing ID regeneration
					unset ( $sfield->ID );
					$sfield->ContentID = $newsitem->ID;
					$sfield->ContentTable = 'News';
					$sfield->save ( );
				}
			}
		}
	}
	return true;
}	

?>
