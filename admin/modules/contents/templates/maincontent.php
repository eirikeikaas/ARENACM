				
				<h1>
					<div id="SmallTopButtons" style="float: right">
					</div>
					 Redigerer: "<?= 
						$this->content->Title ? 
						( strlen ( $this->content->Title ) < 20 ? $this->content->Title : trim ( substr ( $this->content->Title, 0, 17 ) ) . '...' ) : 
						( strlen ( $this->content->MenuTitle ) < 20 ? $this->content->MenuTitle : trim ( substr ( $this->content->MenuTitle, 0, 17 ) ) . '...' )
					?>"
					<?= $this->TemplateTitle ? ( '<small>med mal ' . $this->TemplateTitle . '</small>' ) : '' ?>
				</h1>
				
				<div id="MainContentPlaceholder">
					<form method="post" enctype="multipart/form-data" id="ContentForm" target="ContentsSubmit" action="admin.php?module=contents&amp;action=content">
						
						<input type="hidden" name="ID" value="<?= $this->content->ID ?>" id="ContentID_Value" />
						<input type="hidden" value="ContentElement" id="ContentTable_Value" />
						<input type="hidden" name="DateModified" value="<?= date ( "Y-m-d H:i:s" ) ?>" />
						
						<?if ( isIE ( ) ) { ?><div style="width: 100%"><?}?>
						
						<div id="MainTabs">
							<div style="float: right; margin: 3px 0 0 0" id="quickHierarchy">
							</div>
							<?if ( ( $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->content, 'Write', 'admin' ) && $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->content, 'Publish', 'admin' ) ) || $GLOBALS[ "user" ]->_dataSource == 'core' ) { ?>
							<div class="tab" id="tabEditBox">
								<img src="admin/gfx/icons/page.png" /> Redigering
							</div>
							<?}?>
							<?if ( $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->content, 'Write', 'admin' ) && $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->content, 'Publish', 'admin' ) ) { ?>
							<div class="tab" id="tabPublishing">
								<img src="admin/gfx/icons/eye.png" /> Publisering og tilgang
							</div>
							<?}?>
							<?if ( $GLOBALS[ "user" ]->checkPermission ( $this->content, 'Structure', 'admin' ) ) { ?>
							<div class="tab" id="tabTemplate">
								<img src="admin/gfx/icons/database_edit.png"> Avanserte innstillinger
							</div>
							<?}?>
							<?if ( ( $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->content, 'Write', 'admin' ) && $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->content, 'Publish', 'admin' ) ) || $GLOBALS[ "user" ]->_dataSource == 'core' ) { ?>
							<br style="clear: both" />
							<div class="page" id="pageEditBox" style="margin-bottom: 0">
							<?}?>
								<div class="SubContainer">
									<table class="Layout">
										<tr>
											<th style="padding-right: <?= MarginSize ?>px; width: 12%">
												<strong>Overskrift:</strong>
											</th>
											<th style="padding-right: <?= MarginSize ?>px; width: 33%">
												<input type="text" name="Title" size="45" value="<?= str_replace ( html_entity_decode ( '&#39;' ), "&quot;", $this->content->Title ) ?>" style="width: 100%; box-sizing: border-box; -moz-box-sizing: border-box">
											</th>
											<th style="padding-right: <?= MarginSize ?>px; width: 12%">
												<strong>Menytittel:</strong>
											</th>
											<th style="width: 33%" class="mceSingleLine">
												<input type="text" id="MenuTitleId" name="MenuTitle" size="45" value="" style="width: 100%; box-sizing: border-box; -moz-box-sizing: border-box">
												<textarea id="MenuTitleGhost" style="visibility: hidden; position: absolute; top: -1000px; left: -1000px"><?= str_replace ( getLn ( ), '', str_replace ( html_entity_decode ( '&#39;' ), "&quot;", $this->content->MenuTitle ) ) ?></textarea>
												<script type="text/javascript">
													document.getElementById ( 'MenuTitleId' ).value = document.getElementById ( 'MenuTitleGhost' ).value;
												</script>
											</th>
										</tr>
										<tr>
											<th><em style="display: block; height: 4px"></em></th>
										</tr>
										<tr>
											<th style="padding-right: <?= MarginSize ?>px; width: 12%">
												<strong>Relativ url:</strong>
											</th>
											<th style="padding-right: <?= MarginSize ?>px; width: 33%">
												<input class="Disabled" type="text" size="40" style="width: 100%; box-sizing: border-box; -moz-box-sizing: border-box" value="<?= $this->content->getUrl ( ) ?>">
											</th>
											<th style="padding-right: <?= MarginSize ?>px; width: 12%">
												<strong>Urlnavn:</strong>
											</th>
											<th style="width: 33%">
												<input class="Disabled" type="text" size="40" style="width: 100%; box-sizing: border-box; -moz-box-sizing: border-box" value="<?= $this->content->RouteName ?>">
											</th>
										</tr>
									</table>
								</div>
								<div class="SpacerSmallColored"></div>
								<div class="SubContainer">
								<?= $this->contentgui ?>
								</div>
							<?if ( ( $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->content, 'Write', 'admin' ) && $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->content, 'Publish', 'admin' ) ) || $GLOBALS[ "user" ]->_dataSource == 'core' ) { ?>
							
							</div>
							<?}?>
							<?if ( $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->content, 'Write', 'admin' ) && $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->content, 'Publish', 'admin' ) ) { ?>
							<div class="page" id="pagePublishing">
							
								
								<table width="100%" cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td width="50%">		
											<h2 class="BlockHead">Publisering</h2>
											<div class="BlockContainer" style="height: 80px">
												<div id="Publishbutton">
												</div>
											</div>
										</td>
										<td width="50%" style="padding-left: <?= MarginSize ?>px" id="visibilitystate">
											
										</td>
									</tr>
								</table>
								
								<h2 class="BlockHead">Avansert</h2>
								<div class="BlockContainer">
									<table class="Layout" style="border-spacing: 2px; border-collapse: separate; border: 0">
										<tr>
											<th style="padding-right: <?= MarginSize ?>px">
												<strong>Systemnavn:</strong>
											</th>
											<td>
												<input type="text" name="SystemName" value="<?= $this->content->SystemName ?>" size="25" />
											</td>
											<th style="width: 25%; padding-left: <?= MarginSize ?>px">
												<strong>Systemside:</strong>
											</th>
											<td style="width: 25%">
												<select name="IsSystem">
													<option value="1"<?= $this->content->IsSystem ? " selected=\"selected\"" : "" ?>>Systemside</option>
													<option value="0"<?= !$this->content->IsSystem ? " selected=\"selected\"" : "" ?>>Vanlig side</option>
												</select>
											</td>
										</tr>
									</table>
								</div>
								<div class="SpacerSmall"><em></em></div>
								<div id="ContentPermissionTabs">
									<div class="tab" id="tabWebPermissions">
										<img src="admin/gfx/icons/drive_web.png"> Webrettigheter
									</div>
									<div class="tab" id="tabAdminPermissions">
										<img src="admin/gfx/icons/user_gray.png"> Adminrettigheter
									</div>
									<div class="page" id="pageWebPermissions">
										<?= renderPlugin ( "permissions", Array ( "ContentTable"=>"ContentElement", "ContentID"=>$this->content->ID, "PluginID"=>"web" ) ) ?>
									</div>
									<div class="page" id="pageAdminPermissions">
										<?= renderPlugin ( "permissions", Array ( "ContentTable"=>"ContentElement", "ContentID"=>$this->content->ID, "PermissionType"=>"admin", "PluginID"=>"admin" ) ) ?>
									</div>
								</div>
								<script>
									initTabSystem ( 'ContentPermissionTabs' );
								</script>
								<h2 class="BlockHead">Tilgang nektet side</h2>
								<div class="BlockContainer">
									<p>
										Hvilken side skal brukeren få se når han/hun ikke har tilgang? Dette blir en "symlenke", slik at
										den valgte siden kommer istedet for den beskyttede, på samme sted i strukturen.
									</p>
									<p>
										<select onchange="document.bjx = new bajax (); document.bjx.openUrl('admin.php?module=contents&action=setprotectedsymlink&cid=<?= $this->content->ID ?>&pid=' + this.value, 'get', true ); document.bjx.onload = function ( ){}; document.bjx.send ( );">
											<option value="0"<?= $this->protectedSymlink <= 0 ? ' selected="selected"' : '' ?>>Velg innhold</option>
											<option value="0">------------</option>
											<?
												$page = new dbContent ( );
												function listSubs ( $p, $s, $r = '' )
												{
													if ( $p->MainID == $s ) $se = ' selected="selected"';
													else $se = '';
													$ostr .= '<option value="' . $p->MainID . '"' . $se . '>' . $r . $p->MenuTitle . '</option>';
													if ( $p->loadSubElements ( ) )
													{
														foreach ( $p->subElements as $e )
															$ostr .= listSubs ( $e, $s, $r . '&nbsp;&nbsp;&nbsp;&nbsp;' );
													}
													return $ostr;
												}
												return listSubs ( $page->getRootContent ( ), $this->protectedSymlink );
											?>
										</select>
									</p>
								</div>
							</div>
							<?}?>
							<?if ( $GLOBALS[ "user" ]->_dataSource == 'core' ) { ?>
							<div class="page" id="pageTemplate">
								<?= renderTemplateControls ( $this->content->ID ) ?>
							</div>
							<?}?>
						</div>
						<div class="SpacerSmallColored"></div>
						<div id="ContentButtons" style="">
						</div>
						
						<?if ( isIE ( ) ) { ?></div><?}?>
						
					</form>
				</div>

				
