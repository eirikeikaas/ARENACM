<?php

global $database;
$database->query ( 'DELETE FROM ObjectConnection WHERE ObjectID=\'' . $_REQUEST[ 'objectid' ] . '\' AND ObjectType=\'' . $_REQUEST[ 'objecttype' ] . '\'' );
die ( 'ok' );

?>
