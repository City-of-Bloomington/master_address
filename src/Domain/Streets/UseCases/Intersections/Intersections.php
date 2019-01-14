<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Intersections;

use Domain\Streets\DataStorage\StreetsRepository;

class Intersections
{
    private $repo;

    public function __construct(StreetsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(IntersectionsRequest $request): IntersectionsResponse
    {
        $errors = $this->validate($request);
        if ($errors) {
            return new IntersectionsResponse(null, $errors);
        }

        try {
            return new IntersectionsResponse(
                $this->repo->intersections($request->street_id_1, $request->street_id_2)
            );
        }
        catch (\Exception $e) {
            return new IntersectionsResponse(null, [$e->getMessage()]);
        }
    }

    private function validate(IntersectionsRequest $request): array
    {
        if (!$request->street_id_1 || !$request->street_id_2) {
            return ['missingRequiredFields'];
        }
        return [];
    }
}
