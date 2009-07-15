<div class="menuBar">
	<ul><li><a href="<?php echo BASE_URL; ?>">Home</a></li>
		<?php
			if (isset($_SESSION['USER'])) {
				echo "<li><a href=\"".BASE_URL."/login/logout.php\">Logout</a></li>";
			}
			else {
				echo "<li><a href=\"".BASE_URL."/login\">Login</a></li>";
			}
		?>
		<li><a href="<?php echo BASE_URL; ?>/towns">Towns</a></li>
		<li><a href="<?php echo BASE_URL; ?>/townships">Townships</a></li>
		<li><a href="<?php echo BASE_URL; ?>/plats">Plats</a></li>
		<li><a href="<?php echo BASE_URL; ?>/precincts">Precincts</a></li>
		<li><a href="<?php echo BASE_URL; ?>/jurisdictions">Jurisdictions</a></li>
		<li><a href="<?php echo BASE_URL; ?>/buildings">Buildings</a></li>
		<li><a href="<?php echo BASE_URL; ?>/streets">Streets</a></li>
		<li><a href="<?php echo BASE_URL; ?>/addresses">Addresses</a></li>
        <li><a href="<?php echo BASE_URL; ?>/addressChange">Address Change</a></li>
   		<li><a href="<?php echo BASE_URL; ?>/sanitation">Sanitation</a></li>
		<li><a href="<?php echo BASE_URL; ?>/locations">Locations</a></li>
		<li><a href="<?php echo BASE_URL; ?>/subunits">Subunits</a></li>
   		<li><a href="<?php echo BASE_URL; ?>/subdivisions">Subdivisions</a></li>
		<li><a href="<?php echo BASE_URL; ?>/annexations">Annexations</a></li>
		<?php
			if (userHasRole('Administrator')) {
				echo "
				<li><a href=\"".BASE_URL."/people\">People</a></li>
				<li><a href=\"".BASE_URL."/users\">User Accounts</a></li>
				";
			}
		?>
	</ul>
</div>
