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

$c = new dbContent ( );
if ( $c->load ( $Session->contentID ) )
{
	if ( $p = $c->findSingle ( 'SELECT * FROM ContentElement WHERE ID != MainID AND MainID = ' . $c->Parent ) )
	{
		if ( $Session->AdminUser->checkPermission ( $p, 'Structure', 'admin' ) )
		{
			$db = &dbObject::globalValue ( "database" );
			
			$db->query ( 'UPDATE ContentElement SET IsSystem = 0 WHERE IsSystem IS NULL' );
			
			if ( $rows = $db->fetchObjectRows ( "
				SELECT Title, ID, MainID, SortOrder, IsSystem, IsTemplate, IsDeleted FROM ContentElement WHERE Parent='{$c->Parent}' AND MainID = ID AND !IsTemplate AND !IsDeleted
				ORDER BY IsSystem ASC, SortOrder ASC, ID DESC
			", MYSQL_ASSOC ) )
			{
				$len = count ( $rows );
				$b = 0;
				for ( $a = 0; $a < $len; $a++ )
				{
					$rows[ $a ]->Order = $a;
					if ( $rows[ $a ]->MainID == $c->MainID )
					{
						if ( $a < $len - 1 && $_REQUEST[ "offset" ] > 0 )
						{
							$db->query ( "UPDATE ContentElement SET SortOrder='" . ( $b + 1 ) . "' WHERE MainID='" . $rows[ $a ]->MainID . "'" );
							$db->query ( "UPDATE ContentElement SET SortOrder='" . ( $b ) . "' WHERE MainID='" . $rows[ $a + 1 ]->MainID . "'" );
							$a++; $b++;
						}
						else if ( $a > 0 && $_REQUEST[ "offset" ] < 0 )
						{
							$db->query ( "UPDATE ContentElement SET SortOrder='" . ( $b - 1 ) . "' WHERE MainID='" . $rows[ $a ]->MainID . "'" );
							$db->query ( "UPDATE ContentElement SET SortOrder='" . ( $b ) . "' WHERE MainID='" . $rows[ $a - 1 ]->MainID . "'" );
						}
					}
					else
					{
						$db->query ( "UPDATE ContentElement SET SortOrder='$b' WHERE MainID='" . $rows[ $a ]->MainID . "'" );
					}
					$b++;
				}
			}
		}
	}
}

ob_clean ( );

$rootpage = new dbContent ( );
$rootpage = $rootpage->getRootContent ( );
$rootpage->loadSubElements ( array ( 'editmode' => 1 ) );

$curr = new dbObject ( 'ContentElement' );
$curr->load ( $Session->contentID );
$curr = $curr->ID;

$str = contentToList ( $rootpage, BASE_URL, $curr, $rootpage->ID );

die ( $str );
?>
