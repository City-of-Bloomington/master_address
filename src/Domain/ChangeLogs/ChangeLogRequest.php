<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\ChangeLogs;

class ChangeLogRequest
{
    public $entity_id;

    public function __construct(int $id)
    {
        $this->entity_id = $id;
    }
}
