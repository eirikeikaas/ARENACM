	
	
	<h1>Slett kategori "<?= $this->cat->Name; ?>"</h1>
		<div class="Container">
			
				<div  class="SubContainer">
				<p><b>Er du sikkert at du vil slette denne kategorien?</b></p>
				
				<div id="deleteCatMsg">
				<?if( $this->catscount > 0 || $this->contcount > 0 ){ ?>
					<p class="error">Denne kategori innholder i minst <? return ( $this->catscount + $this->contcount ); ?> element<?= ( $this->catscount + $this->contcount ) != 1 ? 'er' : ''?>!</p>
				<?}?>
				</div>
				<form id="deleteLevelForm" action="admin.php?module=news&action=deletecategory&deletestep=2&cid=<?= $this->cat->ID; ?>" method="POST">
						
						<span>Hva skal skje med innholdet i kategorien?</span><br />
						<input type="hidden" name="newsMoveContent" id="newsMoveContent" value="<?= ( ( $this->catscount + $this->contcount ) < 1 ? 'delete' : '' )?>" />
						
						<input id="idldl" onclick="document.getElementById( 'newsMoveContent' ).value = 'delete'" type="radio" name="movecontent" value="delete" <?= ( ( $this->catscount + $this->contcount ) < 1 ? ' checked="checked"' : '' )?>/> <label for="idldl"> Slett innhold</label><br />
						<input id="iddlm" onclick="document.getElementById( 'newsMoveContent' ).value = 'move'" type="radio" name="movecontent" value="move" /> <label for="iddlm"> Flytt innhold til mappen</label><br />
						<div class="SpacerSmall"></div>
						<select name="newcontentfolder" size="1" class="w200" onchange="document.getElementById( 'iddlm' ).checked = true">
							<?= $this->otherfolders ?>
						</select>		
	  					<div class="SpacerSmall"></div>
					
			  	</form>
				</div>
				<div class="SpacerSmall"></div>
				
				<div class="SubContainer">
					<button type="button" onclick="doDeleteCategory( <?= $this->cat->ID; ?> )">
						<img src="admin/gfx/icons/folder_delete.png" /> Slett
					</button>
					<button type="button" onclick="abortNewsEdit( <?= $this->cat->ID; ?> )">
						<img src="admin/gfx/icons/cancel.png" /> Abort
					</button>
				</div>
				<div class="SpacerSmall"></div>
		</div>