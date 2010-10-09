		
		<h4>
			<div class="Buttons">
				<button title="Endre feltet" type="button" onclick="editEditorField ( <?= $this->field->ID ?>, '<?= $this->fieldType ?>' )"><img src="admin/gfx/smallbutton_edit.png"></button>
				<button title="Fjern feltet" type="button" onclick="removeField ( <?= $this->field->ID ?>, '<?= $this->fieldType ?>' )"><img src="admin/gfx/smallbutton_remove.png"></button>
				<button title="Flytt opp" type="button" onclick="reorderField ( <?= $this->field->ID ?>, '<?= $this->fieldType ?>', -1 )"><img src="admin/gfx/smallbutton_up.png"></button>
				<button title="Flytt ned" type="button" onclick="reorderField ( <?= $this->field->ID ?>, '<?= $this->fieldType ?>', 1 )"><img src="admin/gfx/smallbutton_down.png"></button>
			</div>
			<a onclick="javascript: scrollTo ( 0, getElementTop ( this ) );"><?= str_replace ( '_', ' ', $this->field->Name ) ?> (i <?= $this->fieldGroup ?>):</a>
		</h4>
		<div class="Container" style="padding: <?= MarginSize ?>px">
			<table>
				<tr>
					<td>
						Sideutlisting fra:
					</td>
					<td>
						<?
							$select = dbContent::RenderSelect ( "pagelisting_{$this->field->ID}", false, $this->field->DataInt );
							$s1 = $this->field->DataInt == 0 ? ' selected="selected"' : '';
							$s2 = $this->field->DataInt == -1 ? ' selected="selected"' : '';
							$suboptions = '';
							$suboptions .= '<option value="0">=================================</option>';
							$suboptions .= '<option value="0"' . $s1 . '>Arv overstående sides innstilling</option>';
							$suboptions .= '<option value="-1"' . $s2 . '>List ut undersider</option>';
							$select = str_replace ( Array ( 'name="', '</select>' ), Array ( 'id="', $suboptions . '</select>' ), $select );
							return $select;
						?>
					</td>
				</tr>
				<?
					$opts = explode ( "\\n", $this->field->DataMixed );
					$this->options = new Dummy ( );
					foreach ( $opts as $o )
					{
						list ( $k, $v ) = explode ( "\\t", $o );
						if ( !trim ( $k ) ) continue;
						$this->options->$k = $v;
					}
				?>
				<tr>
					<td>
						List ut:
					</td>
					<td>
						<select id="pagelisting_listout_<?= $this->field->ID ?>">
						<?
							$alternatives = Array ( 'titles'=>'Titler', 'intro'=>'Ingress', 'titlesandintro'=>'Titler og ingress', 'body'=>'Body', 'titlesandbody'=>'Titler og body', 'everything'=>'Alt' );
							$o = '';
							foreach ( $alternatives as $k=>$a )
							{
								$o .= "<option value=\\"{$k}\\"" . ( $k == $this->field->DataString ? ' selected="selected"' : '' ) . ">$a</option>";
							}
							return $o;
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						Utfør rekrusivt:
					</td>
					<td>
						<input type="checkbox" id="pagelisting_recursion_<?= $this->field->ID ?>"<?= $this->options->usingRecursion ? ' checked="checked"' : '' ?>>
					</td>
				</tr>
				<tr>
					<td>
						Bruker utlistingsside sin heading:
					</td>
					<td>
						<input type="checkbox" id="pagelisting_pageheading_<?= $this->field->ID ?>"<?= $this->options->usingParentHeading ? ' checked="checked"' : '' ?>>
					</td>
				</tr>
			</table>
		</div>
		<div class="Spacer"></div>
		
		<script type="text/javascript">
			AddSaveFunction ( function ( )
			{
				var mixed = new Array ( );
				mixed.push ( "usingParentHeading\t" + ( document.getElementById ( "pagelisting_pageheading_<?= $this->field->ID ?>" ).checked ? 1 : 0 ) );
				mixed.push ( "usingRecursion\t" + ( document.getElementById ( "pagelisting_recursion_<?= $this->field->ID ?>" ).checked ? 1 : 0 ) );
				v = mixed.join ( "\n" );
				
				var j = new bajax ( );
				j.openUrl ( 'admin.php?plugin=extrafields&pluginaction=setfieldoption&type=Small&id=<?= $this->field->ID ?>', 'post', true );
				j.addVar ( 'DataInt', document.getElementById ( 'pagelisting_<?= $this->field->ID ?>' ).value );
				j.addVar ( 'DataString', document.getElementById ( 'pagelisting_listout_<?= $this->field->ID ?>' ).value );
				j.addVar ( 'DataMixed', mixed.join ( "\n" ) );
				j.onload = function ( ){ /*alert ( this.getResponseText ( ) );*/ }
				j.send ( );
			}
			);
		</script>
		
