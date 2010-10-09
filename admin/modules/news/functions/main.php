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

$module = new cPTemplate ( "$tplDir/main.php" );

$db =& dbObject::globalValue ( 'database' );

$all = new Dummy (); 
$all->ID = 'all';
$all->Name = 'Alle kategorier';

/**
 * Set up categories
**/
// Make sure we at least have a root category..
if ( !( $cats = $db->fetchObjectRows ( "SELECT * FROM NewsCategory WHERE Language='{$Session->CurrentLanguage}' ORDER BY Name ASC" ) ) )
{
	$cat = new dbObject ( 'NewsCategory' );
	$cat->Name = 'Nyheter';
	$cat->Language = $Session->CurrentLanguage;
	$cat->save ( );
	$cats = array ( $cat );
}
$cats = array_reverse ( $cats );
// Add "Alle kategorier" if one has more than one
$cauth = 0;
foreach ( $cats as $cat )
{
	if ( $Session->AdminUser->checkPermission ( $cat, 'Read', 'admin' ) )
		$cauth++;
}
if ( $cauth )
	$cats[] = $all;
$cats = array_reverse ( $cats );

$module->levels = generateLevelTree( $cats, 0, $Session->news_currentCategory );

$news = new dbObject ( 'News' );
$nwhere = Array ( );
$nlimit = '';
$norderby = '';

$where = Array ( );
if ( $category->ID > 0 )
	$where[] = "n.CategoryID='{$category->ID}' AND n.CategoryID = c.ID";
else $where[] = 'n.CategoryID = c.ID';
if ( $Session->CurrentLanguage )
	$where[] = "c.Language='{$Session->CurrentLanguage}'";
/**
 * Search query
**/
if ( $_REQUEST[ 'keywords' ] )
{
	$keywords = explode ( ' ', str_replace ( ',', ' ', $_REQUEST[ 'keywords' ] ) );
	$result = Array ( );
	foreach ( $keywords as $k )
	{
		$k = trim ( $k );
		$result[] = "( n.Title LIKE \"%$k%\" OR n.Intro LIKE \"%$k%\" OR n.Article LIKE \"%$k%\" )";
	}
	$nwhere[] = '( ' . implode ( ' OR ', $result ) . ')';
}

if ( $Session->news_currentCategory && $Session->news_currentCategory != 'all' )
{
	$nwhere[] = 'n.CategoryID = ' . $Session->news_currentCategory;
}
else
{
	$nwhere[] = 'c.Language = \'' . $Session->CurrentLanguage . '\'';
}
$nwhere[] = 'n.CategoryID = c.ID';



/**
 * Distinguish between news and events or not
**/
switch ( $Session->news_show )
{	
	case 'events':
		$nwhere[] = 'n.IsEvent=\'1\'';
		break;
	case 'news':
		$nwhere[] = 'n.IsEvent=\'0\'';
		break;
	default:
		break;
}

/**
 * Languages
**/
$languages = new dbObject ( 'Languages' );
$languages->addClause ( 'ORDER BY', 'IsDefault DESC, NativeName ASC' );
if ( $languages = $languages->find ( ) )
{
	$str = '';
	foreach ( $languages as $lang )
	{
		if ( $lang->ID == $Session->CurrentLanguage ) 
			$s = ' selected="selected"';
		else $s = '';
		$str .= "<option value=\"{$lang->ID}\"$s>{$lang->NativeName} ({$lang->Name})</option>";
	}
	$module->languages = $str;
} else $module->languages = false;

$showNews = true;


/*
	Groups available to user
*/
if ( $Session->AdminUser->loadGroups ( ) )
{
	$groups = Array ( );
	foreach ( $Session->AdminUser->groups as $g )
	{
		foreach ( $cats as $cat )
		{
			$cat->_tableName = 'NewsCategory';
			$cat->_primaryKey = 'ID';
			$cat->_isLoaded = true;
			if ( $Session->AdminUser->checkPermission ( $cat, 'Read', 'admin' ) )
			{
				if ( $cat->ID != 'all' ) $groups[] = '( c.ID = ' . $cat->ID . ' )';
			}
		}
	}
	$groups = count ( $groups ) > 0 ? ( '(' . implode ( ' OR ', $groups ) . ')' ) : '( c.ID <= 0 )';
}

/**
 * Get some vars related to pagination
**/
$pos = $_GET[ 'pos' ];
if ( $pos <= 0 ) $pos = '0';
$limit = 3;
list ( $count ) = $db->fetchRow ( 
	'SELECT COUNT(*) FROM News n, NewsCategory c WHERE ' . implode ( ' AND ', $nwhere ) . ' ' . ( $groups ? ( ' AND ' . $groups ) : '' )
);
$norderby = ' ORDER BY n.DateActual DESC, n.ID DESC';
$nlimit = " LIMIT $pos, $limit";

/**
 * Pagination
**/
include_once ( 'lib/classes/pagination/cpagination.php' );
$nav = new cPagination ( );
$nav->Count = $count;
$nav->Position = $pos;
$nav->Limit = $limit;
$module->Navigation = $nav->render ( );

/**
 * Find our news items and attach them to the template
**/

if ( $news = $news->find ( '
	SELECT n.* FROM News n, NewsCategory c WHERE ' . implode ( ' AND ', $nwhere ) . ' ' . ( $groups ? ( ' AND ' . $groups ) : '' ) . ' 
	GROUP BY n.ID ' . $norderby . $nlimit 
) )
{
	$tpl = new cPTemplate ( "$tplDir/newsitem.php" );
	for ( $a = 0; $a < $limit; $a++ )
	{
		if ( $a + $pos >= $count ) continue;
		$tpl->data = $news[ $a ];
		if ( !$categories[ $news[ $a ]->CategoryID ] )
			$categories[ $news[ $a ]->CategoryID ] = $db->fetchObjectRow ( 'SELECT * FROM NewsCategory WHERE ID=\'' . $news[ $a ]->CategoryID . '\' LIMIT 1' );
		$tpl->category = &$categories[ $news[ $a ]->CategoryID ];
		$tpl->category->_tableName = 'NewsCategory';
		$tpl->category->_primaryKey = 'ID';
		$tpl->category->_isLoaded = true;
		$tpl->canEdit = ( $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $categories[ $news[ $a ]->CategoryID ], 'Read', 'admin' ) );
		if ( $a < $count - 1 ) $tpl->Spacer = true;
		else $tpl->Spacer = false;
		$oStr .= $tpl->render ( );
	}
	$module->NewsItems = $oStr;
	unset ( $oStr, $news );
}
else
{
	$module->NewsItems = new cPTemplate ( "$tplDir/no_newsitems.php" );
	$module->NewsItems = $module->NewsItems->render ( );
}

/**
 * Add current cat
**/
$module->CurrentCategory = new dbObject ( 'NewsCategory' );
$module->CurrentCategory->load ( $Session->news_currentCategory );
?>
