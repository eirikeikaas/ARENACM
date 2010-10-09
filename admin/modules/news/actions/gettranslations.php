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
$oStr = '';
$none = 'Ingen oversettelser finnes.';
$news = new dbObject ( 'News' );
if ( $news->load ( $_REQUEST[ 'nid' ] ) )
{
	$obj = new dbObject ( 'ObjectConnection' );
	$obj->addClause ( 'WHERE', "ObjectID = '{$news->ID}' AND ObjectType='News' AND Label='Translation' AND ConnectedObjectType='News'" );
	$obj->addClause ( 'ORDER BY', "SortOrder ASC, ID ASC" );
	if ( $obj = $obj->find ( ) )
	{
		$ttpl = new cPTemplate ( 'admin/modules/news/templates/translation_row.php' );
		foreach ( $obj as $o )
		{
			$n = new dbObject ( 'News' );
			$n->load ( $o->ConnectedObjectID );
			$c = new dbObject ( 'NewsCategory' );
			$c->load ( $n->CategoryID );
			$t = new dbObject ( 'Languages' );
			$t->load ( $c->Language );
			$ttpl->languagecode = $t->Name . '/' . $t->NativeName;
			$ttpl->data = &$n;
			$oStr .= $ttpl->render ( );
		}
	} else $oStr = $none;
} else $oStr = $none;
die ( $oStr );
?>
