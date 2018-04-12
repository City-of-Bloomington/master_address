<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Correct;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\ChangeLogResponse;
use Domain\Logs\Metadata as ChangeLog;
use Domain\Subunits\DataStorage\SubunitsRepository;
Use Domain\Subunits\UseCases\Validate\Validate;
use Domain\Subunits\UseCases\Validate\ValidateResponse;

class Correct
{
    private $repo;

    public function __construct(SubunitsRepository $repository)
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
                'action'    => ChangeLog::$actions[ChangeLog::ACTION_CORRECT],
                'entity_id' => $req->subunit_id,
                'person_id' => $req->user_id,
                'notes'     => $req->change_notes
            ])));
        }
        catch (\Exception $e) {
            return new ChangeLogResponse(null, [$e->getMessage()]);
        }
    }

    /**
     * Apply the corrected fields to the subunit and validate.
     * This function does the validation without saving any data.
     */
    private function validate(CorrectRequest $req): ValidateResponse
    {
        $validate = new Validate();
        $test = $this->repo->load($req->subunit_id);
        foreach ($req as $k=>$v) {
            if (property_exists($test, $k)) { $test->$k = $v; }
        }
        return $validate($test);
    }
}
