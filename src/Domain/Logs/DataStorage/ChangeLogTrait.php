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
    public function logChange(ChangeLogEntry $entry): int
    {
        $insert = $this->queryFactory->newInsert();
        $insert->into("{$this->logType}_change_log")
               ->cols([
                    "{$this->logType}_id" => $entry->entity_id,
                    'person_id'  => $entry->person_id,
                    'contact_id' => $entry->contact_id,
                    'action'     => array_key_exists($entry->action, Metadata::$actions) ? Metadata::$actions[$entry->action] : $entry->action,
                    'notes'      => $entry->notes
               ]);
        $query = $this->pdo->prepare($insert->getStatement());
        $query->execute($insert->getBindValues());

        $pk = $insert->getLastInsertIdName('id');
        return (int)$this->pdo->lastInsertId($pk);
    }

    /**
     * @param int  $entity_id
     * @param bool $hydrate    Whether to hydrate the related entity objects
     */
    public function loadChangeLog(?int   $entity_id   =null,
                                  ?bool  $hydrate     =false,
                                  ?array $order       =null,
                                  ?int   $itemsPerPage=null,
                                  ?int   $currentPage =null): array
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(["l.{$this->logType}_id as entity_id",
                       'l.id', 'l.person_id', 'l.contact_id', 'l.action_date', 'l.action', 'l.notes',
                       'p.firstname as  person_firstname', 'p.lastname as  person_lastname',
                       'c.firstname as contact_firstname', 'c.lastname as contact_lastname'])
               ->from("{$this->logType}_change_log l")
               ->join('LEFT', 'people p', 'p.id=l.person_id')
               ->join('LEFT', 'people c', 'c.id=l.contact_id');
        if ($entity_id) {
            $select->where("{$this->logType}_id=?", $entity_id);
        }
        $select->orderBy($order ?? ['l.action_date desc']);
        $result = parent::performSelect($select, $itemsPerPage, $currentPage);

        $changeLog = [];
        foreach ($result['rows'] as $row) {
            if ($hydrate) {
                $entity = $this->load((int)$row['entity_id']);
                $changeLog[] = ChangeLogEntry::hydrate($row, $entity);
            }
            else {
                $changeLog[] = ChangeLogEntry::hydrate($row);
            }
        }
        $result['rows'] = $changeLog;
        return $result;
    }
}

