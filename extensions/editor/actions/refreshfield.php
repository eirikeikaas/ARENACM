<?php

$f = new dbObject ( 'ContentData' . $_POST[ 'fieldtype' ] );
if ( $f->load ( $_POST[ 'fieldid' ] ) )
{
	die ( encodeArenaHTML ( $f->{$_POST[ 'field' ]} ) );
}
die ( '<!--fail-->' );

?>
