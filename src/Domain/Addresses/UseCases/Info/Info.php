<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
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

    public function __invoke(int $address_id): InfoResponse
    {
        $info = new InfoResponse();
        try {
            $info->address   = $this->repo->load         ($address_id);
            $info->statusLog = $this->repo->loadStatusLog($address_id, $this->repo::LOG_TYPE);
            $info->locations = $this->repo->findLocations($address_id);
            $info->purposes  = $this->repo->findPurposes ($address_id);
            $info->places    = $this->repo->findPlaces   ($address_id);

            $result = $this->repo->findSubunits(['address_id'=>$address_id]);
            $info->subunits = $result['rows'];

            $result = $this->repo->changeLog($address_id);
            $info->changeLog = new ChangeLogResponse($result['rows'], $result['total']);
        }
        catch (\Exception $e) {
            $info->errors = [$e->getMessage()];
        }
        return $info;
    }
}
