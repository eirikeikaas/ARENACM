
		<div class="Container Imagecontainer"
			onclick="toggleSelectedImage ( this )"
			ondblclick="editLibraryImage( '<?= $this->image->ID?>' )" 
			id="imagecontainer<?= $this->image->ID?>"
			onmousedown="dragger.startDrag ( this.getElementsByTagName ( 'img' )[0], { pickup: 'clone', objectType: 'Image', objectID: '<?= $this->image->ID?>' } ); return false"
		>
			
				<div style="position: relative; width: 120px; height: 100px; overflow: hidden">
					<img src="<? $this->image->setBackgroundColor ( 0xffffff ); return $this->image->getImageUrl( 120, 100, 'centered' ); ?>">
					<span style="position: absolute; top: 0px; left: 0px; width: 120px; height: 100px; z-index: 2; background: #fff">
					</span>
				</div>
				<h4><?= strlen( $this->image->Title ) < 12 ? $this->image->Title : trimtext( $this->image->Title, 12 ) ?></h4>
			
			
		</div>
		<script>
			addToolTip( '<?= $this->image->Title ?>','<?= trim( $this->image->Description ) != '' ? ( '<b>Beskrivelse:</b><br />' . str_replace ( array ( getLn ( 'windows' ), getLn ( ), getLn ( 'r' ) ), '', nl2br( $this->image->Description ) ) . '<hr />' ) : ''?><b>Filenavn:</b> <?= nl2br ( trim ( $this->image->Filename ) ) ?><br /><b>Størrelse:</b> <?= filesizeToHuman( $this->image->Filesize ); ?>', 'imagecontainer<?= $this->image->ID?>' );
			setOpacity ( document.getElementById ( 'imagecontainer<?= $this->image->ID?>' ).getElementsByTagName ( 'span' )[0], 0 );
		</script>	
