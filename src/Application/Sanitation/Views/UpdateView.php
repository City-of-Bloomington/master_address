<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Sanitation\Views;

use Application\Block;
use Application\Template;

use Domain\Locations\Metadata;
use Domain\Locations\Entities\Sanitation;

class UpdateView extends Template
{
    public function __construct(Sanitation $sanitation,
                                Metadata   $metadata,
                                array      $locations,
                                string     $return_url)
    {
        parent::__construct('default', 'html');
        $this->vars['title'] = $this->_('sanitation_edit');

        $this->blocks = [
            new Block('locations/locations.inc', [
                'locations'      => $locations,
                'disableButtons' => true
            ]),
            new Block('sanitation/updateForm.inc', [
                'location_id'  => $sanitation->location_id,
                'trash_day'    => $sanitation->trash_day,
                'recycle_week' => $sanitation->recycle_week,
                'trashDays'    => $metadata->trashDays(),
                'recycleWeeks' => $metadata->recycleWeeks(),
                'return_url'   => $return_url
            ])
        ];
    }
}
