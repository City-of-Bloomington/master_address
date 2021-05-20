<?php
/**
 * @copyright 2009-2021 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array   $this->locations
 */
declare (strict_types=1);
// Group locations by location_id
$locations = [];
foreach ($this->locations as $l) {
    $locations[$l->location_id][] = $l;
}
?>
<section>
<?php
    foreach (array_keys($locations) as $location_id) {
        echo "
        <article>
            <header><h3>{$this->_('location_id')} $location_id</h3></header>
            <table>
                <thead>
                    <tr><th></th>
                        <th>{$this->_('active'       )}</th>
                        <th>{$this->_('status'       )}</td>
                        <th>{$this->_('type'         )}</th>
                        <th>{$this->_('mailable'     )}</th>
                        <th>{$this->_('occupiable'   )}</th>
                        <th>{$this->_('group_quarter')}</th>
                        <th>{$this->_('trash_day'    )}</th>
                        <th>{$this->_('recycle_week' )}</th>
                    </tr>
                </thead>
                <tbody>
        ";
        $fields = [
            'location_id', 'type_code',
            'mailable', 'occupiable', 'group_quarter', 'active',
            'trash_day', 'recycle_week',
            'address', 'subunit'
        ];
        foreach ($locations[$location_id] as $l) {
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

            if ($l->subunit_id) {
                $uri    = parent::generateUri( 'subunits.view', ['id'=>$l->subunit_id]);
                $status = $l->subunit_status;
            }
            else {
                $uri    = parent::generateUri('addresses.view', ['id'=>$l->address_id]);
                $status = $l->address_status;
            }

            echo "
            <tr><th><a href=\"$uri\">$address $subunit</a></th>
                <td>$active</td>
                <td>$status</td>
                <td>$type_code</td>
                <td>$mailable</td>
                <td>$occupiable</td>
                <td>$group_quarter</td>
                <td>$trash_day</td>
                <td>$recycle_week</td>
            </tr>
            ";
        }
        echo "
                </tbody>
            </table>
        </article>
        ";
    }
?>
</section>
