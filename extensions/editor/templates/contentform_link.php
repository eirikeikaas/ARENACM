
					<h4>
						Lenke
					</h4>
					<input id="LinkText" type="text" value="<?= str_replace ( '"', '&quot;', stripslashes ( $this->page->Link ) ) ?>" style="-moz-box-sizing: border-box; box-sizing: border-box; width: 100%">
					<div class="Spacer"></div>
					<?= $this->extrafields ?>
