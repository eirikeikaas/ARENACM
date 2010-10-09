	<div id="FileEditContainer">
	<?if( $this->file->ID ){?>
		<h1><div style="float: right"><button onclick="removeModalDialogue ( 'EditLevel' );"><img src="admin/gfx/icons/cancel.png" /></button></div>Rediger fil</h1>
	<?}?>
	<?if( !$this->file->ID ){?>
		<h1><div style="float: right"><button onclick="removeModalDialogue ( 'EditLevel' );"><img src="admin/gfx/icons/cancel.png" /></button></div>Legg til fil</h1>
	<?}?>


	<div id="uploadFileTabs">
		<iframe style="visibility: hidden; width: 1px; height: 1px; position: absolute" name="FileUpload"></iframe>

		<div class="tab" id="tabRediger">
			<img src="admin/gfx/icons/page_white.png"/>
			<?if( $this->file->ID ){?>
			Rediger filen
			<?}?>
			<?if( !$this->file->ID ){?>
			Ny fil
			<?}?>
		</div>
		<?if ( $this->file->ID ) { ?>
		<div class="tab" id="tabProperties">
			<img src="admin/gfx/icons/page_gear.png"/> Avansert
		</div>
		<?}?>
		<?if ( !$this->file->ID ) { ?>
		<div class="tab" id="tabMultiple">
			<img src="admin/gfx/icons/page_go.png"/> Batch - last opp flere
		</div>
		<?}?>
		<div class="page" id="pageRediger">
			<table cellspacing="0" cellpadding="0" border="0" width="100%">
				<tr>
					<td>
						<form method="post" enctype="multipart/form-data" id="uploadForm" name="uploadForm" action="admin.php?module=library&action=savefile" target="FileUpload">
							<div class="SubContainer">
								<input type="hidden" name="fileok" id="fileok" value="0" />
								<?if( $this->file->ID ){?>
									<input type="hidden" name="fileID" id="fileID" value="<?= $this->file->ID; ?>" />
									<input type="hidden" name="fileFolder" id="fileFolder" value="<?= $this->file->FileFolder; ?>" />
								<?}?>
	
								<?if( !$this->file->ID ){?>
									<input type="hidden" name="fileFolder" id="fileFolder" value="<?= $this->folderID; ?>" />
								<?}?>			
							
								<label for="fileTitle">Navn</label>
								<input id="fileTitle" type="text" name="fileTitle" class="w300" value="<?= $this->file->Title; ?>"/>
							
								<div class="SpacerSmall"></div>
								<label for="uploadFile">Fil</label>
								<input id="uploadFile" type="file" name="uploadFile" onchange="checkFileUpload()" />
					
								<div id="uploadInfoBox"></div>
								<div class="SpacerSmall"></div>
								<label for="fileDesc">Beskrivelse</label>
								<textarea id="fileDesc" name="fileDesc" class="w300" rows="5" style="width: 300px; -moz-box-sizing: border-box; box-sizing: border-box;"><?= $this->file->Description; ?></textarea>
							</div>
						
							<?if( $this->file->ID ){?>
								<div class="SpacerSmall"></div>
								<div class="SubContainer">
									<table>
										<tr>
											<td>Filnavn: </td>
											<td><input type="text" value="<?= $this->file->Filename?>" size="44" name="FileFilename"/><br </></td>
										</tr>
										<tr>
											<td>Filstørrelse: </td>
											<td><?= filesizeToHuman( $this->file->Filesize ); ?></td>
										</tr>
										<tr>
											<td>Last ned fil: </td>
											<td><a href="<?= BASE_URL . $this->file->Folder->DiskPath .'/' .$this->file->Filename?>" target="_blank"><?= $this->file->Filename?></a></td>
										</tr>
									</table>
								</div>
							<?}?>
						</form>
					</td>
				</tr>
			</table>

			<div class="SpacerSmall"></div>
		
			<?if ( $this->edit ) { ?>
			<button onclick="submitFileUpload()"><img src="admin/gfx/icons/page_go.png" /> Lagre</button>
			<?}?>
			<?if( $this->file->ID && $this->edit ){?>
			<button onclick="deleteLibraryFile('<?= $this->file->ID; ?>');"><img src="admin/gfx/icons/page_delete.png" /> Slett</button>
			<?}?>
			<button onclick="removeModalDialogue ( 'EditLevel' );"><img src="admin/gfx/icons/cancel.png" /> Lukk</button>
		
		</div>
		<?if ( $this->file->ID ) { ?>
		<div class="page" id="pageProperties">
			<?
				list ( , $ext ) = explode ( '.', $this->file->Filename );
				switch ( strtolower ( $ext ) )
				{
					case 'swf':
						$properties = new cPTemplate ( 'admin/modules/library/templates/file_properties_swf.php' );
						$properties->file =& $this->file;
						return $properties->render ( );
					case 'css':
					case 'txt':
					case 'locale':
					case 'js':
						if ( file_exists ( 'upload/' . $this->file->Filename ) )
						{
							$properties = new cPTemplate ( 'admin/modules/library/templates/file_properties_text.php' );
							$properties->file =& $this->file;
							$properties->contents = file_get_contents ( 'upload/' . $this->file->Filename );
							return $properties->render ( );
						}
						return 'Filen finnes ikke på disk.';
					default:
						$properties = new cPTemplate ( 'admin/modules/library/templates/file_properties_generic.php' );
						$properties->file =& $this->file;
						return $properties->render ( );
				}
			?>
		</div>
		<?}?>
		<?if ( !$this->file->ID ) { ?>
		<div class="page" id="pageMultiple">
			<form method="post" enctype="multipart/form-data" action="admin.php?module=library&action=savefiles" target="FileUpload">
			
				<input type="hidden" name="fileFolder" id="fileFolder" value="<?= $this->folderID; ?>" />
				
				<div id="multipleFiles">
				</div>
				
				<p>
					<button type="button" onclick="mulIncreaseFiles ( )">
						<img src="admin/gfx/icons/add.png"/> Flere filer
					</button>
					<button type="button" onclick="mulDecreaseFiles ( )">
						<img src="admin/gfx/icons/delete.png"/> Færre filer
					</button>
				</p>
				<p>
					<button type="submit">
						<img src="admin/gfx/icons/monitor_go.png" /> Lagre
					</button>
					<button onclick="removeModalDialogue ( 'EditLevel' );">
						<img src="admin/gfx/icons/cancel.png" /> Lukk
					</button>
				</p>
			
			</form>
		</div>
		<?}?>
	</div>
		
		
	<script>
		initTabSystem ( 'uploadFileTabs' );
		function mulFiles ( num )
		{
			var ostr = '';
			for ( var a = 0; a < num; a++ )
			{
				ostr += '<tr><td><p>Fil tittel ' + ( a + 1 ) + ':</p>';
				ostr += '<p>';
				ostr += '<input type="text" size="20" name="filename_' + a + '"/>';
				ostr += '</p></td><td>';
				ostr += '<p>Fil ' + ( a + 1 ) + ':</p>';
				ostr += '<p>';
				ostr += '<input type="file" name="file_' + a + '"/>';
				ostr += '</p></td></tr>';
			}
			document.getElementById ( 'multipleFiles' ).innerHTML = '<table id="MultipleFilesTable">' + ostr + '</table>';
		}
		<?if ( !$this->file->ID ) { ?>
		mulFiles ( 2 );
		<?}?>
		if ( document.getElementById ( 'tabRediger' ) )
			document.getElementById ( 'tabRediger' ).onclick ( );
	</script>

	
