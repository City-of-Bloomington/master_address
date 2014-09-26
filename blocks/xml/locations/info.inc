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
echo "
<location
    location_id=\"$location_id;\"
    addressString=\"$addressString\"
    location_type_id=\"$location_type_id\"
    street_address_id=\"$street_address_id\"
    subunit_id=\"$subunit_id\"
    mailable_flag=\"$mailable_flag\"
    livable_flag=\"$livable_flag\"
    active=\"$active\">
</location>
";
