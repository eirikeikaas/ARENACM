	<?
		$this->name = "Extra_{$this->data->ID}_Small_DataString";
	?>
	<div class="SpacerSmall"></div>
	<strong><?= $this->data->Name ?>:</strong>
	<div class="SpacerSmall"></div>
	<?
		if ( file_exists ( 'extensions/' . $this->data->DataString . '/templates/websnippetconfig.php' ) )
		{
			$tpl = new cPTemplate ( 'extensions/' . $this->data->DataString . '/templates/websnippetconfig.php' );
			$tpl->data =& $this->data;
			$tpl->content =& $this->content;
			$tpl->page =& $this->page;
			return $tpl->render ( );
		}
		else return 'Ekstensjonen har ingen instillinger.';
	?>
	<div class="SpacerSmall"></div>



