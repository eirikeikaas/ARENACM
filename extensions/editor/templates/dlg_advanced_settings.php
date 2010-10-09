	
	<h1 style="overflow: hidden; white-space: nowrap">
		Avanserte innstillinger for: <?= $this->content->MenuTitle ?>
	</h1>
	<div class="Container" style="padding: <?= MarginSize ?>px">
		<form id="advanced_form" method="post" action="#">
			<table class="LayoutColumns">
				<tr>
					<td>
						<p>
							<strong>Viser siden?</strong>
						</p>
					</td>
					<td>
						<table class="LayoutVCenter">
							<tr>
								<td>Ja</td>
								<td><input type="radio" name="IsPublished" value="1"<?= $this->content->IsPublished ? ' checked="checked"' : '' ?>></td>
								<td>Nei</td>
								<td><input type="radio" name="IsPublished" value="0"<?= !$this->content->IsPublished ? ' checked="checked"' : '' ?>></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<p>
							<strong>Skjult i menyen:</strong>
						</p>
					</td>
					<td>
						<table class="LayoutVCenter">
							<tr>
								<td>Ja</td>
								<td><input type="radio" name="IsSystem" value="1"<?= $this->content->IsSystem ? ' checked="checked"' : '' ?>></td>
								<td>Nei</td>
								<td><input type="radio" name="IsSystem" value="0"<?= !$this->content->IsSystem ? ' checked="checked"' : '' ?>></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<p><strong>Innholdstype:</strong></p>
					</td>
					<td>
						<p>
							<select name="ContentType">
							<?
								$options = '';
								foreach ( Array ( 
									'extensions'=>'Kjører en innholdsmodul',
									'text'=>'Ren tekstside',
									'extrafields'=>'Ekstrafelter',
									'link'=>'Lenke til en annen side'
								) as $k=>$v )
								{
									$options .= '<option value="' . $k . '"' . ( $k == $this->content->ContentType ? ' selected="selected"' : '' ) . '>' . $v . '</option>';
								}
								return $options;
							?>
							</select>
						</p>
					</td>
				</tr>
				<?
					if ( $this->content->ContentType != 'extensions' )
						return;
					$this->content->loadExtraFields ( );
					$config = explode ( "\\n", $this->content->Intro );
					$ext = '';
					foreach ( $config as $cfg )
					{
						list ( $opt, $val ) = explode ( "\\t", $cfg );
						if ( trim ( $opt ) == 'ExtensionName' )
							$ext = trim ( $val );
						if ( trim ( $opt ) == 'ExtensionContentGroup' )
							$grp = trim ( $val );
					}
					if ( $dir = opendir ( 'extensions' ) )
					{
						$ostr = '<option value="">Velg modul</option>';
						while ( $file = readdir ( $dir ) )
						{
							if ( $file{0} == '.' ) continue;
							if ( file_exists ( 'extensions/' . $file . '/webmodule.php' ) )
							{
								list ( $name, ) = explode ( '|', file_get_contents ( 'extensions/' . $file . '/info.csv' ) );
								$s = $ext == $file ? ' selected="selected"' : '';
								$ostr .= '<option value="' . $file . '"' . $s . '>' . $name . '</option>';
							}
						}
						closedir ( $dir );
						$str = '<tr><td><p><strong>Velg innholdsmodul:</strong></p></td><td><p><select name="ModuleName">' . $ostr . '</select></p></td></tr>';
						if ( $groups = explode ( ',', $this->content->ContentGroups ) )
						{
							$ostr = '';
							foreach ( $groups as $group )
							{
								$group = trim ( $group );
								$s = $grp == $group ? ' selected="selected"' : '';
								$ostr .= '<option value="' . $group . '"' . $s . '>' . $group . '</option>';
								foreach ( $this->content as $k=>$v )
								{
									if ( substr ( $k, 0, 7 ) == '_extra_' )
									{
										$fn = substr ( $k, 7, strlen ( $k ) - 7 );
										if ( $this->content->{"_field_$fn"}->ContentGroup == $group )
										{
											$s = $grp == $fn ? ' selected="selected"' : '';
											$ostr .= '<option value="' . $fn . '"' . $s . '>' . $fn . '</option>';
										}
									}
								}
							}
							$str .= '<tr><td><p><strong>Modulplassering:</strong></p></td><td><p>Plasser i <select name="ModuleContentGroup">' . $ostr . '</select></p></td></tr>';
						}
						return $str;
					}
					return;
				?>
				<tr>
					<td>
						<p><strong>Systemnavn:</strong></p>
					</td>
					<td>
						<p>
							<input type="text" name="SystemName" size="30" value="<?= $this->content->SystemName ?>">
						</p>
					</td>
				</tr>
				<tr>
					<td>
						<p><strong>Innholdsgrupper:</strong></p>
					</td>
					<td>
						<p>
							<input type="text" name="ContentGroups" size="30" value="<?= $this->content->ContentGroups ?>">
						</p>
					</td>
				</tr>
				<tr>
					<td>
						<p><strong>Undersider har mal:</strong></p>
					</td>
					<td>
						<p>
							<select name="ContentTemplateID">
								<?
									$db =& dbObject::globalValue ( 'database' );
									if ( $rows = $db->fetchObjectRows ( 'SELECT * FROM ContentElement WHERE IsTemplate AND !IsDeleted AND MainID = ID ORDER BY SortOrder ASC' ) )
									{
										$str .= '<option value="0">Ikke bruk mal</option>';
										foreach ( $rows as $row )
										{
											if ( $row->ID == $this->content->ContentTemplateID )
												$s = ' selected="selected"';
											else $s = '';
											$str .= '<option value="' . $row->ID . '"' . $s . '>' . $row->MenuTitle . '</option>';
										}
										return $str;
									}
									return '<option value="0">Ingen mal er satt opp</option>';
								?>
							</select>
						</p>
					</td>
				</tr>
				<?if ( file_exists ( BASE_DIR . '/templates' ) && is_dir ( BASE_DIR . '/templates' ) ) { ?>
				<tr>
					<td>
						<p><strong>Teknisk mal:</strong></p>
					</td>
					<td>
						<p>
							<select name="Template">
								<?
									$str = '<option value="">Ikke bruk teknisk mal.</option>';
									if ( $dir = opendir ( BASE_DIR . '/templates' ) )
									{
										while ( $file = readdir ( $dir ) )
										{
											if ( $file{0} == '.' ) continue;
											$s = ( $file == $this->content->Template ? ' selected="selected"' : '' );
											$str .= '<option value="' . $file . '"' . $s . '>' . str_replace ( array ( 'page_', '.php' ), '', $file ) . '</option>';
										}
									}
									return $str;
								?>
							</select>
						</p>
					</td>
				</tr>
				<?}?>
			</table>
		</form>
	</div>
	<div class="SpacerSmallColored"></div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<button type="button" onclick="executeAdvanced ( )">
					<img src="admin/gfx/icons/accept.png"> Lagre
				</button>
				<button type="button" onclick="removeModalDialogue ( 'advanced' )">
					<img src="admin/gfx/icons/cancel.png"> Avbryt	
				</button>
			</td>
			<td style="text-align: right">
				<button type="button" onclick="createTemplate ( )">
					<img src="admin/gfx/icons/page_white_star.png"> Lagre som mal
				</button>
			</td>
		</tr>
	</table>
	
