<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Plats\UseCases\Search;

use Domain\Plats\Metadata;
use Domain\Plats\DataStorage\PlatsRepository;
use Domain\Plats\Entities\Plat;
use Domain\UseCase;

class Search
{
    private $repo;
    private $metadata;

    public function __construct(PlatsRepository $repository)
    {
        $this->repo = $repository;
        $this->metadata = new Metadata($repository);
    }

    public function __invoke(SearchRequest $req): SearchResponse
    {
        $options = [
            'plat_types' => $this->metadata->types(),
            'cabinets'   => $this->metadata->cabinets(),
            'townships'  => $this->metadata->townships()
        ];

        try {
            $result = $this->repo->search($req);
            return new SearchResponse($result['rows'], $options, $result['total']);
        }
        catch (\Exception $e) {
            return new SearchResponse([], $options, 0, [$e->getMessage()]);
        }
    }
}
