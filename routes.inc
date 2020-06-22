<?php
/**
 * @copyright 2017-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
$rf = new \Aura\Router\RouterFactory(BASE_URI);
$ROUTES = $rf->newInstance();
$ROUTES->addTokens(['id' => '\d+']);

$ROUTES->add('home.index', '/')->setValues([
    'controller' => 'Application\HomeController',
    'action'     => 'index'
]);
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
        $ns = 'Application\\'.ucfirst($t).'\Controllers';

        $r->add('update', '/update{/id}')->setValues([
            'controller' => "$ns\UpdateController",
            'action'     => 'update'
        ]);
        $r->add('index',  '')->setValues([
            'controller' => "$ns\IndexController",
            'action'     => 'index'
        ]);
    });
}

$ROUTES->attach('subunits', "/subunits", function ($r) {
    $ns = 'Application\Subunits\Controllers';

    $r->add('view',     '/{id}'    )->setValues(['action'=>'view'    , 'controller'=>"$ns\ViewController"    ]);
    $r->add('add',      '/add'     )->setValues(['action'=>'add'     , 'controller'=>"$ns\AddController"     ]);
    $r->add('activate', '/activate')->setValues(['action'=>'activate', 'controller'=>"$ns\ActivateController"]);
    foreach (['changeStatus', 'correct', 'update', 'verify'] as $a) {
        $r->add($a, "/$a{/id}")->setValues(['action'=>$a, 'controller'=>"$ns\\".ucfirst($a).'Controller']);
    }
});
$ROUTES->attach('streetNames', "/streets/names", function ($r) {
    $r->setValues(['controller'=> 'Application\Streets\Names\Controller']);

    $r->add('view',    '/{id}'        )->addValues(['action'=>'view'   ]);
    $r->add('index',   ''             )->addValues(['action'=>'index'  ]);
    $r->add('add',     '/add'         )->addValues(['action'=>'add'    ]);
    $r->add('correct', '/correct/{id}')->addValues(['action'=>'correct']);
});
$ROUTES->attach('streets', "/streets", function ($r) {
    $r->setValues(['controller'=> 'Application\Streets\Controller']);

    $r->add('view',         '/{id}')->addValues(['action'=>'view'        ]);
    $r->add('index',        ''     )->addValues(['action'=>'index'       ]);
    $r->add('add',          '/add' )->addValues(['action'=>'add'         ]);
    $r->add('alias',               '/alias/{id}')->addValues(['action'=>'alias'       ]);
    $r->add('update',             '/update/{id}')->addValues(['action'=>'update'      ]);
    $r->add('changeName',     '/changeName/{id}')->addValues(['action'=>'changeName'  ]);
    $r->add('changeStatus', '/changeStatus/{id}')->addValues(['action'=>'changeStatus']);
    $r->add('renumber',         '/renumber/{id}')->addValues(['action'=>'renumber'    ]);
    $r->add('reorder',           '/reorder/{id}')->addValues(['action'=>'reorder'     ]);
    $r->add('intersections','/intersections')->addValues(['action'=>'intersections']);
    $r->add('intersectingStreets','/intersectingStreets{/id}')->addValues(['action'=>'intersectingStreets']);
});
$ROUTES->attach('designations', "/designations", function ($r) {
    $r->setValues(['controller'=> 'Application\Streets\Designations\Controller']);

    $r->add('update', '/update/{id}')->addValues(['action'=>'update']);
});
$ROUTES->attach('addresses', "/addresses", function ($r) {
    $r->setValues(['controller'=> 'Application\Addresses\Controller']);

    $r->add('view',      '/{id}'     )->addValues(['action'=>'view'     ]);
    $r->add('index',     ''          )->addValues(['action'=>'index'    ]);
    $r->add('add',       '/add'      )->addValues(['action'=>'add'      ]);
    $r->add('parse',     '/parse'    )->addValues(['action'=>'parse'    ]);
    $r->add('changeLog', '/changeLog')->addValues(['action'=>'changeLog']);
    $r->add('activate',  '/activate' )->addValues(['action'=>'activate' ]);
    foreach (['changeStatus', 'correct', 'update', 'readdress', 'validate', 'verify'] as $a) {
        $r->add($a, "/$a{/id}")->addValues(['action'=>$a]);
    }
});

$ROUTES->attach('sanitation', '/sanitation', function ($r) {
    $r->setValues(['controller'=> 'Application\Sanitation\Controller']);

    $r->add('update', '/update')->addValues(['action'=>'update']);
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

$ROUTES->attach('reports', '/reports', function ($r) {
    $r->setValues(['controller'=> 'Application\Reports\Controller']);

    $r->add('report', '/{name}')->addValues(['action'=>'report']);
    $r->add('index',  '')       ->addValues(['action'=>'index' ]);
});

$ROUTES->attach('zipCodes', '/zipCodes', function ($r) {
    $r->setValues(['controller'=> 'Application\ZipCodes\Controller']);

    $r->add('index',  '')->addValues(['action'=>'index' ]);
});
