<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Streets\Names\Views;

use Application\Block;
use Application\Template;

use Domain\Streets\Names\UseCases\Info\InfoResponse;

class InfoView extends Template
{
    public function __construct(InfoResponse $info)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $this->vars['title'] = parent::escape($info->name);

        $this->blocks = [
            new Block('streets/names/info.inc', ['name'    => $info->name   ]),
            new Block('streets/list.inc',       ['streets' => $info->streets])
        ];
    }
}
