<?
    $db =& dbObject::globalValue('database');
    $q = 'SELECT * FROM Folder';
 
    $str = '<h3>Legg til bilder</h3><div class="SpacerSmallColored"></div>';
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

<div class="SpacerSmallColored"></div>
<button type="button" onclick="mod_gallery_save()">
    <img src="admin/gfx/icons/page_go.png">Lagre galleri
</button>

<button onclick="mod_gallery_abortedit()" type="button">
	<img src="admin/gfx/icons/cancel.png"/>Lukk
</button>