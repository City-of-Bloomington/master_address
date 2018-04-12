<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Add;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\ChangeLogResponse;
use Domain\Logs\Metadata as ChangeLog;
use Domain\Locations\Entities\Location;
use Domain\Subunits\DataStorage\SubunitsRepository;
use Domain\Subunits\Entities\Subunit;

class Add
{
    const FAKE_SUBUNIT_ID = 999999; // Make sure this is not a real ID
    private $repo;

    public function __construct(SubunitsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(AddRequest $req): ChangeLogResponse
    {
        try {
            $validation = $this->validate($req);
            if ($validation && $validation->errors) {
                return new ChangeLogResponse(null, $validation->errors);
            }

            $subunit = self::subunit($req);
            $subunit_id = $this->repo->save($subunit);
            $this->repo->saveStatus($subunit_id, $req->subunitStatus);

            $location = self::location($req);
            $location->subunit_id = $subunit_id;
            $location_id = $this->repo->saveLocation($location);
            $this->repo->saveLocationStatus($location_id, $req->locationStatus);

            return new ChangeLogResponse($this->repo->logChange(new ChangeLogEntry([
                'action'    => ChangeLog::$actions[ChangeLog::ACTION_ADD],
                'entity_id' => $subunit_id,
                'person_id' => $req->user_id,
                'notes'     => $req->change_notes
            ])));
        }
        catch (\Exception $e) {
            return new ChangeLogResponse(null, [$e->getMessage()]);
        }
    }

    /**
     * Pulls the subunit fields out into a Subunit entity
     */
    private static function subunit(AddRequest $req): Subunit
    {
        return new Subunit([
            'address_id'    => $req->address_id,
            'type_id'       => $req->subunitType_id,
            'identifier'    => $req->identifier,
            'notes'         => $req->notes,
            'state_plane_x' => $req->state_plane_x,
            'state_plane_y' => $req->state_plane_y,
            'latitude'      => $req->latitude,
            'longitude'     => $req->longitude,
            'usng'          => $req->usng,
            'status'        => $req->subunitStatus
        ]);
    }

    /**
     * Pulls the location fields out into a Location entity
     */
    private static function location(AddRequest $req): Location
    {
        return new Location([
            'type_id'      => $req->locationType_id,
            'address_id'   => $req->address_id,
            'mailable'     => $req->mailable,
            'occupiable'   => $req->occupiable,
            'trash_day'    => $req->trash_day,
            'recycle_week' => $req->recycle_week,
            'status'       => $req->locationStatus
        ]);
    }

    /**
     * Validates the Subunit and Location data for a request
     *
     * @param  AddRequest       $req
     * @return ValidateResponse
     */
    private function validate(AddRequest $req)
    {
        $validate = new \Domain\Subunits\UseCases\Validate\Validate();
        $subunit  = self::subunit($req);
        $validation = $validate($subunit);
        if ($validation->errors) { return $validation; }

        $validate = new \Domain\Locations\UseCases\Validate\Validate();
        $location = self::location($req);
        $location->subunit_id = self::FAKE_SUBUNIT_ID;
        $location->active     = true;
        $validation = $validate($location);
        if ($validation->errors) { return $validation; }
    }
}
