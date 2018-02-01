<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Plats\Views;

use Application\Block;
use Application\Template;

use Domain\Plats\UseCases\Info\InfoResponse;
use Domain\Addresses\UseCases\Search\SearchResponse;

class InfoView extends Template
{
    public function __construct(InfoResponse $info, SearchResponse $search)
    {
        $format   = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        $template = $format == 'html' ? 'two-column' : 'default';
        parent::__construct($template, $format);

        $this->vars['title'] = parent::escape($info->plat->name);

        $this->blocks[]              = new Block('plats/info.inc',     ['plat'      => $info->plat]);
        $this->blocks['panel-one'][] = new Block('addresses/list.inc', ['addresses' => $search->addresses]);
    }
}
