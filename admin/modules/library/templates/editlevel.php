

	<div id="editLevelContainer">
		<?if ( $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->folder, 'Write', 'admin' ) ) { ?>
		<iframe name="hiddenlevel" class="Hidden"></iframe>
		<form id="editLevelForm" target="hiddenlevel" method="post" action="admin.php?module=library&function=editlevel&action=savelevel">
		<?}?>
		<h1>
			<div class="HeaderBox">
				<?if ( $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->folder, 'Write', 'admin' ) ) { ?>
				<button type="submit">
					<img src="admin/gfx/icons/disk.png" /> 
				</button>
				<?}?>
				<button type="button" onclick="editor.removeControl ( 'folderDescription' ); removeModalDialogue ( 'EditLevel' ); getLibraryLevelTree ( );">
					<img src="admin/gfx/icons/cancel.png" />
				</button>
			</div>
			Rediger nivå "<?= $this->folder->Name; ?>"
		</h1>		
		<div class="Container">
	
				<input type="hidden" value="<?= $this->folder->ID ?>" name="ID">
			
				<table class="Gui">
					<tr>
						<td>
							<label for="folderName">Navn</label>
							<input id="folderName" class="w300" type="text" name="folderName" value="<?= $this->folder->Name; ?>" maxlength="200"/>
						</td>
						<td>
							<label for="folderPosition">Plassering</label>
							<select id="folderPosition" name="Parent">
								<?
									function fhierarchy ( $current, $parent = '0', $r = '' )
									{
										$db =& dbObject::globalValue ( 'database' );
										if ( $rows = $db->fetchObjectRows ( 'SELECT * FROM Folder WHERE Parent=' . $parent ) )
										{
											foreach ( $rows as $row )
											{
												$c = $row->ID == $current ? ' selected="selected"' : '';
												$name = $inactivate ? ">> {$row->Name})" : $row->Name;
												$str .= '<option value="' . ( $row->ID == $current ? $row->Parent : $row->ID ) . '"' . $c . '>' . $r . stripslashes ( $name ) . '</option>';
												if ( !$c ) $str .= fhierarchy ( $current, $row->ID, $r . '&nbsp;&nbsp;&nbsp;&nbsp;' );
											}
											return $str;
										}
									}
									return fhierarchy ( $this->folder->ID );
								?>
							</select>
						</td>
					</tr>
				</table>
		
				<div class="SpacerSmall"></div>
		
		
				<label for="folderDescription">Beskrivelse</label><br />
				<textarea id="folderDescription" class="mceSelector" name="folderDescription" rows="10" style="height: 300px" cols="30"><?= $this->folder->Description; ?></textarea>
	

				<div class="Spacer"></div>

				<?if ( $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->folder, 'Write', 'admin' ) ) { ?>
				<button type="submit">
					<img src="admin/gfx/icons/disk.png" /> Lagre
				</button>
				<?}?>
				<button type="button" onclick="editor.removeControl ( 'folderDescription' ); removeModalDialogue ( 'EditLevel' ); getLibraryLevelTree ( );">
					<img src="admin/gfx/icons/cancel.png" /> Lukk
				</button>
		
		</div>
		<div style="clear:both"><em></em></div>
		<?if ( $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->folder, 'Write', 'admin' ) ) { ?>
		</form>
		<?}?>

	</div>
	
