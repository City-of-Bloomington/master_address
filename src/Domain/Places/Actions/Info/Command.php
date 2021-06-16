<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Places\Actions\Info;

use Domain\Places\DataStorage\PlacesRepository;

class Command
{
    private $repo;

    public function __construct(PlacesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(int $place_id): Response
    {
        $res = new Response();
        try {
            $res->place = $this->repo->load($place_id);
            if ($res->place->location_id) {
                $res->locations = $this->repo->locations($res->place->location_id);
            }
        }
        catch (\Exception $e) {
            $res->errors = [$e->getMessage()];
        }
        return $res;
    }
}
