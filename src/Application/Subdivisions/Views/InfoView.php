<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Subdivisions\Views;

use Application\Block;
use Application\Template;
use Domain\Subdivisions\UseCases\Info\InfoResponse;

class InfoView extends Template
{
    public function __construct(InfoResponse $response)
    {
        $format   = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        $template = $format == 'html' ? 'two-column' : 'default';
        parent::__construct($template, $format);

        $this->vars['title'] = parent::escape($response->subdivision->name);

        $this->blocks[] = new Block('subdivisions/info.inc', ['subdivision'=>$response->subdivision]);
        #$this->blocks['panel-one'][] = new Block('address/list.inc', ['addresses'=>$this->plat->getAddresses()]);
    }
}
