<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Import;

use Domain\Addresses\DataStorage\AddressesRepository;
use Domain\Addresses\UseCases\Add\Add;

class Command
{
    private $repo;

    public function __construct(AddressesRepository $repository)
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
            if ($e) { $errors[$i] = $e; }
        }
        if ($errors) { return new Response($errors); }

        // Write all the new addresses to the database
        $errors = [];
        foreach ($req->addRequests as $r) {
            $res = $add($r);
            if ($res->errors) { $errors[$i] = $res->errors; }
        }
        if ($errors) { return new Response($errors); }

        return new Response();
    }
}
