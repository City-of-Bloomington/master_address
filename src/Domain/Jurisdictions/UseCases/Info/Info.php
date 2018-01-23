<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Jurisdictions\UseCases\Info;

use Domain\Jurisdictions\DataStorage\JurisdictionsRepository;

class Info
{
    private $repo;

    public function __construct(JurisdictionsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(InfoRequest $req): InfoResponse
    {
        try {
            $jurisdiction = $this->repo->load($req);
            return new InfoResponse($jurisdiction);
        }
        catch (\Exception $e) {
            return new InfoResponse(null, [$e->getMessage()]);
        }
    }
}
