<?
    global $Session;
    $Session->imgTemp ? $this->imgTemp = $Session->imgTemp : $this->imgTemp = array();
?>

<div class="gallery_image" id="imagecontainer<?= $this->image->ID ?>">
    <img src="<?= $this->image->getImageUrl(120, 100, 'centered', '', 0xffffff) ?>"/>
    <div><input type="checkbox" onclick="mod_update_imagelist(<?=$this->image->ID?>)" id="checkbox_<?= $this->image->ID ?>" <?= in_array($this->image->ID, $this->imgTemp) ? 'checked="checked"' : '' ?>><span class="image_title"><?= substr($this->image->Title, 0, 12) ?>...</span></div>
</div>

<script>
    addToolTip( '<?= $this->image->Title ?>','<?= trim( $this->image->Description ) != '' ? ( '<b>Beskrivelse:</b><br />' . str_replace ( array ( "\\\n", "\\\r" ), '', nl2br( $this->image->Description ) ) . '<hr />' ) : ''?><b>Filnavn:</b> <?= nl2br ( trim ( $this->image->Filename ) ) ?><br /><b>Størrelse:</b> <?= filesizeToHuman( $this->image->Filesize ); ?>', 'imagecontainer<?= $this->image->ID?>' );
</script>	
