<div id="ButtonRow">
    <button type="button" onclick="mod_gallery_choose(1)">
        <img src="admin/gfx/icons/add.png">Velg alle
    </button>
    <button type="button" onclick="mod_gallery_choose(0)">
        <img src="admin/gfx/icons/cancel.png">Velg ingen
    </button>
</div>

<?
	$mtpldir = 'skeleton/modules/mod_gallery/templates/';
    
    if ($_POST['id'])
    {
        $db =& dbObject::globalValue('database');
        $q = 'SELECT i.Filename, i.ID, f.Name FROM Image i, Folder f WHERE ImageFolder =' . $_POST['id'] . ' AND i.ImageFolder = f.ID';
        if ($results = $db->fetchObjectRows($q))
            foreach ($results as $result)
            {
                $tpl = new cpTemplate($mtpldir . 'image_details.php');
                $tpl->image = new dbImage($result->ID);
                $str .= $tpl->render();
            }
    }
    return $str;    
?>
<br class="Clear"/>