<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Jurisdictions\UseCases\Search;

use Domain\Jurisdictions\DataStorage\JurisdictionsRepository;
use Domain\Jurisdictions\Entities\Jurisdiction;

class Search
{
    private $repo;

    public function __construct(JurisdictionsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(SearchRequest $req): SearchResponse
    {
        try {
            $result = $this->repo->search($req);
            $jurisdictions = [];
            foreach ($result['rows'] as $row) { $jurisdictions[] = new Jurisdiction($row); }
            return new SearchResponse($jurisdictions);
        }
        catch (\Exception $e) {
            return new SearchResponse([], [$e->getMessage()]);
        }
    }
}
