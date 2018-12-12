<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Locations\UseCases\Find;

use Domain\Locations\DataStorage\LocationsRepository;

class Find
{
    private $repo;

    public function __construct(LocationsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(array $fields): FindResponse
    {
        try {
            $locations = $this->repo->find($fields);
            return new FindResponse($locations);
        }
        catch (\Exception $e) {
            return new FindResponse(null, [$e->getMessage()]);
        }
    }
}
