<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\StatusLogs\DataStorage;

trait StatusLogTrait
{
    public function loadStatusLog(int $entity_id): array
    {
        $statusLog = [];
        $sql = "select id, status, start_date, end_date
                from  {$this->logType}_status
                where {$this->logType}_id=?
                order by start_date desc";
        foreach ($this->doQuery($sql, [$entity_id]) as $row) {
            $row['start_date'] = !empty($row['start_date']) ? new \DateTime($row['start_date']) : null;
            $row['end_date'  ] = !empty($row['end_date'  ]) ? new \DateTime($row['end_date'  ]) : null;
            $statusLog[] = $row;
        }
        return $statusLog;
    }

    public function saveStatus(int $entity_id, string $status)
    {

    }
}
