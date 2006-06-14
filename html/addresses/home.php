<?php
/*
	For now, we just need the addresses page to go directly to the add address form
*/
	if (isset($_SESSION['USER']) && $_SESSION['USER']->hasRole(array("Administrator","ADDRESS COORDINATOR"))) { Header("Location: findSegmentForm.php"); }
	else { Header("Location: findAddressForm.php"); }
?>