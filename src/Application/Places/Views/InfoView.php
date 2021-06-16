<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Places\Views;

use Application\Block;
use Application\Template;

use Domain\Places\Actions\Info\Response;

class InfoView extends Template
{
    public function __construct(Response $res)
    {
        $format   = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        $template = $format == 'html' ? 'two-column' : 'default';
        parent::__construct($template, $format);

        $this->vars['title'] = $res->place->name;
        if ($res->errors) { $_SESSION['errorMessages'] = $res->errors; }

        $this->blocks = [ new Block('places/info.inc', ['place' => $res->place]) ];
        if ($res->locations) {
            $this->blocks['panel-one'] = [ new Block('locations/locations.inc', ['locations' => $res->locations]) ];
        }
    }
}
