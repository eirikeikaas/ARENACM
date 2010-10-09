<<? ?>?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>
			<?= $this->page->Title . $this->page->ExtraTitle ?>
		</title>
	</head>
	<body>
		<div id="Empty__"></div>
			<div id="CenterBox__">
			<div id="Center__">
				<div id="Content__">
					<div id="TopMenu__"><?= $this->renderNavigation ( defined( 'NAVIGATION_ROOTPARENT' ) ? NAVIGATION_ROOTPARENT :  0, defined( 'NAVIGATION_LEVELS' ) ? NAVIGATION_LEVELS : 1 , defined( 'NAVIGATION_MODE' ) ? NAVIGATION_MODE : 'FOLLOW', true ); ?></div>
					<div id="InnerContent__">
						<div id="NotPublished">
							<h1>
								<?= i18n ( 'Not published', $GLOBALS[ 'Session' ]->LanguageCode ) ?>!
							</h1>
							<p>
								<?= i18n ( 'This page is marked as "not published"', $GLOBALS[ 'Session' ]->LanguageCode ) ?>.
							</p>
							<?= $this->page->Parent > 0 ? ( '<p><a href="' . $this->parentPage->getUrl ( ) . '">' . i18n ( 'Go to parent page', $GLOBALS[ 'Session' ]->LanguageCode ) . '</a></p>' ) : '' ?>
						</div>
					</div>
				</div>
				<div id="Footer__"><?= $this->page->footerline != '' ? '<div id="InnerFooter__">'.$this->page->footerline.'</div>' : ''; ?></div>
			</div>
		</div>
	</body>
</html>