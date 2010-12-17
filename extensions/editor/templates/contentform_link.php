
					
					<div class="SpacerSmall"></div>
					<table class="LayoutColumns">
						<tr>
							<td width="110px">
								<h4><?= i18n ( 'Link url' ) ?>:</h4>
							</td>
							<td>
								<input id="LinkText" type="text" value="<?= str_replace ( '"', '&quot;', stripslashes ( $this->page->Link ) ) ?>" style="-moz-box-sizing: border-box; box-sizing: border-box; width: 100%">
							</td>
						</tr>
						<tr>
							<td><h4><?= i18n ( 'Link target' ) ?>:</h4></td>
							<td>
								<select id="LinkTarget">
									<?
										$str = '';
										foreach ( array ( '_self'=>'Samme vindu', '_blank'=>'Nytt vindu' ) as $m=>$l )
										{
											$s = ( $this->linkData->LinkTarget == $m ? ' selected="selected"' : '' );
											$str .= '<option value="' . $m . '"'. $s .'>' . $l . '</option>';
										}
										return $str;
									?>
								</select>
							</td>
						</tr>
					</table>
					<div class="SpacerSmallColored"></div>
					<?= $this->extrafields ?>
