<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Site\Reports\BloomingtonAddressActivity;

use Domain\Reports\Report as BaseReport;
use Domain\Reports\ReportResponse;

class Report extends BaseReport
{
    public static function metadata(): array
    {
        return [
            'name'   => 'BloomingtonAddressActivity',
            'title'  => 'Bloomington Address and Subunit activity',
            'params' => [
                'startDate' => [
                    'type'    => 'date',
                    'default' => '-30 days'
                ],
                'endDate'   => [
                    'type'    => 'date',
                    'default' => 'now'
                ]
            ]
        ];
    }

    public function execute(array $request, ?int $itemsPerPage=null, ?int $currentPage=null): ReportResponse
    {
        $total     = null;
        $startDate = $request['startDate']->format('Y-m-d');
        $endDate   = $request[  'endDate']->format('Y-m-d');

        $qq  = file_get_contents(__DIR__.'/query.sql');
        $sql = "$qq order by action_date";
        if ($itemsPerPage) {
            $query = $this->pdo->prepare("select count(*) as count from ($qq) o");

            $query->bindValue('start_date_1', $startDate);
            $query->bindValue('start_date_2', $startDate);
            $query->bindValue(  'end_date_1',   $endDate);
            $query->bindValue(  'end_date_2',   $endDate);

            $query->execute();

            $result = $query->fetchAll(\PDO::FETCH_ASSOC);
            $total  = (int)$result[0]['count'];

            $currentPage =  $currentPage ?  $currentPage : 1;
            $offset      = $itemsPerPage * ($currentPage - 1);

            $sql.= " limit $itemsPerPage offset $offset";
        }

        $query = $this->pdo->prepare($sql);
        $query->bindValue('start_date_1', $startDate);
        $query->bindValue('start_date_2', $startDate);
        $query->bindValue(  'end_date_1',   $endDate);
        $query->bindValue(  'end_date_2',   $endDate);

        $query->execute();
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);

        return new ReportResponse($result, $total);
    }
}
