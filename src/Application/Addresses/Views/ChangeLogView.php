<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Views;

use Application\Block;
use Application\Template;
use Application\Paginator;

use Domain\Logs\ChangeLogResponse;

class ChangeLogView extends Template
{
    public function __construct(ChangeLogResponse $res, int $itemsPerPage, int $currentPage)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);
        $this->vars['title'] = $this->_('changeLog');
        $this->blocks = [
            new Block('logs/entityChangeLog.inc', ['entries'      => $res->entries,
                                                   'total'        => $res->total,
                                                   'itemsPerPage' => $itemsPerPage,
                                                   'currentPage'  => $currentPage])
        ];
    }
}
