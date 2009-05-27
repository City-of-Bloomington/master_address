<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<div class="interfaceBox">
	<h1>
		<?php
			if (userHasRole('Administrator')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/streetStatuses/addStreetStatus.php\">Add</a>";
			}
		?>
		Street Statuses
	</h1>
	<ul><?php
			foreach ($this->streetStatusList as $streetStatus) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/streetStatuses/updateStreetStatus.php');
					$url->status_code = $streetStatus->getStatus_code();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				echo "<li>$editButton $streetStatus</li>";
			}
		?>
	</ul>
</div>
