<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param TownList $this->townList
 */
?>
<div class="interfaceBox">
	<h1><?php
			if (userIsAllowed('Town')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/towns/addTown.php\">Add</a>";
			}
		?>
		Towns
	</h1>
	<table>
	<?php
		foreach ($this->townList as $town) {
			$editButton = '';
			if (userIsAllowed('Town')) {
				$url = new URL(BASE_URL.'/towns/updateTown.php');
				$url->town_id = $town->getId();
				$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
			}
			$description = View::escape($town->getDescription());
			$code = View::escape($town->getCode());
			echo "
			<tr><td>$editButton</td>
				<td>$description</td>
				<td>$code</td>
			</tr>
			";
		}
	?>
	</table>
</div>
