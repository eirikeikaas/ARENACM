<?php

global $database;
if ( $rows = $database->fetchObjectRows ( '
	SELECT * FROM
	(
		( SELECT Name FROM ContentDataSmall WHERE ContentTable="ContentElement" AND ContentID=\'' . $_REQUEST[ 'cid' ] . '\' )
		UNION
		( SELECT Name FROM ContentDataBig WHERE ContentTable="ContentElement" AND ContentID=\'' . $_REQUEST[ 'cid' ] . '\' )
	)z
	ORDER BY Name ASC
' ) )
{
	$str = '';
	foreach ( $rows as $row )
	{
		$str .= '<option value="' . $row->Name . '">' . $row->Name . '</option>';
	}
	die ( 'ok<!--separate--><select id="TxFieldName">' . $str . '</select>' );
}
die ( 'fail' );

?>
