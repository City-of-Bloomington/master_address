<?php
	if (isset($_SESSION['USER']) && $_SESSION['USER']->hasRole(array("Administrator","ADDRESS COORDINATOR")))
	{
		echo "
		<div class=\"sidebar\">
			<div class=\"titleBar\">Addresses</div>
			<ul><li><a href=\"".BASE_URL."/addresses/findSegmentForm.php\">Add an Address</a></li>
				<li><a href=\"".BASE_URL."/addresses/findAddressForm.php\">Find an Address</a></li>
			</ul>
		</div>
		";
	}
?>
