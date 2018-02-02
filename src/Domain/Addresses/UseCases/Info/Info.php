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
        try {
            $street = $this->repo->load($req);
            return new InfoResponse($street);
        }
        catch (\Exception $e) {
            return new InfoResponse(null, [$e->getMessage()]);
        }
    }
}
