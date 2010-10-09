
	<div class="Container<?= $this->data->IsEvent ? " Event" : "" ?>">
		<div class="NewsRightSide">
			<table>
				<?if ( $this->data->IsEvent ) { ?>
				<tr>
					<td colspan="2">
						<h2>
							Datostyring:
						</h2>
						<h4>Periode vist på nettsiden</h4>
					</td>
				</tr>
				<tr>
					<td>
						<label>Fra:</label>
					</td>
					<td>
						<span class="Weak"><?= ArenaDate ( DATE_FORMAT, $this->data->DateFrom ) ?></span>
					</td>
					<td>
						<label>Til:</label>
					</td>
					<td>
						<span class="Weak"><?= ArenaDate ( DATE_FORMAT, $this->data->DateTo ) ?></span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div class="Spacer"></div>
						<h4>Aktuell dato</h4>
					</td>
				</tr>
				<?}?>		
				<?if ( !$this->data->IsEvent ) { ?>
				<tr>
					<td colspan="2">	
						<h2>Dato:</h2>
					</td>
				</tr>
				<?}?>
				<tr>
					<td>
						<label>Aktuell:</label>
					</td>
					<td>
						<?= ArenaDate ( DATE_FORMAT, $this->data->DateActual ) ?> <small style="color: #a00">vist dato</small>
					</td>
				</tr>
				<tr>
					<td>
						<label>Endret:</label>
					</td>
					<td>
						<span class="Weak"><?= ArenaDate ( DATE_FORMAT, $this->data->DateModified ) ?></span>
					</td>
				</tr>
				<tr>
					<td>
						<label>Opprettet:</label>
					</td>
					<td>
						<span class="Weak"><?= ArenaDate ( DATE_FORMAT, $this->data->DateCreated ) ?></span>
					</td>
				</tr>
				<tr>
					<td>
						<label>Publisering:</label>
					</td>
					<td>
						<span class="Weak"><?= $this->data->IsPublished ? "Publisert" : "Arkivert" ?></span>
					</td>
				</tr>
			</table>
		</div>
		<div class="NewsLeftSide">
			<h2><?= $this->data->Title ?></h2>
			<p>
				<small>Kategori: <strong><?= $this->category->Name ?></strong> Type: <strong><?= $this->data->IsEvent ? "Hendelse" : "Nyhet" ?></strong></small>
			</p>
			<?= preg_replace ( "/<a([^>]+)>/", '', $this->data->Intro ) ?>
		</div>
		<br style="clear: both" />
		<div class="Spacer"></div>
		<?if ( $this->canEdit ) { ?>
		<button type="button" onclick="<?= $this->data->IsEvent ? 'editEvent' : 'editNews' ?> ( '<?= $this->data->ID ?>' )">
			<img src="admin/gfx/icons/page_edit.png" /> Endre <?= $this->data->IsEvent ? 'hendelsen' : 'nyheten' ?>
		</button>
		<button type="button" onclick="deleteNews ( '<?= $this->data->ID ?>' )">
			<img src="admin/gfx/icons/page_delete.png" /> Slett
		</button>
		<?}?>
		<button type="button" onclick="addToWorkbench ( '<?= $this->data->ID ?>', 'News' )">
			<img src="admin/gfx/icons/plugin.png" />
		</button>
	</div>
	<?if ( $this->Spacer ) { ?>
	<div class="Spacer"></div>
	<?}?>
