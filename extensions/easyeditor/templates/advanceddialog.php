<h1>
	<div style="float: right">
		<button type="button" onclick="SaveAdvancedOptions ()">
			<img src="admin/gfx/icons/disk.png"/>
		</button>
		<button type="button" onclick="SaveAdvancedOptions ( 1 )">
			<img src="admin/gfx/icons/accept.png"/>
		</button>
		<button type="button" onclick="removeModalDialogue ( 'advanced' )">
			<img src="admin/gfx/icons/cancel.png"/>
		</button>
	</div>
	<?= i18n ( 'Advanced options' ) ?>
</h1>
<div class="SubContainer">
	<table>
		<tr>
			<td>
				<strong>
					<?= i18n ( 'Show extra fields' ) ?>:
				</strong>
			</td>
			<td>
				<select id="adv_extra_count">
					<?
						global $Session;
						$str = '';
						for ( $a = 1; $a < 50; $a++ )
						{
							$s = GetSettingValue ( 'EasyEditor', 'FieldCount_' . $Session->EditorContentID ) == $a ? ' selected="selected"' : '';
							$str .= '<option value="' . $a . '"' . $s . '>' . $a . '</option>';
						}
						return $str;
					?>
				</select>
			</td>
		</tr>
	</table>
</div>
<div class="SpacerSmallColored"></div>
<div>
	<button type="button" onclick="SaveAdvancedOptions ()">
		<img src="admin/gfx/icons/disk.png"/> <?= i18n ( 'Save' ) ?>
	</button>
	<button type="button" onclick="SaveAdvancedOptions ( 1 )">
		<img src="admin/gfx/icons/accept.png"/> <?= i18n ( 'Save and close' ) ?>
	</button>
	<button type="button" onclick="removeModalDialogue ( 'advanced' )">
		<img src="admin/gfx/icons/cancel.png"/> <?= i18n ( 'Close' ) ?>
	</button>
</div>
