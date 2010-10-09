
	<h1 style="overflow: hidden; white-space: nowrap">
		<div style="float: right">
			<button type="button" onclick="executeAddField ( )" title="Lagre">
				<img src="admin/gfx/icons/accept.png"> 
			</button>
			<button type="button" onclick="removeModalDialogue ( 'addfield' )" title="Avbryt">
				<img src="admin/gfx/icons/cancel.png"> 
			</button>
		</div>
		Legg til felt i: <?= $this->content->MenuTitle ?>
	</h1>
	<div class="Container" style="padding: <?= MarginSize ?>px">
		<form id="diaform" action="#" method="get">
			<p>
				Gi feltet et navn:
			</p>
			<div class="SubContainer" style="padding: <?= MarginSize ?>px">
				<input type="text" value="Navnløst" name="Name" size="30">
			</div>
			<div class="SpacerSmallColored"></div>
			<div class="Spacer"></div>
			<p>
				Hvilken innholdsgruppe ønsker du å legge til feltet i?
			</p>
			<div class="SubContainer">
				<?= $this->contentgroups ?>
			</div>
			<div class="SpacerSmallColored"></div>
			<div class="Spacer"></div>
			<p>
				Velg hvilken type felt du ønsker å legge til.
			</p>
			<div class="SubContainer" style="padding: <?= MarginSize ?>px">
				<table width="100%">
					<tr>
						<td width="12px">
							<input type="radio" name="type" value="text" checked="checked">
						</td>
						<td>
							Et fullt tekst felt med alle redigerings-muligheter
						</td>
					</tr>
					<tr>
						<td width="12px">
							<input type="radio" name="type" value="varchar">
						</td>
						<td>
							Et enkelt tekst felt for ren tekst
						</td>
					</tr>
					<tr>
						<td width="12px">
							<input type="radio" name="type" value="leadin">
						</td>
						<td>
							Et lite tekst felt med alle redigerings-muligheter
						</td>
					</tr>
					<tr>
						<td width="12px">
							<input type="radio" name="type" value="whitespace">
						</td>
						<td>
							Et stilbart mellomrom
						</td>
					</tr>
					<?if ( $GLOBALS[ 'user' ]->_dataSource == 'core' ) { ?>
					<tr>
						<td width="12px">
							<input type="radio" name="type" value="script">
						</td>
						<td>
							Javascript felt
						</td>
					</tr>
					<tr>
						<td width="12px">
							<input type="radio" name="type" value="style">
						</td>
						<td>
							Et stilark felt
						</td>
					</tr>
					<tr>
						<td width="12px">
							<input type="radio" name="type" value="objectconnection">
						</td>
						<td>
							Et objekttilkoblingsfelt
						</td>
					</tr>
					<?}?>
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
									$opts .= '<option value="' . $f . '">' . strtoupper ( $f{0} ) . substr ( $f, 1, strlen ( $f ) - 1 ) . '</option>';
								}
							}
							closedir ( $dir );
							
						}
						if ( $opts )
						{
							return '
					<tr>
						<td width="12px">
							<input type="radio" name="type" value="extension">
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
			<?if ( $GLOBALS[ 'Session' ]->AdminUser->isSuperUser ( ) ) { ?>
			<div class="Spacer"></div>
			<p>
				Skal feltet vise på alle sidene?
			</p>
			<div class="SubContainer" style="padding: <?= MarginSize ?>px">
				<table width="100%">
					<tr>
						<td width="12px">
							<input type="radio" name="global" value="0" checked="checked">
						</td>
						<td>
							Nei, bare vis feltet på denne siden
						</td>
					</tr>
					<tr>
						<td width="12px">
							<input type="radio" name="global" value="1">
						</td>
						<td>
							Ja, vis feltet på alle sidene
						</td>
					</tr>
				</table>
			</div>
			<?}?>
		</form>
	</div>
	<div class="SpacerSmallColored"></div>
	<button type="button" onclick="executeAddField ( )">
		<img src="admin/gfx/icons/accept.png"> Legg til feltet
	</button>
	<button type="button" onclick="removeModalDialogue ( 'addfield' )">
		<img src="admin/gfx/icons/cancel.png"> Avbryt	
	</button>
