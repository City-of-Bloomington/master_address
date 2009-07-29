<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Street $this->street
 */
$street = $this->street;
$name = View::escape($street->getStreetName());
$status = View::escape($street->getStatus()->getDescription());
$town = View::escape($street->getTown()->getDescription());
$notes = View::escape($street->getNotes());
?>
<div class="interfaceBox" id="streetInfo">
	<h2>Street Information
		<?php
			if (userIsAllowed('Street')) {
				$url = new URL(BASE_URL.'/streets/updateStreet.php');
				$url->street_id = $street->getId();
				echo "<a class=\"edit button\" id=\"editStreetButton\" href=\"$url\">Edit</a>";
			}
		?>
	</h2>
	<table>
	<?php
		echo "
		<tr><th>Name</th><td>$name</td></tr>
		<tr><th>Status</th><td>$status</td></tr>
		<tr><th>Town</th><td>$town</td></tr>
		<tr><th>Notes</th><td>$notes</td></tr>
		";
	?>
	</table>
</div>
<?php
	if (userIsAllowed('Street')) {
		echo '<div class="interfaceBox" id="updateStreetForm" style="display:none;">';
		include APPLICATION_HOME.'/blocks/html/streets/updateStreetForm.inc';
		echo "</div>";
?>
<script type="text/javascript">
	document.getElementById('editStreetButton').href = "javascript:editStreet();";
	var updateStreetForm = document.getElementById('updateStreetForm').getElementsByTagName('form')[0];
	var cancelButton = YAHOO.util.Dom.getElementsByClassName('cancel','button','updateStreetForm')[0];
	var submitButton = YAHOO.util.Dom.getElementsByClassName('submit','button','updateStreetForm')[0];
	cancelButton.onclick = cancelEditStreet;
	submitButton.onclick = saveStreet;

	function editStreet()
	{
		document.getElementById('streetInfo').style.display = 'none';
		document.getElementById('updateStreetForm').style.display = 'block';
	}

	function cancelEditStreet()
	{
		document.getElementById('updateStreetForm').style.display = 'none';
		document.getElementById('streetInfo').style.display = 'block';
	}

	function saveStreet()
	{
		var el = new YAHOO.util.Element(document.createElement('input'));
		el.set('type','hidden');
		el.set('name','format');
		el.set('value','json');

		YAHOO.util.Dom.insertBefore(el,submitButton);

		alert(updateStreetForm.action);
		return false;
	}
</script>
<?php
	}
?>