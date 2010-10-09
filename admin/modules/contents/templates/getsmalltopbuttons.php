			
					<?if ( $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->content, 'Write', 'admin' ) ) { ?>
					<button type="button" id="Smallsavepage" onclick="savePage ( )">
						<img src="admin/gfx/icons/disk.png" />
					</button>
					<?}?>
					<?if ( $this->content->DateModified != $this->main->DateModified && $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->content, 'Publish', 'admin' ) ) { ?>
					<button id="SmallMainPublishbutton" type="button" onclick="if ( confirm ( 'Er du sikker på at du ønsker å publisere denne versjonen?\\nBesøkende vil kunne se sidene umiddelbart.' ) ) { publishPage ( <?= $this->content->ID ?> ); }">
						<img src="admin/gfx/icons/page_go.png" />
					</button>
					<button id="SmallMainRollbackbutton" type="button" onclick="if ( confirm ( 'Er du sikker på at du ønsker å bruke online versjonen istedet for arbeidsversjonen?' ) ) { rollBack ( <?= $this->content->ID ?> ); }">
						<img src="admin/gfx/icons/page_refresh.png" />
					</button>
					<?}?>
					<button type="button" id="Smallpreview" onclick="showPreview ( '<?= BASE_URL . $this->content->getPath ( ) ?>?editmode=1' )">
						<img src="admin/gfx/icons/eye.png" />
					</button>
					<?if ( $this->content->Parent > 0 && $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->content, 'Publish', 'admin' ) ) { ?>
					<button type="button" id="Smallmakeblankcopy" onclick="makeCopy ( )"<?= isIE () ? " style=\"width: 32px\"" : "" ?>>
						<img src="admin/gfx/icons/page_copy.png" />
					</button>
					<?}?>
					<?if ( $this->content->Parent > 0 && $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->content, 'Structure', 'admin' ) ) { ?>
					<button type="button" id="Smalldeletepage" onclick="deletePage ( )"<?= isIE () ? " style=\"width: 32px\"" : "" ?>>
						<img src="admin/gfx/icons/bin.png" />
					</button>
					<?}?>
					<button type="button" id="Smalldoneediting" onclick="document.getElementById ( 'tabStructure' ).onclick()">
						<img src="admin/gfx/icons/folder_page.png" />
					</button>
