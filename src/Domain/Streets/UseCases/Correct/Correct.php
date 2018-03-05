<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Correct;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\ChangeLogResponse;
use Domain\Logs\Metadata as ChangeLog;
use Domain\Streets\DataStorage\StreetsRepository;
use Domain\Streets\UseCases\Validate\Validate;
use Domain\Streets\UseCases\Validate\ValidateResponse;

class Correct
{
    private $repo;

    public function __construct(StreetsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(CorrectRequest $req): ChangeLogResponse
    {
        try {
            $validation = $this->validate($req);
            if ($validation->errors) {
                return new ChangeLogResponse(null, $validation->errors);
            }

            $this->repo->correct($req);

            return new ChangeLogResponse($this->repo->logChange(new ChangeLogEntry([
                'action'     => ChangeLog::$actions['correct'],
                'entity_id'  => $req->street_id,
                'person_id'  => $req->user_id,
                'contact_id' => $req->contact_id,
                'notes'      => $req->change_notes
            ])));
        }
        catch (\Exception $e) {
            return new ChangeLogResponse(null, [$e->getMessage()]);
        }
    }

    private function validate(CorrectRequest $req): ValidateResponse
    {
        $validate = new Validate();
        $street = $this->repo->load($req->street_id);
        foreach ($req as $k=>$v) {
            if (property_exists($street, $k)) { $street->$k = $v; }
        }
        return $validate($street);
    }
}
