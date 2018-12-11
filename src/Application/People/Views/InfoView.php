<?php
/**
 * @copyright 2016-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\People\Views;

use Application\Block;
use Application\Template;

use Domain\People\UseCases\Info\InfoResponse;

class InfoView extends Template
{
    public function __construct(InfoResponse $response)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }
        $person = $response->person;

        $this->vars['title'] = parent::escape("{$person->firstname} {$person->lastname}");
		$this->blocks[] = new Block('people/info.inc', ['person'=>$person]);
    }
}
