<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\Names\UseCases\Validate;

use Domain\Streets\Entities\Name;
use Domain\Streets\Names\DataStorage\NamesRepository;

class Validate
{
    private $repo;

    public function __construct(NamesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(Name $name): ValidateResponse
    {
        $errors = [];
        if (!$name->name) {
            $errors[] = 'missingRequiredFields';
        }

        return new ValidateResponse($name, $errors);
    }
}
