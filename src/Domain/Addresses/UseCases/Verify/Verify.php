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
use Domain\Logs\Metadata as ChangeLog;
use Domain\Addresses\DataStorage\AddressesRepository;

class Verify
{
    private $repo;

    public function __construct(AddressesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(VerifyRequest $req): VerifyResponse
    {
        $address_id = $req->address_id;
        try {
            $entry = new ChangeLogEntry([
                'action'     => ChangeLog::$actions['verify'],
                'entity_id'  => $address_id,
                'person_id'  => $req->user_id,
                'contact_id' => $req->contact_id,
                'notes'      => $req->change_notes
            ]);
            $log_id = $this->repo->logChange($entry, $this->repo::LOG_TYPE);
            return new VerifyResponse($log_id, $address_id);
        }
        catch (\Exception $e) {
            return new VerifyResponse(null, $address_id, [$e->getMessage()]);
        }
    }
}
