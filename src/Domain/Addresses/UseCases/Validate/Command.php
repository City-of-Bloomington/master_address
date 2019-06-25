<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Validate;

use Domain\Addresses\DataStorage\AddressesRepository;

use Domain\Addresses\UseCases\Parse\Parse;
use Domain\Addresses\UseCases\Search\Search;
use Domain\Addresses\UseCases\Search\SearchRequest;

class Command
{
    private $repo;
    private $parse;
    private $search;

    public function __construct(AddressesRepository $repository)
    {
        $this->repo   = $repository;
        $this->parse  = new Parse($repository);
        $this->search = new Search($repository);
    }

    public function __invoke(string $address): Response
    {
        $query = ($this->parse)($address)->toSearchQuery();
        $req   = new SearchRequest($query);
        $res   = ($this->search)($req);

        if (count($res->addresses) == 1) {
            return new Response($res->addresses[0]);
        }

        return new Response();
    }
}
