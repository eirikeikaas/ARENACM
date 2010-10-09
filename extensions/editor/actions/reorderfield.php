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

$cnt = new dbContent ( );
if ( $cnt->load ( $_REQUEST[ 'cid' ] ) )
{
	if ( $cnt->checkPermission ( $GLOBALS[ 'user' ], 'write', 'admin' ) )
	{
		$f = new dbObject ( $_REQUEST[ 'ft' ] );
		if ( $f->load ( $_REQUEST[ 'fid' ] ) )
		{
			if ( $db =& $f->getDatabase ( ) )
			{
				if ( $fields = $db->fetchObjectRows ( '
					SELECT * FROM  
					( 
						SELECT ID, `Name`, "ContentDataSmall" as `Table`, SortOrder FROM ContentDataSmall WHERE ContentTable="ContentElement" AND ContentID=' . $cnt->ID . '
						UNION 
						SELECT ID, `Name`, "ContentDataBig" as `Table`, SortOrder FROM ContentDataBig WHERE ContentTable="ContentElement" AND ContentID=' . $cnt->ID . '
					) as z 
					ORDER BY SortOrder ASC, ID ASC
				' ) )
				{
					for ( $a = 0; $a < count ( $fields ); $a++ )
					{
						// Move up
						if ( $_REQUEST[ 'dir' ] < 0 )
						{
							if ( ( $a - 1 ) >= 0 && $fields[ $a ]->ID == $_REQUEST[ 'fid' ] )
							{
								$tmp = $fields[ $a ];
								$fields[ $a ] = $fields[ $a - 1 ];
								$fields[ $a - 1 ] = $tmp;
							}
						}
						// Move down
						else
						{
							if ( $a - 1 >= 0 && $fields[ $a - 1 ]->ID == $_REQUEST[ 'fid' ] )
							{
								$tmp = $fields[ $a ];
								$fields[ $a ] = $fields[ $a - 1 ];
								$fields[ $a - 1 ] = $tmp;
							}
						}
					}
					$a = 0;
					//$str = '';
					foreach ( $fields as $field )
					{
						//$str .= 'UPDATE `' . $field->Table . '` SET SortOrder = \'' . $a . '\' WHERE ID=\'' . $field->ID . '\' (' . $field->Name . ')' . getLn ( );
						$db->query ( 'UPDATE `' . $field->Table . '` SET SortOrder = \'' . $a . '\' WHERE ID=\'' . $field->ID . '\'' );
						$a++;
					}
					//die ( $str );
				}
			}
		}
	}
}
ob_clean ( );
header ( 'location: admin.php?module=extensions&extension=editor&cid=' . $_REQUEST[ 'cid' ] );
die ( );
?>
