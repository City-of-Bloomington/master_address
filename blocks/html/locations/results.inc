<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array   $this->locations
 */
declare (strict_types=1);
?>
<table>
<thead>
    <tr><th><?= $this->_('location_id'  ); ?></th>
        <th><?= $this->_('address'      ); ?></th>
        <th><?= $this->_('active'       ); ?></th>
        <th><?= $this->_('type'         ); ?></th>
        <th><?= $this->_('mailable'     ); ?></th>
        <th><?= $this->_('occupiable'   ); ?></th>
        <th><?= $this->_('group_quarter'); ?></th>
        <th><?= $this->_('trash_day'    ); ?></th>
        <th><?= $this->_('recycle_week' ); ?></th>
        <th><?= $this->_('status'       ); ?></th>
    </tr>
</thead>
<tbody>
<?php
    foreach ($this->locations as $l) {
        foreach ($l as $f => $v) {
            switch($f) {
                case 'mailable':
                case 'occupiable':
                case 'group_quarter':
                case 'active':
                    $$f = $v ? 'Y' : '';
                break;
                default:
                    $$f = parent::escape($v);
            }
        }
        $status = !empty($subunit_status) ? $subunit_status : (!empty($address_status) ? $address_status : $status);
        $uri    = $subunit_id
                  ? parent::generateUri( 'subunits.view', ['id'=>$subunit_id])
                  : parent::generateUri('addresses.view', ['id'=>$subunit_id]);
        echo "
        <tr><th>$location_id</th>
            <th><a href=\"$uri\">$address $subunit</a></th>
            <td>$active</td>
            <td>$type_code</td>
            <td>$mailable</td>
            <td>$occupiable</td>
            <td>$group_quarter</td>
            <td>$trash_day</td>
            <td>$recycle_week</td>
            <td>$status</td>
        </tr>
        ";
    }
?>
</tbody>
</table>
