<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->location
 */
$fields = [
    'location_id', 'addressString', 'location_type_id', 'street_address_id', 'subunit_id',
    'mailable_flag', 'livable_flag', 'active',
];
foreach ($fields as $f) {
    $$f = View::escape($this->location[$f]);
}

$url = BASE_URL.'/addresses/viewAddress.php?address_id='.$street_address_id;
?>
<h2>Location ID <?php echo $location_id; ?></h2>
<h3><?php echo "<a href=\"$url\">$addressString</a>"; ?></h3>
<table>
    <tr><th>Location Use Type</th>
        <td><?php echo $location_type_id; ?></td>
    </tr>
    <tr><th>Street Address ID</th>
        <td><?php echo $street_address_id; ?></td>
    </tr>
    <tr><th>Subunit ID</th>
        <td><?php echo $subunit_id; ?></td>
    </tr>
    <tr><th>Mailable</th>
        <td><?php echo $mailable_flag; ?></td>
    </tr>
    <tr><th>Livable</th>
        <td><?php echo $livable_flag; ?></td>
    </tr>
    <tr><th>Active</th>
        <td><?php echo $active; ?></td>
    </tr>
</table>
