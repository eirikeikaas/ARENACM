
	<h3>
		<?= i18n ( 'Search and replace' ) ?>
	</h3>
	<div class="Container">
		<?if ( !$this->sreplace ) { ?>
		<p>
			<?= i18n ( 'Search_replace_desc' ) ?>
		</p>
		<form method="post" action="admin.php?module=core&action=searchreplace" name="srform">
			<table>
				<tr>
					<td>
						<strong><?= i18n ( 'Search for' ) ?>:</strong>
					</td>
					<td>
						<input type="text" name="searchfor" size="25" value=""/>
					</td>
				</tr>
				<tr>
					<td>
						<strong><?= i18n ( 'Replace with' ) ?>:</strong>
					</td>
					<td>
						<input type="text" name="replacewith" size="25" value=""/>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<button type="submit">
							<img src="admin/gfx/icons/magnifier.png"/> <?= i18n ( 'Start' ) ?>
						</button>
					</td>
				</tr>
			</table>
		</form>
		<?}?>
		<?= $this->sreplace ?>
	</div>

