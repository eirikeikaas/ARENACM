
							<h2 class="BlockHead">Mal innstillinger:</h2>
								<div class="BlockContainer">
									<table class="Layout">
										<tr>
											<td style="width: 20%; padding-right: <?= MarginSize ?>px">
												<strong><small>php</small>Mal:</strong>
											</td>
											<td style="width: 30%; padding-right: <?= MarginSize ?>px">
												<select name="Template">
													<option value=""<?= !$this->content->Template ? " selected=\"selected\"" : "" ?>>Innebygget template</option>
													<?= $this->templates ?>
												</select>
											</td>
											<td style="width: 20%; padding-right: <?= MarginSize ?>px">
												<strong><small>php</small>Mal Arkivert:</strong>
											</td>
											<td style="width: 30%">
												<select name="TemplateArchived">
													<option value=""<?= !$this->content->TemplateArchived ? " selected=\"selected\"" : "" ?>>Innebygget template</option>
													<?= $this->templatesArchived ?>
												</select>
											</td>
										</tr>
										<tr>
											<td style="width: 20%; padding-right: <?= MarginSize ?>px">
												<strong>Innholdsgrupper:</strong>
											</td>
											<td style="width: 30%; padding-right: <?= MarginSize ?>px" colspan="3">
												<input type="text" size="60" name="ContentGroups" value="<?= trim ( $this->content->ContentGroups ) ?>"/>
											</td>
										</tr>
										<tr>
											<th style="width: 20%; padding-right: <?= MarginSize ?>px">
												<strong>Innholdstype:</strong>
											</th>
											<th style="width: 30%; padding-right: <?= MarginSize ?>px">
												<select name="ContentType">
													<option value="text"<?= $this->content->ContentType == "text" ? " selected=\"selected\"" : "" ?>>Tekst</option>
													<option value="extrafields"<?= $this->content->ContentType == "extrafields" ? " selected=\"selected\"" : "" ?>>Kun ekstrafelter</option>
													<option value="link"<?= $this->content->ContentType == "link" ? " selected=\"selected\"" : "" ?>>Lenke</option>
													<?= $this->contenttypes ?>
												</select>
											</th>
											<th style="width: 20%; padding-right: <?= MarginSize ?>px">
												<strong>Innholdsmal:</strong>
											</th>
											<th style="width: 30%">
												<?= $this->TemplateTitle ?>
											</th>
										</tr>
									</table>
								</div>
								<div class="SpacerSmall"><em></em></div>
								<?= renderPlugin ( "extrafields", Array ( "ContentType"=>"ContentElement", "ContentID"=>$this->content->ID, "Ajax"=>1 ) ) ?>
								<div class="SpacerSmall"><em></em></div>
								<div class="SubContainer">
									<button type="button" onclick="makeTemplate ( )">
										<img src="admin/gfx/icons/script_add.png" /> Lagre som mal for nytt innhold
									</button>
								</div>

