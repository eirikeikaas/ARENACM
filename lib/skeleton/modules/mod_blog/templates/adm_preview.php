<?
	if ( !strstr ( $_SERVER[ 'HTTP_USER_AGENT' ], 'MSIE 6' ) )
		return '<' . '?xml version="1.0"?' . '>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>
			<?= $this->blog->Title ?>
		</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
		<?if ( file_exists ( 'upload/main.css' ) ) { ?>
		<link rel="stylesheet" href="upload/main.css"/>
		<?}?>
		<?if ( file_exists ( 'css/main.css' ) ) { ?>
		<link rel="stylesheet" href="css/main.css"/>
		<?}?>
	</head>
	<body>
		<div class="Preview">
			<?= $this->bloghtml ?>
		</div>
	</body>
</html>
