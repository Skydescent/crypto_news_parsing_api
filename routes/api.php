<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('api/v1/articles', ['uses' => 'ArticleController@index']);
