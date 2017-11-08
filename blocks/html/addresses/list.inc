<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array  $this->addresses
 * @param Street $this->street    (optional)
 */
use Application\People\Person;
?>
<section>
    <header>
        <h2><?= $this->_(['address', 'addresses', count($this->addresses)]); ?></h2>
    </header>
    <table>
        <thead>
            <tr><th><?= $this->_('id'); ?></th>
                <th><?= $this->_('type'); ?></th>
                <th><?= $this->_('street'); ?></th>
                <th><?= $this->_('status'); ?></th>
                <th><?= $this->_('jurisdiction'); ?></th>
                <th><?= $this->_('zip'); ?></th>
                <th><?= $this->_('city'); ?></th>
                <th><?= $this->_(['subunit', 'subunits', 10]); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php
            $fields = [
                'streetNumber', 'type', 'jurisdiction', 'zip', 'city'
            ];
            foreach ($this->addresses as $a) {
                foreach ($fields as $f) {
                    $get = 'get'.ucfirst($f);
                    $$f  = parent::escape($a->$get());
                }
                $streetName = parent::escape($a->getStreet()->getStreetName()->getName());
                $status = '';
                $uri = parent::generateUri('addresses.view', ['id'=>$a->getId()]);
                echo "
                <tr><td><a href=\"$uri\">$streetNumber</a></td>
                    <td><a href=\"$uri\">$type</a></td>
                    <td><a href=\"$uri\">$streetName</a></td>
                    <td><a href=\"$uri\">$status</a></td>
                    <td><a href=\"$uri\">$jurisdiction</a></td>
                    <td><a href=\"$uri\">$zip</a></td>
                    <td><a href=\"$uri\">$city</a></td>
                    <td></td>
                </tr>
                ";
            }
        ?>
        </tbody>
    </table>
</section>
