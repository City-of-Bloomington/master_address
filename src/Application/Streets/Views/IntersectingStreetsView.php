<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Streets\Views;

use Application\Block;
use Application\Template;

use Domain\Streets\Entities\Street;
use Domain\Streets\UseCases\IntersectingStreets\IntersectingStreetsResponse;

class IntersectingStreetsView extends Template
{
    public function __construct(IntersectingStreetsResponse $response,
                                ?Street                     $street=null)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $this->vars['title'] = $this->_(['intersection', 'intersections', 10]);
        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        if ($format == 'html') {
            $this->blocks = [
                new Block('intersections/streetSearchForm.inc', [
                    'street_id'   => $street ? $street->id : null,
                    'street_name' => $street ? $street->__toString() : null,
                    'streets'     => $response->streets
                ])
            ];
        }
        else {
            $this->blocks = [
                new Block('streets/list.inc', ['streets'=>$response->streets])
            ];
        }
    }
}
