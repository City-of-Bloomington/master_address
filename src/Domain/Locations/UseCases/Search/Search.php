<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Locations\UseCases\Search;

use Domain\Locations\DataStorage\LocationsRepository;

class Search
{
    private $repo;

    public function __construct(LocationsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(SearchRequest $req): SearchResponse
    {
        try {
            $result = $this->repo->search($req);
            return new SearchResponse($result['rows'], $result['total']);
        }
        catch (\Exception $e) {
            return new SearchResponse(null, 0, [$e->getMessage()]);
        }
    }
}
