	<div id="ImageEditContainer">
		<?if( $this->image->ID ){?>
			<h1>
				<div class="HeaderBox MsieFix">
					<button onclick="removeModalDialogue ( 'EditLevel' );" style="float: right">
						<img src="admin/gfx/icons/cancel.png" alt="cancel"/>
					</button>
				</div> 
				Rediger bilde
			</h1>
		<?}?>
		<?if( !$this->image->ID ){?>
			<h1>
				<div class="HeaderBox">
					<button onclick="removeModalDialogue ( 'EditLevel' );">
						<img src="admin/gfx/icons/cancel.png" alt="cancel"/>
					</button>
				</div>
				Legg til bilde
			</h1>
		<?}?>
		<div id="uploadTabs">
			<?if( !$this->image->ID ){?>
			<div class="tab" id="tabRediger">
				<img src="admin/gfx/icons/image.png"/>
				<?= $this->image->ID ? 'Rediger bildet' : 'Nytt bilde' ?>
			</div>
			<div class="tab" id="tabMultiple">
				<img src="admin/gfx/icons/images.png"/> Last opp flere bilder samtidig			
			</div>
			<br style="clear: both">
			<div class="page" id="pageRediger">
			<?}?>
				<iframe style="visibility: hidden; width: 1px; height: 1px; position: absolute" name="ImageUpload"></iframe>
				<form method="post" enctype="multipart/form-data" id="uploadForm" name="uploadForm" action="admin.php?module=library&action=saveimage" target="ImageUpload">
					<table style="display: block; border-spacing: 0; border-collapse: collapse;">
						<tr>
							<td style="width: <?= $this->image->ID ? '50' : '100' ?>%; padding: 0">

								<div class="SubContainer">		
								
									<input type="hidden" name="fileok" id="fileok" value="0" />
									<?if( $this->image->ID ){?>
										<input type="hidden" name="imageID" id="imageID" value="<?= $this->image->ID; ?>" />
										<input type="hidden" name="fileFolder" id="fileFolder" value="<?= $this->image->ImageFolder; ?>" />
									<?}?>
					
									<?if( !$this->image->ID ){?>
									<input type="hidden" name="fileFolder" id="fileFolder" value="<?= $this->folderID; ?>" />
									<?}?>
								
									<label for="fileTitle">Navn</label>
									<input id="fileTitle" type="text" name="fileTitle" class="w300" value="<?= $this->image->Title; ?>"/>
									<label for="fileTags">Tags</label>
									<input id="fileTags" type="text" name="fileTags" class="w300" value="<?= $this->image->Tags; ?>"/>
								
									<div class="SpacerSmall"></div>
									<label for="uploadFile">Bilde</label>
									<input id="uploadFile" type="file" name="uploadFile" onchange="checkImageUpload()" />
						
									<div id="uploadInfoBox"></div>
									<div class="SpacerSmall"></div>
									<label for="fileDesc">Beskrivelse</label>
									<textarea id="fileDesc" name="fileDesc" class="w300" rows="5" style="width:300px; -moz-box-sizing: border-box; box-sizing: border-box;"><?= $this->image->Description; ?></textarea>
								</div>
							
								<?if( $this->image->ID ){?>
									<div class="SpacerSmall"></div>
									<div class="SubContainer">
										<table class="Gui">
											<tr>
												<th>Filnavn: </th>
												<td><input type="text" value="<?= $this->image->Filename?>" size="44" name="ImageFilename"/><br </></td>
											</tr>
											<tr>
												<th>Filstørrelse: </th>
												<td><?= filesizeToHuman( $this->image->Filesize ); ?><br /></td>
											</tr>
											<tr>
												<th>Dimensjoner: </th>
												<td><?= $this->image->Width; ?>x<?= $this->image->Height; ?> px</td>
											</tr>
											<tr>
												<th>Vis bildet: </th>
												<td><a href="<?= $this->image->getMasterImage(); ?>" target="_blank"><?= $this->image->Filename?></a></td>
											</tr>
											<?
												$fn = BASE_DIR . '/' . $this->image->getFolderPath () . '/' . $this->image->BackupFilename;
												$lfn = BASE_URL . $this->image->getFolderPath () . '/' . $this->image->BackupFilename;
												if ( file_exists ( $fn ) && trim ( $this->image->BackupFilename ) )
												{
													$info = stat ( $fn );
													$date = date ( 'd/m/Y H:i', $info[ 'mtime' ] );
													return '
											<tr>
												<th>Backup:</th>
												<td>
													<a href="' . $lfn . '">' . $this->image->BackupFilename . '</a> (' . $date . ')</a>
												</td>
											</tr>';
												}
											?>
										</table>
									</div>
								<?}?>
							
							</td>
							<td style="width: 50%; padding: 0; padding-left: 2px;">
								<?if( $this->image->ID ){?>
								<h2 class="BlockHead" style="margin-top: 0">Forhåndsvisning</h2>
								<div class="BlockContainer" style="height: 207px; text-align: center; vertical-align: center; overflow: hidden">
									<div style="display: block;">
										<?= $this->imageHTML ?>
									</div>
								</div>
								<div class="SpacerSmallColored"></div>
								<table class="Layout">
									<tr>
										<td style="vertical-align: middle">
											<strong>Vises fra:</strong>
										</td>
										<td style="vertical-align: middle">
											<?= dateToPulldowns ( 'DateFrom', $this->image->DateFrom ) ?>
										</td>
									</tr>
									<tr>
										<td style="vertical-align: middle">
											<strong>Vises til:</strong>
										</td>
										<td style="vertical-align: middle">
											<?= dateToPulldowns ( 'DateTo', $this->image->DateTo ) ?>
										</td>
									</tr>
								</table>
								<button type="button" onclick="initializeImageSlice(<?= $this->image->ID ?>)">
									<img src="admin/gfx/icons/application_view_gallery.png"/> Lag bildeutsnitt
								</button>
								<?}?>
							</td>
						</tr>
					</table>
				</form>
				
				<div class="SpacerSmallColored"></div>
			
			
				<?if ( $this->edit ) { ?>
				<button onclick="submitImageUpload()">
					<img src="admin/gfx/icons/disk.png" /> Lagre
				</button>
				<button onclick="submitImageUpload('close')">
					<img src="admin/gfx/icons/accept.png" /> Lagre & lukk
				</button>
				<?}?>
				<?if( $this->image->ID && $this->edit ){?>
				<button onclick="deleteLibraryImage('<?= $this->image->ID; ?>');">
					<img src="admin/gfx/icons/image_delete.png" /> Slett
				</button>
				<?}?>
				
				<button onclick="removeModalDialogue ( 'EditLevel' );"><img src="admin/gfx/icons/cancel.png" /> Lukk</button>
			
			<?if( !$this->image->ID ){?>
			</div>
			<div class="page" id="pageMultiple">
			
				<form method="post" enctype="multipart/form-data" action="admin.php?module=library&action=saveimages" target="LibraryUpload">
				
				<input type="hidden" name="fileFolder" id="fileFolder" value="<?= $this->folderID; ?>" />
				
				<div id="multipleImages">
				</div>
				
				<p>
					<button type="button" onclick="mulIncreaseImages ( )">
						<img src="admin/gfx/icons/add.png"/> Flere bilder
					</button>
					<button type="button" onclick="mulDecreaseImages ( )">
						<img src="admin/gfx/icons/delete.png"/> Færre bilder
					</button>
				</p>
				<p>
					<button type="button" onclick="submitImageUpload()">
						<img src="admin/gfx/icons/disk.png" /> Lagre
					</button>
					<button type="button" onclick="submitImageUpload('close')">
						<img src="admin/gfx/icons/accept.png" /> Lagre & Lukk
					</button>
					<button type="button" onclick="removeModalDialogue ( 'EditLevel' );">
						<img src="admin/gfx/icons/cancel.png" /> Lukk
					</button>
				</p>
				
				</form>
			
			</div>
			<?}?>
	
		</div>
	
		<script>
			<?if( !$this->image->ID ){?>
			initTabSystem ( 'uploadTabs' );
			<?}?>
			function mulImages ( num )
			{
				var ostr = '';
				for ( var a = 0; a < num; a++ )
				{
					ostr += '<tr class="sw'+(a%2+1)+'"><td>Bildetittel ' + ( a + 1 ) + ': ';
					ostr += '<input type="text" size="20" name="filename_' + a + '"/>';
					ostr += '</td><td>';
					ostr += 'Bilde ' + ( a + 1 ) + ': ';
					ostr += '<input type="file" name="image_' + a + '"/>';
					ostr += '</td></tr>';
				}
				if ( document.getElementById ( 'multipleImages' ) )
					document.getElementById ( 'multipleImages' ).innerHTML = '<table id="MultipleFilesTable" class="List">' + ostr + '</table>';
			}
			
			<?if ( !$this->file->ID ) { ?>
			mulImages ( 2 );
			<?}?>
			if ( document.getElementById ( 'tabRediger' ) )
				document.getElementById ( 'tabRediger' ).onclick ( );
		</script>
	
	</div>

	
