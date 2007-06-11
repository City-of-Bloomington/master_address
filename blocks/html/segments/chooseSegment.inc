<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
/*
	$this->segmentList
	$this->response

	Lists all the segments with an HREF back to response.  Useful in cases
	where you want the user to pick one segment
*/
	switch (count($this->segmentList))
	{
		case 0:
			echo "<p>No Segments Found</p>";
			break;

		default:
			echo "
			<h2>Choose a Segment</h2>
			<table>
			<tr><th>ID</th><th>Tag<th><th>Address Range</th></tr>
			";

			foreach($this->segmentList as $segment)
			{
				$this->response->parameters['segment_id'] = $segment->getId();
				echo "
				<tr><td><a href=\"{$this->response->getURL()}\">{$segment->getId()}</a></td>
					<td><a href=\"{$this->response->getURL()}\">{$segment->getTag()}</a></td>
					<td><a href=\"{$this->response->getURL()}\">{$segment->getLowAddressNumber()} - {$segment->getHighAddressNumber()}</a></td>
				</tr>
				";
			}

			echo "</table>";
	}
?>