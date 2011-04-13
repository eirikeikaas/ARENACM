<?php

$search = trim ( $_REQUEST[ 'searchfor' ] );
$replace = trim ( $_REQUEST[ 'replacewith' ] );

if ( $search )
{
	// Search and replace all ContentElement ------------------------------------- /
	$obj = new dbObject ( 'ContentElement' );
	if ( $objs = $obj->find () )
	{
		foreach ( $objs as $obj )
		{
			$obj->Intro = str_replace ( $search, $replace, $obj->Intro );
			$obj->Body = str_replace ( $search, $replace, $obj->Body );
			$obj->save ();
		}
	}

	// Search and replace all ContentDataSmall ----------------------------------- /
	$obj = new dbObject ( 'ContentDataSmall' );
	if ( $objs = $obj->find () )
	{
		foreach ( $objs as $obj )
		{
			$obj->DataMixed = str_replace ( $search, $replace, $obj->DataMixed );
			$obj->save ();
		}
	}

	// Search and replace all ContentDataBig ------------------------------------- /
	$obj = new dbObject ( 'ContentDataBig' );
	if ( $objs = $obj->find () )
	{
		foreach ( $objs as $obj )
		{
			$obj->DataText = str_replace ( $search, $replace, $obj->DataText );
			$obj->save ();
		}
	}
}

header ( 'Location: admin.php?module=core' );
die ( );

?>
