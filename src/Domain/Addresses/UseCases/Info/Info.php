<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Info;

use Domain\Addresses\DataStorage\AddressesRepository;
use Domain\Logs\ChangeLogResponse;

class Info
{
    private $repo;

    public function __construct(AddressesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(InfoRequest $req): InfoResponse
    {
        $info = new InfoResponse();
        try {
            $info->address   = $this->repo->load         ($req->id);
            $info->statusLog = $this->repo->loadStatusLog($req->id, $this->repo::LOG_TYPE);

            // Load other addresses related to the locations
            $info->locations = $this->repo->findLocations([
                'address_id' => $req->id,
                'subunit_id' => null
            ]);
            foreach ($info->locations as $i=>$l) {
                $result = $this->repo->find(['location_id'=>$l->location_id]);
                $info->locations[$i]->addresses = $result['rows'];

                $result = $this->repo->findSubunits(['location_id' => $l->location_id]);
                $info->locations[$i]->subunits = $result['rows'];
            }

            $result = $this->repo->findSubunits(['address_id'=>$req->id]);
            $info->subunits = $result['rows'];

            $result = $this->repo->changeLog($req->id);
            $info->changeLog = new ChangeLogResponse($result['rows'], $result['total']);
        }
        catch (\Exception $e) {
            $info->errors = [$e->getMessage()];
        }
        return $info;
    }
}
