<?php
	if (isset($_SESSION['USER']) && in_array(array("Administrator","ADDRESS COORDINATOR"),$_SESSION['USER']->getRoles()))
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