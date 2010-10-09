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



global $Session;

include_once ( "lib/classes/dbObjects/dbFolder.php" );
include_once( 'admin/modules/library/include/functions_levels.php' );

$f = new dbFolder();
$f->Name = $_REQUEST[ 'libraryNewLevel' ];
$f->Parent = intval( $_REQUEST[ 'libraryParentLevel' ] );
$f->save();


$Session->Set ( 'LibraryCurrentLevel', $f->ID );

/** ------------------------------------------------------------------------------------------------
 * output content
 */
 
$root = dbFolder::getRootFolder ( );
$levels = generateLevelTree( $root, $Session->LibraryCurrentLevel );

ob_clean();
die( $levels . 	'<!--SEPERATOR-->' . $f->ID );


?>
