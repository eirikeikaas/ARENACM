
	<form method="post" action="admin.php?module=users&action=savecollection">
		<h1>
			<?= $this->collection->ID ? ( i18n ( 'Endre' ) . ': ' . $this->collection->Name ) : i18n ( 'New collection' ) ?>
		</h1>
		<input type="hidden" value="<?= ( $_REQUEST[ 'parentid' ] ? $_REQUEST[ 'parentid' ] : $this->collection->UserCollectionID ) ?>" class="hidden" name="UserCollectionID">
		<input type="hidden" value="<?= $this->collection->ID ?>" class="hidden" name="ID">
		<div class="Container">
			<table>
				<tr>
					<td>
						<strong><?= i18n ( 'Name' ) ?>:</strong>
					</td>
					<td>
						<input type="text" name="Name" value="<?= $this->collection->Name ?>">
					</td>
				</tr>
				<tr>
					<td>
						<strong><?= i18n ( 'Description' ) ?>:</strong>
					</td>
					<td>
						<textarea name="Description" cols="40" rows="5"><?= $this->collection->Description ?></textarea>
					</td>
				</tr>
			</table>
		</div>
		<div class="SpacerSmallColored"></div>
		<button>
			<img src="admin/gfx/icons/disk.png"> <?= i18n ( 'Save' ) ?>
		</button>
		<button onclick="removeModalDialogue ( 'collection' )">
			<img src="admin/gfx/icons/cancel.png"> <?= i18n ( 'Close' ) ?>
		</button>
	</form>
