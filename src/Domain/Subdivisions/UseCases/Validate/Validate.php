<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subdivisions\UseCases\Validate;

use Domain\Subdivisions\Metadata;
use Domain\Subdivisions\Entities\Subdivision;

class Validate
{
    private $metadata;

    public function __construct(Metadata $metadata)
    {
        $this->metadata = $metadata;
    }

    public function __invoke(Subdivision $subdivision): ValidateResponse
    {
        $errors = [];
        if (empty($subdivision->name) || empty($subdivision->status)) {
            $errors[] = 'missingRequiredFields';
        }
        if (!in_array($subdivision->status, $this->metadata->statuses())) {
            $errors[] = 'invalidStatus';
        }
        return new ValidateResponse($subdivision, $errors);
    }
}
