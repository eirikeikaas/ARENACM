
	<?if( $this->folder ){?>
		<h1>Slett nivå "<?= $this->folder->Name; ?>"</h1>
		<div class="Container">
			
				
				<h2>Er du sikker på at du ønsker å slette dette nivået?</h2>
				
				<br />
				
				<form id="deleteLevelForm">
						
					<span>Hvis du skal slette nivået, så vil undernivåer, filer og bilder bli anfektet. Hva ønsker du å gjøre med dette innholdet?</span><br />
					<input type="hidden" name="libraryMoveContent" id="libraryMoveContent" value="delete" />
					<br/>
					
					<table cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td style="padding-right: <?= MarginSize ?>px">
								<label for="idldl">1. Slett innhold</label>
							</td>
							<td>
								<input id="idldl" onclick="document.getElementById( 'libraryMoveContent' ).value = 'delete'" type="radio" name="movecontent" value="delete" checked="checked"/>
							</td>
						</tr>
						<tr>
							<td style="padding-right: <?= MarginSize ?>px">
								<label for="iddlm">2. Flytt innhold til valgt mappe under </label>
							</td>
							<td>
								<input id="iddlm" onclick="document.getElementById( 'libraryMoveContent' ).value = 'move'" type="radio" name="movecontent" value="move" />
							</td>
						</tr>
					</table>
					<br/>
						
					<div class="SpacerSmall"></div>
					
					<p>
						<strong>Velg mappe ved valg 2:</strong>
					</p>
					
					<select name="newcontentfolder" size="1" class="w200" onchange="document.getElementById( 'iddlm' ).checked = true">
						<?= $this->otherfolders ?>
					</select>		
					
					<div class="SpacerSmall"></div>
				
				</form>
			
		</div>
		
		<div class="SpacerSmall"></div>
		
		<div class="Container">
				
				
				<button type="button" onclick="doDeleteLibraryLevelEdit( <?= $this->folder->ID; ?> )">
					<img src="admin/gfx/icons/folder_delete.png" /> Slett
				</button>
				<button type="button" onclick="abortLibraryLevelEdit( <?= $this->folder->ID; ?> )">
					<img src="admin/gfx/icons/cancel.png" /> Abort
				</button>
				
		</div>
	<?}?>
	<?if( !$this->folder ){?>
		<i>Kunne ikke finne mappen.</i>
	<?}?>