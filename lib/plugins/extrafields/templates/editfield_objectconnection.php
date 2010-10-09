
	<div class="SpacerSmall"></div>
	<strong><?= $this->data->Name ?>:</strong>
	<div class="SpacerSmall"></div>
	<?
		$this->name = "Extra_{$this->data->ID}_{$this->data->DataTable}_DataString";
	?>
	<div class="Dropzone" id="Drop<?= $this->name ?>">
		Slipp objekter her
	</div>
	<div class="SpacerSmall"></div>		
	<div class="SubContainer" id="Preview<?= $this->name ?>">
		<?= showConnectedObjects ( $this->data->ContentID, $this->data->ContentTable ); ?>
	</div>
	<script>
		
		function Init<?= $this->name ?>( )
		{
			var ele = document.getElementById ( 'Drop<?= $this->name ?>' );
			dragger.addTarget ( ele );
			ele.onDragDrop = function ( )
			{
				var bjax = new bajax ( );
				bjax.openUrl ( 'admin.php', 'post', true );
				bjax.addVar ( 'plugin', 'extrafields' );
				bjax.addVar ( 'pluginaction', 'connectobject' );
				bjax.addVar ( 'coid', dragger.config.objectID );
				bjax.addVar ( 'cotype', dragger.config.objectType );
				bjax.addVar ( 'oid', '<?= $this->data->ContentID ?>' );
				bjax.addVar ( 'otype', '<?= $this->data->ContentTable ?>' );
				bjax.onload = function ( )
				{
					document.getElementById ( 'Preview<?= $this->name ?>' ).innerHTML = this.getResponseText ( );
				}
				bjax.send ( );
			}
		}
		Init<?= $this->name ?>( );
		
	</script>
	<div class="SpacerSmall"></div>
	
