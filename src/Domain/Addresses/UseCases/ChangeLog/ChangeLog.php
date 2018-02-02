<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\ChangeLog;

use Domain\ChangeLogs\ChangeLogRequest;
use Domain\ChangeLogs\ChangeLogResponse;
use Domain\Addresses\DataStorage\AddressesRepository;

class ChangeLog
{
    private $repo;

    public function __construct(AddressesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(ChangeLogRequest $req)
    {
        try {
            $log = $this->repo->changeLog($req->entity_id);
            return new ChangeLogResponse($log);
        }
        catch (\Exception $e) {
            return new ChangeLogResponse(null, [$e->getMessage()]);
        }
    }
}
