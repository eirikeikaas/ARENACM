<?php

function listSubpageLevels ( $pp, $currlev, $maxlevels, $fieldObject, $content, $options )
{
	$subpages = new dbContent ();
	$subpages->Parent = $pp->MainID;
	$subpages->addClause ( 'WHERE', 'MainID = ID AND !IsDeleted' );
	$subpages->addClause ( 'ORDER BY', 'SortOrder ASC, ID DESC' );
	$str = '';
	if ( $subpages = $subpages->find ( ) )
	{
		if ( $options->Mode == 'mode_brief' )
		{
			$str .= '<ul>';
		}
		foreach ( $subpages as $p )
		{
			$str .= '<div class="Block '.$p->RouteName . '">';
			$p->{"_locked_".$fieldObject->Name} = 'true';
			if ( $options->Mode == 'mode_brief' )
			{
				if ( $content->MainID == $p->MainID )
					$c = ' current';
				else $c = '';
				$str .= '<li class="' . $p->RouteName . $c . '"><a href="' . $p->getUrl () . '">' . $p->MenuTitle . '</a>';
				$tl = $currlev + 1;
				if ( $tl < $maxlevels )
				{
					$str .= listSubpageLevels ( $p, $tl, $maxlevels, $fieldObject, $content, $options );
				}
				$str .= '</li>';
			}
			else
			{
				$str .= preg_replace ( '/id\=\"([^"]*?)\"/i', 'class="$1"', $p->renderExtraFields () );
				if ( $tl < $maxlevels )
				{
					$str .= listSubpageLevels ( $p, $tl, $maxlevels, $fieldObject, $content, $options );
				}
			}
			$str .= '</div>';
		}
		if ( $options->Mode == 'mode_brief' )
		{
			$str .= '</ul>';
		}
	}
	return $str;
}

?>
