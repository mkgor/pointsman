# Pointsman

Laravel-like routing for your project

# Installation

`composer require mkgor/pointsman`

# Usage

#### Specifying routes
````php
<?php

use Pointsman\Pointsman;

Pointsman::get('getUserById', '/api/user/get/[id]', 'ApiController::getUserById');
Pointsman::post('createUser', '/api/user/create', 'ApiController::createUser');
Pointsman::update('editUserById', '/api/user/edit/[id]', 'ApiController::editUserById');
Pointsman::delete('deleteUserById', '/api/user/delete/[id]', function ($id) {
    //deleting user
});

//You can use any PSR-7 compatible request
Pointsman::handleUrl(Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
));
````

#### Using prefix
````php
<?php

use Pointsman\Pointsman;

Pointsman::prefix('api', function() {
    Pointsman::get('getUserById', '/user/get/[id]', 'ApiController::getUserById');
    Pointsman::post('createUser', '/user/create', 'ApiController::createUser');
    Pointsman::update('editUserById', '/user/edit/[id]', 'ApiController::editUserById');
    Pointsman::delete('deleteUserById', '/user/delete/[id]', 'ApiController::deleteUserById');
});

//You can use any PSR-7 compatible request
Pointsman::handleUrl(Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
));
````

#### Dumping routes
````php
<?php

//.... defining routes

echo Pointsman::dumpRoutes();
````

#### Parameter types

````php
<?php

use Pointsman\Pointsman;

// [ ] - means that parameter is required
Pointsman::get('getUserById', '/user/get/[id]', 'ApiController::getUserById');

// ( ) - parameter is not required
Pointsman::update('editUserById', '/user/edit/(id)', 'ApiController::editUserById');

// You also can specify custom regular expression for parameter
Pointsman::delete('deleteUserById', '/user/delete/[id:\d+]', 'ApiController::deleteUserById');
````

#### Getting route info

````php
<?php

use Pointsman\Pointsman;

Pointsman::get('getUserById', '/user/get/[id]', function($id) {
    //Pointsman::$currentRoute contains all information about current route
    var_dump(Pointsman::$currentRoute);
});
````
