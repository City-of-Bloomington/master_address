<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Load;

use Domain\Streets\DataStorage\StreetsRepository;

class Load
{
    private $repo;

    public function __construct(StreetsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(int $street_id): LoadResponse
    {
        try {
            return new LoadResponse($this->repo->load($street_id));
        }
        catch (\Exception $e) {
            return new LoadResponse(null, [$e->getMessage()]);
        }
    }
}
