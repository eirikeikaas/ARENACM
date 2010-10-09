<?
	global $document;
	$document->addHeadScript ( 'admin/modules/contents/javascript/admin_main.js' );
	$document->addHeadScript ( 'lib/plugins/extrafields/javascript/main.js' );
	$document->addRel ( 'stylesheet', 'admin/modules/contents/css/main.css' );
	if ( isSafari ( ) || isKonqueror ( ) )
		$document->addRel ( 'stylesheet', 'admin/modules/contents/css/khtml.css' );
?>
	<?= enableTextEditor ( ) ?>
	<script type="text/javascript">
		var ContentType = 'ContentElement';
		var ContentID = '<?= $this->content->ID ?>';
	</script>
	<iframe name="ContentsSubmit" style="width: 1px; height: 1px; position: absolute; visibility: hidden"></iframe>

	<div class="ModuleContainer">
		<table class="LayoutColumns">
			<tr>
				<td style="width: 100%" id="ContentsTd">
					
					<span style="float: right">
						<select name="Language" style="height: 18px; font-size: 10px;" onchange="changeLanguage ( this.value )">
							<?= $this->languages ?>
						</select>
					</span>
					<div id="sitemaptabs">
						<div class="tab" id="tabStructure">
							<img src="admin/gfx/icons/folder_page.png" /> Sidestruktur
						</div>
						<div class="tab" id="tabContent" onmousedown="showMainContent( )">
							<img src="admin/gfx/icons/page_edit.png" /> Rediger valgte side
						</div>
						<?if ( $GLOBALS[ "user" ]->_dataSource == 'core' ) { ?>
						<div class="tab" id="tabTpls">
							<img src="admin/gfx/icons/folder_page_white.png" /> Rediger tilgjengelige maler
						</div>
						<?}?>
						<div class="tab" id="tabDeleted">
							<img src="admin/gfx/icons/bin.png" /> Behandle slettet innhold
						</div>
						<?if ( $GLOBALS[ "user" ]->_dataSource == 'core' ) { ?>
						<div class="tab" id="tabTools">
							<img src="admin/gfx/icons/wrench.png" /> Batch verktøy
						</div>
						<?}?>
						<br style="clear: both" />
						<div class="page" id="pageStructure">
							<ul id="Structure" class="Collapsable">
								<li>Laster inn...</li>
							</ul>
						</div>
						<?if ( $GLOBALS[ "user" ]->_dataSource == 'core' ) { ?>
						<div class="page" id="pageTpls" style="padding: 2px">
							<div class="Container" style="margin-bottom: 2px">
								<p>
									Her ser du en liste av maler som finnes i systemet.
								</p>
								<select style="width: 100%" onchange="showTemplates ( this.value )">
									<?= $this->languages ?>
								</select>
							</div>
							<div class="Container" id="TplContent" style="height: 260px; overflow: auto; padding: 2px; margin-bottom: 2px">
							</div>
							<div class="SubContainer" style="padding: 2px">
								<button type="button" onclick="deleteSelected ( 'tpldel' )">
									<img src="admin/gfx/icons/page_white_delete.png"> <span style="color: #a00">Slett</span>
								</button>
							</div>
						</div>
						<?}?>
						<div class="page" id="pageDeleted" style="padding: 2px">
							<div class="Container" style="margin-bottom: 2px">
								Under er en liste av slettede elementer. Gjenoppretter du elementer
								vil de komme opp i rotnivå, upublisert. Sletter du her, vil du ikke kunne få elementet du sletter
								tilbake - det samme gjelder hvis du tømmer slettede elementer.
							</div>
							<div class="Container" id="DeletedContent" style="height: 260px; overflow: auto; padding: 2px; margin-bottom: 2px">
							</div>
							<div class="SubContainer" style="padding: 2px">
								<button type="button" onclick="undeleteSelected ( )">
									<img src="admin/gfx/icons/page_go.png"> <span style="color: #a00">Gjenopprett</span>
								</button>
								<button type="button" onclick="deleteSelected ( )">
									<img src="admin/gfx/icons/bin.png"> <span style="color: #a00">Fjern</span>
								</button>
							</div>
						</div>
						<div class="page" id="pageContent">
							<table class="LayoutColumns">
								<tr>
									<td style="width: 67%; padding-right: <?= MarginSize ?>px;" id="ColumnMiddleTd">
											
										<div class="Container" id="MainContentPlaceholder">
											<?= getLoaderBox ( 'Laster inn innhold' ) ?>
										</div>
										
									</td>
									
									<td style="width: 33%; padding-left: <?= MarginSize ?>px" id="ColumnRightTd">
										
										<?= renderPlugin ( "library", Array ( "ContentType"=>"ContentElement", "ContentID"=>$this->content->ID ) ) ?>
										
									</td>
								</tr>
							</table>
						</div>
						<div class="page" id="pageTools">
							<div class="Container">
								<form name="distroform" action="admin.php?action=distributefields" method="post">
									<h2>
										Rsync ekstrafelter fra publisert versjon
									</h2>
									<p>
										Hvis du har slettet ekstrafelter fra arbeidskopier som finnes i de publiserte versjonene, 
										så kan du kopiere manglende ekstrafelter nå.
									</p>
									<p>
										<button type="button" onclick="if ( confirm ( 'Er du sikker på at du ønsker å rsynce?' ) ) { rSync ( ); }">
											<img src="admin/gfx/icons/wrench.png" /> Rsync ekstrafelter med publisert versjon
										</button>
									</p>
									<h2>
										Publiser hele sidehierarkiet
									</h2>
									<p>
										Bruk denne funksjonaliteten med varhet. Hvis du klikker på knappen under så vil du publisere hele sidehierarkiet, slik
										at alle endringer blir publisert.
									</p>
									<p>
										<button type="button" onclick="if ( confirm ( 'Er du sikker på at du ønsker å publisere alt?' ) ) { publishEverything ( ); }">
											<img src="admin/gfx/icons/transmit_go.png" /> Publiser sidehierarkiet!
										</button>
									</p>
									<h2>
										Distribuér ekstrafelt
									</h2>
									<p>
										Skjekk at ekstrafelt finnes på valgte undersider og opprett dem hvis de ikke finnes.
									</p>
									<table width="100%" class="Gui">
										<tr>
											<th width="50%">
												Felter:
											</th>
											<th width="50%">
												Undersider:
											</th>
										</tr>
										<tr>
											<td width="50%">
												<select name="fields[]" size="15" multiple="multiple" style="box-sizing: border-box; -moz-box-sizing: border-box; width: 100%" id="CheckFieldsFields"></select>
											</td>
											<td width="50%">
												<select name="pages[]" size="15" multiple="multiple" style="box-sizing: border-box; -moz-box-sizing: border-box; width: 100%" id="CheckFieldsPages"></select>
											</td>
									</table>
									<p>
										<button type="button" onclick="if ( confirm ( 'Er du sikker på at du ønsker å distribuere feltet/feltene?' ) ) { document.distroform.submit ( ); }">
											<img src="admin/gfx/icons/table_row_insert.png" /> Distribuér felt
										</button>
									</p>
								</form>
							</div>
						</div>
					</div>
					<script type="text/javascript">
						if ( getUrlVar ( 'cid' ) )
						{
							document.location = 'admin.php?module=contents';
						}
						else
						{
							initTabSystem ( 'sitemaptabs' );
							showStructure ( '<?= $this->content->ID ?>', true );
							<?if ( $GLOBALS[ "user" ]->_dataSource == 'core' ) { ?>showTemplates ( );<?}?>
							showDeletedContent ( );
							if ( getCookie ( 'sitemaptabsactiveTab' ) == 'tabContent' )
								showMainContent ( '<?= $this->content->ID ?>' );
						}
					</script>	
					<div class="Spacer"><em></em></div>
				</td>
			</tr>
		</table>
	</div>

