<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Add;

use Domain\Addresses\DataStorage\AddressesRepository;
use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as Log;

class Add
{
    private $repo;

    public function __construct(AddressesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(AddRequest $req): AddResponse
    {
        try {
            $address_id = $this->repo->add($req);

            switch ($req->status) {
                case Log::STATUS_PROPOSED:
                case Log::STATUS_TEMPORARY:
                    $action = Log::$actions[Log::ACTION_PROPOSE];
                break;

                case Log::STATUS_RETIRED:
                    $action = Log::$actions[Log::ACTION_RETIRE];
                break;

                default:
                    $action = Log::$actions[Log::ACTION_ADD];
            }

            $entry = new ChangeLogEntry(['action'     => $action,
                                         'entity_id'  => $address_id,
                                         'person_id'  => $req->user_id,
                                         'contact_id' => $req->contact_id,
                                         'notes'      => $req->change_notes]);
            $log_id = $this->repo->logChange($entry);
            return new AddResponse($log_id, $address_id);

        }
        catch (\Exception $e) {
            return new AddResponse(null, null, [$e->getMessage()]);
        }
    }
}
