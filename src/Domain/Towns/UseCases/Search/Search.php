<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Towns\UseCases\Search;

use Domain\UseCase;
use Domain\Towns\DataStorage\TownsRepository;
use Domain\Towns\Entities\Town;

class Search
{
    private $repo;

    public function __construct(TownsRepository $townsRepository)
    {
        $this->repo = $townsRepository;
    }

    public function __invoke(SearchRequest $req): SearchResponse
    {
        try {
            $result = $this->repo->search($req);
            $towns = [];
            foreach ($result['rows'] as $row) { $towns[] = new Town($row); }
            return new SearchResponse($towns);
        }
        catch (\Exception $e) {
            return new SearchResponse([], [$e->getMessage()]);
        }
    }
}
