<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Townships\UseCases\Search;

use Domain\Townships\DataStorage\TownshipsRepository;
use Domain\Townships\Entities\Township;

class Search
{
    private $repo;

    public function __construct(TownshipsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(SearchRequest $req): SearchResponse
    {
        try {
            $result = $this->repo->search($req);
            $townships = [];
            foreach ($result['rows'] as $row) { $townships[] = new Township($row); }
            return new SearchResponse($townships);
        }
        catch (\Exception $e) {
            return new SearchResponse([], [$e->getMessage()]);
        }
    }
}
