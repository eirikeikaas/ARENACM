

			<div class="Container" style="padding: <?= MarginSize ?>px">
				<p>
					<strong>Heading:</strong> <input type="text" size="50" value="<?= $this->Heading ?>" id="galHeading_<?= $this->field->ID ?>"/>
				</p>
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<td valign="top" width="*" id="gal_preview">
							<?= $this->preview ?>
						</td>
						<td width="4px">
						</td>
						<td width="200" valign="top">	
							<h1>
								Bildevalg:
							</h1>
							<div class="Container" style="padding: 4px">
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
							<div class="SpacerSmall"></div>
							<div class="Container" id="ImageList_<?= $this->field->ID ?>" style="padding: 4px; overflow: auto">
								<?= $this->folders ?>
							</div>
							<div class="SpacerSmall"></div>
							<h1>
								Velg modus:
							</h1>
							<div class="Container" style="padding: 4px">
								<?
									$modes = array ( 'slideshow'=>'Slideshow', 'gallery'=>'Bildegalleri' );
									$i = 0;
									foreach ( $modes as $mode=>$name )
									{
										if ( ( $i++ == 0 && !$this->currentMode ) || $this->currentMode == $mode )
											$s = ' checked="checked"';
										else $s = '';
										$str .= '<td><input type="radio" name="mode" value="' . $mode . '"' . $s . ' onclick="changeGalMode(this)"/> ' . $name . '</td>';
									}
									return '<table><tr>' . $str . '</tr></table>';
								?>	
							</div>
							<div class="SpacerSmall"></div>
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
									</table>
								</div>
							</div>
						</td>
					</tr>
				</table>
				<script type="text/javascript">
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
