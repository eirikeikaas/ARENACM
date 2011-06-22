<?php

	$o = new dbObject ( $_REQUEST[ 't' ] );
	if ( $o->load ( $_REQUEST[ 'i' ] ) )
	{
		$o->SortOrder = $_REQUEST[ 'o' ];
		$o->save ();
	}
	ob_clean ();
	header ( 'Location: admin.php?module=library' );
	die ();

?>
