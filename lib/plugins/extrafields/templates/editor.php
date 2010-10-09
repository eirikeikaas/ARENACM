							
							<script type="text/javascript">
								var ContentType = '<?= $this->ContentType ?>';
								var ContentID = '<?= $this->ContentID ?>';
							</script>
							<div class="SubContainer" id="ExtraFields">
								<h2>
									Ekstrafelter:
								</h2>
								<?= $this->extraFields ?>
							</div>
							<div class="SpacerSmall"><em></em></div>
							<div class="SubContainer" id="ExtraFields">
								<table class="Layout">
									<tr>
										<th style="padding-right: <?= MarginSize ?>px">
											<strong>Felt ID:</strong>
										</th>
										<th style="padding-right: <?= MarginSize ?>px">
											<input type="text" id="FieldName" value="" size="16" />
										</th>
										<th style="padding-right: <?= MarginSize ?>px">
											<strong>Felttype:</strong>
										</th>
										<th style="padding-right: <?= MarginSize ?>px">
											<select id="FieldType">
												<option value="varchar">Setning</option>
												<option value="text">Artikkel</option>
												<option value="leadin">Kort tekst</option>
												<option value="file">Fil</option>
												<option value="image">Bilde</option>
												<option value="objectconnection">Objekttilkoblingsfelt</option>
												<option value="pagelisting">Sideutlisting</option>
												<option value="newscategory">Nyhetskategori</option>
												<option value="script">Javascript</option>
												<option value="style">Stilark</option>
												<option value="formprocessor">Skjema prosessering</option>
												<option value="extension">Utvidelse</option>
											</select>
										</th>
										<th style="padding-right: <?= MarginSize ?>px">
											<button type="button" onclick="addExtraField ( )">
												<img src="admin/gfx/icons/table_row_insert.png" /> Legg til feltet
											</button>
										</th>
									</tr>
								</table>
							</div>
							
							<?if ( !$this->Ajax ) { ?>
							<script type="text/javascript" src="lib/plugins/extrafields/javascript/main.js"></script>
							<?}?>
