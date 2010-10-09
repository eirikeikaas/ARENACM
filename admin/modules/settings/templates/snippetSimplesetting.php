<?
	$GLOBALS[ 'simpleSetting' ]++;
	if ( $GLOBALS[ 'simpleSetting' ] > 1 )
		return '<div class="SpacerSmall"></div>';
?>

<p>
	<strong><label for="setf<?= $this->Keystring?>"><?= $this->Title ?></label></strong>
</p>
<p>
<?if ( !$this->_FieldType || $this->_FieldType == 'varchar' ) { ?>
	<input 
		id="setf<?= $this->Keystring?>" 
		type="text" 
		name="<?= $this->Keystring ?>" 
		value="" 
		size="40" 
		style="width: 100%; box-sizing: border-box; -moz-box-sizing: border-box" 
		class="modulesetting"
	/>
	<div class="Hidden" id="setf<?= $this->Keystring ?>_div"><?= $this->Value ?></div>
	<script type="text/javascript">
		document.getElementById ( 'setf<?= $this->Keystring ?>' ).value = document.getElementById ( 'setf<?= $this->Keystring ?>_div' ).innerHTML;
	</script>
<?}?>
<?if ( $this->_FieldType == 'text' ) { ?>
	<textarea
		id="setf<?= $this->Keystring?>" 
		name="<?= $this->Keystring ?>" 
		style="width: 100%; box-sizing: border-box; -moz-box-sizing: border-box" 
		class="modulesetting"
		rows="10"
	><?= $this->Value ?></textarea>
<?}?>
</p>
<div class="SpacerSmall"></div>

