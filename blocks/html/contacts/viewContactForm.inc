<?php
/**
 * @copyright 2009-2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Contact $this->contact
 */
$editButton = '';
if (userIsAllowed('Contact')) {
	$url = new URL(BASE_URL.'/contacts/updateContact.php');
	$url->contact_id = $this->contact->getContact_id();
	$editButton = "
	<button type=\"button\" class=\"edit\" onclick=\"document.location.href='$url';\">
		Edit Contact
	</button>
	";
}
$fields = [
    'last_name', 'first_name', 'contact_type', 'phone_number', 'email',
    'address', 'city', 'state', 'zip',
    'notification', 'coordination',
    'contact_type', 'agency'
];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f = View::escape($this->contact->$get());
}
?>
<h1><?php echo "$first_name $last_name $editButton"; ?></h1>
<h2><?php echo "($contact_type) $agency"; ?></h2>
<div>
    <div><?php echo $address; ?></div>
    <div><?php echo "$city $state $zip"; ?></div>
</div>
<table>
	<tr><th>Email</th>       <td><?php echo "<a href=\"mailto:$email\">$email</a>"; ?></td></tr>
	<tr><th>Phone</th>       <td><?php echo $phone_number; ?></td></tr>
	<tr><th>Notification</th><td><?php echo $notification; ?></td></tr>
	<tr><th>Coordination</th><td><?php echo $coordination; ?></td></tr>
</table>
