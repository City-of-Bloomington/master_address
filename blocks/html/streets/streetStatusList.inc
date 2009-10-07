<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param StreetStatusList $this->streetStatusList
 */
?>
<div class="interfaceBox">
	<h1><?php
			if (userIsAllowed('StreetStatus')) {
				echo "
				<button type=\"button\" class=\"add\" onclick=\"document.location.href='".BASE_URL."/streets/addStreetStatus.php';\">
					Add
				</button>
				";
			}
		?>
		Street Statuses
	</h1>
	<ul><?php
			foreach ($this->streetStatusList as $streetStatus) {
				$editButton = '';
				if (userIsAllowed('StreetStatus')) {
					$url = new URL(BASE_URL.'/streets/updateStreetStatus.php');
					$url->status_code = $streetStatus->getStatus_code();
					$editButton = "
					<button type=\"button\" class=\"edit\" onclick=\"document.location.href='$url';\">
						Edit
					</button>
					";
				}
				echo "<li>$editButton $streetStatus</li>";
			}
		?>
	</ul>
</div>
