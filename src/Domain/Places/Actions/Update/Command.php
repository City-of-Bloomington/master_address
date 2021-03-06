<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Places\Actions\Update;
use Domain\Places\DataStorage\PlacesRepository;

class Command
{
    private $repo;

    public function __construct(PlacesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(Request $req): Response
    {
        $errors = $this->validate($req);
        if ($errors) { return new Response(null, $errors); }

        try {
            $id  = $this->repo->update($req);
            $res = new Response($id);
        }
        catch (\Exception $e) {
            $res = new Response(null, [$e->getMessage()]);
        }
        return $res;
    }

    /**
     * @return array  An array of error messages
     */
    private function validate(Request $req): array
    {
        $errors = [];
        if (empty($req->id         )) { $errors[] = 'missingId';        }
        if (empty($req->name       )) { $errors[] = 'missingName';      }
        if (empty($req->short_name )) { $errors[] = 'missingShortName'; }
        if (empty($req->status     )) { $errors[] = 'missingStatus';    }
        if (empty($req->category_id)) { $errors[] = 'missingCategory';  }
        return $errors;
    }
}
