<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array   $this->locations
 * @param boolean $this->disableButtons (optional)
 */
use Application\Block;
use Application\Url;

$fields = [
    'location_id', 'status', 'type_code',
    'mailable', 'occupiable', 'group_quarter', 'active',
    'trash_day', 'recycle_week',
    'address', 'subunit'
];
?>
<section>
    <?php
        $locations = [];
        foreach ($this->locations as $l) {
            $locations[$l->location_id][] = $l;
        }

        $userCanEdit = parent::isAllowed('sanitation', 'update');
        $h           = $this->template->getHelper('buttonLink');
        $button      = '';
        $editUri     = parent::generateUri('sanitation.update');
        $return_url  = Url::current_url(BASE_HOST);


        foreach (array_keys($locations) as $location_id) {
            $status  = parent::escape($locations[$location_id][0]->status);
            echo "
            <article>
                <header><h3>{$this->_('location_id')} $location_id $status</h3></header>
                <table>
                    <thead>
                        <tr><th></th>
                            <th>{$this->_('active')}</th>
                            <th>{$this->_('type')}</th>
                            <th>{$this->_('mailable')}</th>
                            <th>{$this->_('occupiable')}</th>
                            <th>{$this->_('group_quarter')}</th>
                            <th>{$this->_('trash_day')}</th>
                            <th>{$this->_('recycle_week')}</th>
                            <th></th>
                    </thead>
                    <tbody>
            ";
            foreach ($locations[$location_id] as $l) {
                foreach ($fields as $f) { $$f = parent::escape($l->$f); }

                if (!$this->disableButtons && $userCanEdit) {
                    $button = $h->buttonLink(
                        "$editUri?location_id={$l->location_id};return_url=$return_url",
                        $this->_('sanitation_edit'),
                        'edit'
                    );
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
                    <td>$button</td>
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
