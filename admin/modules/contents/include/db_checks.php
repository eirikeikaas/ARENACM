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
 * Check on ContentDataSmall
**/
$db =& dbObject::globalValue ( 'database' );
$table = new cDatabaseTable ( 'ContentDataSmall' );
$table->load ( );
$contentGroup = false;
$isglobal = false;
foreach ( $table->getFieldNames ( ) as $name )
{
	if ( $name == 'ContentGroup' )
		$contentGroup = true;
	if ( $name == 'IsGlobal' )
		$isglobal = true;
}
if ( !$contentGroup )
{
	$db->query ( 'ALTER TABLE ContentDataSmall ADD `ContentGroup` varchar(255) DEFAULT "Default"' );	
}
if ( !$isglobal )
{
	$db->query ( 'ALTER TABLE ContentDataSmall ADD `IsGlobal` tinyint(4) DEFAULT 0' );	
}
/**
 * Check on ContentDataBig
**/
$db =& dbObject::globalValue ( 'database' );
$table = new cDatabaseTable ( 'ContentDataBig' );
$table->load ( );
$contentGroup = false;
$isglobal = false;
foreach ( $table->getFieldNames ( ) as $name )
{
	if ( $name == 'ContentGroup' )
		$contentGroup = true;
	if ( $name == 'IsGlobal' )
		$isglobal = true;
}
if ( !$contentGroup )
{
	$db->query ( 'ALTER TABLE ContentDataBig ADD ( `ContentGroup` varchar(255) DEFAULT "Default" )' );	
}
if ( !$isglobal )
{
	$db->query ( 'ALTER TABLE ContentDataBig ADD ( `IsGlobal` tinyint(4) DEFAULT 0 )' );	
}
/**
 * Check on ContentElement
**/
$db =& dbObject::globalValue ( 'database' );
$table = new cDatabaseTable ( 'ContentElement' );
$table->load ( );
$contentGroups = false;
foreach ( $table->getFieldNames ( ) as $name )
{
	if ( $name == 'ContentGroups' )
		$contentGroups = true;
}
if ( !$contentGroups )
{
	$db->query ( 'ALTER TABLE ContentElement ADD ( `ContentGroups` varchar(1024) DEFAULT "Default" )' );	
}
?>
