<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Streets\Names\Views;

use Application\Url;
use Application\Block;
use Application\Template;
use Application\Paginator;

use Domain\Streets\Names\UseCases\Search\SearchResponse;

class SearchView extends Template
{
    public function __construct(SearchResponse $response, int $itemsPerPage, int $currentPage)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $this->vars['title'] = $this->_('streetName_search');
        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        if ($format == 'html') {
            $this->blocks[] = new Block('streets/names/searchForm.inc', [
                'names'  => $response->names,
                'hidden' => parent::filterActiveParams($_GET, ['street'])
            ]);

            if ($response->total > $itemsPerPage) {
                $this->blocks[] = new Block('pageNavigation.inc', [
                    'paginator' => new Paginator(
                        $response->total,
                        $itemsPerPage,
                        $currentPage
                )]);
            }
        }
        else {
            $this->blocks = [
                new Block('streets/names/list.inc', ['names'=>$response->names])
            ];
        }
    }
}
