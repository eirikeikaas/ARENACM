<?if( is_array( $this->contents ) ){?>
	<?
		$o = '';
		
		$countListed = 0;
		$startat = intval( $_REQUEST[ 'position' ] > 0 ) ? $_REQUEST[ 'position' ] : 0;
		$stopat = $startat + LIBRARY_ITEMSPERPAGE;
		
		if( is_array( $this->contents['images'] ) && count( $this->contents['images'] ) > 0 )
		{
			$tpl = new cPTemplate( 'admin/modules/library/templates/listed_image.php' );
			foreach( $this->contents['images'] as $image )
			{
				if( $countListed < $startat ) { $countListed++; continue; }
				if( $countListed >= $stopat ) break;
				
				$tpl->image = $image;
				$o .= $tpl->render();
				$countListed++;
			}
		}	
		else if( !is_array( $this->contents['images'] ) ) $this->contents['images'] = array();
		
		if( is_array( $this->contents['files'] ) && count( $this->contents['files'] ) > 0 && $countListed < $stopat )
		{
			$tpl = new cPTemplate( 'admin/modules/library/templates/listed_file.php' );
			foreach( $this->contents['files'] as $file )
			{
				if( $countListed < $startat ) { $countListed++; continue; }
				if( $countListed >= $stopat ) break;
				
				$tpl->tfile = $file;
				$o.= $tpl->render();
				$countListed++;
			}
		}
		else if(  !is_array( $this->contents['files'] ) ) $this->contents['files'] = array();
		
		
		$btn = '';
		
		// pageination
		if( count ( $this->contents['images'] ) + count ( $this->contents['files'] ) > 10 ) 
		{
			$cp = new cPagination();
			$cp->Count = count ( $this->contents[ 'images' ] ) + count ( $this->contents[ 'files' ] );
			$cp->Position = $startat;
			$cp->Limit = LIBRARY_ITEMSPERPAGE;
			$cp->Template = 'admin/modules/library/templates/pagination.php';
			$btn .= $cp->render ( );
		}
		
		$this->pagination = $btn;
		
		return $o;
	?>
	<div class="SpacerSmall"></div>
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<?= $this->pagination ? ( '<td style="white-space: nowrap; padding-right: 2px">' . $this->pagination . '</td>' ) : '' ?>
			<td>
				<div class="SubContainer" style="height: 24px; padding-top: 15px">
					<?= i18n ( 'Folder' ) ?> "<?= $this->folder->Name ?>" <?= i18n ( 'contains' ) ?> <?= count( $this->contents['images'] )?> bilde(r) og <?= count( $this->contents['files'] )?> fil(er).
				</div>
			</td>
		</tr>
	</table>
<?}?>
<?if( !is_array( $this->contents ) ){?>
	<h1>Teknisk feil. Kontakt din leverandør!</h1>
<?}?>
