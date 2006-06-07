<?php
/*
	For now, we just need the addresses page to go directly to the add address form
*/
	if (isset($_SESSION['USER']) && in_array(array("Administrator","ADDRESS COORDINATOR"),$_SESSION['USER']->getRoles())) { Header("Location: findSegmentForm.php"); }
	else { Header("Location: findAddressForm.php"); }
?>