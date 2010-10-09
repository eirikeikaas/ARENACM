
										<h2 class="BlockHead"><?= $this->content->IsPublished ? '<span style="color: #090">Siden er nå satt som synlig</a>' : '<span style="color: #900">Siden er nå satt som usynlig</span>' ?></h2>
											<div class="BlockContainer" style="height: 80px">
												<p>
													For at endringene skal synes på nettsidene må du publisere.
												</p>
												<input name="IsPublished" id="ContentIsPublished" type="hidden" value="<?= $this->content->IsPublished ?>"/>
												<p>
												<?
													if ( !$this->content->IsPublished ) 
													{ 
														return "
															<button type=\"button\" onclick=\"document.getElementById ( 'ContentIsPublished' ).value = '1'; submit ( )\"/> <img src=\"admin/gfx/icons/page_go.png\"/> Gjør siden synlig</button>
														";
													}
													else
													{
														return "
															<button type=\"button\" onclick=\"document.getElementById ( 'ContentIsPublished' ).value = '0'; submit ( )\"/> <img src=\"admin/gfx/icons/page_go.png\"/> Gjør siden usynlig</button>
														";
													}
												?>
												</p>
											</div>

