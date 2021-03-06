<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Towns\UseCases\Info;

use Domain\Towns\DataStorage\TownsRepository;

class Info
{
    private $repo;

    public function __construct(TownsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(InfoRequest $req): InfoResponse
    {
        try {
            $town = $this->repo->load($req);
            return new InfoResponse($town);
        }
        catch (\Exception $e) {
            return new InfoResponse(null, [$e->getMessage()]);
        }
    }
}
