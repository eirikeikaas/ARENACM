
	<div class="Block TipAFriend">
		<h2>
			Tips en venn!
		</h2> 
		
		<p>
			Tips en venn om siden:<br/>
			&nbsp;&nbsp;"<?= $this->content->MenuTitle ?>"
		</p>
	
		<p>
			Ditt navn:
		</p>
		<p>
			<input type="text" value="" size="45" id="mod_tipafriend_name"/>
		</p>
		<p>
			Skriv inn e-post adresse til mottaker:
		</p>
		<p>
			<input type="text" value="" size="45" id="mod_tipafriend_email"/>
		</p>
		<p>
			Skriv en beskjed:
		</p>
		<p>
			<textarea rows="5" cols="46" id="mod_tipafriend_message"></textarea>
		</p>
		<p>
			<button type="button" onclick="mod_tipafriend_send ( )">
				Send tipset
			</button>
			<button type="button" onclick="closeStyledDialog ( )">Lukk vinduet</button>
		</p>
	</div>


