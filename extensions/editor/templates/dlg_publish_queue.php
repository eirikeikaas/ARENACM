
	<div id="PublishQueueWindow">
		<h1>
			Publiseringskø for: <?= $this->content->MenuTitle ?>
		</h1>
		<div class="Container">
			<p>
				Noen tilknyttede elementer er ikke publiserte. Hvis du ønsker å publisere disse
				nå, merker du dem av i listen under. Når du er ferdig kan du klikke på "Publiser"
				knappen for å publisere siden.
			</p>
			<div id="PublishList">
				<?= $this->list ?>
			</div>
		</div>
		<div class="SpacerSmallColored"></div>
		<button type="button" onclick="removeModalDialogue ( 'publishqueue' )">
			<img src="admin/gfx/icons/cancel.png"> Lukk
		</button>
		<button type="button" onclick="publishPageElements ( )">
			<img src="admin/gfx/icons/page_go.png"> Publiser
		</button>
	</div>
	<script>
		resizeModalDialogue ( 'publishqueue', false, getElementHeight ( document.getElementById ( 'PublishQueueWindow' ) ) + 24 );
	</script>
