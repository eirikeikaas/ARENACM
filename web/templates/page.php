<?= $this->docinfo . $this->doctype ?><html<?= $this->xmlns ?>>
	<head>
		<title>
			<?= ( defined ( 'SITE_TITLE' ) ? SITE_TITLE : SITE_ID ) . ' - ' . $this->page->Title . $this->page->ExtraTitle ?>
		</title>
		<?if ( strstr ( $this->userAgent, 'ipad' ) ) { ?>
		<meta name="viewport" content="max-scale = 1, min-scale = 1, initial-scale = 1"/>
		<?}?>
	</head><?
		$agent = strtolower ( $_SERVER[ 'HTTP_USER_AGENT' ] );
		if ( strstr ( $agent, 'webkit' ) )
			$this->userAgent = 'webkit';
		else if ( strstr ( $agent, 'gecko' ) )
			$this->userAgent = 'gecko';
		else $this->userAgent = 'msie';
		if ( strstr ( $agent, 'windows' ) )
			$this->userAgent .= ' windows';
		else if ( strstr ( $agent, 'linux' ) )
			$this->userAgent .= ' linux';
		else if ( strstr ( $agent, 'mac' ) )
			$this->userAgent .= ' mac';
		else $this->userAgent .= ' otheros';
		if ( strstr ( $agent, 'ipad' ) )
			$this->userAgent .= ' ipad';
		else if ( strstr ( $agent, 'android' ) )
			$this->userAgent .= ' android';
	?>
	<body class="<?= $this->userAgent; ?> <?= $this->LanguageCode . ' ' . $this->page->RouteName ?>">
		<? $this->__Content =  executeWebModule ( $this->page, $_REQUEST[ 'ue' ] ? 'extensions' : false ); ?>
		<div id="Empty__"></div>
		<div id="CenterBox__" class="<?= is_numeric ( $this->page->RouteName{0} ) ? ( 'a' . $this->page->RouteName ) : $this->page->RouteName ?>">
			<div id="Center__">
				<div id="Content__">
					<?if ( !defined ( 'TOPMENU_CONTENTGROUP' ) || !TOPMENU_CONTENTGROUP ) { ?>
					<div id="TopMenu__" class="<?= texttourl( trim ( strip_tags ( $this->page->MenuTitle ) ) ) ?>"><?= $this->renderNavigation ( defined( 'NAVIGATION_ROOTPARENT' ) ? NAVIGATION_ROOTPARENT :  0, defined( 'NAVIGATION_LEVELS' ) ? NAVIGATION_LEVELS : 1 , defined( 'NAVIGATION_MODE' ) ? NAVIGATION_MODE : 'FOLLOW', true ); ?></div>
					<?}?>
					<div id="InnerContainer__">
						<div id="InnerContent__">
							<?= $this->__Content ?>
							<?= ( defined( 'NAVIGATION_SHOWBREADCRUMBS' ) && NAVIGATION_SHOWBREADCRUMBS ) ? '<div id="BreadCrumbs__">' . $this->renderBreadCrumbs() . '</div>' : ''; ?>
						</div>
					</div>
				</div>
				<div id="Footer__"><div id="InnerFooter__"><?= $this->page->footerline != '' ? $this->page->footerline : ''; ?></div></div>
			</div>
		</div>
	</body>
</html>
