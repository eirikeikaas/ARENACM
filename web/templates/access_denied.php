<html>
	<head>
		<title>
			Tilgang nektet
		</title>
	</head>
	<body>
		<?if ( $_POST[ "webUsername" ] ) { ?>
		<h1>
			Innloggingen feilet!
		</h1>
		<p>
			Innloggingen feilet. Vennligst prøv igjen.
		</p>
		<?}?>
		<?if ( !$_POST[ "webUsername" ] ) { ?>
		<h1>
			Tilgang nektet!
		</h1>
		<p>
			Siden du prøver å nå krever godkjenning!
		</p>
		<?}?>
		
		<form method="post">
			<p>
				<strong>Brukernavn:</strong>
				<input type="text" name="webUsername" />
			</p>
			<p>
				<strong>Passord:</strong>
				<input type="password" name="webPassword" />
			</p>
			<p>
				<button type="submit">
					Logg inn
				</button>
			</p>
		</form>
		
		<?if ( $_REQUEST[ "logout" ] ) { ?>
		<p>
			<a href="<?= BASE_URL ?>">&laquo; Til hovedsiden</a>
		</p>
		<?}?>
		<?if ( !$_REQUEST[ "logout" ] ) { ?>
		<?= $this->page->Parent > 0 ? ( '<p><a href="' . $this->parentPage->getUrl ( ) . '">' . i18n ( 'Go to parent page', $GLOBALS[ 'Session' ]->LanguageCode ) . '</a></p>' ) : '' ?>
		<?}?>
	</body>
</html>