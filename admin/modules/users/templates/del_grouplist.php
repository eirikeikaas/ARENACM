					<?
						global $Session;
					
						if ( !$this->groups ) return "";
						foreach ( $this->groups as $g )
						{
							if ( !$Session->AdminUser->checkPermission ( $g, 'Read', 'admin' ) ) 
								continue;
							
							if ( $g->ID == $Session->UsersCurrentGroup )
								$act = "Active";
							else $act = "";
						
							$oStr .= "
								<span class=\"Group$act\" onclick=\"goGroupUrl ( 'admin.php?module=users&gid={$g->ID}' )\">
									<span onmouseover=\"overEditButtons = 1\" onmouseout=\"overEditButtons = 0\">
							";
						
							$oStr .= "	<img src=\"admin/gfx/icons/group_edit.png\" onclick=\"editGroup('{$g->ID}')\" />";
							if ( $Session->AdminUser->checkPermission ( $g, 'Write', 'admin' ) )
								$oStr .= "	<img src=\"admin/gfx/icons/group_delete.png\" onclick=\"deleteGroup('{$g->ID}')\" />";
						
							$oStr .= "
										<img src=\"admin/gfx/icons/plugin.png\" onclick=\"addToWorkbench ( '{$g->ID}', 'Groups' )\" />
									</span>
									<img src=\"admin/gfx/icons/group.png\" /> <strong>{$g->Name}</strong>
								</span>";
						}
						return $oStr;
					?>
					<span class="Group<?= $GLOBALS[ 'Session' ]->UsersCurrentGroup == 'all' ? 'Active' : '' ?>" onclick="document.location='admin.php?module=users&gid=all'">
						<img src="admin/gfx/icons/eye.png" /> <strong>Alle brukere</strong>
					</span>
					<?if ( $Session->AdminUser->_dataSource == 'core' ) { ?>
					<span class="Group<?= $GLOBALS[ 'Session' ]->UsersCurrentGroup == 'orphans' ? 'Active' : '' ?>" onclick="document.location='admin.php?module=users&gid=orphans'">
						<img src="admin/gfx/icons/user_gray.png" /> <strong>Brukere uten gruppe</strong>
					</span>
					<?}?>
					<span style="margin: 0;" class="Group<?= $GLOBALS[ 'Session' ]->UsersCurrentGroup == 'inactive' ? 'Active' : '' ?>" onclick="document.location='admin.php?module=users&gid=inactive'">
						<img src="admin/gfx/icons/user_green.png" /> <strong>Deaktiverte brukere</strong>
					</span>
