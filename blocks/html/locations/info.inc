<?php
/**
 * @copyright 2014-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Location $this->location
 */
$fields = [
    'location_id', 'status', 'type_code', 'mailable', 'occupiable', 'active', 'trash_day', 'recycle_week'
];
foreach ($fields as $f) {
    $$f  = parent::escape($this->location->$f);
}
?>
<article>
    <dl><?php
            foreach ($fields as $f) {
                echo "
                <dt>{$this->_($f)}</dt>
                <dd>{$$f}</dd>
                ";

            }
        ?>
        <dt><?= $this->_(['address', 'addresses', count($this->location->addresses)]); ?></dt>
        <dd><ul><?php
                    foreach ($this->location->addresses as $address) {
                        $uri = parent::generateUri('addresses.view', ['id'=>$address->id]);
                        echo "<li><a href=\"$uri\">$address</a></li>";
                    }
                ?>
            </ul>
        </dd>
        <dt><?= $this->_(['subunit', 'subunits', count($this->location->subunits)]); ?></dt>
        <dd><ul><?php
                    foreach ($this->location->subunits as $subunit) {
                        $uri = parent::generateUri('subunits.view', ['id'=>$subunit->id]);
                        echo "<li><a href=\"$uri\">$subunit</a></li>";
                    }
                ?>
            </ul>
        </dd>
    </dl>
</article>
