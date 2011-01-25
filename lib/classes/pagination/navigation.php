
					<?if ( $this->Prev ) { ?>
					<button type="button" onclick="document.location='<?= $this->Target ?><?= $this->obj->PositionVariable ?>=<?= $this->Position - $this->Limit ?><?= $this->ExtraUrlData ?>'">
						<img src="admin/gfx/icons/arrow_left.png" /> Forrige side
					</button>
					<?}?>
					
					<?if ( $this->Select ) { ?>
					<select onchange="document.location = '<?= $this->Target ?><?= $this->obj->PositionVariable ?>=' + this.value<?= $this->ExtraUrlData ? ( " + '" . $this->ExtraUrlData . "'" ) : '' ?>">
						<?= $this->Select ?>
					</select>
					<?}?>
					
					<?if ( $this->Next ) { ?>
					<button type="button" onclick="document.location='<?= $this->Target ?><?= $this->obj->PositionVariable ?>=<?= $this->Position + $this->Limit ?><?= $this->ExtraUrlData ?>'">
						Neste side <img src="admin/gfx/icons/arrow_right.png" />
					</button>
					<?}?>
					
					<?if ( $this->PageCount > 1 ) { ?>
					<div class="SubContainer" style="display: inline">
						Viser side <?= $this->CurrentPage ?> av <?= $this->PageCount ?>
					<?}?>
					
						<?if ( $this->ShowCount ) { ?>
						<div style="display: inline">
							med <?= ( $this->Count ? ( $this->Count . ' ' ) : '' ) ?> elementer totalt.
						</div>
						<?}?>
						
					<?if ( $this->PageCount > 1 ) { ?>
					</div>
					<?}?>
