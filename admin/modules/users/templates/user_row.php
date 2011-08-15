
		<tr class="sw<?= $this->sw ?>">
			<td>
				<?
					$img = new dbImage ( $this->data->Image );
					if ( !$img->ID )
					{
						$ml = '<img src="admin/gfx/arenaicons/user_johndoe_32.png" width="32px" height="32px">';
					}
					else $ml = $img->getImageHTML ( 32, 32, 'framed' );
					return $ml;
				?>
			</td>
			<td<?= $this->data->IsTemplate ? ' style="color: #a00"' : '' ?>>
				<?= $this->data->IsDisabled ? '(<span style="color: #aaa">' : '' ?>
				<?if ( $this->data->Name ) { ?>
					<strong><?= $this->data->Name ?></strong>,<br />
				<?}?>
				<?= $this->data->Username ?>
				<?= $this->data->IsDisabled ? '</span>)' : '' ?>
			</td>
			<td>
				<?= $this->InGroups ?>
			</td>
			<td>
				<?
					if ( strstr ( $this->DateModified, '1970' ) ) 
						return $this->DateCreated; 
					return $this->DateModified;
				?>
			</td>
			<td>
				<?
					if ( strstr ( $this->DateLogin, '1970' ) ) 
						return i18n ( 'Never logged in.' ); 
					return $this->DateLogin;
				?>
			</td>
			<td style="text-align: center">
				<?if ( $this->canWrite ) { ?>
					<input type="checkbox" onchange="if ( this.checked ) addToUniqueList ( 'seluserslist', '<?= $this->data->ID ?>' ); else remFromUniqueList ( 'seluserslist', '<?= $this->data->ID ?>' )" />
				<?}?>
			</td>
			<td style="text-align: center">
				<?if ( $this->canRead ) { ?>
				<button type="button" onclick="document.location='admin.php?module=users&function=user&uid=<?= $this->data->ID ?>';" class="Small">
					<img src="admin/gfx/icons/user_edit.png" />
				</button>
				<?}?>
				<button type="button" onclick="addToWorkbench ( '<?= $this->data->ID ?>', 'Users' )" class="Small">
					<img src="admin/gfx/icons/plugin.png" />
				</button>
			</td>
		</tr>
