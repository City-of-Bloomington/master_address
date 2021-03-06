<?php
/**
 * @copyright 2009-2021 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->locations
 *
 * @param bool  $this->sanitationEditable
 * @param bool  $this->userCanActivate
 */
declare (strict_types=1);

use Application\Block;
use Application\Url;

$sanitationButton = '';
$sanitationUri    = parent::generateUri('sanitation.update' );
$activateUri      = parent::generateUri('addresses.activate');
$addAddressUri    = parent::generateUri('addresses.add'     );

$return_url       = Url::current_url(BASE_HOST);
$helper           = $this->template->getHelper('buttonLink');
$userCanAdd       = parent::isAllowed('addresses', 'add');

// Group locations by location_id
$locations = [];
foreach ($this->locations as $l) { $locations[$l->location_id][] = $l; }
?>
<section>
<?php
    foreach (array_keys($locations) as $location_id) {
        $toolbox   = '';
        if ($userCanAdd) {
            $block = new Block('toolbox.inc', ['actions' => [
                'location_address_add' => [
                    'uri'   => "$addAddressUri?location_id=$location_id;return_url=$return_url",
                    'class' => 'add',
                    'help'  => $this->_("locations.location_address_add", 'messages')
                ]
            ]]);
            $toolbox = $block->render('html', $this->template);
        }
        echo "
        <article>
            <header><h3>{$this->_('location_id')} $location_id</h3>$toolbox</header>
            <table>
                <thead>
                    <tr><th></th>
                        <th>{$this->_('active'       )}</th>
                        <th>{$this->_('status'       )}</th>
                        <th>{$this->_('type'         )}</th>
                        <th>{$this->_('mailable'     )}</th>
                        <th>{$this->_('occupiable'   )}</th>
                        <th>{$this->_('group_quarter')}</th>
                        <th>{$this->_('trash_day'    )}</th>
                        <th>{$this->_('recycle_week' )}</th>
                        <th></th>
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
            foreach ($fields as $f) { $$f = parent::escape($l->$f); }

            if ($this->sanitationEditable) {
                $sanitationButton = $helper->buttonLink(
                    "$sanitationUri?location_id={$l->location_id};return_url=$return_url",
                    $this->_('sanitation_edit'),
                    'edit'
                );
            }

            if ($this->userCanActivate) {
                $checked = $l->active ? 'checked="true"' : '';
                $uri     = "$activateUri?location_id=$location_id;address_id={$l->address_id}";
                $active  = "<input type=\"radio\" $checked
                                   onchange=\"document.location.href='$uri';\"/>";
            }

            $uri    = parent::generateUri('addresses.view', ['id'=>$l->address_id]);
            $status = $l->address_status;
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
                <td>$sanitationButton</td>
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
