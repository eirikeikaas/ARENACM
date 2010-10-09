<div class="ModuleContainer">
	<table class="LayoutColumns">
		<tr>
			<td style="width: 75%; padding-right: <?= MarginSize ?>px">
				<?if ( $this->data->ID ) { ?>
				<div id="NewsCategoryTabs">
					<div class="tab" id="tabEditNC">
						<img src="admin/gfx/icons/folder_edit.png"> <?= "Endre: {$this->data->Name}" ?>
					</div>
					<div class="tab" id="tabExtraNC">
						<img src="admin/gfx/icons/table_row_insert.png"> Endre ekstra felter for nyheter i kategorien
					</div>
					<div class="page" id="pageEditNC">
						<div class="Container" style="float: right; padding: 2px 0px 0px 2px">
							<?
								if ( $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->data, 'Write', 'admin' ) )
								{
									return '
									<button type="button" onclick="verifyForm ( )">
										<img src="admin/gfx/icons/disk.png" />
									</button>
									<button type="button" onclick="verifyForm ( 1 )">
										<img src="admin/gfx/icons/accept.png" />
									</button>
									';
								}
							?>
							<button type="button" onclick="document.location='admin.php?module=news&cid=<?= intval( $_REQUEST[ 'cid' ] ) > 0 ? $_REQUEST[ 'cid' ] : $_REQUEST[ 'parentcat' ]; ?>'">
								<img src="admin/gfx/icons/cancel.png" />
							</button>
						</div>
				<?}?>
						<form method="post" id="CategoryForm" action="admin.php?module=news&amp;action=category">
							<input type="hidden" name="ID" value="<?= $this->data->ID ?>" />
							<input type="hidden" name="Parent" value="<?= $this->data->Parent ? $this->data->Parent : $_REQUEST[ "parentcat" ] ?>" />
							<?if ( !$this->data->ID ) { ?>
							<h1>
								<div style="float: right">
									<?
										if ( $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->data, 'Write', 'admin' ) )
										{
											return '
											<button type="button" onclick="verifyForm ( )">
												<img src="admin/gfx/icons/disk.png" />
											</button>
											<button type="button" onclick="verifyForm ( 1 )">
												<img src="admin/gfx/icons/accept.png" />
											</button>
											';
										}
									?>
									<button type="button" onclick="document.location='admin.php?module=news&cid=<?= intval( $_REQUEST[ 'cid' ] ) > 0 ? $_REQUEST[ 'cid' ] : $_REQUEST[ 'parentcat' ]; ?>'">
										<img src="admin/gfx/icons/cancel.png" />
									</button>
								</div>
								<?= $this->data->ID ? "Endre: {$this->data->Name}" : "Ny kategori" ?>
							</h1>
							<?}?>
							<div class="Container">
								<p>
									<strong>Navn:</strong>
								</p>
								<p>
									<input type="text" id="Name" name="Name" value="<?= $this->data->Name ?>" size="40" />
								</p>
								<p>
									<strong>Hører til under side:</strong>
								</p>
								<p>
									<select name="ContentElementID">
										<?
											function getContent ( $parent = 0, $current, $r = "" )
											{
												$p = new dbObject ( "ContentElement" );
												if ( $parent == 0 ) $parent = "0";
												$p->addClause ( "WHERE", "Parent='{$parent}' AND Language='{$GLOBALS["Session"]->CurrentLanguage}' AND ID=MainID AND !IsTemplate AND !IsDeleted AND IsPublished" );
												$oStr = "";
												if ( $p = $p->find ( ) )
												{
													foreach ( $p as $pp )
													{
														if ( $pp->ID == $current )
															$s = " selected=\"selected\"";
														else $s = "";
														$oStr .= "<option value=\"{$pp->ID}\"$s>$r{$pp->MenuTitle}</option>";
														$oStr .= getContent ( $pp->ID, $current, "$r&nbsp;&nbsp;&nbsp;&nbsp;" );
													}
												}
												return $oStr;
											}
											return getContent ( 0, $this->data->ContentElementID, "" );
										?>
									</select>
								</p>
								<p>
									<strong>Tilbakeknappen fører til side:</strong>
								</p>
								<p>
									<select name="BackPageID">
										<option value="0">Standard</option>
										<?
											return getContent ( 0, $this->data->BackPageID, "" );
										?>
									</select>
								</p>
								<p>
									<strong>Beskrivelse:</strong>
								</p>
								<textarea rows="15" cols="50" class="mceSelector" id="Description" name="Description"><?= $this->data->Description ?></textarea>
								<div class="Spacer"></div>
								<?if ( $this->data->ID > 0 && $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->data, 'Write', 'admin' ) ) { ?>
								<?= renderPlugin ( 'permissions', Array ( "ContentTable"=>"NewsCategory", "ContentID"=>$this->data->ID, "PermissionType"=>"admin" ) ) ?>
								<?}?>
								<div class="Spacer"></div>
								
								
								<?if ( $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->data, 'Write', 'admin' ) ) { ?>
								<button type="button" onclick="verifyForm ( )">
									<img src="admin/gfx/icons/disk.png" /> Lagre
								</button>
								<button type="button" onclick="verifyForm ( )">
									<img src="admin/gfx/icons/accept.png" /> Lagre og lukk
								</button>
								<?}?>
								<button type="button" onclick="document.location='admin.php?module=news&cid=<?= intval( $_REQUEST[ 'cid' ] ) > 0 ? $_REQUEST[ 'cid' ] : $_REQUEST[ 'parentcat' ]; ?>'">
									<img src="admin/gfx/icons/cancel.png" /> Lukk
								</button>
							</div>
						</form>
				<?if ( $this->data->ID ) { ?>		
					</div>
					<div class="page" id="pageExtraNC">
						<?= renderPlugin ( 'extrafields', Array ( 'ContentID'=>$this->data->ID, 'ContentType'=>'NewsCategory' ) ) ?>
					</div>
				</div>
				<script>
					initTabSystem ( 'NewsCategoryTabs' );
				</script>
				<?}?>
			</td>
			<td style="width: 25%; padding-right: <?= MarginSize ?>px">
				<?if ( !$this->data->ID) { ?>
				<h1>
					Objekt tilkobling
				</h1>
				<div class="Container">
					Du må lagre kategorien før du kan benytte deg av objekt tilkobling.
				</div>
				<?}?>
				<?if ( $this->data->ID ) { ?>
				<?= renderPlugin ( 'objectconnector', Array ( 'ObjectID'=>$this->data->ID, 'ObjectType'=>'NewsCategory' ) ) ?>
				<?}?>
			</td>
		</tr>
	</table>
</div>
<script type="text/javascript">
	function verifyForm ( close )
	{
		if ( !close ) close = false;
		if ( document.getElementById ( 'Name' ).value.length <= 0 )
		{
			alert ( "Kategorien må ha et navn." );
			document.getElementById ( 'Name' ).focus ( );
			return false;
		}
		if ( close ) document.getElementById ( 'CategoryForm' ).action += '&close=1';
		document.getElementById ( 'CategoryForm' ).submit ( );
	}
</script>
<?= enableTextEditor ( ) ?>
