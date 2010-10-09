<div class="SubContainer">
	<div style="float: right">
		<img src="admin/gfx/icons/bin.png" onclick="removeAccessElement ( '<?= $this->data->ID ?>' )" style="cursor: hand; cursor: pointer" /> 
	</div>
	<?
		switch ( $this->data->ObjectType )
		{
			case "Users":
				$this->obj = new dbUser ( );
				$this->obj->load ( $this->data->ObjectID );
				$this->obj->Identifier = $this->obj->Name;
				$this->obj->IdentifierType = "Bruker";
				break;
			case "Groups":
				$this->obj = new dbObject ( "Groups" );
				$this->obj->load ( $this->data->ObjectID );
				$this->obj->Identifier = $this->obj->Name;
				$this->obj->IdentifierType = "Gruppe";
				break;
		}
		$this->permission = Array ( );
		if ( $this->data->Read ) $this->permission[] = 'lese';
		if ( $this->data->Write ) $this->permission[] = 'skrive';
		if ( $this->data->Publish ) $this->permission[] = 'publisere';
		if ( $this->data->Structure ) $this->permission[] = 'strukturere';
		$this->permission = implode ( ',', $this->permission );

	?>
	<small><?= $this->obj->IdentifierType ?>:</small> <strong><?= $this->obj->Identifier ?></strong> (<?= $this->permission ?>)
	<br style="clear: both" />
</div>
<div class="SpacerSmall"><em></em></div>
