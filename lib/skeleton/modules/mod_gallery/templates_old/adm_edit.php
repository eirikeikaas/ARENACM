<?
    $db =& dbObject::globalValue('database');
    $q = 'SELECT * FROM Folder';
 
    $str = '<h3>Endre bildevalg</h3><div class="SpacerSmallColored"></div>';
    $str .= '<div id="folderlist"><h4>Velg biblioteksfolder:</h4>';
    if ($folders = $db->fetchObjectRows($q))
        foreach ($folders as $folder)
        {
            $str .= '<div id="folder' . $folder->ID . '"><a href="javascript:open_folder(' . $folder->ID . ')">' . $folder->Name . '</a></div>';
        }
    $str .= '</div><div class="SpacerSmallColored"></div>';
    $str .= '<div id="imagelist"></div>';
    
    return $str;
?>


<?
    if ($this->datamixed)
    {
        list($this->gallerytype, $this->mode, $this->popup, $this->showtitles) = explode('#', $this->datamixed);
        if ($this->gallerytype == 'thumbs') list($this->cols, $this->rows) = explode('_', $this->mode);
    }
?>

<div id="GalleryType"><div class="SelectCaption">Galleritype: </div><div class="SelectSelect"><select id="GalleryTypeSelect" onchange="show_type_settings(this.value)"><option <?= $this->gallerytype == 'slides' ? 'selected="selected"' : '' ?> value="slides">Slides</option><option <?= $this->gallerytype == 'thumbs' ? 'selected="selected"' : '' ?> value="thumbs">Thumbnails</option></select></div></div>

<table id="GallerySlidesSettings" <?= $this->gallerytype == 'slides' ? 'style="display: block"' : 'style="display: none"' ?>>
    <tr class="GallerySettingsItem"><td class="GallerySettingsLabel"><span>Modus:</span></td><td><select  id="SlideMode" <option <?= $this->mode == 'manuell' ? 'selected="selected"' : '' ?> value="manuell">Manuell</option><option <?= $this->mode == 'auto' ? 'selected="selected"' : '' ?> value="auto">Auto</option></select></td></tr>
</table>

<div id="GalleryThumbsSettings" <?= $this->gallerytype == 'thumbs' ? 'style="display: block"' : 'style="display: none"' ?>>
    <div class="GallerySettingsItem">
		<div class="GallerySettingsLabel">
			<span>Antall kolonner:</span>
		</div>
		<div class="GallerySettingsBox">
			<input type="text" size="2" maxlength="2" value="<?= $this->cols ?>" id="ThumbsColumns">
		</div>
	</div>
    <div class="GallerySettingsItem">
		<div class="GallerySettingsLabel">
			<span>Antall rekker:</span>
		</div>
		<div class="GallerySettingsBox">
			<input type="text" size="2" maxlength="2" value="<?= $this->rows ?>" id="ThumbsRows">
		</div>
	</div>
</div>

<div class="SelectOptions" id="Popup"><div class="SelectCaption">Pop-up: </div><div class="SelectSelect"><select id="PopupSelect" <option <?= $this->popup == 'ja' ? 'selected="selected"' : '' ?> value="ja">Ja</option><option <?= $this->popup == 'nei' ? 'selected="selected"' : '' ?> value="nei">Nei</option></select></div><br class="Clear"/></div>

<div class="SelectOptions" id="ShowTitles"><div class="SelectCaption">Vis bildetitler: </div><div class="SelectSelect"><select id="TitleSelect" <option <?= $this->showtitles == 'ja' ? 'selected="selected"' : '' ?> value="ja">Ja</option><option <?= $this->showtitles == 'nei' ? 'selected="selected"' : '' ?> value="nei">Nei</option></select></div><br class="Clear"/></div>

<div class="SpacerSmallColored"></div>
<button type="button" onclick="mod_gallery_save()">
    <img src="admin/gfx/icons/page_go.png">Lagre galleri
</button>

<button onclick="mod_gallery_abortedit()" type="button">
	<img src="admin/gfx/icons/cancel.png"/>Avbryt
</button>
