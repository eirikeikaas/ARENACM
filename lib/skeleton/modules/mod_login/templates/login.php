
	<h2><?= i18n ( 'Login' ) ?></h2>
	<form method="post" name="loginform" action="<?= $this->page->getUrl () ?>">
		<p>
			<label for="fuser"><?= i18n ( 'Username' ) ?>:</label>
			<input type="text" id="fuser" name="webUsername"/>
		</p>
		<p>
			<label for="fpass"><?= i18n ( 'Username' ) ?>:</label>
			<input type="password" id="fpass" name="webPassword"/>
		</p>
		<p>
			<button type="submit">
				<span><?= i18n ( 'Login' ) ?></span>
			</button>
		</p>
	</form>
