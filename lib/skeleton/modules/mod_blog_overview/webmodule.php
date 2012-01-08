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

global $document, $database;
$document->addResource ( 'javascript', 'lib/javascript/arena-lib.js' );
$document->addResource ( 'javascript', 'lib/javascript/bajax.js' );
$document->addResource ( 'javascript', 'lib/javascript/blestbox.js' );
$document->addResource ( 'javascript', 'lib/skeleton/modules/mod_blog_overview/javascript/web.js' );
$db_blog =& dbObject::globalValue('database');
$mtpldir = 'lib/skeleton/modules/mod_blog_overview/templates/';
i18nAddLocalePath ( 'lib/skeleton/modules/mod_blog_overview/locale');

// set up arrays to hold next and prev pos for navigation
$posAr = $pageAr = Array();
if ($_REQUEST['bpos'])
	$posAr= explode('_', $_REQUEST['bpos']);

if ($field->DataMixed)
{
    if (list($pages, $amounts, $navigations, $headings, $listmode, $imagesizex, $imagesizey ) = explode('#', $field->DataMixed))
    {
    	if ( !$listmode ) $listmode == 'titles';
    	$pages = explode('_', $pages);
	    $amounts = explode('_', $amounts);
	    if ( $headings = explode ( "\t\t", $headings ) )
	        foreach ( $headings as $k=>$v ) $headings[$k] = str_replace ( "%hash%", "#", $v );
	        
    	// Full listmode
		if ( $listmode == 'full' )
		{
			$len = count ( $pages );
			for ( $a = 0; $a < $len; $a++ )
			{
				$page = new dbContent ();
				if ( !$page->load ( $pages[$a] ) )
					continue;
				
				if ( $blogs = $database->fetchObjectRows ( '
					SELECT ID FROM BlogItem WHERE ContentElementID=\'' . $pages[$a] . '\' 
					ORDER BY DateUpdated DESC, ID DESC
					LIMIT ' . $amounts[$a] . '
				' ) )
				{
					$btpl = new cPTemplate ( $mtpldir . 'web_blog_w_ingress.php' );
					foreach ( $blogs as $blog )
					{
						$bo = new dbObject ( 'BlogItem' ); $bo->load ( $blog->ID );
						if ( $imagesizey > 0 && $imagesizex > 0 && list ( $image, ) = $bo->getObjects ( 'ObjectType = Image' ) )
						{
							$i = new dbImage ( $image->ID );
							$btpl->image = '<div class="OverviewImage">' . $i->getImageHTML ( $imagesizex, $imagesizey, 'framed' ) . '</div>';
						}
						else $btpl->image = '';
						$btpl->blog = $bo;
						$btpl->link = $page->getRoute () . '/blogitem/' . $bo->ID . '_' . texttourl ( $bo->Title ) . '.html';
						$module .= $btpl->render ();
					}
				}
			}
		}
    	// List only titles and date
    	else 
    	{
		    $blogcontents = array();
		    $hc = count ( $headings ) && $headings[0];

			if (!$posAr)
			{
				for ($k = 0; $k < count($pages); $k++ )
				{
					$posAr[$k] = 0;
				}
			}
		
		    for ($k = 0; $k < count($pages); $k++)
		    {
				$pos = $posAr[$k];
		        $blogs = new dbObject ('BlogItem');
		        $blogs->addClause('SELECT', 'ContentElementID, Title');
		        
		        // Add search
		        if ( $_REQUEST[ 'keywords' ] )
		        {
		        	$keys = explode ( ',', $_REQUEST[ 'keywords' ] );
		        	foreach ( $keys as $key )
		        	{
		        		if ( !trim ( $key ) ) continue;
		        		$wheres[] = "(Title LIKE \"%$key%\" OR Leadin LIKE \"%$key%\" OR Body LIKE \"%$key%\" OR Tags LIKE \"%$key%\")";
		        	}
		        	if ( count ( $wheres ) )
		        		$blogs->addClause ( 'WHERE', '( ' . implode ( ' OR ', $wheres ) . ' )' );
		        }
		        
		        $blogs->addClause('WHERE', 'IsPublished AND DatePublish <= NOW() AND ContentElementID=' . $pages[$k]);
		        $cnt = $blogs->findCount();
				$amount = $amounts[$k];
				$lim = $amount;
		        $blogs->addClause('ORDER BY', 'DateUpdated DESC, ID DESC');
				$blogs->addClause ( 'LIMIT', $pos . ',' . $lim );

		        if ($blogs = $blogs->find())
		        {
		            $page = new dbObject('ContentElement');
		            $page->load($pages[$k]);

				
		            $str .= '<div class="Bold BlogListTitle">' . i18n ( $hc ? $headings[$k] : 'Blogarticles from the page' ) . ( $hc ? '' : $page->Title ) . ':</div>';
		            foreach($blogs as $blog)
		            {
		                if ( !$blogcontents[$blog->ContentElementID] )
		                    $blogcontents[$blog->ContentElementID] = new dbContent ( $blog->ContentElementID );
		                $botpl = new cpTemplate($mtpldir . 'web_blogoversiktlist.php');
		                $botpl->cnt =& $blogcontents[$blog->ContentElementID];
		                $botpl->blog =& $blog;
		                $botpl->date = ArenaDate ( DATE_FORMAT, $blog->DatePublish );
		                $str .= $botpl->render();
		            }
				
					// navigation
					$nextPos = $prevPos = $posAr;
					if ($pos > 0) $prevPos[$k] = $pos - $lim;
					$prevPos = join('_', $prevPos);
					if ($pos + $lim < $cnt) $nextPos[$k] = $pos + $lim;
					$nextPos = join('_', $nextPos);
				
					$keys = $_REQUEST[ 'keywords' ] ? ( '&keywords=' . $_REQUEST[ 'keywords' ] ) : '';
					$next = $prev = $sep = '';

					if ($pos > 0)
						$prev = '<a href="javascript:blog_navigate(\'' . $prevPos . $keys . '\')" class="Prev"><span>' . i18n ('Newer blogs') . '</span></a>';
					if ($pos + $lim < $cnt)
						$next = '<a href="javascript:blog_navigate(\'' . $nextPos . $keys . '\')" class="Next"><span>' . i18n ('Older blogs') . '</span></a>';
					if ($prev && $next) 
						$sep = ' <span class="Separator">&nbsp;</span> ';
					else $sep = '';
					if ($prev || $next)
						$str .= '<div id="mod_blog_navigation"><hr/><p>' . $prev . $sep . $next . '</p></div>';
		        }
		    }
		}
    }
}
if ($_REQUEST['bpos'])
	die($str);
else if ($botpl)
	$module = $str;
?>
