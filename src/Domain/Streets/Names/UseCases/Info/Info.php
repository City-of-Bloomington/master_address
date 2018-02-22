<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);

namespace Domain\Streets\Names\UseCases;

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
        try {
            $name = $this->repo->load($req);
            return new InfoResponse($name);
        }
        catch (\Exception $e) {
            return new InfoResponse(null, [$e->getMessage()]);
        }
    }
}
