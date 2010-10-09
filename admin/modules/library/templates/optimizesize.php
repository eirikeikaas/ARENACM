

	<form name="OptimizeSizeForm" method="post">
		
		<input type="hidden" name="FolderID" value="<?= $this->folder->ID ?>"/>
		<input type="hidden" name="action" value="optimizesize"/>
		<input type="hidden" name="module" value="library"/>
		
		<h1>
			Optimaliser bildene i <?= $this->folder->Name ?>
		</h1>
		<div class="SubContainer">
			<p>
				Dette verktøyet lar deg spare plass på systemet, og gjøre reskaleringer
				av store bilder litt raskere. Dette verktøyet er spesiellt anvendbart for 
				deg som laster opp store bilder fra digitalkamera.
			</p>
		</div>
		<div class="SpacerSmall"></div>
		<div class="SubContainer">
			<p>
				Velg en oppløsning under som du ønsker å reskalere orginalbildene til. Bilder som
				er mindre enn ønsket størrelse blir hoppet over. <strong>Bildene vil alltid beholde aspekt</strong>.
			</p>
			<p>
				<input type="radio" checked="checked" name="OptimizeSize" value="1024x768"/> 1024x768
			</p>
			<p>
				<input type="radio" name="OptimizeSize" value="800x600"/> 800x600
			</p>
			<p>
				<input type="radio" name="OptimizeSize" value="640x480"/> 640x480
			</p>
		</div>
		<div class="SpacerSmall"></div>
		<div class="SubContainer">
			<p>
				Velg en kvalitetsprosent fra 50%-100% (har å gjøre med hvor mye filstørrelsen
				skal forminskes på bekostning av bildekvalitet).
			</p>
			<p>
				<select name="OptimizeQuality">
					<?
						for ( $a = 100; $a >= 50; $a -= 10 )
						{
							$ostr .= '<option value="' . $a . '">' . $a . '</option>';
						}
						return $ostr;
					?>
				</select>
			</p>
		</div>
		<div class="SpacerSmall"></div>
		<button type="button" onclick="if ( confirm ( 'Er du sikker?' ) ){ document.OptimizeSizeForm.submit ( ); }">
			<img src="admin/gfx/icons/image.png"/> Start optimaliseringen
		</button>
		<button type="button" onclick="removeModalDialogue ( 'optimizesize' )">
			<img src="admin/gfx/icons/cancel.png"/> Lukk vinduet
		</button>
	</form>


