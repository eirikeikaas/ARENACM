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
 * Syncronizes amount of extrafields on published versions with unpublished
**/

$db = dbObject::globalValue ( 'database' );

if ( $rows = $db->fetchObjectRows ( '
	SELECT 
		ID, MainID 
	FROM 
		ContentElement 
	WHERE 
		MainID != ID AND !IsDeleted AND !IsTemplate
' ) )
{
	foreach ( $rows as $row )
	{
		foreach ( Array ( 'ContentDataSmall', 'ContentDataBig' ) as $f )
		{
			/**
			 * Check with published extrafields
			**/
			$exsmalls = new dbObject ( $f );
			if( $exsmalls = $exsmalls->find ( 'SELECT * FROM ' . $f . ' WHERE ContentID=' . $row->MainID ) )
			{
				foreach ( $exsmalls as $small )
				{
					$oid = $small->ID;
					unset ( $small->ID );
					$small->ContentID = $row->ID;
					if ( !$small->load ( ) )
						$small->save ( );
				}
			}
		}
	}
}
ob_clean ( );
header ( 'location: admin.php?module=contents' );
die ( );
?>
