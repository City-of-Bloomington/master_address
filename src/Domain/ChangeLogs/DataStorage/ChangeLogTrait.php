<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\ChangeLogs\DataStorage;

use Domain\ChangeLogs\ChangeLogEntry;

trait ChangeLogTrait
{
    public function logChange(ChangeLogEntry $entry): int
    {
        $insert = $this->queryFactory->newInsert();
        $insert->into("{$this->changeLogType}_change_log")
               ->cols([
                    "{$this->changeLogType}_id" => $entry->entity_id,
                    'person_id'  => $entry->person_id,
                    'contact_id' => $entry->contact_id,
                    'action'     => $entry->action,
                    'notes'      => $entry->notes
               ]);
        $query = $this->pdo->prepare($insert->getStatement());
        $query->execute($insert->getBindValues());

        $pk = $insert->getLastInsertIdName('id');
        return (int)$this->pdo->lastInsertId($pk);
    }

    public function loadChangeLog(int $entity_id): array
    {
        $changeLog = [];
        $sql = "select l.{$this->changeLogType}_id as entity_id,
                       l.id, l.person_id, l.contact_id, l.action_date, l.action, l.notes,
                       p.firstname as  person_firstname, p.lastname as  person_lastname,
                       c.firstname as contact_firstname, c.lastname as contact_lastname
                from {$this->changeLogType}_change_log l
                left join people p on l.person_id=p.id
                left join people c on l.contact_id=p.id
                where {$this->changeLogType}_id=?
                order by l.action_date desc";

        foreach ($this->doQuery($sql, [$entity_id]) as $row) {
            $changeLog[] = ChangeLogEntry::hydrate($row);
        }
        return $changeLog;
    }
}
