<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Locations\Sanitation\UseCases\Load;

use Domain\Locations\DataStorage\LocationsRepository;

class Load
{
    private $repo;

    public function __construct(LocationsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(int $location_id): LoadResponse
    {
        try {
            $sanitation = $this->repo->sanitation($location_id);
            return new LoadResponse($sanitation);
        }
        catch (\Exception $e) {
            return new LoadResponse(null, [$e->getMessage()]);
        }
    }
}
