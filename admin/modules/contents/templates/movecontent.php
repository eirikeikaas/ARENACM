<h1>
	Flytt "<?= $this->content->Title ?>" under en annen side:
</h1>
<div class="SubContainer">
	<select size="26" style="width: 100%; -moz-box-sizing: border-box: box-sizing: border-box" id="NewParentContentID">
		<? 
			include_once ( 'lib/classes/dbObjects/dbContent.php' );							
			$page = new dbContent ( );
			$page = $page->getRootContent ( );
			$options .= '<option value="' . $page->MainID . '"' . ( $page->ID == $this->content->Parent ? ' selected="selected"' : '' ) . '>' . $page->MenuTitle . '</option>';
			
			function subPages ( $page, $current, $r = '&nbsp;&nbsp;&nbsp;&nbsp;' )
			{
				if ( !$page->subElementsLoaded )
					$page->loadSubElements ( );
				if ( $page->subElements )
				{
					foreach ( $page->subElements as $e )
					{
						$path = $e->ID;
						if ( $path == $current->ID ) continue;
						if ( $path == $current->Parent ) $s = ' selected="selected"';
						else $s = '';
						if ( !$e->MenuTitle ) $e->MenuTitle = $e->Title;
						if ( !$e->MenuTitle ) $e->MenuTitle = $e->SystemName;
						if ( !$e->MenuTitle ) $e->MenuTitle = 'Ukjent side (' . $e->ID . ')';
						$options .= '<option value="' . $path . '"' . $s . '>' . $r . $e->MenuTitle . '</option>';
						$options .= subPages ( $e, $current, $r . '&nbsp;&nbsp;&nbsp;&nbsp;' );
					}
					return $options;
				}
				return '';
			}
			$options .= subPages ( $page, $this->content );
			return $options;
		?>
	</select>
	<div class="SpacerSmall"></div>
	<button type="button" onclick="moveContent ( '<?= $this->content->ID ?>', document.getElementById ( 'NewParentContentID' ).value ); removeModalDialogue ( 'MoveDialog' )">
		<img src="admin/gfx/icons/accept.png" /> Flytt
	</button>
	<button type="button" onclick="removeModalDialogue ( 'MoveDialog' )">
		<img src="admin/gfx/icons/cancel.png" /> Lukk vinduet
	</button>
</div>
