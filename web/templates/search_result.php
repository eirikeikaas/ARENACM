<div class="Search_Result">
	<h2><?= $this->orderNumber ?>. <a href="<?= $this->path ?>"><?
		if ( $this->data->Title )
			return $this->data->Title;
		else if ( $this->data->Name )
			return $this->data->Name;
	?></a></h2>
	<?
		if ( $this->data->Intro ) 
			return $this->data->Intro;
		else if ( $this->data->Body )
			return $this->data->Body;
		else if ( $this->data->Article )
			return $this->data->Article;
	?>
</div>
<?if ( $this->Spacer ) { ?>
<div class="Spacer">
</div>
<?}?>