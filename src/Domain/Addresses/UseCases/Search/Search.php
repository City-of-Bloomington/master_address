<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Search;

use Domain\Addresses\DataStorage\AddressesRepository;

class Search
{
    private $repo;

    public function __construct(AddressesRepository $repository)
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
            return new SearchResponse([], 0, [$e->getMessage()]);
        }
    }
}
