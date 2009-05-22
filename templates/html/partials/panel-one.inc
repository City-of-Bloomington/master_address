<div id="panel-one">
<?php
	include APPLICATION_HOME.'/templates/html/partials/panel-widgets/Public.inc';

	if (userHasRole('Administrator')) {
		include APPLICATION_HOME.'/templates/html/partials/panel-widgets/Administrator.inc';
	}
?>
</div>
