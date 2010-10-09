
					<h4>Introduksjons tekst:</h4>
					<p>
						<textarea id="IntroText" style="height: 100px" class="mceSelector" cols="40" rows="5"><?= encodeArenaHTML ( $this->page->Intro ) ?></textarea>
					</p>
					<h4>Hoved tekst:</h4>
					<p class="BodyText">
						<textarea id="BodyText" style="height: 400px" class="mceSelector" cols="40" rows="20"><?= encodeArenaHTML ( $this->page->Body ) ?></textarea>
					</p>
