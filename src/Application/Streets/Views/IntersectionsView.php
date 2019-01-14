<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Streets\Views;

use Application\Block;
use Application\Template;

use Domain\Streets\Entities\Street;
use Domain\Streets\UseCases\Intersections\IntersectionsResponse;

class IntersectionsView extends Template
{
    public function __construct(IntersectionsResponse $response,
                                ?Street               $street_1=null,
                                ?Street               $street_2=null)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $this->vars['title'] = $this->_(['intersection', 'intersections', 10]);
        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        if ($format == 'html') {
            $this->blocks = [
                new Block('intersections/searchForm.inc', [
                    'intersections' => $response ? $response->intersections : null,
                    'street_1_id'   => $street_1 ? $street_1->id            : null,
                    'street_1_name' => $street_1 ? $street_1->__toString()  : null,
                    'street_2_id'   => $street_2 ? $street_2->id            : null,
                    'street_2_name' => $street_2 ? $street_2->__toString()  : null
                ])
            ];
        }
        else {
            $this->blocks = [
                new Block('intersections/list.inc', ['intersections' => $response->intersections])
            ];
        }
    }
}
