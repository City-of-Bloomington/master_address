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
    <tr><th></th>
        <th><?= $this->_('active'       ); ?></th>
        <th><?= $this->_('type'         ); ?></th>
        <th><?= $this->_('mailable'     ); ?></th>
        <th><?= $this->_('occupiable'   ); ?></th>
        <th><?= $this->_('group_quarter'); ?></th>
        <th><?= $this->_('trash_day'    ); ?></th>
        <th><?= $this->_('recycle_week' ); ?></th>
    </tr>
</thead>
<tbody>
<?php
    $fields = [
        'location_id', 'status', 'type_code',
        'mailable', 'occupiable', 'group_quarter', 'active',
        'trash_day', 'recycle_week',
        'address', 'subunit'
    ];
    foreach ($this->locations as $l) {
        foreach ($fields as $f) {
            switch($f) {
                case 'mailable':
                case 'occupiable':
                case 'group_quarter':
                case 'active':
                    $$f = $l->$f ? 'Y' : '';
                break;
                default:
                    $$f = parent::escape($l->$f);
            }
        }

        $uri = $l->subunit_id
               ? parent::generateUri( 'subunits.view', ['id'=>$l->subunit_id])
               : parent::generateUri('addresses.view', ['id'=>$l->address_id]);
        echo "
        <tr><th><a href=\"$uri\">$address $subunit</a></th>
            <td>$active</td>
            <td>$type_code</td>
            <td>$mailable</td>
            <td>$occupiable</td>
            <td>$group_quarter</td>
            <td>$trash_day</td>
            <td>$recycle_week</td>
        </tr>
        ";
    }
?>
</tbody>
</table>
