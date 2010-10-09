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



include_once ( 'admin/modules/news/include/functions.php' );

/**
 * Receive form and save it
**/
$news = new dbObject ( "News" );
if ( $_POST[ "ID" ] ) $news->load ( $_POST[ "ID" ] );
else $news->DateCreated = date ( "Y-m-d H:i:s" );
$news->receiveForm ( $_POST );
$news->Intro = cleanHTMLElement ( $news->Intro );
$news->Article = cleanHTMLElement ( $news->Article );
$news->Title = $_REQUEST[ 'NewsTitle' ];
$news->save ( );
verifyNewsExtraFields ( $news );
// Save extra fields
$user->updateExtraFields ( );

/**
 * Transfer back to edit form
**/
ob_clean ( );
if ( $_REQUEST[ 'close' ] )
{
	header ( "Location: admin.php?module=news" );
}
else
{
	if ( $news->IsEvent )
		header ( "Location: admin.php?module=news&function=event&nid={$news->ID}" );
	else
		header ( "Location: admin.php?module=news&function=news&nid={$news->ID}" );
}
die ( );
?>
