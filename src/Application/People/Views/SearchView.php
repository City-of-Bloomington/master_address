<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\People\Views;

use Application\Block;
use Application\Template;
use Application\Paginator;

use Domain\People\UseCases\Search\SearchResponse;

class SearchView extends Template
{
    const ITEMS_PER_PAGE = 20;

    public function __construct(SearchResponse $response, int $currentPage)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $this->vars['title'] = $this->_('people_search');
        if (count($response->errors)) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $this->blocks[] = new Block('people/findForm.inc', ['people'=>$response->people]);

        if ($response->total > self::ITEMS_PER_PAGE) {
            $this->blocks[] = new Block('pageNavigation.inc', [
                'paginator' => new Paginator(
                    $response->total,
                    self::ITEMS_PER_PAGE,
                    $currentPage
            )]);
        }
    }
}
