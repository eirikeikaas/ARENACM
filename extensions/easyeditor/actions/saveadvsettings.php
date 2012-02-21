<?php

// Set fieldcount
$c = new dbContent ( $_POST[ 'cid' ] );
SetSetting ( 'EasyEditor', 'FieldCount_' . $c->MainID, $_POST[ 'editfieldcount' ] );
die ( 'ok' );

?>
