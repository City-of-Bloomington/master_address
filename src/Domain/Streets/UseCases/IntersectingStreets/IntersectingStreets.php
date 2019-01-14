<?php
/**
 * Find streets that intersect with a chosen street
 *
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\IntersectingStreets;

use Domain\Streets\DataStorage\StreetsRepository;

class IntersectingStreets
{
    private $repo;

    public function __construct(StreetsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(int $street_id): IntersectingStreetsResponse
    {
        try {
            return new IntersectingStreetsResponse($this->repo->intersectingStreets($street_id));
        }
        catch (\Exception $e) {
            return new IntersectingStreetsResponse(null, [$e->getMessage()]);
        }
    }
}
