<?php
/**
 * Correct an error in the primary attributes of this address
 *
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Correct;

use Domain\ChangeLogs\ChangeLogEntry;
use Domain\ChangeLogs\ChangeLogResponse;
use Domain\ChangeLogs\Metadata as ChangeLog;
use Domain\Addresses\DataStorage\AddressesRepository;

class Correct
{
    private $repo;
    
    public function __construct(AddressesRepository $repository)
    {
        $this->repo = $repository;
    }
    
    public function __invoke(CorrectRequest $req): ChangeLogResponse
    {
        try {
            $this->repo->correct($req);
            
            return new ChangeLogResponse($this->repo->logChange(new ChangeLogEntry([
                'action'    => ChangeLog::$actions['correct'],
                'entity_id' => $req->address_id,
                'person_id' => $req->user_id,
                'notes'     => $req->change_notes
            ])));
        }
        catch (\Exception $e) {
            return new CorrectResponse(null, [$e->getMessage()]);
        }
    }
}
