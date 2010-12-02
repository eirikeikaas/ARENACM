<?= enableTextEditor ( ) ?>
<?
	// Get current date for use by stats
	if ( $_GET[ "date" ] )
		$_SESSION[ "settingsStatsCurDate" ] = $_GET[ "date" ];
	if ( !$_SESSION[ "settingsStatsCurDate" ] )
		$_SESSION[ "settingsStatsCurDate" ] = date ( "Y-m-d" );	
?>
		
		<script type="text/javascript" src="admin/modules/settings/javascript/main.js"></script>
		
		<div class="ModuleContainer">
			
			<table width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td width="*" valign="top">
					
						<h1>
							Dine innstillinger<?= $this->user->_dataSource == 'core' ? ' (du er logget inn som superbruker!)' : '' ?>
						</h1>
					
						<form method="post" action="admin.php?module=settings&action=savesettings" name="sform">
							<div id="arenasettingtabs">
								<div class="tab" id="tabARENASettingsPlain">
									<img src="admin/gfx/icons/user_green.png"/> Personlige innstillinger
								</div>
								<div class="tab" id="tabARENASettingsAdvanced">
									<img src="admin/gfx/icons/wrench.png"/> Avansert
								</div>
								<div class="page" id="pageARENASettingsPlain">
									<div class="SubContainer">
										<h2>
											Nettside hovedtittel
										</h2>
										<p>
											<input type="text" size="100" name="SiteTitle" value="<?= 
												SITE_TITLE
											?>"/>
										</p>
										<h2>
											Din brukerkonto:
										</h2>
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tr>
												<td>
													<strong>Ditt navn:</strong>
												</td>
												<td>
													<input type="text" size="30" name="Name" value="<?= $this->user->Name ?>"/>
												</td>
											</tr>
											<tr>
												<td>
													<strong>E-mail:</strong>
												</td>
												<td>
													<input type="text" size="30" name="Email" value="<?= $this->user->Email ?>"/>
												</td>
											</tr>
											<tr>
												<td>
													<strong>Bytt passord:</strong>
												</td>
												<td>
													<input type="password" size="30" name="Password" value="********"/>
												</td>
											</tr>
											<tr>
												<td>
													<strong>Gjenta passord:</strong>
												</td>
												<td>
													<input type="password" size="30" name="Password_Confirm" value="********"/>
												</td>
											</tr>
										</table>
										<div class="Spacer"></div>
										<?if ( file_exists ( 'extensions/footer' ) ) { ?>
										<h2>
											Bunntekst på nettstedet
										</h2>
										<p>
											<input type="text" size="100" name="Footertext" value="<?= 
												GetSettingValue ( 'settings', 'FooterText' ) 
											?>"/>
										</p>
										<div class="SpacerSmall"></div>
										<?}?>
									</div>
								</div>
								<div class="page" id="pageARENASettingsAdvanced">
									<table cellspacing="0" cellpadding="0">
										<tr>
											<td valign="top" width="50%" style="padding-right: 8px">
												<h1>Mail innstillinger</h1>
												<div class="SubContainer">									
													<table class="Layout">
														<tr>
															<td width="160px"><strong>Mail server (SMTP):</strong></td>
															<td><input type="text" size="30" name="Email_SMTP" value="<?= 
																defined ( 'MAIL_SMTP_HOST' ) ? MAIL_SMTP_HOST : '' ?>"/></td>
														</tr>
														<tr>
															<td><strong>Mail server brukernavn:</strong></td>
															<td><input type="text" size="30" name="Email_Username" value="<?= 
																defined ( 'MAIL_USERNAME' ) ? MAIL_USERNAME : '' ?>"/></td>
														</tr>
														<tr>
															<td><strong>Mail server passord:</strong></td>
															<td><input type="text" size="30" name="Email_Password" value="<?= 
																defined ( 'MAIL_PASSWORD' ) ? MAIL_PASSWORD : '' ?>"/></td>
														</tr>
														<tr>
															<td><strong>Mail svar-adresse:</strong></td>
															<td><input type="text" size="30" name="Email_ReplyTo" value="<?= 
																defined ( 'MAIL_REPLYTO' ) ? MAIL_REPLYTO : '' ?>"/></td>
														</tr>
														<tr>
															<td><strong>(Mail avsendernavn:)</strong></td>
															<td><input type="text" size="30" name="Email_FromName" value="<?= 
																defined ( 'MAIL_FROMNAME' ) ? MAIL_FROMNAME : '' ?>"/></td>
														</tr>
													</table>
												</div>
												<div class="SpacerSmallColored"></div>
												<h1>Innholdsinnstillinger</h1>
												<div class="SubContainer">
													<table class="Layout">
														<tr>
															<td width="160px"><strong>Innholdsgruppe for meny:</strong></td>
															<td><select name="MenuContentGroup"><?
																$db =& dbObject::globalValue ( 'database' );
																if ( $groups = $db->fetchObjectRows ( 'SELECT ContentGroups AS Gs FROM ContentElement WHERE ID!=MainID' ) )
																{
																	$uniqueGroups = array ();
																	foreach ( $groups as $g )
																	{
																		$cgroups = explode ( ',', $g->Gs );
																		foreach ( $cgroups as $cg )
																		{
																			if ( !trim ( $cg ) ) continue;
																			if ( !in_array ( trim ( $cg ), $uniqueGroups ) )
																				$uniqueGroups[] = trim ( $cg );
																		}
																	}
																	$str = '<option value="">Bruk standard innstilling</option>';
																	foreach ( $uniqueGroups as $g )
																	{
																		if ( defined ( 'TOPMENU_CONTENTGROUP' ) && $g == TOPMENU_CONTENTGROUP )
																			$s = ' selected="selected"';
																		else $s = '';
																		$str .= '<option value="' . $g . '"' . $s . '>' . $g . '</option>';
																	}
																	return $str;
																}
															?></select></td>
														</tr>
														<tr>
															<td width="160px"><strong>Utlistede nivåer for meny:</strong></td>
															<td><select name="MenuLevels"><?
																$options = array ( '0','1','2','3','4','5','10','20','40','99999' );
																$str = '';
																foreach ( $options as $opt )
																{
																	if ( $opt == 'ALL' ) $key = 'Standard';
																	else $key = $opt;
																	$s = $opt == NAVIGATION_LEVELS ? ' selected="selected"' : '';
																	$str .= '<option value="' . $opt . '"' . $s . '>' . $key . '</option>';
																}
																return $str;
															?></select></td>
														</tr>
														<tr>
															<td width="160px"><strong>Utlistingsmetode for meny:</strong></td>
															<td><select name="MenuMode"><?
																$options = array ( 'FOLLOW'=>'Ett undernivå', 'ALL'=>'Vis alle' );
																$str = '';
																foreach ( $options as $value=>$key )
																{
																	$s = $value == NAVIGATION_MODE ? ' selected="selected"' : '';
																	$str .= '<option value="' . $value . '"' . $s . '>' . $key . '</option>';
																}
																return $str;
															?></select></td>
														</tr>
														<tr>
															<td width="160px"><strong>Hoved innholdsgruppe:</strong></td>
															<td><select name="MainContentGroup"><?
																$db =& dbObject::globalValue ( 'database' );
																if ( $groups = $db->fetchObjectRows ( 'SELECT ContentGroups AS Gs FROM ContentElement WHERE ID!=MainID' ) )
																{
																	$uniqueGroups = array ();
																	foreach ( $groups as $g )
																	{
																		$cgroups = explode ( ',', $g->Gs );
																		foreach ( $cgroups as $cg )
																		{
																			if ( !trim ( $cg ) ) continue;
																			if ( !in_array ( trim ( $cg ), $uniqueGroups ) )
																				$uniqueGroups[] = trim ( $cg );
																		}
																	}
																	$str = '<option value="">Bruk standard innstilling</option>';
																	foreach ( $uniqueGroups as $g )
																	{
																		if ( defined ( 'MAIN_CONTENTGROUP' ) && $g == MAIN_CONTENTGROUP )
																			$s = ' selected="selected"';
																		else $s = '';
																		$str .= '<option value="' . $g . '"' . $s . '>' . $g . '</option>';
																	}
																	return $str;
																}
															?></select></td>
														</tr>
													</table>
												</div>
												<!--<div class="SpacerSmallColored"></div>
												<h1>
													ARENA Admin Innstilinger
												</h1>
												<div class="SubContainer">
													<table>
														<tr>
															<td>
																Bruke ressursvennlig ARENA tema?
															</td>
															<td>
																<input id="arenasetting_ResourceFriendlyCSS" type="checkbox"<?= 
																	GetSettingValue ( 'ARENA_Usersettings_' . $GLOBALS[ 'Session' ]->AdminUser->ID, 'ResourceFriendlyCSS' ) ? ' checked="checked"': '' ?>/>
															</td>
														</tr>
													</table>
													<div class="Spacer"></div>
													<p>
														<button type="button" onclick="saveArenaSettings()">
															<img src="admin/gfx/icons/database_save.png"/> Lagre ARENA ADMIN innstillingene
														</button>
													</p>
												</div>-->
											<td>
											<td valign="top">
												<h1>
													Fallback innstillinger
												</h1>
												<div class="SubContainer">
													<table>
														<tr>
															<td>
																Standard datoformat:
															</td>
															<td>
																<input type="text" name="Date_Format" value="<?= defined ( 'DATE_FORMAT' ) ? DATE_FORMAT : 'Y-m-d H:i:s' ?>"/> (ex: Y-m-d H:i:s)
															</td>
														</tr>
													</table>
												</div>
												<h1>
													Språkinnstillinger
												</h1>
												<div class="SubContainer">
													<select name="Admin_Language">
														<?
															$langs = array ( 'no'=>'Norsk', 'en'=>'English' );
															foreach ( $langs as $lang=>$name )
															{
																$s = $lang == ADMIN_LANGUAGE ? ' selected="selected"' : '';
																$str .= '<option value="' . $lang . '"'.$s.'>' . $name . '</option>';
															}
															return $str;
														?>
													</select>
												</div>
											</td>
										</tr>
									</table>
								</div>
							</div>
							<p>
								<button type="button" onclick="verifySettingsForm()">
									<img src="admin/gfx/icons/disk.png"/> Lagre innstillingene
								</button>
							</p>
						</form>
						<?if ( file_exists ( BASE_DIR . '/' . $this->Settings->webalizer_path . '/daily_usage_' . date ( 'Ym' ) . '.png' ) ) { ?>
						<div class="SpacerSmallColored"></div>
						<h1>
							Daglig aktivitet denne måneden (Webalizer):
						</h1>
						<div class="Container">
							<?
								$file = BASE_DIR . '/' . $this->Settings->webalizer_path . '/daily_usage_' . date ( 'Ym' ) . '.png';
								if ( file_exists ( $file ) )
								{
									list ( $w, $h, ) = getimagesize ( $file );
									copy ( $file, BASE_DIR . '/upload/daily_usage_' . date ( 'Ym' ) . '.png' );
									return '<img src="' . BASE_URL . 'upload/daily_usage_' . date ( 'Ym' ) . '.png' . '" width="' . $w . '" height="' . $h . '" border="0" />';
								}
							?>
						</div>
						<?}?>
					</td>
				</tr>
			</table>
		</div>
		
		<br style="clear: both" />

		<script type="text/javascript">
			initTabSystem ( 'arenasettingtabs' );
		</script>
	
