<link rel="stylesheet" href="admin/modules/news/css/editor.css" />
<?= enableTextEditor ( ) ?>
<script type="text/javascript" src="admin/modules/news/javascript/news.js"></script>

<div class="ModuleContainer">
	<form method="post" name="newsform" enctype="multipart/form-data" action="admin.php?module=news&action=news">
		<input type="hidden" name="ID" value="<?= $this->data->ID ?>" id="newsid" />
		<input type="hidden" name="DateModified" value="<?= date ( "Y-m-d H:i:s" ) ?>" />
		<input type="hidden" name="AuthorID" value="<?= $GLOBALS[ "user" ]->_datasource != "core" ? $GLOBALS[ "user" ]->ID : "0" ?>" />
		<input type="hidden" name="IsEvent" value="1" />
		<table class="LayoutColumns">
			<tr>
				<td style="width: 65%; padding-right: <?= MarginSize ?>px">
					<h1>
						<div style="float: right">
							<button type="button" onclick="checkForm ( )">
								<img src="admin/gfx/icons/disk.png" />
							</button>
							<button type="button" onclick="checkForm ( 1 )">
								<img src="admin/gfx/icons/accept.png" />
							</button>
							<button type="button" onclick="document.location='admin.php?module=news'">
								<img src="admin/gfx/icons/cancel.png" />
							</button>
						</div>
						<?= $this->data->ID ? "Endre: {$this->data->Title}" : "Ny nyhet/hendelse" ?>
					</h1>
					<div class="Container">
						<p>
							<strong>
								Tittel:
							</strong>
						</p>
						<p>
							<input type="text" name="NewsTitle" size="50" style="width: 100%; box-sizing: border-box; -moz-box-sizing: border-box;" value="<?= $this->data->Title ?>" />
						</p>
						<p>
							<strong>Publisert:</strong>
						</p>
						<p>
							<input type="hidden" name="IsPublished" value="<?= $this->data->IsPublished ? "1" : "0" ?>" id="ispublished" />
							<input type="checkbox" onchange="document.getElementById ( 'ispublished' ).value = this.checked ? '1' : '0'"<?= $this->data->IsPublished ? " checked=\"checked\"" : "" ?> />
						</p>
						<p>
							<strong>Ingress:</strong>
						</p>
						<p>
							<textarea class="mceSelector" rows="6" cols="50" name="Intro" id="Intro"><?= $this->data->Intro ?></textarea>
						</p>
						<p>
							<strong>Artikkel:</strong>
						</p>
						<p>
							<textarea class="mceSelector" rows="22" cols="50" name="Article" id="Article"><?= $this->data->Article ?></textarea>
						</p>
						<?if ( $this->data->ID ) { ?>
						<?= getPluginFunction ( 'extrafields', 'adminrender', Array ( 'ContentID'=>$this->data->ID, 'ContentType'=>'News', 'ToggleBox'=>'no' ) ) ?>
						<?}?>
						
						<div class="Spacer"></div>
						<button type="button" onclick="checkForm ( )">
							<img src="admin/gfx/icons/disk.png" /> Lagre
						</button>
						<button type="button" onclick="checkForm ( 1 )">
							<img src="admin/gfx/icons/accept.png" /> Lagre og lukk
						</button>
						<button type="button" onclick="document.location='admin.php?module=news'">
							<img src="admin/gfx/icons/cancel.png" /> Lukk
						</button>
						
					</div>
				</td>
				<td style="width: 35%">
				
					<div id="TheEventTabs">
					
						<div class="tab" id="tabEventCats">
							<img src="admin/gfx/icons/table.png" /> Kategori og dato
						</div>
						
						<div class="tab" id="tabEventLibrary">
							<img src="admin/gfx/icons/book.png" /> Bibliotek / Media
						</div>
					
						<div class="page" id="pageEventCats">
						
							<h1>
								Kategori
							</h1>
							<div class="Container">
								<div class="SubContainer">
									<select name="CategoryID" style="width: 100%" size="10">
										<?
											global $Session;
											
											$db =& dbObject::globalValue ( "database" );
											$cats = $db->fetchObjectRows ( "SELECT * FROM NewsCategory WHERE Language='{$Session->CurrentLanguage}' ORDER BY Name ASC" );
											function listCategories ( $parent = 0, $r = "", $cats, $current )
											{
												$len = count ( $cats );
												$oStr = "";
												for ( $a = 0; $a < $len; $a++ )
												{
													if ( $cats[ $a ]->Parent == $parent )
													{
														$cats[ $a ]->_tableName = 'NewsCategory';
														$cats[ $a ]->_primaryKey = 'ID';
														$cats[ $a ]->_isLoaded = true;
														if ( $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $cats[ $a ], 'Write', 'admin' ) )
														{
															if ( $cats[ $a ]->ID == $current || !$current || $current == "alle" )
															{
																$s = " selected=\"selected\"";
																$current = $cats[ $a ]->ID;
															}
															else $s = "";
															$oStr .= "<option value=\"" . $cats[ $a ]->ID . "\"$s>$r" . $cats[ $a ]->Name . "</option>";
															$oStr .= listCategories ( $cats[ $a ]->ID, $r . "&nbsp;&nbsp;", $cats, $current );
														}
													}
												}
												return $oStr;
											}
											return listCategories ( 0, "", $cats, $this->data->ID ? $this->data->CategoryID : $_REQUEST[ "cid" ] );
										?>
									</select>
								</div>
							</div>
							<div class="Spacer"></div>
							<h1>
								Datostyring
							</h1>
							<div class="Container">
								<h2>Nyheten publiseres fra:</h2>
								<p>
									<?= dateToPulldowns ( "DateFrom", $this->data->ID ? $this->data->DateFrom : date ( "Y-m-d H:i:s" ), true ) ?>
								</p>
								<h2>Nyheten publiseres til:</h2>
								<p>
									<?= dateToPulldowns ( "DateTo", $this->data->ID ? $this->data->DateTo : date ( "Y-m-d H:i:s" ), true ) ?>
								</p>
								<h2>Nyheten loggføres med dato:</h2>
								<p>
									<?= dateToPulldowns ( "DateActual", $this->data->ID ? $this->data->DateActual : date ( "Y-m-d H:i:s" ), true ) ?>
								</p>
							</div>
							
						</div>
						
						<div class="page" id="pageEventLibrary">
						
							<?= renderPlugin ( "library", Array ( "ContentType"=>"News", "ContentID"=>$this->data->ID ) ) ?>	
							
						</div>
						
					</div>
					
					<script type="text/javascript">
						initTabSystem ( 'TheEventTabs' );
					</script>
					
					<div class="Spacer"></div>
					<h1>
						Oversettelser
					</h1>
					<div class="Dropzone" id="DropTranslations">
						Slipp andre nyheter her
					</div>
					<div class="SpacerSmall"></div>
					<div class="Container" id="Translations">
					</div>
						
				</td>
			</tr>
		</table>
	</form>
</div>


