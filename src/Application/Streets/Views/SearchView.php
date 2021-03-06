<?php
/**
 * @copyright 2017-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Streets\Views;

use Application\Block;
use Application\Template;
use Application\Paginator;

use Domain\Streets\Metadata;
use Domain\Streets\UseCases\Search\SearchResponse;

class SearchView extends Template
{
    public function __construct(SearchResponse $response,
                                Metadata       $metadata,
                                int            $itemsPerPage,
                                int            $currentPage)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $this->vars['title'] = $this->_('streets_search');
        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        if ($format == 'html') {
            $this->blocks[] = new Block('streets/searchForm.inc', [
                'town_id'        => !empty($_GET['town_id']) ?           (int)$_GET['town_id']  : null,
                'street'         => !empty($_GET['street' ]) ? parent::escape($_GET['street' ]) : '',
                'status'         => !empty($_GET['status' ]) ? parent::escape($_GET['status' ]) : '',
                'streets'        => $response->streets,
                'towns'          => $metadata->towns(),
                'statuses'       => $metadata->statuses()
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
                new Block('streets/list.inc', ['streets'=>$response->streets])
            ];
        }
    }
}
