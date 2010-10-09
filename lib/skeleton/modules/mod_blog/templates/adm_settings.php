
	<div class="SubContainer" style="padding: <?= MarginSize ?>px">
		<table>
			<tr>
				<td>
					<table>
						<tr>
							<td>
								<strong>Antall artikler pr. side:</strong>
							</td>
							<td>
								<input type="text" id="mod_blog_limit" size="10" value="<?= $this->settings->Limit ?>">
							</td>
						</tr>
						<tr>
							<td>
								<strong>Bruker kommentarer:</strong>
							</td>
							<td>
								<input type="checkbox" id="mod_blog_comments"<?= $this->settings->Comments ? ' checked="checked"' : '' ?>>
							</td>
						</tr>
						<tr>
							<td>
								<strong>Viser forfatter:</strong>
							</td>
							<td>
								<input type="checkbox" id="mod_blog_showauthor"<?= $this->settings->ShowAuthor ? ' checked="checked"' : '' ?>>
							</td>
						</tr>
						<tr>
							<td>
								<strong>Viser tagboks:</strong>
							</td>
							<td>
								<input type="checkbox" id="mod_blog_tagbox"<?= $this->settings->TagBoxEnabled ? ' checked="checked"' : '' ?>>
							</td>
						</tr>
						<tr>
							<td>
								<strong>Tagboks plassering:</strong>
							</td>
							<td>
								<select id="mod_tagbox_placement">
									<?
										$db =& dbObject::globalValue ( 'database' );
										if ( $rows = $db->fetchObjectRows ( '
											SELECT DISTINCT(ContentGroups) AS Uni FROM ContentElement WHERE MainID=ID
										' ) )
										{
											$groups = array ( );
											foreach ( $rows as $row )
											{
												if ( $gr = explode ( ',', $row->Uni ) )
												{
													foreach ( $gr as $g )
													{
														if ( !in_array ( trim ( $g ), $groups ) )
															$groups[] = trim ( $g );
													}
												}
											}
											foreach ( $groups as $g )
											{
												if ( $g == $this->settings->TagBoxPosition )
													$s = ' selected="selected"';
												else $s = '';
												$str .= '<option value="' . $g . '"' . $s . '>' . $g . '</option>';
											}
											return $str;
										}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<strong>Viser søkeboks:</strong>
							</td>
							<td>
								<input type="checkbox" id="mod_blog_searchbox"<?= $this->settings->SearchBox ? ' checked="checked"' : '' ?>>
							</td>
						</tr>
						<tr>
							<td>
								<strong>Skjuler detaljer:</strong>
							</td>
							<td>
								<input type="checkbox" id="mod_blog_hide_details"<?= $this->settings->HideDetails ? ' checked="checked"' : '' ?>>
							</td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<td>
								<strong>Bruker denne siden for "Les mer":</strong>
							</td>
							<td id="mod_blog_detailpage">
								<?
									$str = dbContent::RenderSelect ( 'mod_blog_detailpage', false, $this->settings->Detailpage );
									return $str;
								?>
							</td>
						</tr>
						<tr>
							<td>
								<strong>Hent blogger fra denne siden:</strong>
							</td>
							<td id="mod_blog_sourcepage">
								<?
									$str = dbContent::RenderSelect ( 'mod_blog_sourcepage', false, $this->settings->Sourcepage );
									return $str;
								?>
							</td>
						</tr>
						<tr>
							<td>
								<strong>Maks bokstaver i ingress:</strong>
							</td>
							<td id="mod_blog_sourcepage">
								<input type="text" id="mod_blog_leadinlength" size="4" value="<?= $this->settings->LeadinLength ?>">
							</td>
						</tr>
						<tr>
							<td>
								<strong>Maks bokstaver i tittel:</strong>
							</td>
							<td id="mod_blog_sourcepage">
								<input type="text" id="mod_blog_titlelength" size="4" value="<?= $this->settings->TitleLength ?>">
							</td>
						</tr>
						<tr>
							<td>
								<strong>Ingressbilde størrelse:</strong>
							</td>
							<td id="mod_blog_sourcepage">
								<table cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td>
											<input type="text" id="mod_blog_sizex" style="width: 40px" size="4" value="<?= $this->settings->SizeX ?>">
										</td>
										<td> x </td>
										<td>
											<input type="text" id="mod_blog_sizey" style="width: 40px" size="4" value="<?= $this->settings->SizeY ?>">
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<strong>Header tekst:</strong>
							</td>
							<td id="mod_blog_sourcepage">
								<table cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td>
											<input type="text" id="mod_blog_headertext" size="40" value="<?= $this->settings->HeaderText ?>">
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
	<div class="SpacerSmallColored"></div>
	<button type="button" onclick="mod_blog_savesettings ( )">
		<img src="admin/gfx/icons/disk.png"> <span id="mod_blog_savetext">Lagre innstillingene</span>
	</button>
	<button type="button" onclick="mod_blog_abortedit ( )">
		<img src="admin/gfx/icons/newspaper.png"> Vis blog arkivet
	</button>

