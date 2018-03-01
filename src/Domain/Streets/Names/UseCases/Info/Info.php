<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);

namespace Domain\Streets\Names\UseCases\Info;

use Domain\Streets\Names\DataStorage\NamesRepository;


class Info
{
    private $repo;

    public function __construct(NamesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(InfoRequest $req): InfoResponse
    {
        $info = new InfoResponse();
        try {
            $info->name         = $this->repo->load        ($req->id);
            $info->designations = $this->repo->designations($req->id);
        }
        catch (\Exception $e) {
            $info->errors = [$e->getMessage()];
        }
        return $info;
    }
}
