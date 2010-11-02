

	<div class="ShoppingLog">
		
		<h1 class="ShoppingLog"><span><?= i18n ( 'Shoppinglog' ) ?></span></h1>
		
		<table>
			<tr>
				<th><?= i18n ( 'Order number' ) ?>:</th>
				<th><?= i18n ( 'Date updated' ) ?>:</th>
				<th><?= i18n ( 'Status' ) ?>:</th>
				<th><?= i18n ( '#' ) ?></th>
			</tr>
			<?= $this->OrderHistory ?>
		</table>
		
	</div>

