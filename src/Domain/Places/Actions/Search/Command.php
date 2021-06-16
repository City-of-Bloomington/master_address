<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Places\Actions\Search;

use Domain\Places\DataStorage\PlacesRepository;

class Command
{
    private $repo;

    public function __construct(PlacesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(Request $req): Response
    {
        try {
            $result = $this->repo->search((array)$req, $req->order, $req->itemsPerPage, $req->currentPage);
            return new Response($result['rows'], $result['total']);
        }
        catch (\Exception $e) {
            return new Response(null, 0, [$e->getMessage()]);
        }
    }
}
