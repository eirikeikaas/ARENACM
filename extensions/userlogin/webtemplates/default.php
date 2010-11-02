<?if ( !$GLOBALS[ "webuser" ]->ID ) { ?>
	<?
		list ( $serv, ) = explode ( '/', str_replace ( 'http://', '', BASE_URL ) ); 
		$rn = getLn ( 'r' ) . getLn ( );
		$data = '?function=register';
		header ( 'Location: ' . $this->content->getUrl ( ) . $data );
	?>
<?}?>
<?if ( $GLOBALS[ "webuser" ]->ID ) { ?>
	<h2 class="YourProfile"><span><?= i18n ( "Your profile" ) ?></span></h2>
	<p>
	</p>
<?}?>
