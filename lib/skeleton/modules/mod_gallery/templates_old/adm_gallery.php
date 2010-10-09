<?
    $db =& dbObject::globalValue('database');

    $str = '<h1>Opprett nytt galleri</h1>';
    $str .= '<br/><h2>Velg hvilke bilder som skal legges til:</h2><div id="mod_gallery_list"><br/><br/>';

    return $str;
?>

<div class="SpacerSmallColored"></div>
<button type="button" onclick="mod_gallery_save()">
    <img src="admin/gfx/icons/page_go.png"> <span id="mod_gallery_save">Lagre galleri</span>
</button>

<button onclick="updateStructure ( ); removeModalDialogue ( 'gallery_new' )" type="button">
	<img src="admin/gfx/icons/cancel.png"/> Lukk
</button>