
	<div>
		<div>
			<?= $this->blogs ? $this->blogs : 'Du har ingen artikler i arkivet.' ?>
		</div>
		<div class="SpacerSmallColored"></div>
		<?if ( !$this->settings->Sourcepage || $this->settings->Sourcepage == $this->content->MainID ) { ?>
		<button type="button" onclick="mod_blog_new()">
			<img src="admin/gfx/icons/newspaper.png"> <?= i18n ( 'Add article' ) ?>
		</button>
		<button type="button" onclick="mod_blog_authentication()">
			<img src="admin/gfx/icons/page_white.png"> <?= i18n ( 'Authenticate incoming articles' ) ?>
		</button>
		<?}?>
		<button type="button" onclick="mod_blog_settings()">
			<img src="admin/gfx/icons/wrench.png"> <?= i18n ( 'Edit settings' ) ?>
		</button>
	</div>

