
					<?if ( $this->Prev ) { ?>
					<button type="button" onclick="document.location='<?= $this->Target ?><?= $this->obj->PositionVariable ?>=<?= $this->Position - $this->Limit ?><?= $this->ExtraUrlData ?>'">
						<img src="admin/gfx/icons/arrow_left.png" /> <?= i18n ( 'Previous page' ) ?>
					</button>
					<?}?>
					
					<?if ( $this->Select ) { ?>
					<select onchange="document.location = '<?= $this->Target ?><?= $this->obj->PositionVariable ?>=' + this.value<?= $this->ExtraUrlData ? ( " + '" . $this->ExtraUrlData . "'" ) : '' ?>">
						<?= $this->Select ?>
					</select>
					<?}?>
					
					<?if ( $this->Next ) { ?>
					<button type="button" onclick="document.location='<?= $this->Target ?><?= $this->obj->PositionVariable ?>=<?= $this->Position + $this->Limit ?><?= $this->ExtraUrlData ?>'">
						<?= i18n ( 'Next page' ) ?> <img src="admin/gfx/icons/arrow_right.png" />
					</button>
					<?}?>
					
					<?if ( $this->PageCount > 1 ) { ?>
					<div class="SubContainer" style="display: inline">
						<?= i18n ( 'Showing page' ) ?> <?= $this->CurrentPage ?> <?= i18n ( 'of' ) ?> <?= $this->PageCount ?>
					<?}?>
					
						<?if ( $this->ShowCount ) { ?>
						<div style="display: inline">
							<?= ( $this->PageCount <= 1 ? ( i18n ( 'Showing' ) . ' ' . $this->Count . ' ' . ( i18n ( 'elements' ) . '.' ) ) :  
								( i18n ( 'with' ) . ' ' . ( $this->Count ? ( $this->Count . ' ' ) : '' ) . i18n ( 'elements total' ) . '.' ) ) ?>
						</div>
						<?}?>
						
					<?if ( $this->PageCount > 1 ) { ?>
					</div>
					<?}?>
