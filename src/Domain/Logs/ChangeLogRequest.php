<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Logs;

interface ChangeLogRequest
{
    public function __construct(int $entity_id, int $user_id, ?array $data=null);
}
