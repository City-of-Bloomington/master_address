<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Verify;

use Domain\ChangeLogs\ChangeLogEntry;
use Domain\Streets\DataStorage\StreetsRepository;

class Verify
{
    private $repo;
    
    public function __construct(StreetsRepository $repository)
    {
        $this->repo = $repository;
    }
    
    public function __invoke(VerifyRequest $req): VerifyResponse
    {
        try {
            return new VerifyResponse($this->repo->logChange(new ChangeLogEntry([
                'action'    => 'verify',
                'entity_id' => $req->street_id,
                'person_id' => $req->user_id,
                'notes'     => $req->notes
            ])));
        }
        catch (\Exception $e) {
            return new VerifyResponse(null, [$e->getMessage()]);
        }
    }
}
