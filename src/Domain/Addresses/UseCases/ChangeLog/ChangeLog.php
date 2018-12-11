<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\ChangeLog;

use Domain\Addresses\DataStorage\AddressesRepository;
use Domain\Logs\ChangeLogResponse;

class ChangeLog
{
    private $repo;

    public function __construct(AddressesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(ChangeLogRequest $req): ChangeLogResponse
    {
        try {
            $result = $this->repo->changeLog(null,
                                             $req->order,
                                             $req->itemsPerPage,
                                             $req->currentPage);
            return new ChangeLogResponse($result['rows'], $result['total']);
        }
        catch (\Exception $e) {
            return new ChangeLogResponse(null, null, [$e->getMessage()]);
        }
    }
}
