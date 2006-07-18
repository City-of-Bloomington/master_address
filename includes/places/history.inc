<?php
/*
	Required variables:		place
*/
?>
<div class="titleBar">History</div>
<table>
<tr><th>Action</th><th>Date</th></tr>
<?php
	foreach($place->getHistory() as $action)
	{
		echo "
		<tr><td>{$action->getAction()}</td>
			<td>{$action->getDate()}</td>
		</tr>
		";
	}
?>
</table>