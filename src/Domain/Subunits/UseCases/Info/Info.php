<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Info;

use Domain\Logs\ChangeLogResponse;
use Domain\Subunits\DataStorage\SubunitsRepository;

class Info
{
    private $repo;

    public function __construct(SubunitsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(InfoRequest $req): InfoResponse
    {
        $info = new InfoResponse();
        try {
            $info->subunit   = $this->repo->load         ($req->id);
            $info->statusLog = $this->repo->loadStatusLog($req->id, $this->repo::LOG_TYPE);
            $info->address   = $this->repo->loadAddress($info->subunit->address_id);
            $info->locations = $this->repo->findLocations(['subunit_id'=>$req->id]);
            foreach ($info->locations as $i=>$l) {
                $result = $this->repo->findAddresses(['location_id'=>$l->location_id]);
                $info->locations[$i]->addresses = $result['rows'];

                $result = $this->repo->find(['location_id'=>$l->location_id]);
                $info->locations[$i]->subunits = $result['rows'];
            }

            $result = $this->repo->changeLog($req->id);
            $info->changeLog = new ChangeLogResponse($result['rows'], $result['total']);
        }
        catch (\Exception $e) {
            $info->errors = [$e->getMessage()];
        }
        return $info;
    }
}
