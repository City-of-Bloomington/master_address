<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Info;

use Domain\Streets\DataStorage\StreetsRepository;
use Domain\Logs\ChangeLogResponse;

class Info
{
    private $repo;

    public function __construct(StreetsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(InfoRequest $req): InfoResponse
    {
        $info = new InfoResponse();
        try {
            $info->street       = $this->repo->load         ($req->id);
            $info->designations = $this->repo->findDesignations(['street_id'=>$req->id]);

            $result = $this->repo->loadChangeLog($req->id);
            $info->changeLog = new ChangeLogResponse($result['rows'], $result['total']);
        }
        catch (\Exception $e) {
            $info->errors = [$e->getMessage()];
        }
        return $info;
    }
}
