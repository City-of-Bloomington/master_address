<?php $startTime = microtime(1); ?>
<head>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<link rel="SHORTCUT ICON" href="/favicon.ico" />
	<style type="text/css">
		@import url('<?php echo BASE_URL; ?>/skins/default/reset.css');
		@import url('<?php echo BASE_URL; ?>/skins/default/layouts/<?php echo $this->layout; ?>.css');
		@import url('<?php echo BASE_URL; ?>/skins/default/screen.css');
	</style>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>/js/functions.js"></script>

	<!-- Load the YUI toolkit -->
	<script type="text/javascript" src="<?php echo BASE_URL; ?>/js/yui/build/utilities/utilities.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>/js/yui/build/yahoo-dom-event/yahoo-dom-event.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>/js/yui/build/element/element-min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>/js/yui/build/connection/connection-min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>/js/yui/build/datasource/datasource-min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>/js/yui/build/json/json-min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>/js/yui/build/autocomplete/autocomplete-min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>/js/yui/build/dom/dom-min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>/js/yui/build/tabview/tabview-min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>/js/yui/build/container/container-min.js"></script>
	<style type="text/css" media="screen">
		@import url('<?php echo BASE_URL; ?>/js/yui/build/assets/skins/sam/skin.css');
		@import url('<?php echo BASE_URL; ?>/js/yui/build/calendar/assets/skins/sam/calendar.css');
		@import url('<?php echo BASE_URL; ?>/js/yui/build/autocomplete/assets/skins/sam/autocomplete.css');
		@import url('<?php echo BASE_URL; ?>/js/yui/build/tabview/assets/skins/sam/tabview.css');
		@import url('<?php echo BASE_URL; ?>/js/yui/build/container/assets/skins/sam/container.css');
	</style>

	<title>Master Address</title>
</head>
