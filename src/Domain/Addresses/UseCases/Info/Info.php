<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Info;

use Domain\Addresses\DataStorage\AddressesRepository;

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
            $info->changeLog = $this->repo->loadChangeLog($req->id);
            $info->statusLog = $this->repo->loadStatusLog($req->id);
            $info->locations = $this->repo->locations    ($req->id);
            $info->subunits  = $this->repo->subunits     ($req->id);
        }
        catch (\Exception $e) {
            $info->errors = [$e->getMessage()];
        }
        return $info;
    }
}
