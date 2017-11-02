<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$rf = new \Aura\Router\RouterFactory(BASE_URI);
$ROUTES = $rf->newInstance();

$ROUTES->add('home.index', '/')->setValues(['controller'=>'Application\Controllers\Index', 'action'=>'index']);

$ROUTES->attach('login', '/login', function ($r) {
    $r->setValues(['controller'=>'Application\Controllers\Login']);

    $r->add('login',  '/login') ->addValues(['action'=>'login' ]);
    $r->add('logout', '/logout')->addValues(['action'=>'logout']);
    $r->add('index',  '')       ->addValues(['action'=>'index' ]);
});

$ROUTES->attach('users', '/users', function ($r) {
    $r->setTokens(['id'=>'[a-z0-9]+']);
    $r->setValues(['controller'=>'Application\Controllers\Users']);

    $r->add('update', '/update{/id}') ->addValues(['action'=>'update']);
    $r->add('delete', '/delete/{id}') ->addValues(['action'=>'delete']);
    $r->add('index',  '')             ->addValues(['action'=>'index' ]);
});

$tables = [
    'towns', 'townships', 'jurisdictions',
    'subunitTypes', 'locationTypes', 'purposes'
];
foreach ($tables as $t) {
    $ROUTES->attach($t, "/$t", function ($r) use ($t) {
        $r->setTokens(['id'=>'[a-z0-9]+']);
        $r->setValues(['controller'=> 'Application\Controllers\\'.ucfirst($t)]);

        $r->add('update', '/update{/id}') ->addValues(['action'=>'update']);
        $r->add('index',  '')             ->addValues(['action'=>'index' ]);
    });
}
$ROUTES->attach('streetTypes', "/streets/types", function ($r) {
    $r->setTokens(['id'=>'[a-z0-9]+']);
    $r->setValues(['controller'=> 'Application\Controllers\Streets\Types']);

    $r->add('update', '/update{/id}') ->addValues(['action'=>'update']);
    $r->add('index',  '')             ->addValues(['action'=>'index' ]);
});
$ROUTES->attach('streetNameTypes', "/streets/nameTypes", function ($r) {
    $r->setTokens(['id'=>'[a-z0-9]+']);
    $r->setValues(['controller'=> 'Application\Controllers\Streets\NameTypes']);

    $r->add('update', '/update{/id}') ->addValues(['action'=>'update']);
    $r->add('index',  '')             ->addValues(['action'=>'index' ]);
});
$ROUTES->attach('streets', "/streets", function ($r) {
    $r->setTokens(['id'=>'[a-z0-9]+']);
    $r->setValues(['controller'=> 'Application\Controllers\Streets\Streets']);

    $r->add('update', '/update{/id}')->addValues(['action'=>'update']);
    $r->add('view',   '/{id}')       ->addValues(['action'=>'view'  ]);
    $r->add('index',  '')            ->addValues(['action'=>'index' ]);
});
$ROUTES->attach('addresses', "/addresses", function ($r) {
    $r->setTokens(['id'=>'[a-z0-9]+']);
    $r->setValues(['controller'=> 'Application\Controllers\Addresses\Addresses']);

    $r->add('update', '/update{/id}')->addValues(['action'=>'update']);
    $r->add('view',   '/{id}')       ->addValues(['action'=>'view'  ]);
    $r->add('index',  '')            ->addValues(['action'=>'index' ]);
});

// Controllers that only have index, view, update
$tables = [
    'people', 'plats', 'subdivisions'
];
foreach ($tables as $t) {
    $ROUTES->attach($t, "/$t", function ($r) use ($t) {
        $r->setTokens(['id'=>'[a-z0-9]+']);
        $r->setValues(['controller'=> 'Application\Controllers\\'.ucfirst($t)]);

        $r->add('update', '/update{/id}')->addValues(['action'=>'update']);
        $r->add('view',   '/{id}')       ->addValues(['action'=>'view'  ]);
        $r->add('index',  '')            ->addValues(['action'=>'index' ]);
    });
}
