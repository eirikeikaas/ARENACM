
	<h1>
		<?= i18n ( 'Add users to collections' ) ?>
	</h1>
	<form method="post" action="admin.php?module=users&action=addtocollection&apply=true">
		<div class="Container" style="padding: 2px">
			<input type="hidden" class="hidden" id="hiddenusers" value="" name="ids">
			<select name="collections[]" style="-moz-box-sizing: border-box; width: 100%;" size="15">
				<?= $this->cols ?>
			</select>
		</div>
	
		<div class="SpacerSmallColored"></div>
		<button type="submit">
			<img src="admin/gfx/icons/building_go.png"> <?= i18n ( 'Save' ) ?>
		</button>
		<button type="button" onclick="removeModalDialogue ( 'addtocollection' )">
			<img src="admin/gfx/icons/cancel.png"> <?= i18n ( 'Close' ) ?>
		</button>
	</form>
	
	<script type="text/javascript">
		var users = getUniqueListEntries ( 'seluserslist' );
		document.getElementById ( 'hiddenusers' ).value = users;
	</script>

