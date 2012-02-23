<?php

// Set fieldcount
$c = new dbObject ( 'ContentElement' );
$c->load ( $_POST[ 'cid' ] );
SetSetting ( 'EasyEditor', 'FieldCount_' . $c->MainID, $_POST[ 'editfieldcount' ] );
die ( 'ok' );

?>
