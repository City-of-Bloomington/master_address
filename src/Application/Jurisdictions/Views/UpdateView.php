<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Jurisdictions\Views;

use Application\Block;
use Application\Template;

use Domain\Jurisdictions\Entities\Jurisdiction;
use Domain\Jurisdictions\UseCases\Update\UpdateResponse;

class UpdateView extends Template
{
    public function __construct(Jurisdiction $jurisdiction, ?UpdateResponse $res)
    {
        parent::__construct('default', 'html', []);

        if ($res && count($res->errors)) {
            $_SESSION['errorMessages'] = $res->errors;
        }

        $this->blocks[] = new Block('jurisdictions/updateForm.inc', ['jurisdiction'=>$jurisdiction]);
    }
}
