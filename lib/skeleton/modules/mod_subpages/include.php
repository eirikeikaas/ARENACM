<?php

function listSubpageLevels ( $pp, $currlev, $maxlevels, $fieldObject, $content, $options )
{
	$subpages = new dbContent ();
	$subpages->Parent = $pp->MainID;
	$subpages->addClause ( 'WHERE', 'MainID = ID AND !IsDeleted' );
	$subpages->addClause ( 'ORDER BY', 'SortOrder ASC, ID DESC' );
	$str = '';
	$oneOpen = false;
	if ( $subpages = $subpages->find ( ) )
	{
		if ( $options->Mode == 'mode_brief' )
		{
			$str .= '<ul>';
		}
		foreach ( $subpages as $p )
		{
			$open = false;
			$p->{"_locked_".$fieldObject->Name} = 'true';
			if ( $options->Mode == 'mode_brief' )
			{
				if ( $content->MainID == $p->MainID )
					$c = ' current';
				else $c = '';
				$tl = $currlev + 1;
				$istr = '';
				if ( $tl < $maxlevels )
				{
					$a = listSubpageLevels ( $p, $tl, $maxlevels, $fieldObject, $content, $options );
					if ( $a[0] )
						$istr = $a[0];
					if ( $a[1] )
						$open = $a[1];
				}
				$str .= '<li class="' . $p->RouteName . $c . '' . ( $open ? ' open' : '' ) . '"><a href="' . $p->getUrl () . '">' . $p->MenuTitle . '</a>';
				$str .= $istr;
				$str .= '</li>';
				if ( $c ) $open = 1; // subpage is current
			}
			else
			{
				$str .= '<div class="Block '.$p->RouteName . '">';
				$str .= preg_replace ( '/id\=\"([^"]*?)\"/i', 'class="$1"', $p->renderExtraFields () );
				if ( $tl < $maxlevels )
				{
					$a = listSubpageLevels ( $p, $tl, $maxlevels, $fieldObject, $content, $options );
					if ( $a[0] )
						$str .= $a[0];
					if ( $a[1] )
						$open = $a[1];
				}
				$str .= '</div>';
			}
			if ( $open )
				$oneOpen = true;
		}
		if ( $options->Mode == 'mode_brief' )
		{
			$str .= '</ul>';
		}
	}
	return array ( $str, $oneOpen );
}

?>
