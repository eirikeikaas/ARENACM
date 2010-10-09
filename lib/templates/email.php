<html>
	<head>
		<title>
			<?= defined ( 'SITE_TITLE' ) ? SITE_TITLE : SITE_ID ?> har mottatt din bestilling
		</title>
		<?if ( file_exists ( BASE_DIR . '/css/email.css' ) ) { ?>
		<style type="text/css">
			<?= file_get_contents ( BASE_DIR . '/css/email.css' ) ?>
		</style>
		<?}?>
		<?if ( !file_exists ( BASE_DIR . '/css/email.css' ) ) { ?>
		<style type="text/css">
			body
			{
				background: #fff;
				color: #000;
				font-family: arial, helvetica;
				font-size: 11px;
			}
		</style>
		<?}?>
	</head>
	<body>
		<div id="Content">
			<div id="Header">
				<?
					if ( file_exists ( BASE_DIR . '/templates/mail_header.php' ) )
					{
						$tpl = new cPTemplate ( BASE_DIR . '/templates/mail_header.php' );
						return $tpl->render ( );
					}
				?>
			</div>
			<div id="Body">
				<?= $this->data ?>
			</div>
			<div id="Footer">
				<?
					if ( file_exists ( BASE_DIR . '/templates/mail_footer.php' ) )
					{
						$tpl = new cPTemplate ( BASE_DIR . '/templates/mail_footer.php' );
						return $tpl->render ( );
					}
				?>
			</div>
		</div>
	</body>
</html>

