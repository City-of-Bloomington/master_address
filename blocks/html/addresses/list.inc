<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array  $this->addresses
 */
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

            foreach ($this->addresses as $a) {
                $type         = parent::escape($a->address_type);
                $zip          = parent::escape($a->zip);
                $status       = parent::escape($a->status);
                $jurisdiction = parent::escape($a->jurisdiction_name);
                $city         = parent::escape($a->city);

                $streetNumber = parent::escape(implode(' ', [
                    $a->street_number_prefix,
                    $a->street_number,
                    $a->street_number_suffix
                ]));
                $streetName = parent::escape(implode(' ', [
                    $a->street_direction,
                    $a->street_name,
                    $a->street_post_direction,
                    $a->street_suffix_code
                ]));
                $uri = parent::generateUri('addresses.view', ['id'=>$a->id]);
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
