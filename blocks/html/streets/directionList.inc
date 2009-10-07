<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<div class="interfaceBox">
	<h1>Directions</h1>
	<table>
		<thead>
			<tr><th>Code</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->directionList as $direction) {
				$description = View::escape($direction->getDescription());
				echo "
				<tr><td>{$direction->getCode()}</td>
					<td>$description</td>
				</tr>
				";
         }
		?>
		</tbody>
	</table>
</div>
