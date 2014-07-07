<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Street|Address|Subunit|Contact $this->target
 * @param Zend_Paginator $this->paginator
 * @param URL $this->url  If we send a URL, we want buttons rendered for all the possibile formats
 */
$paginator = $this->paginator ? $this->paginator : $this->target->getChangeLogPaginator();
if (isset($_GET['page'])) {
	$paginator->setCurrentPageNumber((int)$_GET['page']);
}

$txtButton = '';
$xmlButton = '';
if (isset($this->url)) {
	$url = $this->url.';format=txt';
	$txtButton = "
		<button type=\"button\" onclick=\"window.open('$url','popup');\">
			TXT
		</button>
	";

	$url = $this->url.';format=xml';
	$xmlButton = "
		<button type=\"button\" onclick=\"window.open('$url','popup');\">
			XML
		</button>
	";
}
echo "
<div class=\"interfaceBox\">
	<h1>Action History $txtButton $xmlButton</h1>
	<table>
		<thead>
			<tr><th>Action Date</th>
				<th></th>
				<th>Action</th>
				<th>User</th>
				<th>Contact</th>
				<th>Notes</th>
			</tr>
		</thead>
		<tbody>
	";
		foreach ($paginator as $row) {
			$logEntry = new ChangeLogEntry($row);
			$username = $logEntry->getUser()->getUsername();
			$notes = View::escape($logEntry->notes);
			$contact = $logEntry->getContact();
			echo "
			<tr><td>{$logEntry->action_date}</td>
				<td>
			";
				$target = $logEntry->getTarget();
				if ($target) {
					echo "<a href=\"{$target->getURL()}\">$target</a>";
				}
			echo "
				</td>
				<td>{$logEntry->action}</td>
				<td>$username</td>
				<td><a href=\"".BASE_URL."/contacts/viewContact.php?contact_id={$contact->getId()}\">
					{$contact->getFirstname()}
					{$contact->getLastname()}
					</a>
				</td>
				<td>$notes</td>
			</tr>
			";
		}
	echo "
		</tbody>
	</table>
	";
	$block = new Block('pageNavigation.inc',array('pages'=>$paginator->getPages()));
	echo $block->render('html');
echo "
</div>
";
