
	<h3 style="position: relative">
		<div style="float: right">
			<button title="Lagre artikkel" type="button" class="Small" onclick="mod_blog_save()">
				<img src="admin/gfx/icons/disk.png">
			</button>
			<?if ( $this->blog->ID ) { ?>
			<button title="Forhåndsvis" type="button" class="Small" onclick="mod_blog_preview(<?= $this->blog->ID ?>)">
				<img src="admin/gfx/icons/eye.png">
			</button>
			<?}?>
			<button title="Avbryt" type="button" class="Small" onclick="mod_blog_abortedit()">
				<img src="admin/gfx/icons/cancel.png">
			</button>
		</div>
		<?if ( $this->blog ) { ?>
			Endre: <?= $this->blog->Title ?>
		<?}?>
		<?if ( !$this->blog ) { ?>
			Ny artikkel
		<?}?>
	</h3>
	<iframe class="Hidden" name="hiddenblogiframe"></iframe>
	<input type="hidden" id="BlogIdentifier" value="<?= $this->blog->ID ?>">
	<div class="SpacerSmallColored"></div>
	<div class="SubContainer" style="padding: <?= MarginSize ?>px">
		
		<table style="width: 100%">
			<tr>
				<td style="width: 110px">
					<strong>Tittel:</strong>
				</td>
				<td>
					<input type="text" id="BlogTitle" size="50" value="<?= $this->blog->Title ?>">
				</td>
			</tr>
			<tr>
				<td>
					<strong>Viser fra:</strong>
				</td>
				<td>
					<?= 
						str_replace ( 
							'<td><strong>Dato:</strong> </td>', '', 
							dateToPulldowns ( 
								'BlogDatePublish', 
								( $this->blog->DatePublish ? $this->blog->DatePublish : date ( 'Y-m-d H:i:s' ) ) 
							)
						) 
					?>
				</td>
			</tr>
			<tr>
				<td>
					<strong>Ført dato:</strong>
				</td>
				<td>
					<?= 
						str_replace ( 
							'<td><strong>Dato:</strong> </td>', '', 
							dateToPulldowns ( 
								'BlogDateUpdated', 
								( $this->blog->DateUpdated ? $this->blog->DateUpdated : date ( 'Y-m-d H:i:s' ) ) 
							)
						) 
					?>
				</td>
			</tr>
			<tr>
				<td>
					<strong>Tags / Kategori-ord:</strong>
				</td>
				<td>
					<input type="text" id="BlogTags" value="<?= $this->blog->Tags ?>">
				</td>
			</tr>
			<tr>
				<td>
					<strong>Forfattet av:</strong>
				</td>
				<td>
					<input type="text" id="BlogAuthorName" value="<?= $this->blog ? $this->blog->AuthorName : $GLOBALS[ 'user' ]->Name ?>">
				</td>
			</tr>
			<tr>
				<td>
					<strong>Publisert:</strong>
				</td>
				<td>
					<input type="hidden" id="BlogIsPublished" value="<?= $this->blog->ID <= 0 ? '1' : $this->blog->IsPublished ?>">
					<input style="width: 22px; border: 0" type="checkbox"<?= ( $this->blog->ID <= 0 ? true : $this->blog->IsPublished ) ? ' checked="checked"' : '' ?> onchange="document.getElementById ( 'BlogIsPublished' ).value = this.checked ? '1' : '0'">
				</td>
			</tr>
			<?if ( $this->blog->ID ) { ?>
			<tr>
				<td>
					<strong>Ingressbilde:</strong>
				</td>
				<td>
					<form method="post" target="hiddenblogiframe" enctype="multipart/form-data" action="admin.php?module=extensions&extension=editor&mod=mod_blog&modaction=saveimage&bid=<?= $this->blog->ID ?>">
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td>
									<div id="BlogImagePreview">
										<?
											if ( $images = $this->blog->getObjects ( 'ObjectType = Image' ) )
											{
												return '<a href="javascript:mod_blog_removeimage(' . $this->blog->ID . ')">' . $images[0]->getImageHTML ( 32, 32, 'framed' ) . '</a>';
											}
										?>
									</div>
								</td>
								<td><input type="file" name="Image"></td>
								<td>&nbsp;
									<button type="submit" title="Last opp bildet">
										<img src="admin/gfx/icons/page_attach.png"> Last opp
									</button>
								</td>
							</tr>
						</table>
					</form>
				</td>
			</tr>
			<?}?>
			<tr>
				<td>
					<strong>Ingress:</strong>
				</td>
				<td>
					<textarea class="mceSelector" id="BlogLeadin" style="height: 120px"><?= $this->blog->Leadin ?></textarea>
				</td>
			</tr>
			<tr>
				<td>
					<strong>Artikkel:</strong>
				</td>
				<td>
					<textarea class="mceSelector" id="BlogBody" style="height: 350px"><?= $this->blog->Body ?></textarea>
				</td>
			</tr>
		</table>
	</div>
	<div class="SpacerSmallColored"></div>
	<button type="button" onclick="mod_blog_save()">
		<img src="admin/gfx/icons/disk.png"> <span id="mod_blog_saveblog">Lagre artikkel</span>
	</button>
	<?if ( $this->blog->ID ) { ?>
	<button type="button" onclick="mod_blog_preview(<?= $this->blog->ID ?>)">
		<img src="admin/gfx/icons/eye.png"> <span id="mod_blog_preview">Forhåndsvis</span>
	</button>
	<?}?>
	<button type="button" onclick="mod_blog_abortedit()">
		<img src="admin/gfx/icons/cancel.png"> <?= $this->blog ? 'Lukk' : 'Avbryt' ?>
	</button>

