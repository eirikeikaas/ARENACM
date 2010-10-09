	
	<?if ( $this->content->DateModified != $this->main->DateModified && $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->main, 'Publish', 'admin' ) ) { ?>
	
	<p>
		Arbeidskopien du jobber på er annerledes enn den publiserte versjonen. Du kan publisere, eller rulle tilbake publisert versjon over arbeidskopien.
	</p>
	<p>
		<button type="button" onclick="if ( confirm ( 'Er du sikker på at du ønsker å publisere denne versjonen?\\nBesøkende vil kunne se sidene umiddelbart.' ) ) { publishPage ( <?= $this->main->ID ?> ); }">
			<img src="admin/gfx/icons/page_go.png" /> Publiser
		</button>
		<button type="button" onclick="if ( confirm ( 'Er du sikker på at du ønsker å bruke online versjonen istedet for arbeidsversjonen?' ) ) { rollBack ( <?= $this->main->ID; ?> ); }">
			<img src="admin/gfx/icons/page_refresh.png" /> Rull tilbake
		</button>
	</p>
	
	<?}?>
	<?if ( $this->content->DateModified == $this->main->DateModified ) { ?>
	
	<p>
		<strong>Status: Oppdatert</strong>
	</p>
	</p>
	<p>
		Siden er ikke forskjellig fra den publiserte siden.
	</p>

	<?}?>
