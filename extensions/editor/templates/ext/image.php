
		<h4>
			<div class="Buttons">
				<button title="Endre feltet" type="button" onclick="editEditorField ( <?= $this->field->ID ?>, '<?= $this->fieldType ?>' )"><img src="admin/gfx/smallbutton_edit.png"></button>
				<button title="Fjern feltet" type="button" onclick="removeField ( <?= $this->field->ID ?>, '<?= $this->fieldType ?>' )"><img src="admin/gfx/smallbutton_remove.png"></button>
				<button title="Flytt opp" type="button" onclick="reorderField ( <?= $this->field->ID ?>, '<?= $this->fieldType ?>', -1 )"><img src="admin/gfx/smallbutton_up.png"></button>
				<button title="Flytt ned" type="button" onclick="reorderField ( <?= $this->field->ID ?>, '<?= $this->fieldType ?>', 1 )"><img src="admin/gfx/smallbutton_down.png"></button>
			</div>
			<a onclick="javascript: scrollTo ( 0, getElementTop ( this ) );"><?= str_replace ( '_', ' ', $this->field->Name ) ?> (i <?= $this->fieldGroup ?>):</a>
		</h4>
		<div class="SpacerSmall"></div>
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td valign="middle">
					<iframe name="<?= $this->fieldID . 'iframe' ?>" class="Hidden"></iframe>
					<form name="<?= $this->fieldID . 'form' ?>" method="post" action="admin.php?module=extensions&extension=editor&action=imagefield&field=<?= $this->field->ID ?>" enctype="multipart/form-data" target="<?= $this->fieldID . 'iframe' ?>">
						<input type="file" class="ExtraFieldData" name="filestream" id="imagefield_<?= $this->fieldID ?>">
					</form>
				</td>
				<td>&nbsp;</td>
				<td width="32" valign="middle">
					<div class="Box" style="width: 32px; height: 32px; border: 1px solid #ccc; background: #eee;" id="imagepreview_<?= $this->field->ID ?>">
						<?
							if ( $this->field->DataInt )
							{
								$i = new dbImage ( $this->field->DataInt );
								return $i->getImageHTML ( 32, 32, 'framed' );
							}
						?>
					</div>
				</td>
				<?if ( $this->field->DataInt ) { ?>
				<td>&nbsp;</td>
				<td valign="middle">
					<button class="Small" type="button" onclick="removeEFImage ( <?= $this->field->DataInt ?> )"><img src="admin/gfx/icons/image_delete.png"> Fjern bilde</button>
				</td>
				<?}?>
			</tr>
		</table>
		<div class="SpacerSmall"></div>
		
		<script>
			AddSaveFunction ( function ( )
			{
				if ( document.getElementById ( 'imagefield_<?= $this->fieldID ?>' ).value )
				{
					document.getElementById ( 'imagefield_<?= $this->fieldID ?>' ).parentNode.submit ( );
				}
			}
			);
		</script>
