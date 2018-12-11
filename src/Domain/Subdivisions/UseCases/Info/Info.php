<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subdivisions\UseCases\Info;

use Domain\Subdivisions\DataStorage\SubdivisionsRepository;

class Info
{
    private $repo;

    public function __construct(SubdivisionsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(InfoRequest $req): InfoResponse
    {
        try {
            $subdivision = $this->repo->load($req);
            return new InfoResponse($subdivision);
        }
        catch (\Exception $e) {
            return new InfoResponse(null, [$e->getMessage()]);
        }
    }
}
