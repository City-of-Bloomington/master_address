<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Plats\UseCases\Search;

use Domain\UseCase;
use Domain\Plats\DataStorage\PlatsRepository;
use Domain\Plats\Entities\Plat;

class Search
{
    private $repo;
    public static $types = ['A', 'C', 'S'];

    public function __construct(PlatsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(SearchRequest $req): SearchResponse
    {
        $options = [
            'plat_types' => self::$types,
            'cabinets'   => $this->repo->distinct('cabinet'),
            'townships'  => $this->repo->townships()
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
