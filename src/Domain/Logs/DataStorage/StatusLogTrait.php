<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Logs\DataStorage;

trait StatusLogTrait
{
    public function loadStatusLog(int $entity_id, string $logType): array
    {
        $statusLog = [];
        $sql = "select id, status, start_date, end_date
                from  {$logType}_status
                where {$logType}_id=?
                order by start_date desc";
        foreach ($this->doQuery($sql, [$entity_id]) as $row) {
            $row['start_date'] = !empty($row['start_date']) ? new \DateTime($row['start_date']) : null;
            $row['end_date'  ] = !empty($row['end_date'  ]) ? new \DateTime($row['end_date'  ]) : null;
            $statusLog[] = $row;
        }
        return $statusLog;
    }

    /**
     * Looks up the current status
     */
    public function getStatus(int $entity_id, string $logType): string
    {
        $sql = "select status
                from  {$logType}_status
                where {$logType}_id=?
                  and start_date <= now()
                  and (end_date is null or end_date >= now())";
        $query = $this->pdo->prepare($sql);
        $query->execute([$entity_id]);
        $result = $query->fetchAll(\PDO::FETCH_COLUMN);

        return count($result) ? $result[0] : '';
    }

	/**
	 * Saves a new status change to the database.
	 *
	 * As we update the status log table, we need to clean up old data.
	 * If there is no current status, we just save the new status.
	 * If there is a current status AND it's the same as the new status - then we don't do anything
	 *
	 * Data Cleanup: If there is a current status, and it's not the same as the
	 * new status, we need to set end dates on ALL the old statuses that need them.
	 * There maybe be multiple status changes in the database, that have not had
	 * their end dates set.  They didn't use to do it that way, but now they do.
	 */
    public function saveStatus(int $entity_id, string $status, string $logType)
    {
		$currentStatus = $this->getStatus($entity_id);

		// If we have a current status, and it's not the same as the new one,
		// Do our data cleanup - use today's date on all the empty end dates
		if ($currentStatus && $currentStatus != $status) {
            $sql = "update {$logType}_status
                    set end_date=now()
                    where {$logType}_id=? and end_date is null";
            $query = $this->pdo->prepare($sql);
            $query->execute([$entity_id]);
		}

		// If we don't have a current status, or it's different than the new one,
		// we have a new status - go ahead and save it.
		// The data should be nice and clean now
		if (!$currentStatus || ($currentStatus != $status)) {
            $sql = "insert into {$logType}_status
                    ({$logType}_id, status, start_date)
                    values(?, ?, now())";
            $query = $this->pdo->prepare($sql);
            $query->execute([$entity_id, $status]);
		}
    }
}
