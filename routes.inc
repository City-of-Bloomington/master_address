<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
$rf = new \Aura\Router\RouterFactory(BASE_URI);
$ROUTES = $rf->newInstance();
$ROUTES->addTokens(['id' => '\d+']);

$ROUTES->add('home.index', '/')->setValues(['controller'=>'Application\Addresses\Controller', 'action'=>'index']);
$ROUTES->attach('login', '/login', function ($r) {
    $r->setValues(['controller'=>'Application\Authentication\LoginController']);

    $r->add('login',  '/login') ->addValues(['action'=>'login' ]);
    $r->add('logout', '/logout')->addValues(['action'=>'logout']);
    $r->add('index',  '')       ->addValues(['action'=>'index' ]);
});
$ROUTES->attach('users', '/users', function ($r) {
    $r->setValues(['controller'=>'Application\Users\Controller']);

    $r->add('update', '/update{/id}') ->addValues(['action'=>'update']);
    $r->add('delete', '/delete/{id}') ->addValues(['action'=>'delete']);
    $r->add('index',  '')             ->addValues(['action'=>'index' ]);
});

$tables = [
    'towns', 'townships', 'jurisdictions'
];
foreach ($tables as $t) {
    $ROUTES->attach($t, "/$t", function ($r) use ($t) {
        $r->setValues(['controller'=> 'Application\\'.ucfirst($t).'\Controller']);

        $r->add('update', '/update{/id}') ->addValues(['action'=>'update']);
        $r->add('index',  '')             ->addValues(['action'=>'index' ]);
    });
}
$ROUTES->attach('subunitTypes', "/subunits/types", function ($r) {
    $r->setValues(['controller'=> 'Application\Subunits\TypesController']);

    $r->add('update', '/update{/id}') ->addValues(['action'=>'update']);
    $r->add('index',  '')             ->addValues(['action'=>'index' ]);
});
$ROUTES->attach('subunits', "/subunits", function ($r) {
    $r->setValues(['controller'=> 'Application\Subunits\Controller']);

    $r->add('update', '/update{/id}')->addValues(['action'=>'update']);
    $r->add('view',   '/{id}')       ->addValues(['action'=>'view'  ]);
    $r->add('index',  '')            ->addValues(['action'=>'index' ]);

    #foreach (\Application\Subunits\Subunit::$actions as $a) {
    #    $r->add($a, "/$a")->addValues(['action'=>$a]);
    #}
});
$ROUTES->attach('streetTypes', "/streets/types", function ($r) {
    $r->setValues(['controller'=> 'Application\Streets\Types\TypesController']);

    $r->add('update', '/update{/id}') ->addValues(['action'=>'update']);
    $r->add('index',  '')             ->addValues(['action'=>'index' ]);
});
$ROUTES->attach('streetDesignations', "/streets/designations", function ($r) {
    $r->setValues(['controller'=> 'Application\Streets\Designations\DesignationsController']);

    $r->add('update', '/update{/id}') ->addValues(['action'=>'update']);
});
$ROUTES->attach('streetNames', "/streets/names", function ($r) {
    $r->setValues(['controller'=> 'Application\Streets\Names\Controller']);

    $r->add('update', '/update{/id}') ->addValues(['action'=>'update']);
    $r->add('index',  '')             ->addValues(['action'=>'index' ]);
});
$ROUTES->attach('streets', "/streets", function ($r) {
    $r->setValues(['controller'=> 'Application\Streets\Controller']);

    $r->add('view',   '/{id}')       ->addValues(['action'=>'view'  ]);
    $r->add('index',  '')            ->addValues(['action'=>'index' ]);
    $r->add('verify', '/verify{/id}')->addValues(['action'=>'verify']);
});
$ROUTES->attach('addresses', "/addresses", function ($r) {
    $r->setValues(['controller'=> 'Application\Addresses\Controller']);

    #foreach (\Application\Addresses\Address::$actions as $a) {
    #    $r->add($a, "/$a")->addValues(['action'=>$a]);
    #}
    $r->add('view',   '/{id}')       ->addValues(['action'=>'view'  ]);
    $r->add('index',  '')            ->addValues(['action'=>'index' ]);
    $r->add('verify', '/verify{/id}')->addValues(['action'=>'verify']);
});
$ROUTES->attach('locationTypes', "/locations/types", function ($r) {
    $r->setValues(['controller'=> 'Application\Locations\TypesController']);

    $r->add('update', '/update{/id}') ->addValues(['action'=>'update']);
    $r->add('index',  '')             ->addValues(['action'=>'index' ]);
});
$ROUTES->attach('purposes', "/locations/purposes", function ($r) {
    $r->setValues(['controller'=> 'Application\Locations\PurposesController']);

    $r->add('update', '/update{/id}') ->addValues(['action'=>'update']);
    $r->add('index',  '')             ->addValues(['action'=>'index' ]);
});


// Controllers that only have index, view, update
$tables = [
    'people', 'plats', 'subdivisions'
];
foreach ($tables as $t) {
    $ROUTES->attach($t, "/$t", function ($r) use ($t) {
        $r->setValues(['controller'=> 'Application\\'.ucfirst($t).'\Controller']);

        $r->add('update', '/update{/id}')->addValues(['action'=>'update']);
        $r->add('view',   '/{id}')       ->addValues(['action'=>'view'  ]);
        $r->add('index',  '')            ->addValues(['action'=>'index' ]);
    });
}
