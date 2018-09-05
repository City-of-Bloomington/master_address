<?php
/**
 * Correct an error in the primary attributes of this address
 *
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Correct;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as ChangeLog;
use Domain\Addresses\DataStorage\AddressesRepository;

class Correct
{
    private $repo;

    public function __construct(AddressesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(CorrectRequest $req): CorrectResponse
    {
        try {
            $this->repo->correct($req);

            $log_id = $this->repo->logChange(new ChangeLogEntry([
                'action'     => ChangeLog::$actions['correct'],
                'entity_id'  => $req->address_id,
                'person_id'  => $req->user_id,
                'contact_id' => $req->contact_id,
                'notes'      => $req->change_notes
            ]));

            return new CorrectResponse($log_id, $req->address_id);
        }
        catch (\Exception $e) {
            return new CorrectResponse(null,    $req->address_id, [$e->getMessage()]);
        }
    }
}
