<?php

	include_once ( 'extensions/editor/include/funcs.php' );
		
	if ( $ids = explode ( ',', $_REQUEST[ 'ids' ] ) )
	{
		foreach ( $ids as $id )
		{
			$c = new dbContent ( );
			if ( $c->load ( $id ) )
				$c->delete ();
		}
	}
	die ( showPageTemplates ( ) );

?>
