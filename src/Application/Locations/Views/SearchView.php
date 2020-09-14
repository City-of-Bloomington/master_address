<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Locations\Views;

use Application\Block;
use Application\Template;

use Domain\Locations\UseCases\Search\SearchRequest;
use Domain\Locations\UseCases\Search\SearchResponse;

class SearchView extends Template
{
    public function __construct(SearchRequest  $req,
                                SearchResponse $res,
                                int            $itemsPerPage,
                                int            $currentPage)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        if ($res->errors) { $_SESSION['errorMessages'] = $res->errors; }

        if ($format == 'html') {
            $vars = [
                'locations'    => $res->locations,
                'total'        => $res->total,
                'itemsPerPage' => $itemsPerPage,
                'currentPage'  => $currentPage
            ];

            $this->blocks = [
                new Block('locations/findForm.inc', $vars)
            ];
        }
        else {
            $this->blocks = [
                new Block('locations/results.inc', ['response' => $res])
            ];
        }
    }
}
