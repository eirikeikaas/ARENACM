

			<div class="Container" style="padding: <?= MarginSize ?>px">
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<td>
							<p>
								<strong>Heading:</strong> 
							</p>
							<p>
								<input type="text" size="50" style="width: 500px" value="<?= $this->Heading ?>" id="galHeading_<?= $this->field->ID ?>"/>
							</p>
						</td>
						<td width="4px">
						</td>
						<td width="240" valign="top" rowspan="2">	
							<div class="tabs" id="gallerytabs">
								<div class="tab" id="tabImageschoice">
									<img src="admin/gfx/icons/images.png"/> Bildevalg
								</div>
								<div class="tab" id="tabGalleryOptions">
									<img src="admin/gfx/icons/wrench.png"/> Innstillinger
								</div>
								<div class="page" id="pageImageschoice">
									<div class="Container">
										<select onchange="addSlideshowFolder(this)">
											<option>Legg til slideshow mappe:</option>
											<?
												function listFlds ( $parent = 0, $p = '' )
												{
													$db =& dbObject::globalValue ( 'database' );
													if ( $rows = $db->fetchObjectRows ( '
														SELECT * FROM Folder WHERE Parent=' . (int)$parent . '
														ORDER BY `Name`
													' ) )
													{
														foreach ( $rows as $row )
														{
															$str .= '<option value="' . $row->ID . '">' . $p . $row->Name . '</option>';
															$str .= listFlds ( $row->ID, "$p&nbsp;&nbsp;&nbsp;&nbsp;" );
														}
													}
													return $str;
												}
												return listFlds();
											?>
										</select>
									</div>
									<div class="SpacerSmallColored"></div>
									<div class="Container" id="ImageList_<?= $this->field->ID ?>" style="padding: 4px; overflow: auto">
										<?= $this->folders ?>
									</div>
								</div>
								<div class="page" id="pageGalleryOptions">
									<div class="Container">
									<?
										$modes = array ( 'slideshow'=>'Slideshow', 'gallery'=>'Bildegalleri' );
										$i = 0;
										foreach ( $modes as $mode=>$name )
										{
											if ( ( $i++ == 0 && !$this->currentMode ) || $this->currentMode == $mode )
												$s = ' checked="checked"';
											else $s = '';
											$str .= '<td width="24px"><input type="radio" name="mode" value="' . $mode . '"' . $s . ' onclick="changeGalMode(this)"/></td>';
											$str .= '<td><p style="padding: 3px 0 0 3px">' . $name . '</p></td>';
										}
										return '<table width="100%" style="margin: 0 0 -12px 0"><tr>' . $str . '</tr></table>';
									?>
									</div>
									<div class="SpacerSmallColored"></div>
									<div id="GalControl_slideshow">
										<div id="GalleryControls_<?= $this->field->ID ?>">
											<div class="Container">
												<table cellspacing="0" cellpadding="0" border="0" width="100%" class="Gui">
													<tr>
														<td><strong>Bredde:</strong></td>
														<td><input type="text" id="galWidth_<?= $this->field->ID ?>" size="4" value="<?= $this->settings->Width ?>"/></td>
													</tr>
													<tr>
														<td><strong>Høyde:</strong></td>
														<td><input type="text" id="galHeight_<?= $this->field->ID ?>" size="4" value="<?= $this->settings->Height ?>"/></td>
													</tr>
													<tr>
														<td><strong>Animert:</strong></td>
														<td><input type="checkbox" id="galAnimated_<?= $this->field->ID ?>"<?= $this->settings->Animated == 1 ? ' checked="checked"' : '' ?>/></td>
													</tr>
													<tr>
														<td><strong>Pause (sek.):</strong></td>
														<td><input type="text" id="galPause_<?= $this->field->ID ?>" size="4" value="<?= $this->settings->Pause >= 1 ? $this->settings->Pause : 2 ?>"/></td>
													</tr>
													<tr>
														<td><strong>Sortering:</strong></td>
														<td>
															<select id="galSortMode_<?= $this->field->ID ?>">
															<?
																$array = array ( 'listmode_date', 'listmode_sortorder' );
																$s = '';
																foreach ( $array as $m )
																{
																	$s = $m == $this->settings->SortMode ? ' selected="selected"' : '';
																	$str .= '<option value="' . $m . '"'.$s.'>' . i18n ( $m ) . '</option>';
																}
																return $str;
															?>
														</td>
													</tr>
													<tr>
														<td>
															<strong>Fremvisning:</strong>
														</td>
														<td>
															<select id="galShowStyle_<?= $this->field->ID ?>">
															<?
																$array = array ( 'showstyle_fade', 'showstyle_scrollx', 'showstyle_scrolly' );
																$s = '';
																foreach ( $array as $m )
																{
																	$s = $m == $this->settings->ShowStyle ? ' selected="selected"' : '';
																	$str .= '<option value="' . $m . '"'.$s.'>' . i18n ( $m ) . '</option>';
																}
																return $str;
															?>
														</td>
													</tr>
													<tr>
														<td>
															<strong>Tempo:</strong>
														</td>
														<td>
															<select id="galSpeed_<?= $this->field->ID ?>">
															<?
																for ( $a = 0; $a <= 100; $a++ )
																{
																	$s = $m == $this->settings->Speed ? ' selected="selected"' : '';
																	$str .= '<option value="' . $a . '"'.$s.'>' . ($a*0.1) . ' sekunder</option>';
																}
																return $str;
															?>
														</td>
													</tr>
												</table>
											</div>
										</div>
									</div>
									<?
										if ( !$this->settings->ThumbWidth ) 
											$this->settings->ThumbWidth = 80;
										if ( !$this->settings->ThumbHeight ) 
											$this->settings->ThumbHeight = 60;
										if ( !$this->settings->ThumbColumns ) 
											$this->settings->ThumbColumns = 4;
									?>
									<div id="GalControl_gallery">
										<div class="Container">
											<table cellspacing="0" cellpadding="0" border="0" width="100%" class="Gui">
												<tr>
													<td><strong>Tommebilde bredde:</strong></td>
													<td><input type="text" id="galThumbWidth_<?= $this->field->ID ?>" size="4" value="<?= $this->settings->ThumbWidth ?>"/></td>
												</tr>
												<tr>
													<td><strong>Tommebilde høyde:</strong></td>
													<td><input type="text" id="galThumbHeight_<?= $this->field->ID ?>" size="4" value="<?= $this->settings->ThumbHeight ?>"/></td>
												</tr>
												<tr>
													<td><strong>Tommebilde kolonner:</strong></td>
													<td><input type="text" id="galThumbColumns_<?= $this->field->ID ?>" size="4" value="<?= $this->settings->ThumbColumns ?>"/></td>
												</tr>
												<tr>
													<td><strong>Detalj bredde:</strong></td>
													<td><input type="text" id="galDetailWidth_<?= $this->field->ID ?>" size="4" value="<?= $this->settings->DetailWidth ?>"/></td>
												</tr>
												<tr>
													<td><strong>Detalj høyde:</strong></td>
													<td><input type="text" id="galDetailHeight_<?= $this->field->ID ?>" size="4" value="<?= $this->settings->DetailHeight ?>"/></td>
												</tr>
												<tr>
													<td><strong>Sortering:</strong></td>
													<td>
														<select id="galSortMode_<?= $this->field->ID ?>">
														<?
															$array = array ( 'listmode_date', 'listmode_sortorder' );
															$s = '';
															foreach ( $array as $m )
															{
																$s = $m == $this->settings->SortMode ? ' selected="selected"' : '';
																$str .= '<option value="' . $m . '"'.$s.'>' . i18n ( $m ) . '</option>';
															}
															return $str;
														?>
													</td>
												</tr>
											</table>
										</div>
									</div>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td valign="top" width="*" id="gal_preview">
							<?= $this->preview ?>
						</td>
						<td width="4px">
						</td>
					</tr>
				</table>
				<script type="text/javascript">
					initTabSystem ( 'gallerytabs' );
					
					// Initialize controls
					var modes = [ 'slideshow', 'gallery' ];
					for ( var a = 0; a < modes.length; a++ )
					{
						if ( ( a == 0 && '<?= $this->currentMode ?>' == '' ) || modes[a] == '<?= $this->currentMode ?>' )
							document.getElementById ( 'GalControl_' + modes[a] ).style.display = '';
						else document.getElementById ( 'GalControl_' + modes[a] ).style.display = 'none';
					}
					
					// Change gallery mode
					function changeGalMode ( obj )
					{
						document.location = 'admin.php?module=extensions&extension=<?= $_REQUEST[ 'extension' ] ?>&modaction=galmode&mode=' + obj.value;
					}
					
					function addSlideshowFolder ( obj )
					{
						var val = obj.value;
						if ( !val ) return;
						if ( confirm ( 'Er du sikker?' ) )
						{
							var j = new bajax ();
							j.openUrl ( 'admin.php?module=extensions&extension=<?= $_REQUEST[ 'extension' ] ?>&modaction=addfolder&fid=' + val + '&fieldid=' + <?= $this->field->ID ?>, 'get', true );
							j.onload = function ()
							{
								document.getElementById ( 'ImageList_<?= $this->field->ID ?>' ).innerHTML = this.getResponseText ();
								for ( var a = 0; a < obj.options.length; a++ )
								{
									if ( a == 0 ) obj.options[a].selected="selected";
									else obj.options[a].selected="";
								}
								RefreshGalPreview ();
							}
							j.send ();
						}
					}
					function RefreshGalPreview ()
					{
						var j = new bajax ( );
						j.openUrl ( 'admin.php?module=extensions&extension=<?= $_REQUEST[ 'extension' ] ?>&modaction=preview', 'post', true );
						j.onload = function ()
						{
							document.getElementById ( 'gal_preview' ).innerHTML = this.getResponseText();
						}
						j.send();
					}
					AddSaveFunction ( function ( )
					{
						var j = new bajax ( );
						j.openUrl ( 'admin.php?module=extensions&extension=<?= $_REQUEST[ 'extension' ] ?>&modaction=savesettings', 'post', true );
						j.addVar ( 'key_Width', document.getElementById ( 'galWidth_<?= $this->field->ID ?>' ).value );
						j.addVar ( 'key_ThumbWidth', document.getElementById ( 'galThumbWidth_<?= $this->field->ID ?>' ).value );
						j.addVar ( 'key_ThumbHeight', document.getElementById ( 'galThumbHeight_<?= $this->field->ID ?>' ).value );
						j.addVar ( 'key_ThumbColumns', document.getElementById ( 'galThumbColumns_<?= $this->field->ID ?>' ).value );
						j.addVar ( 'key_DetailWidth', document.getElementById ( 'galDetailWidth_<?= $this->field->ID ?>' ).value );
						j.addVar ( 'key_DetailHeight', document.getElementById ( 'galDetailHeight_<?= $this->field->ID ?>' ).value );
						j.addVar ( 'fieldid',  <?= $this->field->ID ?> );
						j.addVar ( 'key_Animated',  document.getElementById ( 'galAnimated_<?= $this->field->ID ?>' ).checked ? '1' : '-1' );
						j.addVar ( 'key_Pause',  document.getElementById ( 'galPause_<?= $this->field->ID ?>' ).value );
						j.addVar ( 'key_Height', document.getElementById ( 'galHeight_<?= $this->field->ID ?>' ).value );
						j.addVar ( 'key_Heading', document.getElementById ( 'galHeading_<?= $this->field->ID ?>' ).value );
						j.addVar ( 'key_SortMode', document.getElementById ( 'galSortMode_<?= $this->field->ID ?>' ).value );
						j.addVar ( 'key_ShowStyle', document.getElementById ( 'galShowStyle_<?= $this->field->ID ?>' ).value );
						j.addVar ( 'key_Speed', document.getElementById ( 'galSpeed_<?= $this->field->ID ?>' ).value );
						j.onload = function ()
						{
							document.getElementById ( 'ImageList_<?= $this->field->ID ?>' ).innerHTML = this.getResponseText ();
						}
						j.send();
					}
					);
				</script>
			</div>
			<div class="Spacer"></div>
