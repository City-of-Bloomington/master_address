<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Reports\Views;

use Application\Block;
use Application\Template;

class ListView extends Template
{
    public function __construct(array $reports)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $this->blocks = [
            new Block('reports/list.inc', ['reports'=>$reports])
        ];
    }
}
