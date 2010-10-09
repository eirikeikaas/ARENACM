	
	<?
		$this->contentelement->loadExtraFields ( );
		foreach ( $this->contentelement as $k=>$v )
		{
			if ( substr ( $k, 0, 7 ) == '_extra_' )
			{
				$kk = str_replace ( '_field_', '', $k );
				if ( strlen ( $this->contentelement->$kk ) > 30 )
				{
					$this->data->Leadin = substr ( strip_tags ( $this->contentelement->$kk ), 0, 200 ) . '..';
					break;
				}
			}
		}
	?>
	<div class="Block">
		<p class="Title">
			<a href="<?= $this->data->Url ?>"><?= $this->data->Title ?></a>
		</p>
		<p class="Leadin">
			<?= $this->data->Leadin ?>
		</p>
		<p class="ReadMore">
			<a href="<?= $this->data->Url ?>"><?= i18n ( 'Read more' ) ?></a>
		</p>
	</div>
