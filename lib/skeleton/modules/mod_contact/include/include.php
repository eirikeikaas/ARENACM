<?php

function generateSpamControl ( )
{
	switch ( rand ( 0, 2 ) )
	{
		case 0:
			$q = i18n ( 'What is sum of one plus three?' );
			$a = 4;
			break;
		case 1:
			$q = i18n ( 'Does a zebra have stripes?' );
			$a = i18n ( 'answer_yes' );
			break;
		case 2:
			$q = i18n ( 'Does an average human have two eyes?' );
			$a = i18n ( 'answer_yes' );
			break;
	}
	return array ( $q, $a );
}

?>
