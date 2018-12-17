<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Locations\Sanitation\UseCases\Update;

use Domain\Locations\DataStorage\LocationsRepository;
use Domain\Locations\Entities\Sanitation;

class Update
{
    private $repo;

    public function __construct(LocationsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(Sanitation $sanitation): UpdateResponse
    {
        $errors = $this->validate($sanitation);
        if ($errors) { return new UpdateResponse(null, $errors); }

        try {
            $this->repo->updateSanitation($sanitation);
            return new UpdateResponse($sanitation->location_id);
        }
        catch (\Exception $e) {
            return new UpdateResponse(null, [$e->getMessage()]);
        }
    }

    private function validate(Sanitation $sanitation): array
    {
        $errors = [];
        if (!$sanitation->location_id) { $errors[] = 'missingLocation'; }
        return $errors;
    }
}
