
<h2 class="BlockHead">
	Innstillinger for nyhetsmodulen på "<?= $this->content->Title ?>"
</h2>
<div class="BlockContainer">
	<p>
		<strong>
			Velg nyhetskategorier:
		</strong>
	</p>
	<p>
		<select name="NewsCategories[]" style="width: 100%;" size="10" multiple="multiple">
			<?= $this->NewsCategories ?>
		</select>
	</p>
	<p>
		<strong>Antall nyheter pr. side:</strong>
	</p>
	<p>
		<input type="text" name="PrPage" size="5" value="<?= $this->PrPage ? $this->PrPage : "0" ?>" />
	</p>
	<p>
		<strong>Kommentarer:</strong>
	</p>
	<p>
		<select name="Comments">
			<?= $this->CommentOptions ?>
		</select>
	</p>
	<p>
		<strong>Datoformat på kommentarer:</strong>
	</p>
	<p>
		<input type="text" name="CommentDateFormat" size="30" value="<?= $this->CommentDateFormat ?>" />
	</p>
</div>
	
