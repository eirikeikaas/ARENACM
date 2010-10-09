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
 * Some funcs
**/
function parseConfig ( $searchkey )
{
	$encstring = explode ( "\n", $_SESSION[ "enc__string__" ] );
	foreach ( $encstring as $pair )
	{
		list ( $key, $value ) = explode ( "\t", $pair );
		if ( $key == $searchkey ) return $value;
	}
}
function showCatOptions ( $parent = "0", $r = "&nbsp;&nbsp;&nbsp;&nbsp;" )
{
	$newscats = new dbObject ( "NewsCategory" );
	$newscats->addClause ( "WHERE", "Parent='$parent'" );
	$newscats->addClause ( "ORDER BY", "Language ASC, Name ASC, DateCreated DESC" );
	if ( $newscats = $newscats->find ( ) ) 
	{
		$lang = -1;
		foreach ( $newscats as $cat )
		{
			if ( $parent == "0" && $cat->Language != $lang )
			{
				$lang = $cat->Language;
				$langu = new dbObject ( "Languages" );
				$langu->load ( $cat->Language );
				$oStr .= "<option value=\"0\">{$langu->NativeName} ({$langu->Name})</option>";
			}
			$cats = parseConfig ( "newscategories" );
			$cats = explode ( ",", $cats );
			$istr = "";
			$found = false;
			foreach ( $cats as $c ) 
			{
				if ( $c == $cat->ID )
				{
					$found = true;
					break;
				}
			}
			if ( $found ) $s = " selected=\"selected\""; else $s = "";
			$oStr .= "<option value=\"{$cat->ID}\"$s>{$r}{$cat->Name}$istr</option>";
			$oStr .= showCatOptions ( $cat->ID, $r . "&nbsp;&nbsp;&nbsp;&nbsp;" );
		}
	}
	return $oStr;
}

/**
 * Setup the template and config 
**/

$tpl = new cPTemplate ( "admin/modules/news/templates/pageconfig.php" );
if ( substr ( $content->Intro, 0, 11 ) != "Newsconfig\n" )
{
	$content->Intro = "Newsconfig\n";
	$content->save ( );
}
$tpl->content = &$content;
$_SESSION[ "enc__string__" ] = $content->Intro;

$commentArr = Array ( "0"=>"Ingen kommentarer", "1"=>"Kommentering for innloggede", "2"=>"Kommentering for alle" );
$tpl->CommentOptions = "";
foreach ( $commentArr as $k=>$v )
{
	if ( $k == parseConfig ( "comments" ) ) $s = " selected=\"selected\"";
	else $s = "";
	$tpl->CommentOptions .= "<option value=\"$k\"$s>$v</option>";
}

$tpl->CommentDateFormat = parseConfig ( "CommentDateFormat" );

$tpl->NewsCategories = showCatOptions ( );
$tpl->PrPage = parseConfig ( "prpage" );

/**
 * Done!
**/

$config = $tpl->render ( );
?>
