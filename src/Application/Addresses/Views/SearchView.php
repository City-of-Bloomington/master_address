<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Views;

use Application\Block;
use Application\Template;
use Application\Paginator;

use Domain\Addresses\UseCases\Search\SearchResponse;
use Domain\Logs\ChangeLogResponse;

class SearchView extends Template
{
    public function __construct(SearchResponse     $response,
                                int                $itemsPerPage,
                                int                $currentPage,
                                ?ChangeLogResponse $changeLog    = null,
                                ?int               $changeLogPage= null)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $this->vars['title'] = $this->_('addresses_search');
        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        if ($format != 'html') {
            $this->blocks = [
                new Block('addresses/list.inc', ['addresses' => $response->addresses])
            ];
        }
        else {
            $this->blocks[] = new Block('addresses/findForm.inc', ['addresses' => $response->addresses]);
            if ($response->total > $itemsPerPage) {
                $this->blocks[] = new Block('pageNavigation.inc', [
                    'paginator' => new Paginator(
                        $response->total,
                        $itemsPerPage,
                        $currentPage
                )]);
            }

            if ($changeLog) {
                $this->blocks[] = new Block('logs/entityChangeLog.inc', [
                    'entries'      => $changeLog->entries,
                    'total'        => $changeLog->total,
                    'itemsPerPage' => $itemsPerPage,
                    'currentPage'  => $changeLogPage,
                    'moreLink'     => true
                ]);
            }
        }
    }
}
