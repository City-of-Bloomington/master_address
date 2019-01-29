<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Site\Reports;

use Domain\Reports\Report;

class AddressActivity extends Report
{
    public function metadata(): array
    {
        return [
            'name'   => 'AddressActivity',
            'title'  => 'Address and Subunit activity',
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

    public function execute(array $request)
    {
        $query = $this->pdo->prepare(file_get_contents(__DIR__.'/AddressActivity.sql'));
        $query->bindValue('start_date_1', $request['startDate']->format('Y-m-d'));
        $query->bindValue('start_date_2', $request['startDate']->format('Y-m-d'));
        $query->bindValue(  'end_date_1', $request[  'endDate']->format('Y-m-d'));
        $query->bindValue(  'end_date_2', $request[  'endDate']->format('Y-m-d'));

        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }
}
