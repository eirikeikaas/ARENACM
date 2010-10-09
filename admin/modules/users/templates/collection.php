
	<form method="post" action="admin.php?module=users&action=savecollection">
		<h1>
			<?= $this->collection->ID ? ( 'Endre: ' . $this->collection->Name ) : 'Ny enhet' ?>
		</h1>
		<input type="hidden" value="<?= ( $_REQUEST[ 'parentid' ] ? $_REQUEST[ 'parentid' ] : $this->collection->UserCollectionID ) ?>" class="hidden" name="UserCollectionID">
		<input type="hidden" value="<?= $this->collection->ID ?>" class="hidden" name="ID">
		<div class="Container">
			<table>
				<tr>
					<td>
						<strong>Navn:</strong>
					</td>
					<td>
						<input type="text" name="Name" value="<?= $this->collection->Name ?>">
					</td>
				</tr>
				<tr>
					<td>
						<strong>Beskrivelse:</strong>
					</td>
					<td>
						<textarea name="Description" cols="40" rows="5"><?= $this->collection->Description ?></textarea>
					</td>
				</tr>
				<!--<tr>
					<td>
						<strong>Bilde:</strong>
					</td>
					<td>
						NDA
					</td>
				</tr>-->
			</table>
		</div>
		<div class="SpacerSmallColored"></div>
		<button>
			<img src="admin/gfx/icons/disk.png"> Lagre
		</button>
		<button onclick="removeModalDialogue ( 'collection' )">
			<img src="admin/gfx/icons/cancel.png"> Lukk
		</button>
	</form>
