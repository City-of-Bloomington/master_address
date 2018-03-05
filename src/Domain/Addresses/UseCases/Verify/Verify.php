<?php
/**
 * Declare that an address is correct at the current time.
 *
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Verify;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\ChangeLogResponse;
use Domain\Logs\Metadata as ChangeLog;
use Domain\Addresses\DataStorage\AddressesRepository;

class Verify
{
    private $repo;

    public function __construct(AddressesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(VerifyRequest $req): ChangeLogResponse
    {
        try {
            return new ChangeLogResponse($this->repo->logChange(new ChangeLogEntry([
                'action'     => ChangeLog::$actions['verify'],
                'entity_id'  => $req->address_id,
                'person_id'  => $req->user_id,
                'contact_id' => $req->contact_id,
                'notes'      => $req->change_notes
            ])));
        }
        catch (\Exception $e) {
            return new ChangeLogResponse(null, [$e->getMessage()]);
        }
    }
}
