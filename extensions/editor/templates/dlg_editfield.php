
	<h1 style="overflow: hidden; white-space: nowrap">
		<div style="float: right">
			<button type="button" onclick="executeEditField ( )" title="Lagre">
				<img src="admin/gfx/icons/accept.png">
			</button>
			<button type="button" onclick="removeModalDialogue ( 'editfield' )" title="Lukk">
				<img src="admin/gfx/icons/cancel.png">
			</button>
		</div>
		Endre felt i: <?= $this->content->MenuTitle ?>
	</h1>
	<div class="Container" style="padding: <?= MarginSize ?>px">
		<form id="diaform" action="#" method="get">
			<input type="hidden" name="field_id" value="<?= $this->field->ID ?>">
			<input type="hidden" name="field_type" value="<?= $this->fieldTable ?>">
			<table class="LayoutColumns">
				<tr>
					<td>
						<p>
							Endre feltnavn:
						</p>
					</td>
					<td style="padding-left: 2px">
						<p>
							Full visning:
						</p>
					</td>
					<td style="padding-left: 2px">
						<p>
							Sortering:
						</p>
					</td>
				</tr>
				<tr>
					<td>
						<div class="SubContainer" style="padding: <?= MarginSize ?>px; height: 23px">
							<input type="text" value="<?= $this->field->Name ?>" name="Name" size="25">
						</div>
					</td>
					<td style="padding-left: 2px; text-align: center">
						<div class="SubContainer" style="padding: <?= MarginSize ?>px; height: 23px">
							<input type="checkbox"<?= $this->field->AdminVisibility ? ' checked="checked"' : '' ?> id="fieldadminvisibility">
						</div>
					</td>
					<td style="padding-left: 2px">
						<div class="SubContainer" style="padding: <?= MarginSize ?>px; height: 23px">
							<input type="text" value="<?= $this->field->SortOrder ?>" name="SortOrder" size="3" style="text-align: center">
						</div>
					</td>
				</tr>
			</table>
			<div class="SpacerSmallColored"></div>
			<div class="Spacer"></div>
			<p>
				Hvilken innholdsgruppe ønsker du å legge til feltet i?
			</p>
			<div class="Utvidelse: SubContainer">
				<?= $this->contentgroups ?>
			</div>
			<div class="SpacerSmallColored"></div>
			<div class="Spacer"></div>
			<?if ( in_array ( $this->field->Type, Array ( 'whitespace', 'text', 'varchar', 'leadin', 'extension', 'objectconnection', 'script', 'style' ) ) ) { ?>
			<p>
				Av hvilken type skal feltet være?
			</p>
			<div class="SubContainer" style="padding: <?= MarginSize ?>px">
				<table width="100%">
					<tr>
						<td width="12px">
							<input type="radio" name="type" value="text"<?= $this->field->Type == 'text' ? ' checked="checked"' : '' ?>
						</td>
						<td>
							Et fullt tekst felt med alle redigerings-muligheter
						</td>
					</tr>
					<tr>
						<td width="12px">
							<input type="radio" name="type" value="varchar"<?= $this->field->Type == 'varchar' ? ' checked="checked"' : '' ?>
						</td>
						<td>
							Et enkelt tekst felt for ren tekst
						</td>
					</tr>
					<tr>
						<td width="12px">
							<input type="radio" name="type" value="leadin"<?= $this->field->Type == 'leadin' ? ' checked="checked"' : '' ?>
						</td>
						<td>
							Et lite tekst felt med alle redigerings-muligheter
						</td>
					</tr>
					<tr>
						<td width="12px">
							<input type="radio" name="type" value="whitespace"<?= $this->field->Type == 'whitespace' ? ' checked="checked"' : '' ?>
						</td>
						<td>
							Et stilbart mellomrom
						</td>
					</tr>
					<?
						$checkja = $this->field->Type == 'script' ? ' checked="checked"' : '';
						$checksty = $this->field->Type == 'style' ? ' checked="checked"' : '';
						$checkob = $this->field->Type == 'objectconnection' ? ' checked="checked"' : '';
						if ( $GLOBALS[ 'user' ]->_dataSource == 'core' ) 
						{
							return <<<EOL
					<tr>
						<td width="12px">
							<input type="radio" name="type" value="script"{$checkja}>
						</td>
						<td>
							Javascript felt
						</td>
					</tr>
					<tr>
						<td width="12px">
							<input type="radio" name="type" value="style"{$checksty}>
						</td>
						<td>
							Et stilark felt
						</td>
					</tr>
					<tr>
						<td width="12px">
							<input type="radio" name="type" value="objectconnection"{$checkob}>
						</td>
						<td>
							Et objekttilkoblingsfelt
						</td>
					</tr>
EOL;
						}
					?>
					<?	
						$opts = false;
						if ( $dir = opendir ( 'extensions' ) )
						{
							$opts = '';
							while ( $f = readdir ( $dir ) )
							{
								if ( $f{0} == '.' ) continue;
								if ( file_exists ( 'extensions/' . $f . '/websnippet.php' ) )
								{
									if ( $f == $this->field->DataString )
										$s = ' selected="selected"';
									else $s = '';
									$opts .= '<option value="' . $f . '"' . $s . '>' . strtoupper ( $f{0} ) . substr ( $f, 1, strlen ( $f ) - 1 ) . '</option>';
								}
							}
							closedir ( $dir );
							
						}
						if ( $opts )
						{
							return '
					<tr>
						<td width="12px">
							<input type="radio" name="type" value="extension"' . ( $this->field->Type == 'extension' ? ' checked="checked"' : '' ) . '">
						</td>
						<td>
							Utvidelse: <select name="fieldextension">
								' . $opts . '
							</select>
						</td>
					</tr>
							';
						}
						
					?>
				</table>
			</div>
			<?}?>
			<?if ( !in_array ( $this->field->Type, Array ( 'text', 'varchar', 'leadin', 'extension' ) ) ) { ?>
			<p>
				Du endrer på et spesialfelt (<? 
					$string = $this->field->DataString;
					return trim ( $string ? $string : 'ukjent type' );
				?>):
			</p>
			<div class="SubContainer" style="padding: <?= MarginSize ?>px">
				<p>
					<?
						if ( $this->field->Type == 'contentmodule' )
						{
							return 'Du endrer nå på en innholds modul. Du kan ikke endre modul typen.';
						}
						else
						{	
							return 'Dette feltet er av en spesifisert type. På grunn av dette kan du ikke endre felt type.';
						}
					?>
				</p>
			</div>
			<?}?>
			<?if ( $GLOBALS[ 'Session' ]->AdminUser->isSuperUser ( ) ) { ?>
			<div class="Spacer"></div>
			<p>
				Skal feltet vise på alle sidene?
			</p>
			<div class="SubContainer" style="padding: <?= MarginSize ?>px">
				<table width="100%">
					<tr>
						<td width="12px">
							<input type="radio" name="global" value="0"<?= !$this->field->IsGlobal ? ' checked="checked"' : '' ?>
						</td>
						<td>
							Nei, bare vis feltet på denne siden
						</td>
					</tr>
					<tr>
						<td width="12px">
							<input type="radio" name="global" value="1"<?= $this->field->IsGlobal ? ' checked="checked"' : '' ?>>
						</td>
						<td>
							Ja, vis feltet på alle sidene
						</td>
					</tr>
				</table>
			</div>
			<?}?>
			<?if ( !$GLOBALS[ 'Session' ]->AdminUser->isSuperUser ( ) ) { ?>
			<div class="Spacer"></div>
			<p>
				Visibilitet:
			</p>
			<div class="SubContainer">
				Dette feltet viser <?= $this->field->IsGlobal ? 'på alle sidene' : 'kun på denne siden' ?>.
			</div>
			<?}?>
		</form>
	</div>
	<div class="SpacerSmallColored"></div>
	<button type="button" onclick="executeEditField ( )">
		<img src="admin/gfx/icons/accept.png"> Endre feltet
	</button>
	<button type="button" onclick="removeModalDialogue ( 'editfield' )">
		<img src="admin/gfx/icons/cancel.png"> Avbryt	
	</button>
