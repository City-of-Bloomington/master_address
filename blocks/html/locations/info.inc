<?php
/**
 * @copyright 2014-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Location $this->location
 * @param boolean  $this->disableButtons (optional)
 */
use Application\Url;

$fields = [
    'location_id', 'status', 'type_code', 'mailable', 'occupiable', 'active', 'trash_day', 'recycle_week'
];
foreach ($fields as $f) {
    $$f  = parent::escape($this->location->$f);
}
?>
<article>
    <table>
        <?php
            foreach ($fields as $f) {
                echo "
                <tr><th>{$this->_($f)}</th>
                    <td>{$$f}</td>
                </tr>
                ";

            }
        ?>
        <tr><th><?= $this->_(['address', 'addresses', count($this->location->addresses)]); ?></th>
            <td><ul><?php
                    foreach ($this->location->addresses as $address) {
                        $uri = parent::generateUri('addresses.view', ['id'=>$address->id]);
                        echo "<li><a href=\"$uri\">$address</a></li>";
                    }
                    if (!$this->disableButtons && parent::isAllowed('addresses', 'add')) {
                        $h = $this->template->getHelper('buttonLink');
                        $q = http_build_query([
                            'location_id' => $location_id,
                            'return_url'  => Url::current_url()
                        ]);
                        echo $h->buttonLink(
                            parent::generateUri('addresses.add')."?$q",
                            $this->_('address_add'),
                            'add'
                        );
                    }
                ?>
                </ul>
            </td>
        </tr>
        <tr><th><?= $this->_(['subunit', 'subunits', count($this->location->subunits)]); ?></th>
            <td><ul><?php
                    foreach ($this->location->subunits as $subunit) {
                        $uri = parent::generateUri('subunits.view', ['id'=>$subunit->id]);
                        echo "<li><a href=\"$uri\">$subunit</a></li>";
                    }
                ?>
                </ul>
            </td>
        </tr>
    </table>
</article>
