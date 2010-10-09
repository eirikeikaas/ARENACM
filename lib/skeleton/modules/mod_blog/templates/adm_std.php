
	<div>
		<div>
			<?= $this->blogs ? $this->blogs : 'Du har ingen artikler i arkivet.' ?>
		</div>
		<div class="SpacerSmallColored"></div>
		<?if ( !$this->settings->Sourcepage ) { ?>
		<button type="button" onclick="mod_blog_new()">
			<img src="admin/gfx/icons/newspaper.png"> Legg til en artikkel
		</button>
		<button type="button" onclick="mod_blog_authentication()">
			<img src="admin/gfx/icons/page_white.png"> Godkjenn artikler
		</button>
		<?}?>
		<button type="button" onclick="mod_blog_settings()">
			<img src="admin/gfx/icons/wrench.png"> Endre innstillingene
		</button>
	</div>

