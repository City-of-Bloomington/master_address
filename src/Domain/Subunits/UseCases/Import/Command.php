<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Import;

use Domain\Subunits\DataStorage\SubunitsRepository;
use Domain\Subunits\UseCases\Add\Add;

class Command
{
    private $repo;

    public function __construct(SubunitsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(Request $req): Response
    {
        $add    = new Add($this->repo);

        // Validate all the requests before attempting to save them
        $errors = [];
        foreach ($req->addRequests as $i=>$r) {
            $e = $add->validate($r);
            if ($e) { $errors[$i] = $r; }
        }
        if ($errors) { return new Response($errors); }

        // Write all the new subunits to the database
        $errors = [];
        foreach ($req->addRequests as $r) {
            $res = $add($r);
            if ($res->errors) { $errors[$i] = $res->errors; }
        }
        if ($errors) { return new Response($errors); }

        return new Response();
    }
}
