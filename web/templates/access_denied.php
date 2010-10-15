<html>
	<head>
		<title>
			<?= i18n ( 'Please log in' ) ?>
		</title>
	</head>
	<body class="Login">
		<div class="Login">
			<?if ( $_POST[ "webUsername" ] ) { ?>
			<h1>
				<?= i18n ( 'The username or password was wrong' ) ?>
			</h1>
			<p>
				<?= i18n ( 'Please try to log in anew.' ) ?>
			</p>
			<?}?>
			<?if ( !$_POST[ "webUsername" ] ) { ?>
			<h1>
				<?= i18n ( 'Please log in' ) ?>
			</h1>
			<p>
				<?= i18n ( 'The page you are trying to reach requires user authentication.' ) ?>
			</p>
			<?}?>
		
			<form method="post">
				<p>
					<strong><?= i18n ( 'Username' ) ?>:</strong>
					<input type="text" name="webUsername" />
				</p>
				<p>
					<strong><?= i18n ( 'Password' ) ?>:</strong>
					<input type="password" name="webPassword" />
				</p>
				<p>
					<button type="submit">
						<?= i18n ( 'Login' ) ?>
					</button>
				</p>
			</form>
		
			<?if ( $_REQUEST[ "logout" ] ) { ?>
			<p>
				<a href="<?= BASE_URL ?>">&laquo; <?= i18n ( 'Go back' ) ?></a>
			</p>
			<?}?>
			<?if ( !$_REQUEST[ "logout" ] ) { ?>
			<?= $this->page->Parent > 0 ? ( '<p><a href="' . $this->parentPage->getUrl ( ) . '">' . i18n ( 'Go to parent page', $GLOBALS[ 'Session' ]->LanguageCode ) . '</a></p>' ) : '' ?>
			<?}?>
		</div>
	</body>
</html>
