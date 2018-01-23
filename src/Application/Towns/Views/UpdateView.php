<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Towns\Views;

use Application\Block;
use Application\Template;

use Domain\Towns\Entities\Town;
use Domain\Towns\UseCases\Update\UpdateResponse;

class UpdateView extends Template
{
    public function __construct(Town $town, ?UpdateResponse $res)
    {
        parent::__construct('default', 'html', []);

        if ($res && count($res->errors)) {
            $_SESSION['errorMessages'] = $res->errors;
        }

        $this->blocks[] = new Block('towns/updateForm.inc', ['town'=>$town]);
    }
}
