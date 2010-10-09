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

$cat = new dbObject ( "NewsCategory" );
if ( $_REQUEST[ "ID" ] ) $cat->load ( $_REQUEST[ "ID" ] );
else $cat->Language = $GLOBALS[ "Session" ]->CurrentLanguage;
$cat->receiveForm ( $_POST );
$cat->save ( );
$cat->grantPermission ( $Session->AdminUser, 'Read', '1', 'admin' );
$cat->grantPermission ( $Session->AdminUser, 'Write', '1', 'admin' );
$cat->grantPermission ( $Session->AdminUser, 'Publish', '1', 'admin' );
$cat->grantPermission ( $Session->AdminUser, 'Structure', '1', 'admin' );
ob_clean ( );
if ( $_REQUEST[ 'close' ] )
	header ( "Location: admin.php?module=news&cid={$cat->ID}" );
else header ( "Location: admin.php?module=news&function=category&cid={$cat->ID}" );
die ( );
?>
