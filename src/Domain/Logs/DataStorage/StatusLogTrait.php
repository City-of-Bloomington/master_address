<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Logs\DataStorage;

trait StatusLogTrait
{
    public function loadStatusLog(int $entity_id, string $logType): array
    {
        $statusLog = [];
        $sql = "select id, status, start_date
                from  {$logType}_status
                where {$logType}_id=?
                order by start_date desc";
        foreach ($this->doQuery($sql, [$entity_id]) as $row) {
            $row['start_date'] = !empty($row['start_date']) ? new \DateTime($row['start_date']) : null;
            $statusLog[] = $row;
        }
        return $statusLog;
    }

    /**
     * Looks up the current status
     */
    public function getStatus(int $entity_id, string $logType): string
    {
        $sql = "select distinct on ({$logType}_id) status
                from {$logType}_status
                where {$logType}_id=?
                order by {$logType}_id, start_date desc";

        $query = $this->pdo->prepare($sql);
        $query->execute([$entity_id]);
        $result = $query->fetchAll(\PDO::FETCH_COLUMN);

        return count($result) ? $result[0] : '';
    }

	/**
	 * Saves a new status change to the database.
	 */
    public function saveStatus(int $entity_id, string $status, string $logType)
    {
		$currentStatus = $this->getStatus($entity_id, $logType);

        $sql = "insert into {$logType}_status
                ({$logType}_id, status, start_date)
                values(?, ?, now())";
        $query = $this->pdo->prepare($sql);
        $query->execute([$entity_id, $status]);
    }
}
