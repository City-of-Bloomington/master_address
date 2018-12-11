<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Logs\DataStorage;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata;

trait ChangeLogTrait
{
    /**
     * @return int The ID of the new row in the change log
     */
    public function logChange(ChangeLogEntry $entry, string $logType): int
    {
        $insert = $this->queryFactory->newInsert();
        $insert->into("{$logType}_change_log")
               ->cols([
                    "{$logType}_id" => $entry->entity_id,
                    'person_id'     => $entry->person_id,
                    'contact_id'    => $entry->contact_id,
                    'action'        => array_key_exists($entry->action, Metadata::$actions) ? Metadata::$actions[$entry->action] : $entry->action,
                    'notes'         => $entry->notes
               ]);
        $query = $this->pdo->prepare($insert->getStatement());
        $query->execute($insert->getBindValues());

        $pk = $insert->getLastInsertIdName('id');
        return (int)$this->pdo->lastInsertId($pk);
    }
}

