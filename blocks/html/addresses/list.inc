<?php
/**
 * @copyright 2009-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->addresses
 * @param int   $this->total
 * @param int   $this->itemsPerPage
 * @param int   $this->currentPage
 */
use Application\Paginator;
?>
<table>
    <thead>
        <tr>
            <th><?= $this->_('street'); ?></th>
            <th><?= $this->_('address_id'); ?></th>
            <th><?= $this->_('type'); ?></th>
            <th><?= $this->_('status'); ?></th>
            <th><?= $this->_('jurisdiction'); ?></th>
            <th><?= $this->_('zip'); ?></th>
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

            $address      = parent::escape($a->__toString());
            $subunits     = isset($a->subunit_count) ? $a->subunit_count : '';

            $uri = parent::generateUri('addresses.view', ['id'=>$a->id]);
            echo "
            <tr>
                <td><a href=\"$uri\">$address</a></td>
                <td><a href=\"$uri\">{$a->id}</a></td>
                <td><a href=\"$uri\">$type</a></td>
                <td><a href=\"$uri\">$status</a></td>
                <td><a href=\"$uri\">$jurisdiction</a></td>
                <td><a href=\"$uri\">$zip</a></td>
                <td>$subunits</td>
            </tr>
            ";
        }
    ?>
    </tbody>
</table>
<?php
if ($this->total > $this->itemsPerPage) {
    $this->paginator = new Paginator(
        $this->total,
        $this->itemsPerPage,
        $this->currentPage
    );
    $this->_include('pageNavigation.inc');
}
