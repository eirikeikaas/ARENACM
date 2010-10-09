

<?= $this->folder->ID > 0 ? renderPlugin ( 'permissions', Array ( "ContentTable"=>"Folder", "ContentID"=>$this->folder->ID, "PermissionType"=>"admin" ) ) : '' ?>
<div class="SpacerSmall"></div>
<button onclick="if ( confirm ( 'Er du sikker?' ) ){ document.location='admin.php?module=library&action=hassamepermissions&cid=<?= $this->folder->ID ?>'; }"><img src="admin/gfx/icons/chart_organisation.png"> Utfør på alle undermapper</button>
<button onclick="removeModalDialogue ( 'permissions' );"><img src="admin/gfx/icons/cancel.png"> Close</button>
<div class="SpacerSmall"></div>

