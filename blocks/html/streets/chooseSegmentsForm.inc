<?php
/*
	$this->streetList
*/
	switch (count($this->streetList))
	{
		case 0:
			echo "<p>No Segments Found</p>";
		break;

		default:
			echo "
			<h2>Segments Found</h2>
			<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">
			<fieldset><legend>Choose Segments To Add</legend>
				<ul>
			";
				foreach($this->streetList as $s)
				{
					echo "<li>{$s->getFullStreetName()}<ul>";
					$list = new SegmentList(array("street_id"=>$s->getId()));
					foreach($list as $segment)
					{
						echo "
						<li><table>
							<tr><td><input name=\"segments[{$segment->getId()}]\" id=\"segment{$segment->getId()}\" type=\"checkbox\" /></td>
								<td><label for=\"segment{$segment->getId()}\">{$segment->getTag()}</label></td>
								<td>{$segment->getLowAddressNumber()}</td>
								<td>{$segment->getHighAddressNumber()}</td>
							</tr>
							</table>
						</li>
						";
					}
					echo "</ul></li>";
				}
			echo "
				</ul>

				<button type=\"submit\" class=\"submit\">Submit</button>
			</fieldset>
			</form>
			";
	}
?>