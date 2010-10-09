
	<?= $this->texteditor ?>
	<h3>
		<?= $this->heading ?>
	</h3>
	<div class="Block">
		<table>
			<tr>
				<td><label><?= i18n ( 'Blog title' ) ?>:</label></td>
				<td><input type="text" value="" id="blog_title" size="35"/></td>
			</tr>
			<tr>
				<td><label><?= i18n ( 'Your name' ) ?>:</label></td>
				<td><input type="text" value="" id="blog_author_name" size="35"/></td>
			</tr>
			<tr>
				<td><label><?= i18n ( 'Leadin' ) ?>:</label></td>
				<td><div class="Editor"><textarea id="blog_leadin" class="mceSelector" style="height: 100px" rows="5" cols="35"></textarea></div></td>
			</tr>
			<tr>
				<td><label><?= i18n ( 'Full article' ) ?>:</label></td>
				<td><div class="Editor"><textarea id="blog_article" class="mceSelector" style="height: 250px" rows="20" cols="35"></textarea></div></td>
			</tr>
			<tr>
				<td></td>
				<td><p>
					<button type="button" onclick="mod_blog_tip_send()">
						<?= i18n ( 'Send us your tip' ) ?>
					</button>
				</p></td>
			</tr>
		</table>
	</div>
	
