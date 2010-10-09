
								<div id="DefaultData">	
									<?if ( !$GLOBALS[ 'Settings' ]->surpress_intro->Value ) { ?>
									<h2 class="BlockHead">
										<img src="admin/gfx/icons/textfield.png" />	Ingress:
									</h2>
									<div class="ToggleBox">
										<div class="BlockContainer">
											<div class="TextDisplay" id="TextDisplayIntro" style="height: 150px"><?= encodeArenaHTML ( $this->content->Intro ) ?></div>
										</div>
										<div class="BlockContainer">
											<textarea class="mceSelector" rows="8" cols="50" id="Intro" name="Intro" style="width: 100%; height: 150px;"><?= encodeArenaHTML ( $this->content->Intro ) ?></textarea>
											<div class="SpacerSmall"><em></em></div>
											<div id="contentIntroStatus" class="FieldStatus"></div>
									<?}?>
									<?if ( !$GLOBALS[ 'Settings' ]->surpress_intro->Value ) { ?>
											<button type="button" onclick="swapToggleVisibility ( this.parentNode, this.parentNode.sibling ); document.getElementById ( 'TextDisplayIntro' ).innerHTML = document.getElementById ( 'Intro' ).value">
												<img src="admin/gfx/icons/cancel.png" /> Lukk
											</button>
										</div>
									</div>
									<?}?>
									<h2 class="BlockHead">
										<img src="admin/gfx/icons/textfield.png" />	Body:
									</h2>
									<div class="ToggleBox">
										<div class="BlockContainer">
											<div class="TextDisplay" id="TextDisplayBody" style="height: <?= $GLOBALS[ 'Settings' ]->surpress_intro->Value ? '600' : '480' ?>px"><?= encodeArenaHTML ( $this->content->Body ) ?></div>
										</div>
										<div class="BlockContainer">
											<textarea class="mceSelector" rows="<?= $GLOBALS[ 'Settings' ]->surpress_intro->Value ? '60' : '30' ?>" cols="50" id="Body" name="Body" style="position: relative; width: 100%; height: <?= $GLOBALS[ 'Settings' ]->surpress_intro->Value ? '400' : '280' ?>px"><?= encodeArenaHTML ( $this->content->Body ) ?></textarea>
											<div class="SpacerSmall"><em></em></div>
											<div id="contentBodyStatus" class="FieldStatus"></div>
											<button type="button" onclick="swapToggleVisibility ( this.parentNode, this.parentNode.sibling ); document.getElementById ( 'TextDisplayBody' ).innerHTML = document.getElementById ( 'Body' ).value">
												<img src="admin/gfx/icons/cancel.png" /> Lukk
											</button>
											<button type="button" onclick="showPreview ( '<?= $this->content->getUrl ( ) ?>?editmode=1' )">
												<img src="admin/gfx/icons/eye.png"/> Forhåndsvis
											</button>
										</div>
									</div>
								</div>
								<?= $this->extra ?>
