<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subdivisions\UseCases\Search;

use Domain\Subdivisions\Metadata;
use Domain\Subdivisions\DataStorage\SubdivisionsRepository;

class Search
{
    private $repo;
    private $metadata;

    public function __construct(SubdivisionsRepository $repository)
    {
        $this->repo = $repository;
        $this->metadata = new Metadata($repository);
    }

    public function __invoke(SearchRequest $req): SearchResponse
    {
        $options = [
            'phases'     => $this->metadata->phases(),
            'statuses'   => $this->metadata->statuses(),
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
