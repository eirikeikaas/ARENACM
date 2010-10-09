	
	<?
		$this->name = "Extra_{$this->data->ID}_{$this->data->DataTable}_DataInt";
	?>
	
	<h2 class="BlockHead">
		<img src="admin/gfx/icons/file.png" /> <?= $this->data->Name ?>:
	</h2>
	<div class="BlockContainer">
		<table class="Gui" style="width: 100%">
			<tr>
				<td>
					<div class="SubContainer">
						<h2>
							Last opp en ny fil her
						</h2>
						<input type="file" id="<?= $this->name ?>" name="<?= $this->name ?>" />
					</div>
				</td>
				<td>
					<div class="Dropzone" id="Drop<?= $this->name ?>">
						Slipp en fil her
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<div class="SubContainer" id="prev<?= $this->name ?>">
						<?
							if ( $this->data->DataInt )
							{
								$file = new dbObject ( 'File' );
								$file->load ( $this->data->DataInt );
								return $file->Title . ' (' . $file->Filename . ')';
							}
							return 'Ingen fil bruker dette feltet';
						?>
					</div>
				</td>
			</tr>
		</table>
	</div>
	
	<script>
		document.getElementById ( 'Drop<?= $this->name ?>' ).onDragDrop = function ( )
		{
			var jax = new bajax ( );
			jax.openUrl ( 'admin.php?plugin=extrafields&pluginaction=setfieldoption&type=Small&field=DataInt&id=<?= $this->data->ID ?>&value=' + dragger.config.objectID, 'get', true );
			jax.onload = function ( )
			{
				document.getElementById ( 'prev<?= $this->name ?>' ).innerHTML = 'La til fil med id: ' + dragger.config.objectID;
			}
			jax.send ( );
		}
		dragger.addTarget ( document.getElementById ( 'Drop<?= $this->name ?>' ) );
	</script>
	
