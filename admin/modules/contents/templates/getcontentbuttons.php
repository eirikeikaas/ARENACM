			
					<?if ( $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->content, 'Write', 'admin' ) ) { ?>
					<button type="button" id="savepage" onclick="savePage ( )">
						<img src="admin/gfx/icons/disk.png" /> Lagre
					</button>
					<?}?>
					<?if ( $this->content->DateModified != $this->main->DateModified && $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->content, 'Publish', 'admin' ) ) { ?>
					<button id="MainPublishbutton" type="button" onclick="if ( confirm ( 'Er du sikker på at du ønsker å publisere denne versjonen?\\nBesøkende vil kunne se sidene umiddelbart.' ) ) { publishPage ( <?= $this->content->ID ?> ); }">
						<img src="admin/gfx/icons/page_go.png" /> Publiser
					</button>
					<button id="MainRollbackbutton" type="button" onclick="if ( confirm ( 'Er du sikker på at du ønsker å bruke online versjonen istedet for arbeidsversjonen?' ) ) { rollBack ( <?= $this->content->ID ?> ); }">
						<img src="admin/gfx/icons/page_refresh.png" /> Rull tilbake
					</button>
					<?}?>
					<button type="button" id="preview" onclick="showPreview ( '<?= BASE_URL . $this->content->getPath ( ) ?>?editmode=1' )">
						<img src="admin/gfx/icons/eye.png" /> Forhåndsvis
					</button>
					<?if ( $this->content->Parent > 0 && $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->content, 'Publish', 'admin' ) ) { ?>
					<button type="button" id="makeblankcopy" onclick="makeCopy ( )">
						<img src="admin/gfx/icons/page_copy.png" /> Blank kopi
					</button>
					<?}?>
					<?if ( $this->content->Parent > 0 && $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->content, 'Structure', 'admin' ) ) { ?>
					<button type="button" id="deletepage" onclick="deletePage ( )">
						<img src="admin/gfx/icons/bin.png" /> Slett
					</button>
					<?}?>
					<button type="button" id="doneediting" onclick="document.getElementById ( 'tabStructure' ).onclick()">
						<img src="admin/gfx/icons/folder_page.png" /> Til sidestrukturen
					</button>
