<?
	switch ( $_REQUEST[ 'modaction' ] )
	{
		case 'save':
			$field->DataMixed = trim ( $_REQUEST[ 'tips' ] );
			$field->save ( );
			die ( 'ok' );
			break;
	}

	$module .= '
		<div>
			<textarea id="mod_tips_en_venn_tips" class="mceSelector" style="height: 80px">' . $field->DataMixed . '</textarea>
		</div>
		<script type="text/javascript">
			AddSaveFunction ( function ( )
			{
				var sjax = new bajax ( );
				sjax.openUrl ( ACTION_URL + "mod=mod_tipafriend&modaction=save", "post", true );
				sjax.addVar ( "tips", document.getElementById ( "mod_tips_en_venn_tips" ).value );
				sjax.onload = function ( ){  }
				sjax.send ( );
			}
			);
		</script>
	';
?>
